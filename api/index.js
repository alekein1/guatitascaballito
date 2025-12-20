const express = require('express');
const cors = require('cors');
const path = require("path");

const app = express();
const PORT = process.env.PORT || 4000;

const RoutesAdmin = require('./routes/admin.routes')
const RoutesProductos = require('./routes/productos.routes');
const RoutesPedidos = require('./routes/pedidos.routes');


// Middlewares
app.use(cors());
app.use(express.json());

// Servir archivos (si luego usas imágenes)
app.use("/uploads", express.static(path.join(__dirname, "uploads")));

// Ruta raíz
app.get('/', (req, res) => {
  res.status(200).json({
    status: 'OK',
    message: 'API GuatitaPOS Caballito funcionando correctamente 🍽️',
    version: 'v1.0'
  });
});

app.use('/api/admin', RoutesAdmin);
app.use('/api/productos', RoutesProductos);
app.use('/api/pedidos', RoutesPedidos);


// Iniciar servidor
app.listen(PORT, () => {
  console.log(`🚀 GuatitaPOS Backend corriendo en http://localhost:${PORT}`);
});