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
        c.id_categoria,
        c.nombre AS categoria,
        p.id_producto,
        p.nombre,
        p.precio,
        p.imagen
      FROM categorias c
      LEFT JOIN productos p 
        ON p.id_categoria = c.id_categoria
        AND p.estado = 'ACTIVO'
      ORDER BY c.id_categoria, p.nombre
    `);

    // Agrupar por categoría
    const resultado = {};

    rows.forEach(row => {
      if (!resultado[row.categoria]) {
        resultado[row.categoria] = [];
      }

      if (row.id_producto) {
        resultado[row.categoria].push({
          id_producto: row.id_producto,
          nombre: row.nombre,
          precio: row.precio,
          imagen: row.imagen
        });
      }
    });

    res.json(resultado);

  } catch (error) {
    console.error(error);
    res.status(500).json({
      message: 'Error al obtener productos'
    });
  }
};