const db = require('../db/db');
const { imprimirTicket } = require('../services/print.service');
exports.crearPedido = async (req, res) => {
  try {
    const {
      nombre_cliente,
      numero_mesa,
      tipo_consumo,
      productos
    } = req.body;

    /* ===============================
       VALIDACIONES
    ================================ */
    if (!tipo_consumo || !productos || productos.length === 0) {
      return res.status(400).json({
        message: 'Datos incompletos para crear el pedido'
      });
    }

    /* ===============================
       FECHA Y HORA ECUADOR
    ================================ */
    const fechaEcuador = new Date().toLocaleDateString('en-CA', {
      timeZone: 'America/Guayaquil'
    });

    const horaEcuador = new Date().toLocaleTimeString('en-GB', {
      timeZone: 'America/Guayaquil'
    });

    /* ===============================
       NÚMERO DE PEDIDO DIARIO
    ================================ */
    const [[ultimo]] = await db.query(
      `SELECT MAX(numero_pedido) AS ultimo
       FROM pedidos
       WHERE fecha_pedido = ?`,
      [fechaEcuador]
    );

    const numeroPedido = ultimo?.ultimo ? ultimo.ultimo + 1 : 1;

    /* ===============================
       CREAR PEDIDO
    ================================ */
    const [pedidoResult] = await db.query(
      `INSERT INTO pedidos
       (numero_pedido, nombre_cliente, numero_mesa, tipo_consumo,
        total, fecha_pedido, hora_pedido)
       VALUES (?, ?, ?, ?, 0, ?, ?)`,
      [
        numeroPedido,
        nombre_cliente || null,
        numero_mesa || null,
        tipo_consumo,
        fechaEcuador,
        horaEcuador
      ]
    );

    const idPedido = pedidoResult.insertId;

    /* ===============================
       DETALLE DEL PEDIDO
    ================================ */
    let total = 0;

    for (const item of productos) {
      const subtotal = item.precio * item.cantidad;
      total += subtotal;

      await db.query(
        `INSERT INTO pedido_detalle
         (id_pedido, id_producto, nombre_producto,
          precio_unitario, cantidad, subtotal)
         VALUES (?, ?, ?, ?, ?, ?)`,
        [
          idPedido,
          item.id_producto,
          item.nombre,
          item.precio,
          item.cantidad,
          subtotal
        ]
      );
    }

    /* ===============================
       ACTUALIZAR TOTAL
    ================================ */
    await db.query(
      `UPDATE pedidos SET total = ? WHERE id_pedido = ?`,
      [total, idPedido]
    );

    /* ===============================
       🖨️ IMPRESIÓN AUTOMÁTICA
    ================================ */
    const [impresoras] = await db.query(
      `SELECT * FROM impresoras WHERE activa = 1`
    );

    for (const imp of impresoras) {
      let texto = '';

      if (imp.tipo === 'COCINA') {
        texto += `
================================
   GUATITAS DEL CABALLITO
================================

PEDIDO N° ${String(numeroPedido).padStart(3,'0')}
HORA: ${horaEcuador}

--------------------------------
MESA: ${numero_mesa || '-'}
TIPO: ${tipo_consumo}
--------------------------------

`;

productos.forEach(p=>{
  texto += `${p.nombre} x${p.cantidad}\n`;
});

texto += `
--------------------------------
    *** ENVIAR A COCINA ***
================================
`;
      }

      if (imp.tipo === 'FACTURA') {
        texto += `
==========================================
        LAS GUATITAS DEL CABALLITO
          Tradición desde 1996
            Riobamba-Ecuador
         Calles Chile y Francia
          Telefono: 0992695488
=========================================

PEDIDO N° ${String(numeroPedido).padStart(3,'0')}
FECHA: ${fechaEcuador}
HORA : ${horaEcuador}

CLIENTE: ${nombre_cliente || 'Consumidor Final'}
MESA   : ${numero_mesa || '-'}
TIPO   : ${tipo_consumo}

--------------------------------
DETALLE
--------------------------------
`;

productos.forEach(p=>{
  texto += `${p.nombre} x${p.cantidad}  $${(p.precio*p.cantidad).toFixed(2)}\n`;
});

texto += `
--------------------------------
TOTAL: $${total.toFixed(2)}
--------------------------------

Gracias por su compra
================================
`;
      }

      await imprimirTicket(imp.ip, imp.puerto, texto);

      await db.query(
        `INSERT INTO impresiones_pedido
         (id_pedido, id_impresora, tipo, estado, fecha_hora_impresion)
         VALUES (?, ?, ?, 'IMPRESO', NOW())`,
        [idPedido, imp.id_impresora, imp.tipo]
      );
    }

    /* ===============================
       RESPUESTA FINAL
    ================================ */
    res.status(201).json({
      message: 'Pedido creado e impreso correctamente',
      id_pedido: idPedido,
      numero_pedido: String(numeroPedido).padStart(3, '0'),
      total
    });

  } catch (error) {
    console.error(error);
    res.status(500).json({
      message: 'Error al crear pedido'
    });
  }
};

