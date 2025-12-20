const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const db = require('../db/db');

exports.crearAdmin = async (req, res) => {
  try {
    const { nombre, usuario, password } = req.body;

    if (!nombre || !usuario || !password) {
      return res.status(400).json({ message: 'Todos los campos son obligatorios' });
    }

    const [existe] = await db.query(
      'SELECT id_admin FROM admin WHERE usuario = ?',
      [usuario]
    );

    if (existe.length > 0) {
      return res.status(400).json({ message: 'El usuario ya existe' });
    }

    const password_hash = await bcrypt.hash(password, 10);

    const [result] = await db.query(
      `INSERT INTO admin (nombre, usuario, password_hash)
       VALUES (?, ?, ?)`,
      [nombre, usuario, password_hash]
    );

    res.status(201).json({
      message: 'Administrador creado correctamente',
      id_admin: result.insertId
    });

  } catch (error) {
    console.error(error);
    res.status(500).json({ message: 'Error al crear administrador' });
  }
};

/* ===============================
   🔐 LOGIN ADMIN
================================ */
exports.login = async (req, res) => {
  try {
    const { usuario, password } = req.body;

    if (!usuario || !password) {
      return res.status(400).json({ message: 'Usuario y contraseña requeridos' });
    }

    const [rows] = await db.query(
      `SELECT id_admin, nombre, usuario, password_hash, estado
       FROM admin
       WHERE usuario = ?`,
      [usuario]
    );

    if (rows.length === 0) {
      return res.status(401).json({ message: 'Credenciales incorrectas' });
    }

    const admin = rows[0];

    if (admin.estado !== 'ACTIVO') {
      return res.status(403).json({ message: 'Usuario inactivo' });
    }

    const passwordOk = await bcrypt.compare(password, admin.password_hash);

    if (!passwordOk) {
      return res.status(401).json({ message: 'Credenciales incorrectas' });
    }

    // 🔐 Crear token
    const token = jwt.sign(
      {
        id: admin.id_admin,
        rol: 'admin'
      },
      process.env.JWT_SECRET || 'guatitapos_secret',
      { expiresIn: '8h' }
    );

    res.json({
      message: 'Login correcto',
      token,
      admin: {
        id: admin.id_admin,
        nombre: admin.nombre,
        usuario: admin.usuario,
        rol: 'admin'
      }
    });

  } catch (error) {
    console.error(error);
    res.status(500).json({ message: 'Error en login' });
  }
};