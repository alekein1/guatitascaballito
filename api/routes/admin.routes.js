const express = require("express");
const router = express.Router();
const controller = require("../controllers/admin.controller");

// Registro de administrador (carga inicial)
router.post("/register", controller.crearAdmin);

router.post("/login", controller.login);

module.exports = router;
