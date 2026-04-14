function obtenerFechaEcuador() {
  return new Date().toLocaleDateString('en-CA', {
    timeZone: 'America/Guayaquil'
  });
}

function obtenerHoraEcuador() {
  return new Date().toLocaleTimeString('en-GB', {
    timeZone: 'America/Guayaquil'
  });
}

module.exports = {
  obtenerFechaEcuador,
  obtenerHoraEcuador
};
