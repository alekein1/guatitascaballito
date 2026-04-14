<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú Digital | Las Guatitas del Caballito</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        :root{
            --vino:#6e2b2b;
            --oro:#d4af37;
            --fondo:#faf8f5;
            --texto:#2b2b2b;
        }

        body{
            margin:0;
            font-family:'Segoe UI', Tahoma, sans-serif;
            background:var(--fondo);
            color:var(--texto);
        }

        header{
            background:linear-gradient(135deg,#6e2b2b,#4b1d1d);
            color:#fff;
            padding:60px 20px;
            text-align:center;
        }

        header h1{
            margin:0;
            font-size:38px;
            letter-spacing:1px;
        }

        header p{
            margin-top:12px;
            font-size:15px;
            opacity:.95;
        }

        .container{
            max-width:1200px;
            margin:auto;
            padding:50px 20px;
        }

        .section{
            margin-bottom:70px;
        }

        .section h2{
            font-size:28px;
            color:var(--vino);
            border-bottom:4px solid var(--vino);
            display:inline-block;
            padding-bottom:6px;
            margin-bottom:35px;
        }

        .grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
            gap:30px;
        }

        .card{
            background:#fff;
            border-radius:18px;
            box-shadow:0 12px 30px rgba(0,0,0,.12);
            overflow:hidden;
            transition:.25s;
        }

        .card:hover{
            transform:translateY(-5px);
        }

        .card img{
    width:100%;
    height:240px;
    object-fit: contain;
    background: linear-gradient(180deg,#f7f7f7,#ffffff);
    padding:14px;
}

        .card-body{
            padding:20px;
        }

        .card-body h3{
            margin:0;
            font-size:20px;
            color:var(--vino);
        }

        .card-body p{
            font-size:14px;
            line-height:1.6;
            margin:10px 0 15px;
            color:#555;
        }

        .price{
            font-size:18px;
            font-weight:700;
            color:var(--vino);
        }

        .nota{
            background:#fff3d6;
            border-left:6px solid var(--oro);
            padding:20px;
            border-radius:12px;
            font-weight:600;
            margin-top:40px;
        }

        footer{
            background:#fff;
            border-top:1px solid #ddd;
            text-align:center;
            padding:35px 15px;
            font-size:14px;
            color:#555;
        }
        .logo-sello{
                margin-top: 25px;
                display: flex;
                justify-content: center;
            }

            .logo-sello img{
                width: 110px;
                height: 110px;
                object-fit: contain;
                background: #fff;
                padding: 12px;
                border-radius: 50%;
                box-shadow: 0 12px 30px rgba(0,0,0,.35);
                border: 3px solid var(--oro);
            }
    </style>
</head>
<body>

<header>
    <h1>Las Guatitas del Caballito</h1>
    <p>Tradición gastronómica desde 1996 · Riobamba – Ecuador</p>
    <div class="logo-sello">
    <img src="{{ asset('img/logo.png') }}" alt="Las Guatitas del Caballito">
</div>
</header>

<div class="container">

{{-- ================= PLATOS PRINCIPALES ================= --}}
<div class="section">
    <h2>Platos Principales</h2>
    <div class="grid">

        <div class="card">
            <img src="{{ asset('img/menu/guatita.jpg') }}">
            <div class="card-body">
                <h3>Guatita</h3>
                <p>Arroz - guata - huevo</p>
                <div class="price">$2.50</div>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('img/menu/pollo-broaster.jpg') }}">
            <div class="card-body">
                <h3>Pollo Broaster</h3>
                <p>Arroz - papas fritas - lechuga - tomate - ensalada rusa</p>
                <div class="price">$3.25</div>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('img/menu/seco-pollo.jpg') }}">
            <div class="card-body">
                <h3>Seco de Pollo</h3>
                <p>Arroz - papa cocinada - lechuga - tomate - ensalada rusa</p>
                <div class="price">$3.00</div>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('img/menu/seco-chivo.jpg') }}">
            <div class="card-body">
                <h3>Seco de Chivo</h3>
                <p>Arroz - papa cocinada - lechuga - tomate - ensalada rusa</p>
                <div class="price">$3.75</div>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('img/menu/chuleta.jpg') }}">
            <div class="card-body">
                <h3>Chuleta Asada</h3>
                <p>Arroz - papas fritas - menestra -lechuga - tomate</p>
                <div class="price">$4.50</div>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('img/menu/caldo-gallina.jpg') }}">
            <div class="card-body">
                <h3>Caldo de Gallina</h3>
                <p>*Solo fin de Semana y feriados*</p>
                <div class="price">$3.00</div>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('img/menu/menudencia.jpg') }}">
            <div class="card-body">
                <h3>Menudencia</h3>
                <div class="price">$2.50</div>
            </div>
        </div>

    </div>
