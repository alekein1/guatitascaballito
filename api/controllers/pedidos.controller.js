const db = require('../db/db');
const {
  obtenerFechaOperativaEcuador,
  obtenerHoraEcuador
} = require('../utils/fechas');
const { crearError } = require('../utils/inventario');

exports.crearPedido = async (req, res) => {
  let connection;

  try {
    const {
      nombre_cliente,
      numero_mesa,
      tipo_consumo,
      productos
    } = req.body;

    /* ===============================
       VALIDACIONES
    ================================ */
    if (!tipo_consumo || !Array.isArray(productos) || productos.length === 0) {
      return res.status(400).json({
        message: 'Datos incompletos para crear el pedido'
      });
    }

    const numeroMesa = (
      tipo_consumo === 'LLEVAR' ||
      tipo_consumo === 'ENCOMIENDA' ||
      numero_mesa === undefined ||
      numero_mesa === null ||
      String(numero_mesa).trim() === ''
    )
      ? null
      : Number(numero_mesa);

    if (numeroMesa !== null && (!Number.isInteger(numeroMesa) || numeroMesa <= 0)) {
      return res.status(400).json({
        message: 'La mesa o ficha debe ser un numero valido'
      });
    }

    const productosAgrupados = new Map();

    for (const item of productos) {
      const idProducto = Number(item.id_producto);
      const cantidad = Number(item.cantidad);
      const paraLlevar = Boolean(item.para_llevar);

      if (!Number.isInteger(idProducto) || idProducto <= 0) {
        throw crearError('Hay productos invalidos en el pedido');
      }

      if (!Number.isInteger(cantidad) || cantidad <= 0) {
        throw crearError('La cantidad de cada producto debe ser mayor a 0');
      }

      const claveProducto = `${idProducto}-${paraLlevar ? '1' : '0'}`;

      const actual = productosAgrupados.get(claveProducto) || {
        id_producto: idProducto,
        cantidad: 0,
        para_llevar: paraLlevar
      };

      actual.cantidad += cantidad;
      productosAgrupados.set(claveProducto, actual);
    }

    const itemsPedido = Array.from(productosAgrupados.values());
    const idsProductos = [...new Set(itemsPedido.map(item => item.id_producto))];
    const cantidadSolicitadaPorProducto = new Map();

    itemsPedido.forEach(item => {
      const acumulado = cantidadSolicitadaPorProducto.get(item.id_producto) || 0;
      cantidadSolicitadaPorProducto.set(
        item.id_producto,
        acumulado + item.cantidad
      );
    });

    connection = await db.getConnection();
    await connection.beginTransaction();

    const placeholders = idsProductos.map(() => '?').join(', ');
    const [productosDb] = await connection.query(
      `
        SELECT
          id_producto,
          nombre,
          precio,
          disponible,
          tipo_control_inventario,
          stock_actual
        FROM productos
        WHERE id_producto IN (${placeholders})
        FOR UPDATE
      `,
      idsProductos
    );

    if (productosDb.length !== idsProductos.length) {
      throw crearError('Uno o varios productos ya no existen');
    }

    const productosMap = new Map(
      productosDb.map(producto => [producto.id_producto, producto])
    );

    let total = 0;
    const detallePedido = [];

    for (const item of itemsPedido) {
      const productoDb = productosMap.get(item.id_producto);

      if (!productoDb || !productoDb.disponible) {
        throw crearError(`El producto ${productoDb?.nombre || item.id_producto} no esta disponible`);
      }

      if (
        productoDb.tipo_control_inventario === 'STOCK_FIJO' &&
        productoDb.stock_actual !== null &&
        productoDb.stock_actual < cantidadSolicitadaPorProducto.get(item.id_producto)
      ) {
        throw crearError(
          `Stock insuficiente para ${productoDb.nombre}. Disponible: ${productoDb.stock_actual}`
        );
      }

      const recargoUnitario = item.para_llevar ? 0.25 : 0;
      const precioBase = Number(productoDb.precio);
      const precioUnitario = Number((precioBase + recargoUnitario).toFixed(2));
      const subtotal = Number((precioUnitario * item.cantidad).toFixed(2));
      const nombreProducto = item.para_llevar
        ? `${productoDb.nombre} (Para llevar)`
        : productoDb.nombre;

      total += subtotal;
      detallePedido.push({
        id_producto: productoDb.id_producto,
        nombre_producto: nombreProducto,
        precio_unitario: precioUnitario,
        cantidad: item.cantidad,
        subtotal,
        tipo_control_inventario: productoDb.tipo_control_inventario,
        stock_actual: productoDb.stock_actual,
        para_llevar: item.para_llevar
      });
    }

    total = Number(total.toFixed(2));

    /* ===============================
       FECHA Y HORA ECUADOR
    ================================ */
    const fechaEcuador = obtenerFechaOperativaEcuador();
    const horaEcuador = obtenerHoraEcuador();

    /* ===============================
       NÚMERO DE PEDIDO DIARIO
    ================================ */
    const [[ultimo]] = await connection.query(
      `SELECT MAX(numero_pedido) AS ultimo
       FROM pedidos
       WHERE fecha_pedido = ?`,
      [fechaEcuador]
    );

    const numeroPedido = ultimo?.ultimo ? ultimo.ultimo + 1 : 1;

    /* ===============================
       CREAR PEDIDO
    ================================ */
    const [pedidoResult] = await connection.query(
      `INSERT INTO pedidos
       (numero_pedido, nombre_cliente, numero_mesa, tipo_consumo,
        total, fecha_pedido, hora_pedido)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      [
        numeroPedido,
        nombre_cliente || null,
        numeroMesa,
        tipo_consumo,
        total,
        fechaEcuador,
        horaEcuador
      ]
    );

    const idPedido = pedidoResult.insertId;

    /* ===============================
       DETALLE DEL PEDIDO
       (Total calculado en backend)
    ================================ */
    for (const item of detallePedido) {
      await connection.query(
        `INSERT INTO pedido_detalle
         (id_pedido, id_producto, nombre_producto,
          precio_unitario, cantidad, subtotal)
         VALUES (?, ?, ?, ?, ?, ?)`,
        [
          idPedido,
          item.id_producto,
          item.nombre_producto,
          item.precio_unitario,
          item.cantidad,
          item.subtotal
        ]
      );

      if (
        item.tipo_control_inventario === 'STOCK_FIJO' &&
        item.stock_actual !== null
      ) {
        await connection.query(
          `
            UPDATE productos
            SET stock_actual = stock_actual - ?,
                disponible = CASE
                  WHEN stock_actual - ? <= 0 THEN 0
                  ELSE disponible
                END
            WHERE id_producto = ?
          `,
          [item.cantidad, item.cantidad, item.id_producto]
        );
      }
    }

    await connection.commit();

    /* ===============================
       RESPUESTA FINAL
    ================================ */
    res.status(201).json({
      message: 'Pedido creado correctamente',
      id_pedido: idPedido,
      numero_pedido: String(numeroPedido).padStart(3, '0'),
      total
    });

  } catch (error) {
    if (connection) {
      await connection.rollback();
    }

    console.error(error);
    res.status(error.statusCode || 500).json({
      message: error.message || 'Error al crear pedido'
    });
  } finally {
    if (connection) {
      connection.release();
    }
  }
};
