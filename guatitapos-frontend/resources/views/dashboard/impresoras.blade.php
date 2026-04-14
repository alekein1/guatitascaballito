@extends('dashboard.layout')

@section('titulo', 'Configuración de Impresoras')

@section('contenido')

<style>
    .impresoras-grid{
        display:grid;
        grid-template-columns: 420px 1fr;
        gap:30px;
        align-items:flex-start;
    }

    .box{
        background:#fff;
        padding:30px;
        border-radius:20px;
        box-shadow:0 15px 30px rgba(0,0,0,.1);
    }

    h2{
        color:#7a2d2d;
        margin-bottom:20px;
    }

    .form-group{
        margin-bottom:16px;
    }

    .form-group label{
        font-weight:600;
        margin-bottom:6px;
        display:block;
    }

    .form-group input,
    .form-group select{
        width:100%;
        padding:13px;
        border-radius:12px;
        border:1px solid #ccc;
        font-size:15px;
    }

    .btn{
        padding:12px 22px;
        border-radius:30px;
        border:none;
        font-weight:600;
        cursor:pointer;
        font-size:14px;
    }

    .btn-guardar{ background:#7a2d2d; color:#fff; }
    .btn-test{ background:#0d6efd; color:#fff; }
    .btn-off{ background:#6c757d; color:#fff; }

    .mensaje{
        margin-top:12px;
        padding:12px;
        border-radius:10px;
        display:none;
        font-size:14px;
    }

    .success{ background:#d4edda; color:#155724; }
    .error{ background:#f8d7da; color:#721c24; }

    table{
        width:100%;
        border-collapse:collapse;
    }

    th, td{
        padding:12px;
        border-bottom:1px solid #eee;
        text-align:left;
        font-size:14px;
    }

    th{
        background:#f8f8f8;
    }

    .estado-activa{
        color:green;
        font-weight:bold;
    }

    .estado-inactiva{
        color:#999;
        font-weight:bold;
    }
</style>

<div class="impresoras-grid">

    {{-- FORMULARIO --}}
    <div class="box">
        <h2>➕ Agregar Impresora</h2>

        <form id="impresoraForm">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" placeholder="Ej: Impresora Cocina" required>
            </div>

            <div class="form-group">
                <label>IP</label>
                <input type="text" name="ip" placeholder="Ej: 192.168.1.50" required>
            </div>

            <div class="form-group">
                <label>Puerto</label>
                <input type="number" name="puerto" value="9100">
            </div>

            <div class="form-group">
                <label>Tipo</label>
                <select name="tipo" required>
                    <option value="">Seleccione</option>
                    <option value="COCINA">Cocina</option>
                    <option value="FACTURA">Factura</option>
                </select>
            </div>

            <button class="btn btn-guardar">Guardar</button>

            <div id="mensaje" class="mensaje"></div>
        </form>
    </div>

    {{-- LISTADO --}}
    <div class="box">
        <h2>🖨️ Impresoras Registradas</h2>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>IP</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaImpresoras"></tbody>
        </table>
    </div>

</div>

<script>
    const API_URL = "{{ env('API_URL') }}";
    const token = localStorage.getItem('token');

    const form = document.getElementById('impresoraForm');
    const mensaje = document.getElementById('mensaje');
    const tabla = document.getElementById('tablaImpresoras');

    form.addEventListener('submit', async e => {
        e.preventDefault();
        mensaje.style.display='none';

        const data = Object.fromEntries(new FormData(form));

        const res = await fetch(API_URL + '/impresoras', {
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'Authorization':'Bearer ' + token
            },
            body:JSON.stringify(data)
        });

        const json = await res.json();

        if(res.ok){
            mensaje.className='mensaje success';
            mensaje.innerText='Impresora registrada correctamente';
            mensaje.style.display='block';
            form.reset();
            cargarImpresoras();
        }else{
            mensaje.className='mensaje error';
            mensaje.innerText=json.message;
            mensaje.style.display='block';
        }
    });

    async function cargarImpresoras(){
        const res = await fetch(API_URL + '/impresoras', {
            headers:{ 'Authorization':'Bearer ' + token }
        });

        const data = await res.json();
        tabla.innerHTML='';

        data.forEach(i=>{
            tabla.innerHTML += `
                <tr>
                    <td>${i.nombre}</td>
                    <td>${i.ip}:${i.puerto}</td>
                    <td>${i.tipo}</td>
                    <td class="${i.activa ? 'estado-activa':'estado-inactiva'}">
                        ${i.activa ? 'Activa':'Inactiva'}
                    </td>
                    <td>
                        <button class="btn btn-test" onclick="probar(${i.id_impresora})">
                            Probar
                        </button>
                    </td>
                </tr>
            `;
        });
    }

    async function probar(id){
        await fetch(API_URL + '/impresoras/' + id + '/prueba', {
            method:'POST',
            headers:{ 'Authorization':'Bearer ' + token }
        });

        alert('Prueba enviada (ver backend)');
    }

    cargarImpresoras();
</script>

@endsection