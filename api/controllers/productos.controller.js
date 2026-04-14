const db = require('../db/db');
const {
  esTipoControlInventarioValido,
  normalizarStockActual
} = require('../utils/inventario');
const { listarProductosConInventario } = require('../services/inventario.service');

/* ===============================
   ➕ CREAR PRODUCTO
================================ */
exports.crearProducto = async (req, res) => {
  try {
    const {
      id_categoria,
      nombre,
      precio,
      tipo_control_inventario = 'SIN_CONTROL',
      stock_actual
    } = req.body;

    if (!id_categoria || !nombre || !precio) {
      return res.status(400).json({
        message: 'Todos los campos son obligatorios'
      });
    }

    if (!esTipoControlInventarioValido(tipo_control_inventario)) {
      return res.status(400).json({
        message: 'Tipo de control de inventario invalido'
      });
    }

    let stockNormalizado;

    try {
      stockNormalizado = normalizarStockActual(
        tipo_control_inventario,
        stock_actual
      );
    } catch (error) {
      return res.status(error.statusCode || 400).json({
        message: error.message
      });
    }

    let imagen = null;
    if (req.file) {
      imagen = `uploads/productos/${req.file.filename}`;
    }

    const disponible = (
      tipo_control_inventario === 'STOCK_FIJO' &&
      stockNormalizado === 0
    ) ? 0 : 1;

    const [result] = await db.query(
      `INSERT INTO productos
       (id_categoria, nombre, precio, imagen, tipo_control_inventario, stock_actual, disponible)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      [
        id_categoria,
        nombre,
        precio,
        imagen,
        tipo_control_inventario,
        stockNormalizado,
        disponible
      ]
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
    const rows = await listarProductosConInventario({
      soloDisponibles: true
    });

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
      `SELECT disponible, tipo_control_inventario, stock_actual
       FROM productos
       WHERE id_producto = ?`,
      [id]
    );

    if (!p) {
      return res.status(404).json({ message:'Producto no encontrado' });
    }

    const nuevo = p.disponible ? 0 : 1;

    if (
      nuevo === 1 &&
      p.tipo_control_inventario === 'STOCK_FIJO' &&
      p.stock_actual === 0
    ) {
      return res.status(400).json({
        message: 'No se puede reactivar un producto con stock en 0. Primero reponga inventario.'
      });
    }

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
