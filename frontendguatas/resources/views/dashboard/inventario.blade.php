@extends('dashboard.layout')

@section('titulo', 'Inventario')

@section('contenido')

<style>
.inventario-page{
    display:grid;
    gap:24px;
}

.inventario-stack{
    display:grid;
    gap:24px;
}

.resumen-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));
    gap:16px;
}

.resumen-card,
.inventario-panel{
    background:#fff;
    border-radius:22px;
    padding:24px;
    box-shadow:0 12px 28px rgba(0,0,0,.1);
}

.resumen-card span{
    display:block;
    color:#8a6a52;
    font-size:13px;
    margin-bottom:8px;
}

.resumen-card strong{
    display:block;
    font-size:32px;
    color:#7a2d2d;
}

.resumen-card small{
    display:block;
    margin-top:8px;
    color:#6b7280;
    font-size:13px;
}

.panel-header{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:16px;
    margin-bottom:18px;
}

.panel-header h2{
    margin:0 0 8px;
    color:#7a2d2d;
}

.panel-header p{
    margin:0;
    color:#6b7280;
    max-width:720px;
    line-height:1.6;
}

.btn-recargar{
    border:none;
    border-radius:999px;
    padding:12px 18px;
    background:#7a2d2d;
    color:#fff;
    font-weight:600;
    cursor:pointer;
}

.categoria-bloque{
    margin-top:22px;
}

.categoria-bloque h3{
    color:#7a2d2d;
    margin:0 0 14px;
    font-size:18px;
}

.inventario-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(260px, 1fr));
    gap:16px;
}

.inventario-card{
    border:1px solid #eee;
    border-radius:18px;
    padding:16px;
    display:flex;
    flex-direction:column;
    gap:12px;
    background:#fff;
}

.inventario-card img,
.inventario-placeholder{
    width:100%;
    height:132px;
    object-fit:cover;
    border-radius:14px;
    background:#f4ece5;
}

.inventario-placeholder{
    display:grid;
    place-items:center;
    color:#8a6a52;
    font-weight:600;
    font-size:13px;
}

.inventario-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:12px;
}

.inventario-top h4{
    margin:0;
    font-size:16px;
    color:#2b1d1d;
}

