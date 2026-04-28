const db = require('../db/db');
const {
  esTipoControlInventarioValido,
  normalizarStockActual
} = require('../utils/inventario');
const {
  listarProductosConInventario,
  obtenerProductoConInventario,
  obtenerResumenHistorialInventario,
  listarHistorialInventarioPorFecha,
  listarDiasRecientesHistorialInventario
} = require('../services/inventario.service');
const { obtenerFechaOperativaEcuador } = require('../utils/fechas');

function esFechaValida(fecha) {
  return /^\d{4}-\d{2}-\d{2}$/.test(fecha);
}

exports.obtenerInventario = async (req, res) => {
  try {
    const productos = await listarProductosConInventario();
    res.json(productos);
  } catch (error) {
    console.error(error);
    res.status(500).json({
      message: 'Error al obtener inventario'
    });
  }
};

exports.obtenerHistorialInventario = async (req, res) => {
  try {
    const fecha = req.query.fecha || obtenerFechaOperativaEcuador();

    if (!esFechaValida(fecha)) {
      return res.status(400).json({
        message: 'La fecha debe tener el formato YYYY-MM-DD'
      });
    }

    const [resumen, detalle, diasRecientes] = await Promise.all([
      obtenerResumenHistorialInventario(fecha),
      listarHistorialInventarioPorFecha(fecha),
      listarDiasRecientesHistorialInventario()
    ]);

    res.json({
      fecha,
      resumen,
      dias_recientes: diasRecientes,
      detalle
    });
  } catch (error) {
    console.error(error);
    res.status(500).json({
      message: 'Error al obtener el historial de inventario'
    });
  }
};

exports.actualizarInventarioProducto = async (req, res) => {
  try {
    const { id } = req.params;
    const {
      tipo_control_inventario,
      stock_actual,
      disponible
    } = req.body;

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

    const [[producto]] = await db.query(
      `SELECT id_producto, disponible
       FROM productos
       WHERE id_producto = ?`,
      [id]
    );

    if (!producto) {
      return res.status(404).json({
        message: 'Producto no encontrado'
      });
    }

    const disponibilidadSolicitada = typeof disponible === 'undefined'
      ? null
      : Number(disponible) ? 1 : 0;

    let disponibleFinal = disponibilidadSolicitada ?? producto.disponible;

    if (tipo_control_inventario === 'STOCK_FIJO' && stockNormalizado !== null) {
      disponibleFinal = stockNormalizado > 0
        ? (disponibilidadSolicitada ?? 1)
        : 0;
    }

    await db.query(
      `UPDATE productos
       SET tipo_control_inventario = ?,
           stock_actual = ?,
           disponible = ?
       WHERE id_producto = ?`,
      [
        tipo_control_inventario,
        stockNormalizado,
        disponibleFinal,
        id
      ]
    );

    const actualizado = await obtenerProductoConInventario(id);

    res.json({
      message: 'Inventario actualizado correctamente',
      producto: actualizado
    });
  } catch (error) {
    console.error(error);
    res.status(500).json({
      message: 'Error al actualizar inventario'
    });
  }
};
