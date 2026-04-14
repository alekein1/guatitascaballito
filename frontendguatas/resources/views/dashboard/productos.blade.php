@extends('dashboard.layout')

@section('titulo', 'Productos')

@section('contenido')

<style>
.productos-grid{
    display:grid;
    grid-template-columns:420px 1fr;
    gap:30px;
    align-items:flex-start;
}

.producto-form,
.productos-lista{
    background:#fff;
    padding:28px;
    border-radius:20px;
    box-shadow:0 15px 30px rgba(0,0,0,.1);
}

.producto-form{
    position:sticky;
    top:20px;
}

.producto-form h2,
.productos-lista h2{
    margin:0 0 18px;
    color:#7a2d2d;
    font-size:22px;
}

.ayuda-inventario{
    margin:0 0 18px;
    padding:14px 16px;
    border-radius:14px;
    background:#f7efe5;
    color:#6a4a33;
    font-size:13px;
    line-height:1.5;
}

.form-group{
    margin-bottom:14px;
}

.form-group label{
    font-weight:600;
    display:block;
    margin-bottom:6px;
    font-size:14px;
}

.form-group input,
.form-group select{
    width:100%;
    padding:12px;
    border-radius:12px;
    border:1px solid #ccc;
    font-size:14px;
}

.stock-group.oculto{
    display:none;
}

.btn-guardar{
    width:100%;
    margin-top:15px;
    background:#7a2d2d;
    color:#fff;
    border:none;
    padding:14px;
    border-radius:30px;
    font-weight:600;
    font-size:15px;
    cursor:pointer;
}

.mensaje{
    margin-top:12px;
    padding:12px;
    border-radius:10px;
    display:none;
    font-size:14px;
}

