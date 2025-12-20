@extends('dashboard.layout')

@section('titulo', 'Productos')

@section('contenido')

<style>
    .productos-grid{
        display:grid;
        grid-template-columns: 420px 1fr;
        gap:30px;
        align-items:flex-start;
    }

    /* FORMULARIO */
    .producto-form{
        background:#fff;
        padding:30px;
        border-radius:20px;
        box-shadow:0 15px 30px rgba(0,0,0,.1);
    }

    .producto-form h2{
        margin-bottom:25px;
        color:#7a2d2d;
    }

    .form-group{
        margin-bottom:16px;
    }

    .form-group label{
        font-weight:600;
        display:block;
        margin-bottom:6px;
    }

    .form-group input,
    .form-group select{
        width:100%;
        padding:13px;
        border-radius:12px;
        border:1px solid #ccc;
        font-size:15px;
    }

    .btn-guardar{
        margin-top:15px;
        background:#7a2d2d;
        color:#fff;
        border:none;
        padding:13px 25px;
        border-radius:30px;
        font-weight:600;
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

    /* LISTADO */
    .productos-lista{
        background:#fff;
        padding:30px;
        border-radius:20px;
        box-shadow:0 15px 30px rgba(0,0,0,.1);
    }

    .categoria{
        margin-bottom:30px;
    }

    .categoria h3{
        color:#7a2d2d;
        margin-bottom:15px;
        border-bottom:2px solid #f0e6e0;
        padding-bottom:5px;
    }

    .productos-cards{
        display:grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap:15px;
    }

    .producto-card{
        border:1px solid #eee;
        border-radius:15px;
        padding:12px;
        text-align:center;
    }

    .producto-card img{
        width:100%;
        height:110px;
        object-fit:cover;
        border-radius:12px;
        margin-bottom:8px;
    }

    .producto-card h4{
        font-size:15px;
        margin:5px 0;
    }

    .producto-card span{
        font-weight:bold;
        color:#7a2d2d;
    }
</style>

<div class="productos-grid">

    {{-- IZQUIERDA: FORMULARIO --}}
    <div class="producto-form">
        <h2>➕ Crear Producto</h2>

        <form id="productoForm" enctype="multipart/form-data">
            <div class="form-group">
                <label>Categoría</label>
                <select name="id_categoria" required>
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
                <label>Imagen</label>
                <input type="file" name="imagen" accept="image/*">
            </div>

            <button class="btn-guardar">Guardar Producto</button>
            <div id="mensaje" class="mensaje"></div>
        </form>
    </div>

    {{-- DERECHA: LISTADO --}}
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

        if(res.ok){
            mensaje.className='mensaje success';
            mensaje.innerText='Producto creado correctamente';
            mensaje.style.display='block';
            form.reset();
            cargarProductos();
        }else{
            mensaje.className='mensaje error';
            mensaje.innerText=json.message;
            mensaje.style.display='block';
        }
    });

    async function cargarProductos(){
        const res = await fetch(API_URL + '/productos', {
            headers:{ 'Authorization':'Bearer ' + token }
        });

        const data = await res.json();
        contenedor.innerHTML='';

        for(const categoria in data){
            const productos = data[categoria];

            let html = `
                <div class="categoria">
                    <h3>${categoria}</h3>
                    <div class="productos-cards">
            `;

            productos.forEach(p=>{
                html += `
                    <div class="producto-card">
                        <img src="${API_URL.replace('/api','')}/${p.imagen}">
                        <h4>${p.nombre}</h4>
                        <span>$${p.precio}</span>
                    </div>
                `;
            });

            html += '</div></div>';
            contenedor.innerHTML += html;
        }
    }

    cargarProductos();
</script>

@endsection