const db = require('../db/db');

/* ===============================
   📊 ESTADÍSTICAS DASHBOARD
================================ */
exports.obtenerEstadisticas = async (req, res) => {
  try {
    const fechaHoy = new Date().toLocaleDateString('en-CA', {
      timeZone: 'America/Guayaquil'
    });

    // 1️⃣ Productos registrados
    const [[productos]] = await db.query(
      `SELECT COUNT(*) AS total FROM productos`
    );

    // 2️⃣ Pedidos del día
    const [[pedidosHoy]] = await db.query(
      `SELECT COUNT(*) AS total 
       FROM pedidos 
       WHERE fecha_pedido = ?`,
      [fechaHoy]
    );

    // 3️⃣ Ventas del día
    const [[ventasHoy]] = await db.query(
      `SELECT IFNULL(SUM(total),0) AS total 
       FROM pedidos 
       WHERE fecha_pedido = ?`,
      [fechaHoy]
    );

    // 4️⃣ Categorías activas
    const [[categorias]] = await db.query(
      `SELECT COUNT(*) AS total 
       FROM categorias 
       WHERE estado = 1`
    );

    res.json({
      productos: productos.total,
      pedidos_hoy: pedidosHoy.total,
      ventas_hoy: Number(ventasHoy.total),
      categorias: categorias.total
    });

  } catch (error) {
    console.error(error);
    res.status(500).json({
      message: 'Error al obtener estadísticas del dashboard'
    });
  }
};