.precio{
    color:#7a2d2d;
    font-weight:700;
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

.badge-modo{
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

.campo{
    display:flex;
    flex-direction:column;
    gap:6px;
}

.campo label{
    font-size:13px;
    font-weight:600;
    color:#4b5563;
}

.campo input,
.campo select{
    width:100%;
    padding:11px 12px;
    border-radius:12px;
    border:1px solid #d6d6d6;
    font-size:14px;
}

.campo.oculto{
    display:none;
}

.switch{
    display:flex;
    align-items:center;
    gap:10px;
    font-size:14px;
    color:#4b5563;
}

.estado{
    padding:12px 14px;
    border-radius:14px;
    font-size:13px;
    line-height:1.5;
}

.estado.stock{
    background:#e8f4ea;
    color:#166534;
}

.estado.diario{
    background:#eef2ff;
    color:#4338ca;
}

.estado.neutro{
    background:#f8fafc;
    color:#475569;
}

.btn-guardar{
    border:none;
    border-radius:14px;
    padding:12px;
    background:#7a2d2d;
    color:#fff;
    font-weight:600;
    cursor:pointer;
}

.historial-toolbar{
    display:flex;
    flex-wrap:wrap;
    align-items:flex-end;
    gap:12px;
    margin-bottom:18px;
}

.historial-toolbar .campo{
    min-width:220px;
}

.historial-note{
    margin:0 0 18px;
    padding:14px 16px;
    border-radius:14px;
    background:#f7efe5;
    color:#6a4a33;
    font-size:13px;
    line-height:1.6;
}

.dias-recientes{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin:18px 0 20px;
}

.dia-chip{
    border:none;
    border-radius:999px;
    padding:10px 14px;
    background:#f3e5d8;
    color:#7a2d2d;
    font-weight:600;
    cursor:pointer;
}

.dia-chip.activo{
    background:#7a2d2d;
    color:#fff;
}

.historial-vacio{
    padding:18px;
    border:1px dashed #d8c7b4;
    border-radius:18px;
    color:#6b7280;
    background:#fcfaf7;
}

.historial-tabla{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}

.historial-tabla th,
.historial-tabla td{
    text-align:left;
    padding:12px 10px;
    border-bottom:1px solid #f0e6e0;
    font-size:14px;
}

.historial-tabla th{
    color:#7a2d2d;
    font-size:13px;
    text-transform:uppercase;
    letter-spacing:.03em;
}

.historial-tabla td strong{
    color:#2b1d1d;
}

.historial-stock-actual{
    color:#6b7280;
    font-size:12px;
}

@media (max-width: 768px){
    .panel-header{
        flex-direction:column;
    }

    .btn-recargar{
        width:100%;
    }

    .historial-toolbar .campo{
        min-width:100%;
    }

    .historial-tabla{
        display:block;
        overflow-x:auto;
        white-space:nowrap;
    }
}
</style>

<div class="inventario-page">
    <div class="resumen-grid" id="resumenInventario"></div>

    <div class="inventario-stack">
        <div class="inventario-panel">
            <div class="panel-header">
                <div>
                    <h2>📦 Control por producto</h2>
                    <p>
                        Los productos con <strong>stock fijo</strong> descuentan unidades al venderse.
                        Los productos con <strong>conteo diario</strong> no usan stock y solo muestran cuántos se han vendido hoy.
                        Si dejas el stock vacío en un producto fijo, seguirá pendiente de configuración.
                    </p>
                </div>

                <button class="btn-recargar" onclick="cargarInventario()">Recargar</button>
            </div>

            <div id="inventarioContenedor"></div>
        </div>

        <div class="inventario-panel">
            <div class="panel-header">
                <div>
                    <h2>🗓️ Historial diario</h2>
                    <p>
                        Consulta las ventas de cualquier fecha para revisar cuántos productos se vendieron ese día.
                        Esto te sirve para ver el movimiento diario de inventario en días anteriores.
                    </p>
                </div>
            </div>

            <p class="historial-note">
                El historial muestra las <strong>ventas de la fecha seleccionada</strong>.
                Si ves stock en esta sección, corresponde al <strong>stock actual</strong>, porque el sistema no guarda cierres históricos por producto.
            </p>

            <div class="historial-toolbar">
                <div class="campo">
                    <label>Fecha del historial</label>
                    <input type="date" id="fechaHistorial">
                </div>

                <button class="btn-recargar" onclick="consultarHistorialSeleccionado()">Ver historial</button>
            </div>

            <div class="resumen-grid" id="resumenHistorial"></div>
            <div class="dias-recientes" id="diasRecientesHistorial"></div>
            <div id="detalleHistorial"></div>
        </div>
    </div>
</div>

<script>
const API_URL = "{{ env('API_URL') }}";
const TOKEN = localStorage.getItem('token');
let inventario = [];
let historialInventario = null;

function agruparPorCategoria(items) {
    return items.reduce((acc, item) => {
        if (!acc[item.categoria]) {
            acc[item.categoria] = [];
        }

        acc[item.categoria].push(item);
        return acc;
    }, {});
}

function renderResumen(items) {
    const stockFijo = items.filter(item => item.tipo_control_inventario === 'STOCK_FIJO');
    const conteoDiario = items.filter(item => item.tipo_control_inventario === 'CONTEO_DIARIO');
    const sinConfigurar = stockFijo.filter(item => item.stock_actual === null);
    const vendidosHoy = items.reduce((acc, item) => acc + Number(item.vendido_hoy || 0), 0);

    document.getElementById('resumenInventario').innerHTML = `
        <div class="resumen-card">
            <span>Productos con stock fijo</span>
            <strong>${stockFijo.length}</strong>
            <small>Ideales para aguas, gaseosas y unidades cerradas.</small>
        </div>
        <div class="resumen-card">
            <span>Productos con conteo diario</span>
            <strong>${conteoDiario.length}</strong>
            <small>Platos del día u ollas que solo quieres contar por ventas.</small>
        </div>
        <div class="resumen-card">
            <span>Vendidos hoy</span>
            <strong>${vendidosHoy}</strong>
            <small>Suma de cantidades vendidas hoy entre todos los productos.</small>
        </div>
        <div class="resumen-card">
            <span>Stock fijo pendiente</span>
            <strong>${sinConfigurar.length}</strong>
            <small>Productos con modo fijo pero sin cantidad inicial cargada.</small>
        </div>
    `;
}

function renderImagen(producto) {
    if (!producto.imagen) {
        return '<div class="inventario-placeholder">Sin imagen</div>';
    }

    return `<img src="${API_URL}/${producto.imagen}" alt="${producto.nombre}">`;
}

function renderModo(producto) {
    if (producto.tipo_control_inventario === 'STOCK_FIJO') {
        return '<span class="badge badge-modo">Stock fijo</span>';
    }

    if (producto.tipo_control_inventario === 'CONTEO_DIARIO') {
        return '<span class="badge badge-modo">Conteo diario</span>';
    }

    return '<span class="badge badge-modo">Sin control</span>';
}

function renderEstado(producto) {
    if (producto.tipo_control_inventario === 'STOCK_FIJO') {
        if (producto.stock_actual === null) {
            return '<div class="estado neutro">Este producto está en modo fijo, pero aún no tiene stock cargado.</div>';
        }

        return `<div class="estado stock">Stock actual: <strong>${producto.stock_actual}</strong>. Vendidos hoy: <strong>${producto.vendido_hoy}</strong>.</div>`;
    }

    if (producto.tipo_control_inventario === 'CONTEO_DIARIO') {
        return `<div class="estado diario">Vendidos hoy: <strong>${producto.vendido_hoy}</strong>. No se descuenta stock.</div>`;
    }

    return `<div class="estado neutro">Sin control automático. Vendidos hoy: <strong>${producto.vendido_hoy}</strong>.</div>`;
}

function renderBadgeSecundario(producto) {
    if (producto.tipo_control_inventario === 'STOCK_FIJO') {
        if (producto.stock_actual === null) {
            return '<span class="badge badge-neutro">Stock pendiente</span>';
        }

        return `<span class="badge badge-stock">Stock ${producto.stock_actual}</span>`;
    }

    if (producto.tipo_control_inventario === 'CONTEO_DIARIO') {
        return `<span class="badge badge-diario">Vendidos hoy ${producto.vendido_hoy}</span>`;
    }

    return '<span class="badge badge-neutro">Libre</span>';
}

function cambiarTipo(id) {
    const tipo = document.getElementById(`tipo-${id}`).value;
    const grupoStock = document.getElementById(`grupo-stock-${id}`);
    grupoStock.classList.toggle('oculto', tipo !== 'STOCK_FIJO');
}

function obtenerFechaEcuador() {
    const partes = new Intl.DateTimeFormat('en-CA', {
        timeZone:'America/Guayaquil',
        year:'numeric',
        month:'2-digit',
        day:'2-digit',
        hour:'2-digit',
        minute:'2-digit',
        second:'2-digit',
        hour12:false,
        hourCycle:'h23'
    }).formatToParts(new Date()).reduce((acumulado, parte) => {
        if (parte.type !== 'literal') {
            acumulado[parte.type] = Number(parte.value);
        }

        return acumulado;
    }, {});

    const fechaOperativa = new Date(Date.UTC(
        partes.year,
        partes.month - 1,
        partes.day
    ));

    if (partes.hour < 2) {
        fechaOperativa.setUTCDate(fechaOperativa.getUTCDate() - 1);
    }

    return fechaOperativa.toISOString().slice(0, 10);
}

function formatearFecha(fecha) {
    if (!fecha) {
        return '';
    }

    const [anio, mes, dia] = fecha.split('-').map(Number);
    const fechaLocal = new Date(anio, mes - 1, dia);

    return fechaLocal.toLocaleDateString('es-EC', {
        year:'numeric',
        month:'long',
        day:'numeric'
    });
}

function renderResumenHistorial(data) {
    const resumen = data?.resumen || {
        pedidos: 0,
        productos_vendidos: 0,
        unidades_vendidas: 0,
        total_vendido: 0
    };

    document.getElementById('resumenHistorial').innerHTML = `
        <div class="resumen-card">
            <span>Fecha consultada</span>
            <strong>${formatearFecha(data?.fecha)}</strong>
            <small>Resumen diario de ventas e inventario para esa fecha.</small>
        </div>
        <div class="resumen-card">
            <span>Pedidos registrados</span>
            <strong>${resumen.pedidos}</strong>
            <small>Pedidos activos tomados en cuenta para el historial.</small>
        </div>
        <div class="resumen-card">
            <span>Unidades vendidas</span>
            <strong>${resumen.unidades_vendidas}</strong>
            <small>Cantidad total de productos vendidos ese día.</small>
        </div>
        <div class="resumen-card">
            <span>Total vendido</span>
            <strong>$${Number(resumen.total_vendido || 0).toFixed(2)}</strong>
            <small>Ingresos generados por los productos vendidos ese día.</small>
        </div>
    `;
}

function renderDiasRecientes(dias, fechaActiva) {
    const contenedor = document.getElementById('diasRecientesHistorial');

    if (!dias.length) {
        contenedor.innerHTML = '';
        return;
    }

    contenedor.innerHTML = dias.map(dia => `
        <button
            class="dia-chip ${dia.fecha === fechaActiva ? 'activo' : ''}"
            onclick="cargarHistorial('${dia.fecha}')">
            ${formatearFecha(dia.fecha)}
        </button>
    `).join('');
}

function renderBadgeModoHistorial(producto) {
    if (producto.tipo_control_inventario === 'STOCK_FIJO') {
        return '<span class="badge badge-modo">Stock fijo</span>';
    }

    if (producto.tipo_control_inventario === 'CONTEO_DIARIO') {
        return '<span class="badge badge-modo">Conteo diario</span>';
    }

    return '<span class="badge badge-neutro">Sin control</span>';
}

function renderDetalleHistorial(data) {
    const contenedor = document.getElementById('detalleHistorial');
    const detalle = data?.detalle || [];

    if (!detalle.length) {
        contenedor.innerHTML = `
            <div class="historial-vacio">
                No hay ventas registradas para ${formatearFecha(data?.fecha)}.
            </div>
        `;
        return;
    }

    const grupos = agruparPorCategoria(detalle);
    let html = '';

    Object.keys(grupos).forEach(categoria => {
        html += `
            <section class="categoria-bloque">
                <h3>${categoria}</h3>
                <table class="historial-tabla">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Modo</th>
                            <th>Unidades</th>
                            <th>Pedidos</th>
                            <th>Total</th>
                            <th>Stock actual</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        grupos[categoria].forEach(producto => {
            const stockActual = producto.stock_actual === null
                ? 'Pendiente'
                : producto.stock_actual;

            html += `
                <tr>
                    <td>
                        <strong>${producto.nombre}</strong><br>
                        <span class="historial-stock-actual">$${Number(producto.precio).toFixed(2)} c/u</span>
                    </td>
                    <td>${renderBadgeModoHistorial(producto)}</td>
                    <td>${producto.vendido_fecha}</td>
                    <td>${producto.pedidos_con_producto}</td>
                    <td>$${Number(producto.total_facturado || 0).toFixed(2)}</td>
                    <td>${stockActual}</td>
                </tr>
            `;
        });

        html += `
                    </tbody>
                </table>
            </section>
        `;
    });

    contenedor.innerHTML = html;
}

function renderInventario() {
    const grupos = agruparPorCategoria(inventario);
    const contenedor = document.getElementById('inventarioContenedor');
    contenedor.innerHTML = '';

    Object.keys(grupos).forEach(categoria => {
        let html = `
            <section class="categoria-bloque">
                <h3>${categoria}</h3>
                <div class="inventario-grid">
        `;

        grupos[categoria].forEach(producto => {
            html += `
                <article class="inventario-card">
                    ${renderImagen(producto)}

                    <div class="inventario-top">
                        <div>
                            <h4>${producto.nombre}</h4>
                            <div class="precio">$${Number(producto.precio).toFixed(2)}</div>
                        </div>
                    </div>

                    <div class="badges">
                        ${renderModo(producto)}
                        ${renderBadgeSecundario(producto)}
                    </div>

                    <div class="campo">
                        <label>Control de inventario</label>
                        <select id="tipo-${producto.id_producto}" onchange="cambiarTipo(${producto.id_producto})">
                            <option value="SIN_CONTROL" ${producto.tipo_control_inventario === 'SIN_CONTROL' ? 'selected' : ''}>Sin control</option>
                            <option value="STOCK_FIJO" ${producto.tipo_control_inventario === 'STOCK_FIJO' ? 'selected' : ''}>Stock fijo</option>
                            <option value="CONTEO_DIARIO" ${producto.tipo_control_inventario === 'CONTEO_DIARIO' ? 'selected' : ''}>Conteo diario</option>
                        </select>
                    </div>

                    <div class="campo ${producto.tipo_control_inventario === 'STOCK_FIJO' ? '' : 'oculto'}" id="grupo-stock-${producto.id_producto}">
                        <label>Stock actual</label>
                        <input
                            id="stock-${producto.id_producto}"
                            type="number"
                            min="0"
                            value="${producto.stock_actual ?? ''}"
                            placeholder="Ej. 24">
                    </div>

                    <label class="switch">
                        <input type="checkbox" id="disponible-${producto.id_producto}" ${producto.disponible ? 'checked' : ''}>
                        Disponible para vender
                    </label>

                    ${renderEstado(producto)}

                    <button class="btn-guardar" onclick="guardarInventario(${producto.id_producto})">
                        Guardar cambios
                    </button>
                </article>
            `;
        });

        html += '</div></section>';
        contenedor.innerHTML += html;
    });
}

async function guardarInventario(id) {
    const tipo = document.getElementById(`tipo-${id}`).value;
    const stockInput = document.getElementById(`stock-${id}`);
    const disponible = document.getElementById(`disponible-${id}`).checked ? 1 : 0;

    const payload = {
        tipo_control_inventario: tipo,
        disponible
    };

    payload.stock_actual = tipo === 'STOCK_FIJO'
        ? (stockInput.value === '' ? null : Number(stockInput.value))
        : null;

    const res = await fetch(API_URL + '/inventario/' + id, {
        method:'PUT',
        headers:{
            'Content-Type':'application/json',
            'Authorization':'Bearer ' + TOKEN
        },
        body:JSON.stringify(payload)
    });

    const json = await res.json();

    if (!res.ok) {
        alert(json.message || 'No se pudo actualizar el inventario');
        return;
    }

    await cargarInventario();
}

async function cargarHistorial(fecha = document.getElementById('fechaHistorial').value) {
    const fechaConsulta = fecha || obtenerFechaEcuador();
    document.getElementById('fechaHistorial').value = fechaConsulta;

    const res = await fetch(
        `${API_URL}/inventario/historial?fecha=${encodeURIComponent(fechaConsulta)}`,
        {
            headers:{ 'Authorization':'Bearer ' + TOKEN }
        }
    );

    const data = await res.json();

    if (!res.ok) {
        alert(data.message || 'No se pudo cargar el historial');
        return;
    }

    historialInventario = data;
    renderResumenHistorial(historialInventario);
    renderDiasRecientes(historialInventario.dias_recientes || [], historialInventario.fecha);
    renderDetalleHistorial(historialInventario);
}

function consultarHistorialSeleccionado() {
    cargarHistorial(document.getElementById('fechaHistorial').value);
}

async function cargarInventario() {
    const res = await fetch(API_URL + '/inventario', {
        headers:{ 'Authorization':'Bearer ' + TOKEN }
    });

    const data = await res.json();

    if (!res.ok) {
        alert(data.message || 'No se pudo cargar el inventario');
        return;
    }

    inventario = data;
    renderResumen(inventario);
    renderInventario();
}

document.getElementById('fechaHistorial').value = obtenerFechaEcuador();
cargarInventario();
cargarHistorial();
</script>

@endsection
