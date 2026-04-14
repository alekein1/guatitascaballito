const express = require('express');
const router = express.Router();

const controller = require('../controllers/pedidosDia.controller');
const { verifyToken, checkRole } = require('../middlewares/auth.middleware');

// 🔐 SOLO ADMIN
router.use(verifyToken, checkRole(['admin']));

// 📋 Pedidos del día
router.get('/hoy', controller.listarPedidosHoy);

// 🔍 Detalle pedido
router.get('/:id', controller.verDetallePedido);

module.exports = router;