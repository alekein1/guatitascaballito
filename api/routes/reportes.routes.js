const express = require('express');
const router = express.Router();

const controller = require('../controllers/reportes.controller');
const { verifyToken, checkRole } = require('../middlewares/auth.middleware');

// 🔐 Solo ADMIN / CAJA
router.use(verifyToken, checkRole(['admin']));

router.get('/hoy', controller.reporteHoy);

router.post('/egresos', controller.crearEgreso);
router.get('/egresos/hoy', controller.egresosHoy);

router.post('/cierre-caja', controller.cerrarCaja);

module.exports = router;