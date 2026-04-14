const express = require('express');
const router = express.Router();

const controller = require('../controllers/inventario.controller');
const { verifyToken, checkRole } = require('../middlewares/auth.middleware');

router.use(verifyToken, checkRole(['admin']));

router.get('/', controller.obtenerInventario);
router.get('/historial', controller.obtenerHistorialInventario);
router.put('/:id', controller.actualizarInventarioProducto);

module.exports = router;
