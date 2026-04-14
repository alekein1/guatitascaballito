@extends('dashboard.layout')

@section('titulo', 'POS - Ventas')

@section('contenido')

<style>
.pos-container{
    display:grid;
    grid-template-columns:420px 1fr;
    gap:20px;
}

.box{
    background:#fff;
    border-radius:22px;
    padding:22px;
    box-shadow:0 12px 28px rgba(0,0,0,.12);
}

.box h2{
    font-size:22px;
    margin:0 0 15px;
    color:#7a2d2d;
}

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

.productos{
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(170px,1fr));
    gap:18px;
    margin-top:10px;
}

.producto{
    border:1px solid #eee;
    border-radius:18px;
    padding:14px;
    text-align:center;
    cursor:pointer;
    transition:transform .2s ease, box-shadow .2s ease;
}

.producto:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 22px rgba(0,0,0,.08);
}

.producto img,
.producto-placeholder{
    width:100%;
    height:110px;
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

.producto strong{
    display:block;
    margin-top:8px;
    font-size:17px;
}

.producto span{
    display:block;
    font-size:16px;
    font-weight:600;
    margin-top:4px;
}

.meta-producto{
    display:flex;
    justify-content:center;
    flex-wrap:wrap;
    gap:8px;
    margin-top:8px;
}

.tag{
    display:inline-flex;
    align-items:center;
    padding:5px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
}

.tag-stock{
    background:#e8f4ea;
    color:#166534;
}

.tag-diario{
    background:#eef2ff;
    color:#4338ca;
}

.pedido-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
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

input, select{
    width:100%;
    padding:12px;
    margin-bottom:10px;
    border-radius:12px;
    border:1px solid #ccc;
    font-size:16px;
}

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

@media (max-width: 1024px){
    .pos-container{
        grid-template-columns:1fr;
    }
}
</style>

<div class="pos-container">

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

function renderImagen(producto) {
    if (!producto.imagen) {
        return '<div class="producto-placeholder">Sin imagen</div>';
    }

    return `<img src="${API_URL}/${producto.imagen}" alt="${producto.nombre}">`;
}

function renderMetaProducto(producto) {
    if (producto.tipo_control_inventario === 'STOCK_FIJO' && producto.stock_actual !== null) {
        return `<div class="meta-producto"><small class="tag tag-stock">Stock: ${producto.stock_actual}</small></div>`;
    }

    if (producto.tipo_control_inventario === 'CONTEO_DIARIO') {
        return `<div class="meta-producto"><small class="tag tag-diario">Vendidos hoy: ${producto.vendido_hoy}</small></div>`;
    }

    return '';
}

async function cargarProductos() {
    const res = await fetch(API_URL + '/productos', {
        headers:{ 'Authorization':'Bearer ' + TOKEN }
    });

    productosPorCategoria = await res.json();

    const categorias = Object.keys(productosPorCategoria);
    const contCat = document.getElementById('categorias');
    contCat.innerHTML = '';

    if (!categorias.length) {
        document.getElementById('productos').innerHTML = '<p>No hay productos disponibles.</p>';
        return;
    }

    categorias.forEach((categoria, index) => {
        const btn = document.createElement('button');
        btn.innerText = categoria;
        btn.className = index === 0 ? 'active' : '';
        btn.onclick = () => mostrarProductos(categoria, btn);
        contCat.appendChild(btn);
    });

    mostrarProductos(categorias[0], contCat.children[0]);
}

function mostrarProductos(categoria, boton) {
    document.querySelectorAll('.categorias button').forEach(item => item.classList.remove('active'));
    boton.classList.add('active');

    const cont = document.getElementById('productos');
    cont.innerHTML = '';

    const lista = productosPorCategoria[categoria] || [];

    lista.forEach(producto => {
        cont.innerHTML += `
            <div class="producto" onclick='agregarProducto(${JSON.stringify(producto)})'>
                ${renderImagen(producto)}
                <strong>${producto.nombre}</strong>
                <span>$${Number(producto.precio).toFixed(2)}</span>
                ${renderMetaProducto(producto)}
            </div>
        `;
    });
}

function agregarProducto(producto) {
    const existe = pedido.find(item => item.id_producto === producto.id_producto);

    if (
        producto.tipo_control_inventario === 'STOCK_FIJO' &&
        producto.stock_actual !== null
    ) {
        const cantidadActual = existe ? existe.cantidad : 0;

        if (cantidadActual >= producto.stock_actual) {
            alert(`No hay mas stock disponible para ${producto.nombre}`);
            return;
        }
    }

    if (existe) {
        existe.cantidad++;
    } else {
        pedido.push({ ...producto, cantidad:1 });
    }

    renderPedido();
}

function eliminarProducto(id) {
    pedido = pedido.filter(producto => producto.id_producto !== id);
    renderPedido();
}

function renderPedido() {
    const cont = document.getElementById('pedido');
    let subtotal = 0;
    cont.innerHTML = '';

    pedido.forEach(producto => {
        subtotal += Number(producto.precio) * producto.cantidad;
        cont.innerHTML += `
            <div class="pedido-item">
                <span>${producto.nombre} x${producto.cantidad}</span>
                <span>$${(Number(producto.precio) * producto.cantidad).toFixed(2)}</span>
                <button onclick="eliminarProducto(${producto.id_producto})">✖</button>
            </div>
        `;
    });

    document.getElementById('subtotal').innerText = subtotal.toFixed(2);
    document.getElementById('total').innerText = subtotal.toFixed(2);
}

function cambiarTipo() {
    const tipo = document.getElementById('tipo_consumo').value;
    const mesa = document.getElementById('numero_mesa');

    if (tipo === 'LLEVAR') {
        mesa.style.display = 'none';
        mesa.value = '';
    } else {
        mesa.style.display = 'block';
    }
}

async function confirmarPedido() {
    if (pedido.length === 0) {
        alert('Agregue al menos un producto');
        return;
    }

    const body = {
        nombre_cliente: document.getElementById('nombre_cliente').value,
        numero_mesa: document.getElementById('numero_mesa').value,
        tipo_consumo: document.getElementById('tipo_consumo').value,
        total: Number(document.getElementById('total').innerText),
        productos: pedido.map(producto => ({
            id_producto: producto.id_producto,
            cantidad: producto.cantidad
        }))
    };

    const res = await fetch(API_URL + '/pedidos', {
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'Authorization':'Bearer ' + TOKEN
        },
        body:JSON.stringify(body)
    });

    const json = await res.json();

    if (!res.ok) {
        alert(json.message || 'No se pudo registrar el pedido');
        await cargarProductos();
        return;
    }

    alert(`Pedido ${json.numero_pedido} registrado correctamente. Total: $${Number(json.total).toFixed(2)}`);
    location.reload();
}

cargarProductos();
</script>

@endsection
