<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Menú Digital | Las Guatitas del Caballito</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
:root{
  --vino:#7a2d2d;
  --vino-oscuro:#5a1f1f;
  --oro:#f2c94c;
  --blanco:#ffffff;
}

*{ box-sizing:border-box; }

body{
  margin:0;
  font-family:'Poppins', sans-serif;
  background:var(--vino);
  color:var(--blanco);
}

/* ================= HERO ================= */
.hero{
  position:relative;
  height:230px;
  background:
    linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.55)),
    url('{{ asset("img/logo.png") }}') center/cover no-repeat;
  display:flex;
  flex-direction:column;
  justify-content:center;
  align-items:center;
  text-align:center;
  padding:20px;
}

.whatsapp-float{
  position:fixed;
  bottom:20px;
  right:20px;
  width:56px;
  height:56px;
  background:#25D366;
  border-radius:50%;
  display:flex;
  justify-content:center;
  align-items:center;
  box-shadow:0 10px 25px rgba(0,0,0,.35);
  z-index:9999;
  transition:.3s;
}

.whatsapp-float:hover{
  transform:scale(1.1);
}

.whatsapp-float svg{
  width:28px;
  height:28px;
}

.hero h1{
  margin:0;
  font-size:28px;
  font-weight:700;
}

/* ================= BOTONES FLOTANTES ================= */
.social-fab{
  position:fixed;
  bottom:20px;
  right:16px;
  display:flex;
  flex-direction:column;
  gap:12px;
  z-index:999;
}

.fab{
  width:54px;
  height:54px;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:24px;
  text-decoration:none;
  color:#fff;
  box-shadow:0 10px 25px rgba(0,0,0,.45);
  transition:.25s;
}

.fab:hover{
  transform:scale(1.1);
}

