const express = require("express");
const router = express.Router();

const controller = require("../controllers/impresoras.controller");
const { verifyToken, checkRole } = require("../middlewares/auth.middleware");

// ===============================
// 🔐 SOLO ADMIN
// ===============================
router.use(verifyToken, checkRole(["admin"]));

/* ===============================
   ➕ Crear impresora
================================ */
router.post("/", controller.crearImpresora);

/* ===============================
   📋 Listar impresoras
================================ */
router.get("/", controller.listarImpresoras);

/* ===============================
   🔄 Activar / Desactivar impresora
================================ */
router.put("/:id/estado", controller.cambiarEstadoImpresora);

/* ===============================
   🧪 Probar impresora
================================ */
router.post("/:id/prueba", controller.imprimirPrueba);

module.exports = router;