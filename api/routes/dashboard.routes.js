const express = require('express');
const router = express.Router();
const dashboardController = require('../controllers/dashboard.controller');
const { verifyToken, checkRole } = require("../middlewares/auth.middleware");

// ===============================
// 🔐 SOLO ADMIN
// ===============================
router.use(verifyToken, checkRole(["admin"]));
router.get('/estadisticas', dashboardController.obtenerEstadisticas);

module.exports = router;