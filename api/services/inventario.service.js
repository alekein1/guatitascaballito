const db = require('../db/db');
const { obtenerFechaEcuador } = require('../utils/fechas');

function normalizarFechaResultado(fecha) {
  if (!fecha) {
    return null;
  }

  if (fecha instanceof Date) {
    return fecha.toISOString().slice(0, 10);
  }

  return String(fecha).slice(0, 10);
}

function construirWhere({ soloDisponibles = false, idProducto = null } = {}) {
  const condiciones = ['p.id_producto IS NOT NULL'];

  if (soloDisponibles) {
    condiciones.push('p.disponible = 1');
    condiciones.push(`(
      p.tipo_control_inventario <> 'STOCK_FIJO'
      OR p.stock_actual IS NULL
      OR p.stock_actual > 0
    )`);
  }

  if (idProducto !== null) {
    condiciones.push('p.id_producto = ?');
  }

  return condiciones.length ? `WHERE ${condiciones.join(' AND ')}` : '';
}

async function listarProductosConInventario(
  { soloDisponibles = false, idProducto = null } = {},
  connection = db
) {
  const fecha = obtenerFechaEcuador();
  const params = [fecha];

  if (idProducto !== null) {
    params.push(idProducto);
  }

  const where = construirWhere({ soloDisponibles, idProducto });

  const [rows] = await connection.query(
    `
      SELECT
        c.id_categoria,
        c.nombre AS categoria,
        p.id_producto,
        p.nombre,
        p.precio,
        p.imagen,
        p.disponible,
        p.tipo_control_inventario,
        p.stock_actual,
        COALESCE(vendido.vendido_hoy, 0) AS vendido_hoy
      FROM categorias c
      LEFT JOIN productos p
        ON p.id_categoria = c.id_categoria
      LEFT JOIN (
        SELECT
          pd.id_producto,
          SUM(pd.cantidad) AS vendido_hoy
        FROM pedido_detalle pd
        INNER JOIN pedidos pe
          ON pe.id_pedido = pd.id_pedido
        WHERE pe.fecha_pedido = ?
          AND pe.estado <> 'ANULADO'
        GROUP BY pd.id_producto
      ) vendido
        ON vendido.id_producto = p.id_producto
      ${where}
      ORDER BY c.id_categoria, p.nombre
    `,
    params
  );

  return rows;
}

async function obtenerProductoConInventario(idProducto, connection = db) {
  const rows = await listarProductosConInventario(
    { idProducto },
    connection
  );

  return rows[0] || null;
}

async function obtenerResumenHistorialInventario(fecha, connection = db) {
  const [[resumen]] = await connection.query(
    `
      SELECT
        COUNT(DISTINCT pe.id_pedido) AS pedidos,
        COUNT(DISTINCT pd.id_producto) AS productos_vendidos,
        COALESCE(SUM(pd.cantidad), 0) AS unidades_vendidas,
        COALESCE(SUM(pd.subtotal), 0) AS total_vendido
      FROM pedidos pe
      LEFT JOIN pedido_detalle pd
        ON pd.id_pedido = pe.id_pedido
      WHERE pe.fecha_pedido = ?
        AND pe.estado <> 'ANULADO'
    `,
    [fecha]
  );

  return {
    pedidos: Number(resumen?.pedidos || 0),
    productos_vendidos: Number(resumen?.productos_vendidos || 0),
    unidades_vendidas: Number(resumen?.unidades_vendidas || 0),
    total_vendido: Number(resumen?.total_vendido || 0)
  };
}

async function listarHistorialInventarioPorFecha(fecha, connection = db) {
  const [rows] = await connection.query(
    `
      SELECT
        c.id_categoria,
        c.nombre AS categoria,
        p.id_producto,
        p.nombre,
        p.precio,
        p.imagen,
        p.disponible,
        p.tipo_control_inventario,
        p.stock_actual,
        historial.vendido_fecha,
        historial.total_facturado,
        historial.pedidos_con_producto
      FROM (
        SELECT
          pd.id_producto,
          SUM(pd.cantidad) AS vendido_fecha,
          SUM(pd.subtotal) AS total_facturado,
          COUNT(DISTINCT pd.id_pedido) AS pedidos_con_producto
        FROM pedido_detalle pd
        INNER JOIN pedidos pe
          ON pe.id_pedido = pd.id_pedido
        WHERE pe.fecha_pedido = ?
          AND pe.estado <> 'ANULADO'
        GROUP BY pd.id_producto
      ) historial
      INNER JOIN productos p
        ON p.id_producto = historial.id_producto
      INNER JOIN categorias c
        ON c.id_categoria = p.id_categoria
      ORDER BY c.id_categoria, historial.vendido_fecha DESC, p.nombre
    `,
    [fecha]
  );

  return rows.map(row => ({
    ...row,
    vendido_fecha: Number(row.vendido_fecha || 0),
    total_facturado: Number(row.total_facturado || 0),
    pedidos_con_producto: Number(row.pedidos_con_producto || 0)
  }));
}

async function listarDiasRecientesHistorialInventario(limit = 10, connection = db) {
  const limite = Number(limit) > 0 ? Number(limit) : 10;
  const [rows] = await connection.query(
    `
      SELECT
        pe.fecha_pedido AS fecha,
        COUNT(DISTINCT pe.id_pedido) AS pedidos,
        COALESCE(SUM(pd.cantidad), 0) AS unidades_vendidas,
        COALESCE(SUM(pd.subtotal), 0) AS total_vendido
      FROM pedidos pe
      LEFT JOIN pedido_detalle pd
        ON pd.id_pedido = pe.id_pedido
      WHERE pe.estado <> 'ANULADO'
      GROUP BY pe.fecha_pedido
      ORDER BY pe.fecha_pedido DESC
      LIMIT ${limite}
    `
  );

  return rows.map(row => ({
    fecha: normalizarFechaResultado(row.fecha),
    pedidos: Number(row.pedidos || 0),
    unidades_vendidas: Number(row.unidades_vendidas || 0),
    total_vendido: Number(row.total_vendido || 0)
  }));
}

module.exports = {
  listarProductosConInventario,
  obtenerProductoConInventario,
  obtenerResumenHistorialInventario,
  listarHistorialInventarioPorFecha,
  listarDiasRecientesHistorialInventario
};
