const express = require("express");
const router = express.Router();

const controller = require("../controllers/pedidos.controller");
const { verifyToken, checkRole } = require("../middlewares/auth.middleware");

// ===============================
// 🔐 SOLO ADMIN / CAJA
// ===============================
router.use(verifyToken, checkRole(["admin"]));

/* ===============================
   ➕ Crear pedido (POS)
================================ */
router.post("/", controller.crearPedido);

router.post('/:id/reimprimir-factura', controller.reimprimirFactura);
module.exports = router;