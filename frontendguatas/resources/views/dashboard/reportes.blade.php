@extends('dashboard.layout')

@section('titulo', 'Reportes del Día')

@section('contenido')

<style>
.reportes-container{
    display:grid;
    grid-template-columns: 1fr 420px;
    gap:25px;
}

/* CAJAS */
.box{
    background:#fff;
    border-radius:22px;
    padding:22px;
    box-shadow:0 12px 28px rgba(0,0,0,.12);
}

/* TITULOS */
.box h2{
    font-size:22px;
    margin-bottom:15px;
    color:#7a2d2d;
}

/* TARJETAS */
.cards{
    display:grid;
    grid-template-columns: repeat(3,1fr);
    gap:20px;
}

.card{
    padding:22px;
    border-radius:20px;
    text-align:center;
    font-size:18px;
    font-weight:bold;
}

.ingresos{ background:#d1e7dd; color:#0f5132; }
.egresos{ background:#f8d7da; color:#842029; }
.saldo{ background:#cff4fc; color:#055160; }

.card span{
    display:block;
    font-size:28px;
    margin-top:8px;
}

/* FORM */
input{
    width:100%;
    padding:14px;
    border-radius:14px;
    border:1px solid #ccc;
    margin-bottom:12px;
    font-size:15px;
}

/* BOTONES */
.btn{
    width:100%;
    padding:14px;
    border:none;
    border-radius:30px;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    margin-top:10px;
}

.btn-egreso{ background:#dc3545; color:#fff; }
.btn-cierre{ background:#198754; color:#fff; }

/* TABLA */
table{
    width:100%;
    border-collapse:collapse;
    font-size:14px;
}

th, td{
    padding:10px;
    border-bottom:1px solid #eee;
}

th{ background:#f8f8f8; }

</style>

<div class="reportes-container">

    {{-- IZQUIERDA --}}
    <div class="box">
        <h2>📊 Resumen del Día</h2>

        <div class="cards">
            <div class="card ingresos">
                Ventas
                <span>$<span id="totalVentas">0.00</span></span>
            </div>

            <div class="card egresos">
                Egresos
                <span>$<span id="totalEgresos">0.00</span></span>
            </div>

            <div class="card saldo">
                Saldo
                <span>$<span id="saldoFinal">0.00</span></span>
            </div>
        </div>

        <button class="btn btn-cierre" onclick="cerrarCaja()">
            🧾 Cerrar Caja
        </button>
    </div>

    {{-- DERECHA --}}
    <div class="box">
        <h2>💸 Registrar Egreso</h2>

        <input id="descripcion" placeholder="Descripción del egreso">
        <input id="monto" type="number" step="0.01" placeholder="Monto">

        <button class="btn btn-egreso" onclick="guardarEgreso()">
            Registrar Egreso
        </button>

        <hr style="margin:20px 0">

        <h2>📋 Egresos del Día</h2>

        <table>
            <thead>
                <tr>
                    <th>Hora</th>
                    <th>Descripción</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody id="tablaEgresos"></tbody>
        </table>
    </div>

</div>

<script>
const API_URL = "{{ env('API_URL') }}";
const token = localStorage.getItem('token');

/* ===============================
   CARGAR REPORTE
================================ */
async function cargarReporte(){
    const res = await fetch(API_URL + '/reportes/hoy', {
        headers:{ 'Authorization':'Bearer ' + token }
    });
    const d = await res.json();

    document.getElementById('totalVentas').innerText = d.total_vendido.toFixed(2);
    document.getElementById('totalEgresos').innerText = d.total_egresos.toFixed(2);
    document.getElementById('saldoFinal').innerText = d.saldo_actual.toFixed(2);
}

/* ===============================
   EGRESOS
================================ */
async function cargarEgresos(){
    const res = await fetch(API_URL + '/reportes/egresos/hoy', {
        headers:{ 'Authorization':'Bearer ' + token }
    });

    const data = await res.json();
    const tabla = document.getElementById('tablaEgresos');
    tabla.innerHTML='';

    data.forEach(e=>{
        tabla.innerHTML += `
            <tr>
                <td>${e.hora}</td>
                <td>${e.descripcion}</td>
                <td>$${Number(e.monto).toFixed(2)}</td>
            </tr>
        `;
    });
}

/* ===============================
   GUARDAR EGRESO
================================ */
async function guardarEgreso(){
    const descripcion = document.getElementById('descripcion').value;
    const monto = document.getElementById('monto').value;

    if(!descripcion || !monto){
        alert('Complete todos los campos');
        return;
    }

    await fetch(API_URL + '/reportes/egresos', {
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'Authorization':'Bearer ' + token
        },
        body:JSON.stringify({ descripcion, monto })
    });

    document.getElementById('descripcion').value='';
    document.getElementById('monto').value='';

    cargarReporte();
    cargarEgresos();
}

/* ===============================
   CIERRE DE CAJA
================================ */
async function cerrarCaja(){
    if(!confirm('¿Desea cerrar la caja del día?')) return;

    const res = await fetch(API_URL + '/reportes/cierre-caja', {
        method:'POST',
        headers:{ 'Authorization':'Bearer ' + token }
    });

    const d = await res.json();
    alert(`Caja cerrada\nSaldo final: $${d.saldo_final.toFixed(2)}`);
}

/* INIT */
cargarReporte();
cargarEgresos();
</script>

@endsection