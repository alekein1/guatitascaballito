const TIME_ZONE = 'America/Guayaquil';
const HORA_CAMBIO_DIA_OPERATIVO = 2;

function obtenerPartesFechaHoraEcuador(referencia = new Date()) {
  const partes = new Intl.DateTimeFormat('en-CA', {
    timeZone: TIME_ZONE,
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: false,
    hourCycle: 'h23'
  }).formatToParts(referencia);

  return partes.reduce((acumulado, parte) => {
    if (parte.type !== 'literal') {
      acumulado[parte.type] = Number(parte.value);
    }

    return acumulado;
  }, {});
}

function formatearFechaIso({ year, month, day }) {
  return [
    String(year).padStart(4, '0'),
    String(month).padStart(2, '0'),
    String(day).padStart(2, '0')
  ].join('-');
}

function obtenerFechaCalendarioEcuador(referencia = new Date()) {
  const partes = obtenerPartesFechaHoraEcuador(referencia);
  return formatearFechaIso(partes);
}

function obtenerFechaOperativaEcuador(referencia = new Date()) {
  const partes = obtenerPartesFechaHoraEcuador(referencia);
  const fechaOperativa = new Date(Date.UTC(
    partes.year,
    partes.month - 1,
    partes.day
  ));

  if (partes.hour < HORA_CAMBIO_DIA_OPERATIVO) {
    fechaOperativa.setUTCDate(fechaOperativa.getUTCDate() - 1);
  }

  return fechaOperativa.toISOString().slice(0, 10);
}

function obtenerHoraEcuador(referencia = new Date()) {
  const partes = obtenerPartesFechaHoraEcuador(referencia);

  return [
    String(partes.hour).padStart(2, '0'),
    String(partes.minute).padStart(2, '0'),
    String(partes.second).padStart(2, '0')
  ].join(':');
}

module.exports = {
  TIME_ZONE,
  HORA_CAMBIO_DIA_OPERATIVO,
  obtenerFechaCalendarioEcuador,
  obtenerFechaOperativaEcuador,
  obtenerFechaEcuador: obtenerFechaOperativaEcuador,
  obtenerHoraEcuador
};