.success{ background:#d4edda; color:#155724; }
.error{ background:#f8d7da; color:#721c24; }

.categoria{
    margin-bottom:32px;
}

.categoria h3{
    color:#7a2d2d;
    margin-bottom:15px;
    border-bottom:2px solid #f0e6e0;
    padding-bottom:6px;
    font-size:18px;
}

.productos-cards{
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(200px, 1fr));
    gap:16px;
}

.producto-card{
    border:1px solid #eee;
    border-radius:18px;
    padding:14px;
    background:#fff;
    display:flex;
    flex-direction:column;
    gap:10px;
}

.producto-card.agotado{
    opacity:.5;
}

.producto-card img,
.producto-placeholder{
    width:100%;
    height:130px;
    object-fit:cover;
    border-radius:14px;
    background:#f4ece5;
}

.producto-placeholder{
    display:grid;
    place-items:center;
    color:#8a6a52;
    font-weight:600;
    font-size:13px;
}

.producto-card h4{
    font-size:15px;
    margin:0;
    min-height:40px;
}

.precio{
    font-weight:bold;
    color:#7a2d2d;
    font-size:16px;
}

.badges{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
}

.badge{
    display:inline-flex;
    align-items:center;
    padding:6px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
}

.badge-control{
    background:#f3e5d8;
    color:#7a2d2d;
}

.badge-stock{
    background:#e8f4ea;
    color:#166534;
}

.badge-diario{
    background:#eef2ff;
    color:#4338ca;
}

.badge-neutro{
    background:#f1f5f9;
    color:#475569;
}

.btn-toggle{
    margin-top:auto;
    width:100%;
    padding:10px;
    border:none;
    border-radius:20px;
    font-size:13px;
    cursor:pointer;
    font-weight:600;
}

.btn-agotar{ background:#dc3545; color:#fff; }
.btn-habilitar{ background:#198754; color:#fff; }

@media (max-width: 1024px){
    .productos-grid{
        grid-template-columns:1fr;
    }

    .producto-form{
        position:relative;
        top:0;
    }
}

@media (max-width: 768px){
    .productos-grid{
        gap:20px;
    }

    .producto-form,
    .productos-lista{
        padding:20px;
        border-radius:16px;
    }

    .productos-cards{
        grid-template-columns:repeat(auto-fill, minmax(160px, 1fr));
    }
}
</style>

<div class="productos-grid">

    <div class="producto-form">
        <h2>➕ Crear Producto</h2>
        <p class="ayuda-inventario">
            Usa <strong>Stock fijo</strong> para bebidas o productos contables por unidades.
            Usa <strong>Conteo diario</strong> para platos u ollas del día.
            Si dejas vacío el stock en un producto fijo, quedará pendiente de configurar.
        </p>

        <form id="productoForm" enctype="multipart/form-data">
            <div class="form-group">
                <label>Categoría</label>
                <select name="id_categoria" id="categoriaSelect" required>
                    <option value="">Seleccione categoría</option>
                    <option value="1">Platos a la carta</option>
                    <option value="2">Bebidas calientes</option>
                    <option value="3">Bebidas frías</option>
                    <option value="4">Postres</option>
                    <option value="5">Otros</option>
                </select>
            </div>

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" required>
            </div>

            <div class="form-group">
                <label>Precio ($)</label>
                <input type="number" step="0.01" name="precio" required>
            </div>

            <div class="form-group">
                <label>Control de inventario</label>
                <select name="tipo_control_inventario" id="tipoControl">
                    <option value="SIN_CONTROL">Sin control</option>
                    <option value="STOCK_FIJO">Stock fijo</option>
                    <option value="CONTEO_DIARIO">Conteo diario</option>
                </select>
            </div>

            <div class="form-group stock-group oculto" id="stockGroup">
                <label>Stock inicial</label>
                <input type="number" min="0" name="stock_actual" id="stockInput" placeholder="Ej. 24">
            </div>

            <div class="form-group">
                <label>Imagen</label>
                <input type="file" name="imagen" accept="image/*">
            </div>

            <button class="btn-guardar">Guardar Producto</button>
            <div id="mensaje" class="mensaje"></div>
        </form>
    </div>

    <div class="productos-lista">
        <h2>📦 Productos</h2>
        <div id="contenedorProductos"></div>
    </div>

</div>

<script>
const API_URL = "{{ env('API_URL') }}";
const token = localStorage.getItem('token');

const form = document.getElementById('productoForm');
const mensaje = document.getElementById('mensaje');
const contenedor = document.getElementById('contenedorProductos');
const categoriaSelect = document.getElementById('categoriaSelect');
const tipoControl = document.getElementById('tipoControl');
const stockGroup = document.getElementById('stockGroup');
const stockInput = document.getElementById('stockInput');

function actualizarVisibilidadStock() {
    const mostrar = tipoControl.value === 'STOCK_FIJO';
    stockGroup.classList.toggle('oculto', !mostrar);

    if (!mostrar) {
        stockInput.value = '';
    }
}

function sugerirTipoControl() {
    if (tipoControl.dataset.editado === '1') {
        actualizarVisibilidadStock();
        return;
    }

    if (categoriaSelect.value === '1') {
        tipoControl.value = 'CONTEO_DIARIO';
    } else if (categoriaSelect.value === '3') {
        tipoControl.value = 'STOCK_FIJO';
    } else {
        tipoControl.value = 'SIN_CONTROL';
    }

    actualizarVisibilidadStock();
}

tipoControl.addEventListener('change', () => {
    tipoControl.dataset.editado = '1';
    actualizarVisibilidadStock();
});

categoriaSelect.addEventListener('change', sugerirTipoControl);
sugerirTipoControl();

function renderBadgeInventario(producto) {
    if (producto.tipo_control_inventario === 'STOCK_FIJO') {
        if (producto.stock_actual === null) {
            return '<span class="badge badge-neutro">Stock pendiente</span>';
        }

        return `<span class="badge badge-stock">Stock ${producto.stock_actual}</span>`;
    }

    if (producto.tipo_control_inventario === 'CONTEO_DIARIO') {
        return `<span class="badge badge-diario">Vendidos hoy ${producto.vendido_hoy}</span>`;
    }

    return '<span class="badge badge-neutro">Sin control</span>';
}

function renderModoInventario(producto) {
    if (producto.tipo_control_inventario === 'STOCK_FIJO') {
        return '<span class="badge badge-control">Stock fijo</span>';
    }

    if (producto.tipo_control_inventario === 'CONTEO_DIARIO') {
        return '<span class="badge badge-control">Conteo diario</span>';
    }

    return '<span class="badge badge-control">Libre</span>';
}

function renderImagen(producto) {
    if (!producto.imagen) {
        return '<div class="producto-placeholder">Sin imagen</div>';
    }

    return `<img src="${API_URL}/${producto.imagen}" alt="${producto.nombre}">`;
}

form.addEventListener('submit', async e => {
    e.preventDefault();
    mensaje.style.display = 'none';

    const data = new FormData(form);

    const res = await fetch(API_URL + '/productos', {
        method:'POST',
        headers:{ 'Authorization':'Bearer ' + token },
        body:data
    });

    const json = await res.json();

    if (res.ok) {
        mensaje.className = 'mensaje success';
        mensaje.innerText = 'Producto creado correctamente';
        mensaje.style.display = 'block';
        form.reset();
        tipoControl.dataset.editado = '';
        sugerirTipoControl();
        cargarProductos();
        return;
    }

    mensaje.className = 'mensaje error';
    mensaje.innerText = json.message || 'No se pudo crear el producto';
    mensaje.style.display = 'block';
});

async function cargarProductos() {
    const res = await fetch(API_URL + '/productos', {
        headers:{ 'Authorization':'Bearer ' + token }
    });

    const data = await res.json();
    contenedor.innerHTML = '';

    for (const categoria in data) {
        let html = `
            <div class="categoria">
                <h3>${categoria}</h3>
                <div class="productos-cards">
        `;

        data[categoria].forEach(producto => {
            html += `
                <div class="producto-card ${producto.disponible ? '' : 'agotado'}">
                    ${renderImagen(producto)}
                    <h4>${producto.nombre}</h4>
                    <div class="precio">$${Number(producto.precio).toFixed(2)}</div>
                    <div class="badges">
                        ${renderModoInventario(producto)}
                        ${renderBadgeInventario(producto)}
                    </div>

                    <button
                        class="btn-toggle ${producto.disponible ? 'btn-agotar':'btn-habilitar'}"
                        onclick="toggleProducto(${producto.id_producto})">
                        ${producto.disponible ? '🚫 Deshabilitar hoy' : '🔄 Reactivar'}
                    </button>
                </div>
            `;
        });

        html += '</div></div>';
        contenedor.innerHTML += html;
    }
}

async function toggleProducto(id) {
    const res = await fetch(API_URL + '/productos/' + id + '/toggle-disponible', {
        method:'PUT',
        headers:{ 'Authorization':'Bearer ' + token }
    });

    const json = await res.json();

    if (!res.ok) {
        alert(json.message || 'No se pudo cambiar la disponibilidad');
        return;
    }

    cargarProductos();
}

cargarProductos();
</script>

@endsection
