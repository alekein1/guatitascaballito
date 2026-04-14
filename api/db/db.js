const mysql = require('mysql2');

// Configuración de la conexión a la base de datos
const pool = mysql.createPool({
  host: '50.31.188.124',          // Cambia esto si tu BD está en otro host
  user: 'guatitasca1_adminguatitaspro',         // Tu usuario de MySQL
  password: 'rJlKdXQpsUpH',  // Tu contraseña de MySQL
  database: 'guatitasca1_caballitopro2026', // El nombre de la base de datos que creaste
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0,
  timezone: '-05:00'
});

// Exportamos la promesa para facilitar el uso de async/await
module.exports = pool.promise();