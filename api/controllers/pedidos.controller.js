const db = require('../db/db');
const { imprimirTicket } = require('../services/print.service');
exports.crearPedido = async (req, res) => {
  try {
    const {
      nombre_cliente,
      numero_mesa,
      tipo_consumo,
      productos,
      total // 👈 TOTAL FINAL VIENE DEL FRONTEND
    } = req.body;

    /* ===============================
       VALIDACIONES
    ================================ */
    if (!tipo_consumo || !productos || productos.length === 0) {
      return res.status(400).json({
        message: 'Datos incompletos para crear el pedido'
      });
    }

    if (typeof total !== 'number') {
      return res.status(400).json({
        message: 'Total inválido'
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
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      [
        numeroPedido,
        nombre_cliente || null,
        numero_mesa || null,
        tipo_consumo,
        total, // 👈 TOTAL FINAL
        fechaEcuador,
        horaEcuador
      ]
    );

    const idPedido = pedidoResult.insertId;

    /* ===============================
       DETALLE DEL PEDIDO
       (NO recalcula total)
    ================================ */
    for (const item of productos) {
      const subtotal = item.precio * item.cantidad;

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
       RESPUESTA FINAL
    ================================ */
    res.status(201).json({
      message: 'Pedido creado correctamente',
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
