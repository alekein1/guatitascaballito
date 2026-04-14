<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GuatitasPOS | Administración</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        :root{
            --vino:#7a2d2d;
            --mostaza:#d4a640;
            --crema:#faf7f2;
            --oscuro:#2b1d1d;
        }

        *{ box-sizing:border-box; }

        body{
            margin:0;
            font-family:'Poppins', sans-serif;
            background:var(--crema);
        }

        .app{
            display:flex;
            min-height:100vh;
        }

        .sidebar{
            width:260px;
            background:var(--oscuro);
            color:#fff;
            display:flex;
            flex-direction:column;
        }

        .sidebar-header{
            padding:24px;
            text-align:center;
            border-bottom:1px solid rgba(255,255,255,.12);
        }

        .sidebar-header img{
            max-width:120px;
            margin-bottom:8px;
        }

        .menu{
            flex:1;
            padding:18px;
        }

        .menu a{
            display:flex;
            align-items:center;
            gap:12px;
            padding:14px 16px;
            margin-bottom:12px;
            background:rgba(255,255,255,.06);
            border-radius:14px;
            color:#fff;
            text-decoration:none;
            font-size:15px;
        }

        .menu a:hover{ background:var(--vino); }

        .menu a.active{
            background:var(--mostaza);
            color:#000;
            font-weight:600;
        }

        .content{
            flex:1;
            padding:30px;
        }

        .topbar{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            margin-bottom:30px;
        }

        .topbar h1{
            font-size:26px;
            color:var(--vino);
            margin:0;
        }

        .menu-toggle{
            display:none;
            font-size:26px;
            background:none;
            border:none;
            color:var(--vino);
            cursor:pointer;
        }

        .logout{
            background:#c0392b;
            color:#fff;
            border:none;
            padding:12px 22px;
            border-radius:25px;
            font-weight:600;
            cursor:pointer;
        }

        .overlay{ display:none; }

        @media (max-width: 768px){

            .app{
                position:relative;
                overflow-x:hidden;
            }

            .sidebar{
                position:fixed;
                top:0;
                left:0;
                height:100vh;
                width:260px;
                transform:translateX(-100%);
                transition:.3s ease;
                z-index:3000;
            }

            .sidebar.active{
                transform:translateX(0);
            }

            .menu-toggle{
                display:block;
                z-index:4000;
            }

            .content{
                padding:20px;
            }

            .overlay{
                display:none;
                position:fixed;
                inset:0;
                background:rgba(0,0,0,.45);
                z-index:2000;
            }

            .overlay.active{
                display:block;
            }
        }
    </style>
</head>

<body>

<div class="app">

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('logo.png') }}" alt="GuatitasPOS">
            <h3>Panel Administrativo</h3>
        </div>

        <nav class="menu">
            <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">🏠 Dashboard</a>
            <a href="/productos" class="{{ request()->is('productos*') ? 'active' : '' }}">🍽️ Productos</a>
            <a href="/inventario" class="{{ request()->is('inventario*') ? 'active' : '' }}">📦 Inventario</a>
            <a href="/pedidos" class="{{ request()->is('pedidos') ? 'active' : '' }}">🧾 POS / Ventas</a>
            <a href="/impresoras" class="{{ request()->is('impresoras*') ? 'active' : '' }}">🖨️ Impresoras</a>
            <a href="/pedidos-dia" class="{{ request()->is('pedidos-dia*') ? 'active' : '' }}">📋 Pedidos del Día</a>
            <a href="/reportes" class="{{ request()->is('reportes*') ? 'active' : '' }}">📊 Reportes</a>
        </nav>
    </aside>

    <div class="overlay" id="overlay"></div>

    <main class="content">

        <div class="topbar">
            <button class="menu-toggle" id="menuToggle">☰</button>
            <h1>@yield('titulo')</h1>
            <button class="logout" id="logoutBtn">Cerrar sesión</button>
        </div>

        @yield('contenido')

    </main>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = "/login";
        return;
    }

    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.getElementById('menuToggle');
    const overlay = document.getElementById('overlay');
    const logoutBtn = document.getElementById('logoutBtn');

    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }

    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            localStorage.clear();
            window.location.href = "/login";
        });
    }
});
</script>

</body>
</html>
