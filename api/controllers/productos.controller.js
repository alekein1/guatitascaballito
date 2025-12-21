const db = require('../db/db');

/* ===============================
   ➕ CREAR PRODUCTO
================================ */
exports.crearProducto = async (req, res) => {
  try {
    const { id_categoria, nombre, precio } = req.body;

    if (!id_categoria || !nombre || !precio) {
      return res.status(400).json({
        message: 'Todos los campos son obligatorios'
      });
    }

    let imagen = null;
    if (req.file) {
      imagen = `uploads/productos/${req.file.filename}`;
    }

    const [result] = await db.query(
      `INSERT INTO productos (id_categoria, nombre, precio, imagen)
       VALUES (?, ?, ?, ?)`,
      [id_categoria, nombre, precio, imagen]
    );

    res.status(201).json({
      message: 'Producto creado correctamente',
      id_producto: result.insertId
    });

  } catch (error) {
    console.error(error);
    res.status(500).json({
      message: 'Error al crear producto'
    });
  }
};

/* ===============================
   📦 OBTENER PRODUCTOS POR CATEGORÍA
================================ */
exports.obtenerProductosPorCategoria = async (req, res) => {
  try {
    const [rows] = await db.query(`
      SELECT 
        c.nombre AS categoria,
        p.id_producto,
        p.nombre,
        p.precio,
        p.imagen,
        p.disponible
      FROM categorias c
      LEFT JOIN productos p 
        ON p.id_categoria = c.id_categoria
      WHERE p.disponible = 1
      ORDER BY c.id_categoria, p.nombre
    `);

    const resultado = {};

    rows.forEach(r => {
      if (!resultado[r.categoria]) resultado[r.categoria] = [];
      if (r.id_producto) resultado[r.categoria].push(r);
    });

    res.json(resultado);

  } catch (e) {
    console.error(e);
    res.status(500).json({ message:'Error' });
  }
};

exports.toggleDisponible = async (req, res) => {
  try {
    const { id } = req.params;

    const [[p]] = await db.query(
      `SELECT disponible FROM productos WHERE id_producto = ?`,
      [id]
    );

    if (!p) {
      return res.status(404).json({ message:'Producto no encontrado' });
    }

    const nuevo = p.disponible ? 0 : 1;

    await db.query(
      `UPDATE productos SET disponible = ? WHERE id_producto = ?`,
      [nuevo, id]
    );

    res.json({ disponible: nuevo });

  } catch (e) {
    console.error(e);
    res.status(500).json({ message:'Error al cambiar disponibilidad' });
  }
};