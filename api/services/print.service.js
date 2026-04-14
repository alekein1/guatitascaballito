const escpos = require('escpos');
const escposNetwork = require('escpos-network');

escpos.Network = escposNetwork;

exports.imprimirTicket = (ip, puerto, texto) => {
  return new Promise((resolve, reject) => {
    try {
      const device = new escpos.Network(ip, puerto);
      const printer = new escpos.Printer(device);

      device.open(() => {
        printer
          .text(texto)
          .cut()
          .close();

        resolve(true);
      });
    } catch (e) {
      reject(e);
    }
  });
};