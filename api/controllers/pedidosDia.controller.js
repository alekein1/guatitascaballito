const db = require('../db/db');

/* ===============================
   📋 LISTAR PEDIDOS DEL DÍA
================================ */
exports.listarPedidosHoy = async (req, res) => {
  try {
    const fechaEcuador = new Date().toLocaleDateString('en-CA', {
      timeZone: 'America/Guayaquil'
    });

    const [pedidos] = await db.query(
      `SELECT 
          id_pedido,
          numero_pedido,
          nombre_cliente,
          numero_mesa,
          tipo_consumo,
          total,
          hora_pedido
       FROM pedidos
       WHERE fecha_pedido = ?
       ORDER BY id_pedido DESC`,
      [fechaEcuador]
    );

    res.json(pedidos);

  } catch (error) {
    console.error(error);
    res.status(500).json({
      message: 'Error al obtener pedidos del día'
    });
  }
};

/* ===============================
   🔍 VER DETALLE DEL PEDIDO
================================ */
exports.verDetallePedido = async (req, res) => {
  try {
    const { id } = req.params;

    const [[pedido]] = await db.query(
      `SELECT * FROM pedidos WHERE id_pedido = ?`,
      [id]
    );

    if (!pedido) {
      return res.status(404).json({
        message: 'Pedido no encontrado'
      });
    }

    const [detalle] = await db.query(
      `SELECT nombre_producto, cantidad, precio_unitario, subtotal
       FROM pedido_detalle
       WHERE id_pedido = ?`,
      [id]
    );

    res.json({
      pedido,
      detalle
    });

  } catch (error) {
    console.error(error);
    res.status(500).json({
      message: 'Error al obtener detalle del pedido'
    });
  }
};