.fab.maps{ background:#e74c3c; }
.fab.whatsapp{ background:#25D366; }
.fab.facebook{ background:#1877F2; }

.hero p{
  margin-top:8px;
  font-size:14px;
  opacity:.9;
}

/* ================= CATEGORÍAS ================= */
.categorias{
  display:flex;
  gap:10px;
  padding:14px;
  overflow-x:auto;
  background:var(--vino-oscuro);
}

.categorias button{
  border:none;
  background:#fff;
  color:#000;
  padding:10px 18px;
  border-radius:20px;
  font-weight:600;
  cursor:pointer;
  white-space:nowrap;
}

.categorias button.active{
  background:var(--oro);
}

/* ================= CONTENEDOR ================= */
.container{
  padding:18px;
  max-width:520px;
  margin:auto;
}

/* ================= SECCIÓN ================= */
.section{
  margin-bottom:40px;
}

.section h2{
  margin:0 0 14px;
  font-size:20px;
  color:var(--oro);
}

/* ================= TARJETAS ================= */
.grid{
  display:grid;
  gap:16px;
}

.card{
  background:rgba(255,255,255,.12);
  backdrop-filter:blur(12px);
  border-radius:18px;
  padding:14px;
  display:flex;
  gap:14px;
  align-items:center;
  box-shadow:0 12px 25px rgba(0,0,0,.35);
  transition:.25s;
}

.card:hover{
  transform:scale(1.02);
}

.card img{
  width:90px;
  height:90px;
  object-fit:cover;
  border-radius:14px;
  background:#fff;
}

.card-body{
  flex:1;
}

.card-body h3{
  margin:0;
  font-size:16px;
  font-weight:600;
}

.card-body p{
  margin:4px 0 0;
  font-size:13px;
  opacity:.9;
}

.price{
  font-size:18px;
  font-weight:700;
  color:var(--oro);
}

/* ================= FOOTER ================= */
.footer{
  text-align:center;
  font-size:13px;
  opacity:.85;
  padding:30px 10px;
}

.aviso-llevar{
  background:rgba(255,255,255,.9);
  color:#000;
  padding:10px 16px;
  margin:0 auto;
  max-width:520px;
  text-align:center;
  font-size:14px;
  font-weight:500;
  border-radius:0 0 14px 14px;
  box-shadow:0 6px 14px rgba(0,0,0,.25);
}

.aviso-llevar strong{
  font-weight:700;
}
</style>
</head>

<body>

<!-- HERO -->
<section class="hero">
  <h1>Las Guatitas del Caballito</h1>
  <p>Tradición gastronómica · Riobamba</p>
</section>

<div class="aviso-llevar">
  <span>📦</span>
  <strong>Servicio para llevar:</strong>
  <span>adicional $0.25 por envase</span>
</div>

<!-- CATEGORÍAS -->
<nav class="categorias">
  <button onclick="scrollToSection('platos')">🍲 Platos</button>
  <button onclick="scrollToSection('mixtos')">🍗 Mixtos</button>
    <button onclick="scrollToSection('extras')">🍲 Extras</button>
  <button onclick="scrollToSection('bebidas')">🥤 Bebidas</button>
</nav>

<div class="container">

<!-- ================= PLATOS ================= -->
<div class="section" id="platos">
<h2>🍲 Platos a la carta</h2>
<div class="grid">

<div class="card">
  <img src="{{ asset('img/menu/guatita.jpg') }}">
  <div class="card-body">
    <h3>Guatita</h3>
    <p>Arroz - guata - huevo</p>
  </div>
  <div class="price">$2.50</div>
</div>

<div class="card">
  <img src="{{ asset('img/menu/pollo-broaster.jpg') }}">
  <div class="card-body">
    <h3>Pollo Broaster</h3>
    <p>Arroz - papas fritas - lechuga - tomate - ensalada rusa</p>
  </div>
  <div class="price">$3.25</div>
</div>
<div class="card">
  <img src="{{ asset('img/menu/seco-pollo.jpg') }}">
  <div class="card-body">
    <h3>Seco de Pollo</h3>
    <p>Arroz - papa cocinada - lechuga - tomate - ensalada rusa</p>
  </div>
  <div class="price">$3.00</div>
</div>
<div class="card">
  <img src="{{ asset('img/menu/seco-chivo.jpg') }}">
  <div class="card-body">
    <h3>Seco de Chivo</h3>
    <p>Arroz - papa cocinada - lechuga - tomate - ensalada rusa</p>
  </div>
  <div class="price">$3.75</div>
</div>
<div class="card">
  <img src="{{ asset('img/menu/chuleta.jpg') }}">
  <div class="card-body">
    <h3>Chuleta Asada</h3>
    <p>Arroz - papas fritas - menestra - lechuga - tomate</p>
  </div>
  <div class="price">$4.50</div>
</div>
<div class="card">
  <img src="{{ asset('img/menu/caldo-gallina.jpg') }}">
  <div class="card-body">
    <h3>Caldo de Gallina</h3>
    <p>*Solo fin de Semana y feriados*</p>
  </div>
  <div class="price">$3.00</div>
</div>
<div class="card">
  <img src="{{ asset('img/menu/menudencia.jpg') }}">
  <div class="card-body">
    <h3>Menudencia</h3>
  </div>
  <div class="price">$2.50</div>
</div>

</div>
</div>

<!-- ================= BROASTER ================= -->
<div class="section" id="mixtos">
<h2>🍗 Mixtos</h2>
<div class="grid">

<div class="card">
  <img src="{{ asset('img/menu/guata-broaster.jpg') }}">
  <div class="card-body">
    <h3>Guata Broaster</h3>
    <p>Arroz - papas fritas - guata - pollo broaster - lechuga - tomate - ensalada rusa</p>
  </div>
  <div class="price">$3.75</div>
</div>

<div class="card">
  <img src="{{ asset('img/menu/guata-chivo.jpg') }}">
  <div class="card-body">
    <h3>Guata Chivo</h3>
    <p>Arroz - papa cocinada - lechuga - tomate - ensalada rusa</p>
  </div>
  <div class="price">$4.75</div>
</div>
<div class="card">
  <img src="{{ asset('img/menu/guata-chuleta.jpg') }}">
  <div class="card-body">
    <h3>Guata chuleta</h3>
    <p>Arroz - guata - chuleta - lechuga - tomate - ensalada rusa</p>
  </div>
  <div class="price">$5.00</div>
</div>

</div>
</div>

<div class="section" id="extras">
<h2>🍗 Extras</h2>
<div class="grid">

<div class="card">
  <img src="{{ asset('img/menu/arroz.jpg') }}">
  <div class="card-body">
    <h3>Porción de Arroz</h3>
  </div>
  <div class="price">$1.00</div>
</div>

<div class="card">
  <img src="{{ asset('img/menu/menestra.jpg') }}">
  <div class="card-body">
    <h3>Porción de Menestra</h3>
  </div>
  <div class="price">$1.00</div>
</div>
<div class="card">
  <img src="{{ asset('img/menu/porcion-guatita.jpg') }}">
  <div class="card-body">
    <h3>Porción de Guatita</h3>
  </div>
  <div class="price">$1.25</div>
</div>

<div class="card">
  <img src="{{ asset('img/menu/porcion-guatita.jpg') }}">
  <div class="card-body">
    <h3>Porción de Papas Fritas</h3>
  </div>
  <div class="price">$1.00</div>
</div>

<div class="card">
  <img src="{{ asset('img/menu/papas-fritas.jpg') }}">
  <div class="card-body">
    <h3>Porción de Papas Fritas</h3>
  </div>
  <div class="price">$1.00</div>
</div>
<div class="card">
  <img src="{{ asset('img/menu/consome.jpg') }}">
  <div class="card-body">
    <h3>Consumé</h3>
  </div>
  <div class="price">$1.00</div>
</div>
<div class="card">
  <img src="{{ asset('img/menu/tarrina-guata.jpg') }}">
  <div class="card-body">
    <h3>Tarrina de guata</h3>
  </div>
  <div class="price">$3.50</div>
</div>
<div class="card">
  <img src="{{ asset('img/menu/tarrina-morocho.jpg') }}">
  <div class="card-body">
    <h3>Tarrina de Morocha</h3>
  </div>
  <div class="price">$1.75</div>
</div>
<div class="card">
  <img src="{{ asset('img/menu/tarrina-avena.jpg') }}">
  <div class="card-body">
    <h3>Tarina de Avena</h3>
  </div>
  <div class="price">$1.25</div>
</div>

</div>
</div>
<!-- ================= BEBIDAS ================= -->
<div class="section" id="bebidas">
<h2>🥤 Bebidas</h2>
<div class="grid">

<div class="card">
  <img src="{{ asset('img/menu/fuze-tea.jpg') }}">
  <div class="card-body">
    <h3>Fuze Tea</h3>
    <p>Fría</p>
  </div>
  <div class="price">$1.50</div>
</div>

<div class="card">
  <img src="{{ asset('img/menu/coca-cola.jpg') }}">
  <div class="card-body">
    <h3>Gaseosa</h3>
    <p>Coca-Cola, Fanta, Sprite</p>
  </div>
  <div class="price">$1.50</div>
</div>

<div class="card">
  <img src="{{ asset('img/menu/inca-kola.jpg') }}">
  <div class="card-body">
    <h3>Inca Kola</h3>
    <p>Fria o al Ambiente</p>
  </div>
  <div class="price">$1.00</div>
</div>
<div class="card">
  <img src="{{ asset('img/menu/avena.jpg') }}">
  <div class="card-body">
    <h3>Avena</h3>
    <p>Caliente</p>
  </div>
  <div class="price">$0.50</div>
</div>
<div class="card">
  <img src="{{ asset('img/menu/morocho.jpg') }}">
  <div class="card-body">
    <h3>Morocho</h3>
    <p>Caliente</p>
  </div>
  <div class="price">$0.75</div>
</div>

<div class="card">
  <img src="{{ asset('img/menu/agua.jpg') }}">
  <div class="card-body">
    <h3>Agua</h3>
    <p>Fria o al Ambiente</p>
  </div>
  <div class="price">$0.75</div>
</div>

</div>
</div>

</div>



<div class="footer">
📍 Riobamba – Ecuador · 📲 099 269 5488  
<br>© {{ date('Y') }} · Tradición desde 1996
</div>

<div class="social-fab">

  <!-- GOOGLE MAPS -->
  <a href="https://maps.app.goo.gl/ea3tAewFjU7bDaxu6"
     target="_blank"
     class="fab maps"
     title="Ver ubicación">
    📍
  </a>

  <!-- WHATSAPP -->
  <a href="https://wa.me/593992695488?text=Hola%20👋%20vi%20su%20menú%20digital%20y%20quisiera%20hacer%20un%20pedido"
     target="_blank"
     class="fab whatsapp"
     title="WhatsApp">
    💬
  </a>

</div>

<script>
function scrollToSection(id){
  document.getElementById(id).scrollIntoView({
    behavior:'smooth',
    block:'start'
  });
}
</script>

</body>
</html>