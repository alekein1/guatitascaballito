const express = require("express");
const router = express.Router();

const controller = require("../controllers/productos.controller");
const upload = require("../middlewares/uploadProducto.middleware");
const { verifyToken, checkRole } = require("../middlewares/auth.middleware");

// 🔐 SOLO ADMIN
router.use(verifyToken, checkRole(["admin"]));

// Crear producto (con imagen)
router.post(
  "/",
  upload.single("imagen"),
  controller.crearProducto
);

router.get("/", controller.obtenerProductosPorCategoria);


module.exports = router;