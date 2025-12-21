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
            display:flex;
            height:100vh;
        }

        /* SIDEBAR */
        .sidebar{
            width:260px;
            background:var(--oscuro);
            color:#fff;
            display:flex;
            flex-direction:column;
        }

        .sidebar-header{
            padding:25px;
            text-align:center;
            border-bottom:1px solid rgba(255,255,255,.1);
        }

        .sidebar-header img{
            max-width:120px;
            margin-bottom:10px;
        }

        .menu{
            flex:1;
            padding:20px;
        }

        .menu a{
            display:flex;
            align-items:center;
            gap:12px;
            padding:14px 16px;
            margin-bottom:12px;
            background:rgba(255,255,255,.05);
            border-radius:14px;
            color:#fff;
            text-decoration:none;
            font-size:15px;
            transition:.2s;
        }

        .menu a:hover{ background:var(--vino); }

        .menu a.active{
            background:var(--mostaza);
            color:#000;
            font-weight:600;
        }

        /* CONTENT */
        .content{
            flex:1;
            padding:30px;
            overflow-y:auto;
        }

        .topbar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:30px;
        }

        .topbar h1{
            font-size:28px;
            color:var(--vino);
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

        .logout:hover{ background:#a93226; }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('logo.png') }}" alt="GuatitasPOS">
        <h3>Panel Administrativo</h3>
    </div>

    <div class="menu">
        <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">🏠 Dashboard</a>
        <a href="/productos" class="{{ request()->is('productos*') ? 'active' : '' }}">🍽️ Productos</a>
        <a href="/impresoras" class="{{ request()->is('impresoras*') ? 'active' : '' }}">🖨️ Impresoras</a>
        <a href="/pedidos" class="{{ request()->is('pedidos*') ? 'active' : '' }}">🧾 POS Ventas</a>
        <a href="/pedidos-dia" class="{{ request()->is('pedidos-dia*') ? 'active' : '' }}">📋 Pedidos del Día</a>
        <a href="/reportes" class="{{ request()->is('reportes*') ? 'active' : '' }}">📊 Reportes</a>
    </div>
</div>

<!-- CONTENT -->
<div class="content">

    <div class="topbar">
        <h1>@yield('titulo')</h1>
        <button class="logout" id="logoutBtn">Cerrar sesión</button>
    </div>

    @yield('contenido')

</div>

<script>
/* =====================================================
   🔐 PROTECCIÓN DE SESIÓN (MODELO SIC-CAM)
===================================================== */
const token = localStorage.getItem('token');

if (!token) {
    window.location.href = "/login";
}

/* =====================================================
   🚨 INTERCEPTOR GLOBAL FETCH
   (expulsa SOLO si backend responde 401 / 403)
===================================================== */
(function () {
    const originalFetch = window.fetch;

    window.fetch = async function (...args) {
        const response = await originalFetch(...args);

        if (response.status === 401 || response.status === 403) {
            alert('⚠️ Tu sesión ha expirado. Inicia sesión nuevamente.');
            localStorage.clear();
            window.location.href = '/login';
        }

        return response;
    };
})();

/* =====================================================
   🚪 LOGOUT LIMPIO Y DEFINITIVO
===================================================== */
document.getElementById('logoutBtn').addEventListener('click', () => {
    localStorage.clear();
    window.location.href = "/login";
});
</script>

</body>
</html>