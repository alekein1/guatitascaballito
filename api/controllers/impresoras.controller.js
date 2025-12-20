const db = require('../db/db');

/* ===============================
   ➕ CREAR IMPRESORA
================================ */
exports.crearImpresora = async (req, res) => {
  try {
    const { nombre, ip, puerto, tipo } = req.body;

    if (!nombre || !ip || !tipo) {
      return res.status(400).json({
        message: 'Nombre, IP y tipo son obligatorios'
      });
    }

    const puertoFinal = puerto || 9100;

    const [result] = await db.query(
      `INSERT INTO impresoras (nombre, ip, puerto, tipo)
       VALUES (?, ?, ?, ?)`,
      [nombre, ip, puertoFinal, tipo]
    );

    res.status(201).json({
      message: 'Impresora creada correctamente',
      id_impresora: result.insertId
    });

  } catch (error) {
    console.error(error);
    res.status(500).json({
      message: 'Error al crear impresora'
    });
  }
};

/* ===============================
   📋 LISTAR IMPRESORAS
================================ */
exports.listarImpresoras = async (req, res) => {
  try {
    const [rows] = await db.query(
      `SELECT id_impresora, nombre, ip, puerto, tipo, activa
       FROM impresoras
       ORDER BY tipo`
    );

    res.json(rows);

  } catch (error) {
    console.error(error);
    res.status(500).json({
      message: 'Error al obtener impresoras'
    });
  }
};

/* ===============================
   🔄 ACTIVAR / DESACTIVAR
================================ */
exports.cambiarEstadoImpresora = async (req, res) => {
  try {
    const { id } = req.params;
    const { activa } = req.body;

    await db.query(
      `UPDATE impresoras SET activa = ? WHERE id_impresora = ?`,
      [activa, id]
    );

    res.json({
      message: 'Estado de impresora actualizado'
    });

  } catch (error) {
    console.error(error);
    res.status(500).json({
      message: 'Error al actualizar impresora'
    });
  }
};

/* ===============================
   🧪 IMPRIMIR PRUEBA
================================ */
exports.imprimirPrueba = async (req, res) => {
  try {
    const { id } = req.params;

    const [[impresora]] = await db.query(
      `SELECT ip, puerto, nombre, tipo
       FROM impresoras
       WHERE id_impresora = ? AND activa = 1`,
      [id]
    );

    if (!impresora) {
      return res.status(404).json({
        message: 'Impresora no encontrada o inactiva'
      });
    }

    /**
     * ⚠️ AQUÍ TODAVÍA NO IMPRIMIMOS
     * Solo confirmamos conectividad por ahora
     * (la impresión real viene después)
     */

    res.json({
      message: 'Impresora lista para imprimir',
      impresora
    });

  } catch (error) {
    console.error(error);
    res.status(500).json({
      message: 'Error al probar impresora'
    });
  }
};