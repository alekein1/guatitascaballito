@extends('dashboard.layout')

@section('titulo', 'Pedidos del Día')

@section('contenido')

<style>
/* ===============================
   LAYOUT
================================ */
.pedidos-container{
    display:grid;
    grid-template-columns: 1fr 420px;
    gap:25px;
}

/* ===============================
   CAJAS
================================ */
.box{
    background:#fff;
    border-radius:22px;
    padding:22px;
    box-shadow:0 12px 28px rgba(0,0,0,.12);
}

/* ===============================
   TITULOS
================================ */
.box h2{
    font-size:22px;
    margin-bottom:15px;
    color:#7a2d2d;
}

/* ===============================
   TABLA
================================ */
table{
    width:100%;
    border-collapse:collapse;
    font-size:15px;
}

th, td{
    padding:12px;
    border-bottom:1px solid #eee;
    text-align:left;
}

th{
    background:#f8f8f8;
}

tr:hover{
    background:#fdf1f1;
    cursor:pointer;
}

/* ===============================
   BADGES
================================ */
.badge{
    padding:6px 12px;
    border-radius:20px;
    font-weight:600;
    font-size:13px;
}

.mesa{ background:#0d6efd; color:#fff; }
.llevar{ background:#6c757d; color:#fff; }

/* ===============================
   DETALLE
================================ */
.detalle-item{
    display:flex;
    justify-content:space-between;
    margin-bottom:8px;
    font-size:16px;
}

.total{
    font-size:22px;
    font-weight:bold;
    margin-top:15px;
    color:#7a2d2d;
}

/* ===============================
   BOTONES
================================ */
.btn{
    margin-top:15px;
    width:100%;
    padding:14px;
    border:none;
    border-radius:30px;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
}

.btn-reimprimir{
    background:#198754;
    color:#fff;
}
</style>

<div class="pedidos-container">

    {{-- LISTADO --}}
    <div class="box">
        <h2>📋 Pedidos de Hoy</h2>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Hora</th>
                    <th>Mesa</th>
                    <th>Tipo</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody id="tablaPedidos"></tbody>
        </table>
    </div>

    {{-- DETALLE --}}
    <div class="box">
        <h2>🧾 Detalle del Pedido</h2>

        <div id="detallePedido">
            <p>Seleccione un pedido</p>
        </div>
    </div>

</div>

<script>
const API_URL = "{{ env('API_URL') }}";
const token = localStorage.getItem('token');

const tabla = document.getElementById('tablaPedidos');
const detalle = document.getElementById('detallePedido');

/* ===============================
   📋 CARGAR PEDIDOS DEL DÍA
================================ */
async function cargarPedidos(){
    const res = await fetch(API_URL + '/pedidos-dia/hoy', {
        headers:{
            'Authorization':'Bearer ' + token
        }
    });

    const data = await res.json();
    tabla.innerHTML = '';

    data.forEach(p => {
        tabla.innerHTML += `
            <tr onclick="verDetalle(${p.id_pedido})">
                <td>${String(p.numero_pedido).padStart(3,'0')}</td>
                <td>${p.hora_pedido}</td>
                <td>${p.numero_mesa || '-'}</td>
                <td>
                    <span class="badge ${p.tipo_consumo === 'MESA' ? 'mesa' : 'llevar'}">
                        ${p.tipo_consumo}
                    </span>
                </td>
                <td>$${p.total}</td>
            </tr>
        `;
    });
}

/* ===============================
   🔍 VER DETALLE
================================ */
async function verDetalle(id){
    const res = await fetch(API_URL + '/pedidos-dia/' + id, {
        headers:{
            'Authorization':'Bearer ' + token
        }
    });

    const data = await res.json();

    let html = `
        <p><strong>Pedido:</strong> ${String(data.pedido.numero_pedido).padStart(3,'0')}</p>
        <p><strong>Cliente:</strong> ${data.pedido.nombre_cliente || 'Consumidor Final'}</p>
        <p><strong>Mesa:</strong> ${data.pedido.numero_mesa || '-'}</p>
        <hr>
    `;

    data.detalle.forEach(i => {
        html += `
            <div class="detalle-item">
                <span>${i.nombre_producto} x${i.cantidad}</span>
                <span>$${i.subtotal}</span>
            </div>
        `;
    });

    html += `
        <div class="total">TOTAL: $${data.pedido.total}</div>

        <button class="btn btn-reimprimir"
            onclick="reimprimirFactura(${data.pedido.id_pedido})">
            🖨️ Reimprimir Factura
        </button>
    `;

    detalle.innerHTML = html;
}

/* ===============================
   🖨️ REIMPRIMIR FACTURA
================================ */
async function reimprimirFactura(id) {
    if (!confirm('¿Reimprimir factura?')) return;

    await fetch(API_URL + '/pedidos/' + id + '/reimprimir-factura', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + token
        }
    });

    alert('Factura enviada a impresión');
}

cargarPedidos();
</script>

@endsection