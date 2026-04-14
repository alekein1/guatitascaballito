const db = require('../db/db');

/* ===============================
   📊 REPORTE DEL DÍA
================================ */
exports.reporteHoy = async (req, res) => {
  try {
    const fecha = new Date().toLocaleDateString('en-CA', {
      timeZone: 'America/Guayaquil'
    });

    const [[ventas]] = await db.query(
      `SELECT IFNULL(SUM(total),0) AS total_vendido
       FROM pedidos
       WHERE fecha_pedido = ?`,
      [fecha]
    );

    const [[egresos]] = await db.query(
      `SELECT IFNULL(SUM(monto),0) AS total_egresos
       FROM egresos
       WHERE fecha = ?`,
      [fecha]
    );

    const saldo = Number(ventas.total_vendido) - Number(egresos.total_egresos);

    res.json({
      fecha,
      total_vendido: Number(ventas.total_vendido),
      total_egresos: Number(egresos.total_egresos),
      saldo_actual: saldo
    });

  } catch (error) {
    console.error(error);
    res.status(500).json({ message: 'Error en reporte del día' });
  }
};

/* ===============================
   💸 REGISTRAR EGRESO
================================ */
exports.crearEgreso = async (req, res) => {
  try {
    const { descripcion, monto } = req.body;

    if (!descripcion || !monto) {
      return res.status(400).json({ message: 'Datos incompletos' });
    }

    const fecha = new Date().toLocaleDateString('en-CA', {
      timeZone: 'America/Guayaquil'
    });

    const hora = new Date().toLocaleTimeString('en-GB', {
      timeZone: 'America/Guayaquil'
    });

    await db.query(
      `INSERT INTO egresos (descripcion, monto, fecha, hora)
       VALUES (?, ?, ?, ?)`,
      [descripcion, monto, fecha, hora]
    );

    res.status(201).json({ message: 'Egreso registrado' });

  } catch (error) {
    console.error(error);
    res.status(500).json({ message: 'Error al registrar egreso' });
  }
};

/* ===============================
   📋 EGRESOS DEL DÍA
================================ */
exports.egresosHoy = async (req, res) => {
  try {
    const fecha = new Date().toLocaleDateString('en-CA', {
      timeZone: 'America/Guayaquil'
    });

    const [rows] = await db.query(
      `SELECT id_egreso, descripcion, monto, hora
       FROM egresos
       WHERE fecha = ?
       ORDER BY id_egreso DESC`,
      [fecha]
    );

    res.json(rows);

  } catch (error) {
    console.error(error);
    res.status(500).json({ message: 'Error al obtener egresos' });
  }
};

/* ===============================
   🧾 CIERRE DE CAJA
================================ */
exports.cerrarCaja = async (req, res) => {
  try {
    const fecha = new Date().toLocaleDateString('en-CA', {
      timeZone: 'America/Guayaquil'
    });

    const [[ventas]] = await db.query(
      `SELECT IFNULL(SUM(total),0) AS total_vendido
       FROM pedidos
       WHERE fecha_pedido = ?`,
      [fecha]
    );

    const [[egresos]] = await db.query(
      `SELECT IFNULL(SUM(monto),0) AS total_egresos
       FROM egresos
       WHERE fecha = ?`,
      [fecha]
    );

    const saldo = Number(ventas.total_vendido) - Number(egresos.total_egresos);

    await db.query(
      `INSERT INTO cierres_caja
       (fecha, total_ingresos, total_egresos, saldo_final)
       VALUES (?, ?, ?, ?)`,
      [fecha, ventas.total_vendido, egresos.total_egresos, saldo]
    );

    res.json({
      message: 'Caja cerrada correctamente',
      fecha,
      total_ingresos: ventas.total_vendido,
      total_egresos: egresos.total_egresos,
      saldo_final: saldo
    });

  } catch (error) {
    console.error(error);
    res.status(500).json({ message: 'Error al cerrar caja' });
  }
};