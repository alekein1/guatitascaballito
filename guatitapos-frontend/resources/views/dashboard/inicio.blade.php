@extends('dashboard.layout')
@section('titulo', 'Dashboard')

@section('contenido')

<style>
    .dashboard-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));
        gap:25px;
        margin-bottom:40px;
    }

    .card{
        background:#fff;
        border-radius:18px;
        padding:25px;
        box-shadow:0 12px 25px rgba(0,0,0,.08);
        display:flex;
        align-items:center;
        gap:20px;
    }

    .card-icon{
        font-size:42px;
        width:70px;
        height:70px;
        border-radius:16px;
        display:flex;
        align-items:center;
        justify-content:center;
        color:#fff;
    }

    .bg-vino{ background:#7a2d2d; }
    .bg-mostaza{ background:#d4a640; color:#000; }
    .bg-verde{ background:#27ae60; }
    .bg-azul{ background:#2980b9; }

    .card-info h3{
        margin:0;
        font-size:26px;
        color:#333;
    }

    .card-info span{
        font-size:14px;
        color:#777;
    }

    .welcome{
        background:linear-gradient(135deg, #7a2d2d, #4e1b1b);
        color:#fff;
        padding:35px;
        border-radius:22px;
        box-shadow:0 15px 30px rgba(0,0,0,.2);
    }

    .welcome h2{
        font-size:32px;
        margin-bottom:10px;
    }

    .welcome p{
        font-size:17px;
        opacity:.95;
    }
</style>

{{-- TARJETAS RESUMEN --}}
<div class="dashboard-grid">

    <div class="card">
        <div class="card-icon bg-vino">🍽️</div>
        <div class="card-info">
            <h3 id="totalProductos">0</h3>
<span>Productos registrados</span>
            <span>Productos registrados</span>
        </div>
    </div>

    <div class="card">
        <div class="card-icon bg-mostaza">🧾</div>
        <div class="card-info">
            <h3 id="pedidosHoy">0</h3>
<span>Pedidos del día</span>
            <span>Pedidos del día</span>
        </div>
    </div>

    <div class="card">
        <div class="card-icon bg-verde">💵</div>
        <div class="card-info">
            <h3 id="ventasHoy">$0.00</h3>
<span>Ventas hoy</span>
            <span>Ventas hoy</span>
        </div>
    </div>

    <div class="card">
        <div class="card-icon bg-azul">📦</div>
        <div class="card-info">
            <h3 id="categoriasActivas">0</h3>
<span>Categorías activas</span>
            <span>Categorías activas</span>
        </div>
    </div>

</div>

{{-- BIENVENIDA --}}
<div class="welcome">
    <h2>Bienvenido a GuatitasPOS</h2>
    <p>
        Desde este panel puedes administrar productos, registrar pedidos,
        controlar ventas diarias y mantener el flujo de atención
        rápido y ordenado.
    </p>
</div>
<script>
const API_URL = "{{ env('API_URL') }}";
const token = localStorage.getItem('token');

async function cargarDashboard() {
    try {
        const res = await fetch(`${API_URL}/dashboard/estadisticas`, {
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await res.json();

        document.getElementById('totalProductos').innerText = data.productos;
        document.getElementById('pedidosHoy').innerText = data.pedidos_hoy;
        document.getElementById('ventasHoy').innerText = `$${data.ventas_hoy.toFixed(2)}`;
        document.getElementById('categoriasActivas').innerText = data.categorias;

    } catch (e) {
        console.error('Error cargando dashboard', e);
    }
}

cargarDashboard();
</script>
@endsection