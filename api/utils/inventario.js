const TIPOS_CONTROL_INVENTARIO = [
  'SIN_CONTROL',
  'STOCK_FIJO',
  'CONTEO_DIARIO'
];

function crearError(message, statusCode = 400) {
  const error = new Error(message);
  error.statusCode = statusCode;
  return error;
}

function esTipoControlInventarioValido(tipo) {
  return TIPOS_CONTROL_INVENTARIO.includes(tipo);
}

function normalizarStockActual(tipoControl, stockActual) {
  if (tipoControl !== 'STOCK_FIJO') {
    return null;
  }

  if (
    stockActual === undefined ||
    stockActual === null ||
    String(stockActual).trim() === ''
  ) {
    return null;
  }

  const stock = Number(stockActual);

  if (!Number.isInteger(stock) || stock < 0) {
    throw crearError('El stock debe ser un numero entero mayor o igual a 0');
  }

  return stock;
}

module.exports = {
  TIPOS_CONTROL_INVENTARIO,
  crearError,
  esTipoControlInventarioValido,
  normalizarStockActual
};
