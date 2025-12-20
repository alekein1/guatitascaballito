const db = require('../db/db');

/* ===============================
   ➕ CREAR PEDIDO (POS)
================================ */
exports.crearPedido = async (req, res) => {
  try {
    const {
      nombre_cliente,
      numero_mesa,
      tipo_consumo,
      productos
    } = req.body;

    // Validaciones básicas
    if (!tipo_consumo || !productos || productos.length === 0) {
      return res.status(400).json({
        message: 'Datos incompletos para crear el pedido'
      });
    }

    /* ===============================
       🕒 FECHA Y HORA ECUADOR
    ================================ */
    const fechaEcuador = new Date().toLocaleDateString('en-CA', {
      timeZone: 'America/Guayaquil'
    });

    const horaEcuador = new Date().toLocaleTimeString('en-GB', {
      timeZone: 'America/Guayaquil'
    });

    /* ===============================
       🔢 NÚMERO DE PEDIDO DIARIO
    ================================ */
    const [[ultimo]] = await db.query(
      `SELECT MAX(numero_pedido) AS ultimo
       FROM pedidos
       WHERE fecha_pedido = ?`,
      [fechaEcuador]
    );

    const numeroPedido = ultimo.ultimo ? ultimo.ultimo + 1 : 1;

    /* ===============================
       💾 CREAR PEDIDO
    ================================ */
    const [pedido] = await db.query(
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

    const idPedido = pedido.insertId;

    /* ===============================
       🍽️ DETALLE DEL PEDIDO
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
       🧮 ACTUALIZAR TOTAL
    ================================ */
    await db.query(
      `UPDATE pedidos SET total = ? WHERE id_pedido = ?`,
      [total, idPedido]
    );

    res.status(201).json({
      message: 'Pedido creado correctamente',
      id_pedido: idPedido,
      numero_pedido: numeroPedido,
      total
    });

  } catch (error) {
    console.error(error);
    res.status(500).json({
      message: 'Error al crear pedido'
    });
  }
};