</div>

{{-- ================= PLATOS MIXTOS ================= --}}
<div class="section">
    <h2>Platos Mixtos</h2>
    <div class="grid">

        <div class="card">
            <img src="{{ asset('img/menu/guata-broaster.jpg') }}">
            <div class="card-body">
                <h3>Guata Broaster</h3>
                <p>Arroz - papas fritas - guata - pollo broaster - lechuga - tomate - ensalada rusa</p>
                <div class="price">$3.75</div>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('img/menu/guata-chivo.jpg') }}">
            <div class="card-body">
                <h3>Guata Chivo</h3>
                <p>Arroz - papa cocinada - lechuga - tomate - ensalada rusa</p>
                <div class="price">$4.75</div>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('img/menu/guata-chuleta.jpg') }}">
            <div class="card-body">
                <h3>Guata Chuleta</h3>
                <p>Arroz - guata - chuleta - lechuga - tomate - ensalada rusa</p>
                <div class="price">$5.00</div>
            </div>
        </div>

    </div>
</div>

{{-- ================= BEBIDAS ================= --}}
<div class="section">
    <h2>Bebidas</h2>
    <div class="grid">

        <div class="card">
            <img src="{{ asset('img/menu/fuze-tea.jpg') }}">
            <div class="card-body">
                <h3>Fuze Tea</h3>
                <div class="price">$1.50</div>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('img/menu/coca-cola.jpg') }}">
            <div class="card-body">
                <h3>Coca Cola</h3>
                <div class="price">$1.50</div>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('img/menu/inca-kola.jpg') }}">
            <div class="card-body">
                <h3>Inca Kola</h3>
                <div class="price">$1.00</div>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('img/menu/avena.jpg') }}">
            <div class="card-body">
                <h3>Avena</h3>
                <div class="price">$0.50</div>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('img/menu/morocho.jpg') }}">
            <div class="card-body">
                <h3>Morocho</h3>
                <div class="price">$0.75</div>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('img/menu/agua.jpg') }}">
            <div class="card-body">
                <h3>Agua</h3>
                <div class="price">$0.75</div>
            </div>
        </div>

    </div>
</div>


{{-- ================= EXTRAS ================= --}}
<div class="section">
    <h2>Extras</h2>
    <div class="grid">

        <div class="card">
            <img src="{{ asset('img/menu/arroz.jpg') }}">
            <div class="card-body">
                <h3>Porción de Arroz</h3>
                <div class="price">$1.00</div>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('img/menu/menestra.jpg') }}">
            <div class="card-body">
                <h3>Porción de Menestra</h3>
                <div class="price">$1.00</div>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('img/menu/porcion-guatita.jpg') }}">
            <div class="card-body">
                <h3>Porción de Guatita</h3>
                <div class="price">$1.25</div>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('img/menu/papas-fritas.jpg') }}">
            <div class="card-body">
                <h3>Porción de Papas Fritas</h3>
                <div class="price">$1.00</div>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('img/menu/consome.jpg') }}">
            <div class="card-body">
                <h3>Consomé</h3>
                <div class="price">$1.00</div>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('img/menu/tarrina-guata.jpg') }}">
            <div class="card-body">
                <h3>Tarrina de Guatita</h3>
                <div class="price">$3.50</div>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('img/menu/tarrina-morocho.jpg') }}">
            <div class="card-body">
                <h3>Tarrina de Morocho</h3>
                <div class="price">$1.75</div>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('img/menu/tarrina-avena.jpg') }}">
            <div class="card-body">
                <h3>Tarrina de Avena</h3>
                <div class="price">$1.25</div>
            </div>
        </div>

    </div>
</div>

<div class="nota">
    ⚠️ Pedidos para llevar tienen un recargo adicional de <strong>$0.25</strong>.
</div>

</div>

<footer>
    📞 099 269 5488 · Las Guatitas del Caballito  
    <br>© {{ date('Y') }} — Tradición desde 1996
</footer>

</body>
</html>