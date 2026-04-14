@extends('dashboard.layout')

@section('titulo', 'POS - Ventas')

@section('contenido')

<style>
.pos-container{
    display:grid;
    grid-template-columns: 420px 1fr;
    gap:20px;
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

/* CATEGORIAS */
.categorias button{
    margin:5px 5px 10px 0;
    padding:12px 22px;
    border:none;
    border-radius:22px;
    background:#eee;
    font-weight:600;
    font-size:15px;
    cursor:pointer;
}

.categorias button.active{
    background:#7a2d2d;
    color:#fff;
}

/* PRODUCTOS */
.productos{
    display:grid;
    grid-template-columns: repeat(auto-fill, minmax(170px,1fr));
    gap:18px;
    margin-top:10px;
}

.producto{
    border:1px solid #eee;
    border-radius:18px;
    padding:14px;
    text-align:center;
    cursor:pointer;
}

.producto img{
    width:100%;
    height:110px;
    object-fit:cover;
    border-radius:14px;
}

.producto strong{
    display:block;
    margin-top:8px;
    font-size:17px;
}

.producto span{
    font-size:16px;
    font-weight:600;
}

/* PEDIDO */
.pedido-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:10px;
    font-size:16px;
}

.pedido-item button{
    background:#dc3545;
    border:none;
    color:#fff;
    border-radius:50%;
    width:26px;
    height:26px;
    cursor:pointer;
}

.total{
    font-size:24px;
    font-weight:bold;
    margin-top:15px;
}

/* INPUTS */
input, select{
    width:100%;
    padding:12px;
    margin-bottom:10px;
    border-radius:12px;
    border:1px solid #ccc;
    font-size:16px;
}

/* BOTON */
.btn-confirmar{
    margin-top:18px;
    width:100%;
    padding:16px;
    background:#7a2d2d;
    color:#fff;
    border:none;
    border-radius:30px;
    font-size:18px;
    font-weight:600;
    cursor:pointer;
}
</style>

<div class="pos-container">

    {{-- IZQUIERDA: PEDIDO --}}
    <div class="box">
        <h2>🧾 Pedido</h2>

        <input id="nombre_cliente" placeholder="Nombre cliente (opcional)">

        <select id="tipo_consumo" onchange="cambiarTipo()">
            <option value="MESA">Servirse</option>
            <option value="LLEVAR">Para llevar</option>
        </select>

        <input id="numero_mesa" placeholder="Mesa o ficha">

        <div id="pedido"></div>

        <div class="total">
            Subtotal: $<span id="subtotal">0.00</span><br>
            Total: $<span id="total">0.00</span>
        </div>

        <button class="btn-confirmar" onclick="confirmarPedido()">
            CONFIRMAR PEDIDO
        </button>
    </div>

    {{-- DERECHA: PRODUCTOS --}}
    <div class="box">
        <h2>🍽️ Productos</h2>

        <div class="categorias" id="categorias"></div>
        <div class="productos" id="productos"></div>
    </div>

</div>

<script>
const API_URL = "{{ env('API_URL') }}";
const TOKEN = localStorage.getItem('token');

let productosPorCategoria = {};
let pedido = [];

/* CARGAR PRODUCTOS */
async function cargarProductos(){
    const res = await fetch(API_URL + '/productos', {
        headers:{ 'Authorization':'Bearer ' + TOKEN }
    });
    productosPorCategoria = await res.json();

    const categorias = Object.keys(productosPorCategoria);
    const contCat = document.getElementById('categorias');
    contCat.innerHTML='';

    categorias.forEach((c,i)=>{
        const btn = document.createElement('button');
        btn.innerText = c;
        btn.className = i===0?'active':'';
        btn.onclick = ()=>mostrarProductos(c,btn);
        contCat.appendChild(btn);
    });

    mostrarProductos(categorias[0], contCat.children[0]);
}

/* MOSTRAR PRODUCTOS */
function mostrarProductos(cat, btn){
    document.querySelectorAll('.categorias button').forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');

    const cont = document.getElementById('productos');
    cont.innerHTML='';

    const lista = productosPorCategoria[cat] || [];

    lista.forEach(p=>{
        cont.innerHTML += `
            <div class="producto" onclick='agregarProducto(${JSON.stringify(p)})'>
                <img src="${API_URL.replace('/api','')}/${p.imagen}">
                <strong>${p.nombre}</strong>
                <span>$${p.precio}</span>
            </div>
        `;
    });
}

/* AGREGAR PRODUCTO */
function agregarProducto(p){
    const existe = pedido.find(i=>i.id_producto===p.id_producto);
    if(existe){
        existe.cantidad++;
    }else{
        pedido.push({...p,cantidad:1});
    }
    renderPedido();
}

/* ELIMINAR PRODUCTO */
function eliminarProducto(id){
    pedido = pedido.filter(p=>p.id_producto!==id);
    renderPedido();
}

/* RENDER PEDIDO */
function renderPedido(){
    const cont = document.getElementById('pedido');
    let subtotal = 0;
    cont.innerHTML='';

    pedido.forEach(p=>{
        subtotal += p.precio * p.cantidad;
        cont.innerHTML += `
            <div class="pedido-item">
                ${p.nombre} x${p.cantidad}
                $${(p.precio*p.cantidad).toFixed(2)}
                <button onclick="eliminarProducto(${p.id_producto})">✖</button>
            </div>
        `;
    });

    document.getElementById('subtotal').innerText = subtotal.toFixed(2);
    document.getElementById('total').innerText = subtotal.toFixed(2);
}

/* CAMBIO SERVIRSE / LLEVAR */
function cambiarTipo(){
    const tipo = document.getElementById('tipo_consumo').value;
    const mesa = document.getElementById('numero_mesa');

    if(tipo === 'LLEVAR'){
        mesa.style.display = 'none';
        mesa.value = '';
    }else{
        mesa.style.display = 'block';
    }
}

/* CONFIRMAR PEDIDO */
async function confirmarPedido(){
    if(pedido.length === 0){
        alert('Agregue al menos un producto');
        return;
    }

    const body = {
        nombre_cliente: document.getElementById('nombre_cliente').value,
        numero_mesa: document.getElementById('numero_mesa').value,
        tipo_consumo: document.getElementById('tipo_consumo').value,
        productos: pedido
    };

    await fetch(API_URL + '/pedidos',{
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'Authorization':'Bearer ' + TOKEN
        },
        body:JSON.stringify(body)
    });

    alert('Pedido registrado correctamente');
    location.reload();
}

cargarProductos();
</script>

@endsection