const db = require('../db/db');

async function columnaExiste(nombreColumna) {
  const [rows] = await db.query(
    'SHOW COLUMNS FROM productos LIKE ?',
    [nombreColumna]
  );

  return rows.length > 0;
}

async function ejecutar() {
  let seAgregoTipo = false;

  if (!(await columnaExiste('tipo_control_inventario'))) {
    await db.query(`
      ALTER TABLE productos
      ADD COLUMN tipo_control_inventario
      ENUM('SIN_CONTROL', 'STOCK_FIJO', 'CONTEO_DIARIO')
      NOT NULL DEFAULT 'SIN_CONTROL'
      AFTER disponible
    `);
    seAgregoTipo = true;
    console.log('Columna tipo_control_inventario agregada');
  }

  if (!(await columnaExiste('stock_actual'))) {
    await db.query(`
      ALTER TABLE productos
      ADD COLUMN stock_actual INT NULL DEFAULT NULL
      AFTER tipo_control_inventario
    `);
    console.log('Columna stock_actual agregada');
  }

  if (seAgregoTipo) {
    await db.query(`
      UPDATE productos p
      INNER JOIN categorias c
        ON c.id_categoria = p.id_categoria
      SET p.tipo_control_inventario = CASE
        WHEN LOWER(c.nombre) LIKE '%plato%' THEN 'CONTEO_DIARIO'
        WHEN LOWER(c.nombre) LIKE '%fría%' THEN 'STOCK_FIJO'
        WHEN LOWER(c.nombre) LIKE '%fria%' THEN 'STOCK_FIJO'
        ELSE 'SIN_CONTROL'
      END
      WHERE p.tipo_control_inventario = 'SIN_CONTROL'
    `);

    console.log('Configuracion inicial de inventario aplicada');
  }

  console.log('Esquema de inventario listo');
}

ejecutar()
  .then(async () => {
    await db.end();
    process.exit(0);
  })
  .catch(async error => {
    console.error('No se pudo preparar el esquema de inventario');
    console.error(error);
    await db.end();
    process.exit(1);
  });