/* ===============================
   🖨️ REIMPRIMIR SOLO FACTURA
================================ */
exports.reimprimirFactura = async (req, res) => {
  try {
    const { id } = req.params;

    /* ===============================
       OBTENER PEDIDO
    ================================ */
    const [[pedido]] = await db.query(
      `SELECT * FROM pedidos WHERE id_pedido = ?`,
      [id]
    );

    if (!pedido) {
      return res.status(404).json({ message: 'Pedido no encontrado' });
    }

    /* ===============================
       OBTENER DETALLE
    ================================ */
    const [detalle] = await db.query(
      `SELECT nombre_producto, cantidad, precio_unitario, subtotal
       FROM pedido_detalle
       WHERE id_pedido = ?`,
      [id]
    );

    /* ===============================
       OBTENER IMPRESORA FACTURA
    ================================ */
    const [[impresora]] = await db.query(
      `SELECT * FROM impresoras
       WHERE tipo = 'FACTURA' AND activa = 1
       LIMIT 1`
    );

    if (!impresora) {
      return res.status(400).json({
        message: 'No hay impresora de FACTURA activa'
      });
    }

    /* ===============================
       TEXTO FACTURA
    ================================ */
    let texto = `
==========================================
        LAS GUATITAS DEL CABALLITO
          Tradición desde 1996
            Riobamba-Ecuador
         Calles Chile y Francia
          Tel: 0992695488
==========================================

PEDIDO N° ${String(pedido.numero_pedido).padStart(3,'0')}
FECHA: ${pedido.fecha_pedido}
HORA : ${pedido.hora_pedido}

CLIENTE: ${pedido.nombre_cliente || 'Consumidor Final'}
MESA   : ${pedido.numero_mesa || '-'}
TIPO   : ${pedido.tipo_consumo}

--------------------------------
DETALLE
--------------------------------
`;

    detalle.forEach(d => {
  const subtotal = Number(d.subtotal);
  texto += `${d.nombre_producto} x${d.cantidad}  $${subtotal.toFixed(2)}\n`;
});
    texto += `
--------------------------------
TOTAL: $${Number(pedido.total).toFixed(2)}
--------------------------------

REIMPRESIÓN DE FACTURA
================================
`;

    /* ===============================
       IMPRIMIR
    ================================ */
    await imprimirTicket(
      impresora.ip,
      impresora.puerto,
      texto
    );

    /* ===============================
       REGISTRAR IMPRESIÓN
    ================================ */
    await db.query(
      `INSERT INTO impresiones_pedido
       (id_pedido, id_impresora, tipo, estado, fecha_hora_impresion)
       VALUES (?, ?, 'FACTURA', 'REIMPRESO', NOW())`,
      [id, impresora.id_impresora]
    );

    res.json({ message: 'Factura reimpresa correctamente' });

  } catch (error) {
    console.error(error);
    res.status(500).json({ message: 'Error al reimprimir factura' });
  }
};