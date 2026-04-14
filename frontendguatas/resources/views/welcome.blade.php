<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Las Guatitas del Caballito | Tradición desde 1996</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

   <style>
    :root{
        --vino:#7a2d2d;
        --mostaza:#d4a640;
        --crema:#faf7f2;
        --oscuro:#2b1d1d;
    }

    *{
        box-sizing:border-box;
        margin:0;
        padding:0;
    }

    html, body{
        height:100%;
    }

    body{
        font-family:'Poppins', sans-serif;
        background:var(--crema);
        color:#2a2a2a;
        display:flex;
        flex-direction:column;
    }

    /* HEADER */
    header{
        background:linear-gradient(135deg, var(--vino), #5a1f1f);
        color:#fff;
        padding:50px 20px;
    }

    .header-content{
        max-width:1300px;
        margin:auto;
        display:flex;
        align-items:center;
        gap:30px;
    }

    .logo img{
        max-height:110px;
    }

    .title h1{
        font-family:'Playfair Display', serif;
        font-size:52px;
        font-weight:700;
        line-height:1.1;
    }

    .title p{
        font-size:22px;
        margin-top:8px;
        opacity:.95;
    }

    /* MAIN */
    main{
        flex:1;
    }

    .container{
        max-width:1300px;
        margin:auto;
        padding:70px 20px;
    }

    .about{
        display:grid;
        grid-template-columns:1.2fr 1fr;
        gap:50px;
        align-items:center;
    }

    .about h2{
        font-family:'Playfair Display', serif;
        color:var(--vino);
        font-size:40px;
        margin-bottom:25px;
    }

    .about p{
        line-height:1.8;
        margin-bottom:18px;
        font-size:19px;
    }

    .card-highlight{
        background:var(--mostaza);
        padding:40px;
        border-radius:20px;
        color:#1f1f1f;
        box-shadow:0 18px 35px rgba(0,0,0,.25);
    }

    .card-highlight ul{
        list-style:none;
    }

    .card-highlight li{
        margin-bottom:18px;
        font-weight:600;
        font-size:18px;
    }

    .actions{
        margin-top:70px;
        display:flex;
        gap:30px;
        justify-content:center;
        flex-wrap:wrap;
    }

    .btn{
        padding:20px 42px;
        border-radius:50px;
        font-weight:600;
        text-decoration:none;
        font-size:18px;
        transition:.3s;
        display:inline-flex;
        align-items:center;
        gap:12px;
    }

    .btn-menu{
        background:var(--mostaza);
        color:#000;
    }

    .btn-menu:hover{
        background:#c1912e;
        transform:translateY(-2px);
    }

    .btn-login{
        background:var(--vino);
        color:#fff;
    }

    .btn-login:hover{
        background:#5a1f1f;
        transform:translateY(-2px);
    }

    /* FOOTER */
    footer{
        background:var(--oscuro);
        color:#ddd;
        padding:40px 20px;
    }

    .footer-content{
        max-width:1300px;
        margin:auto;
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:40px;
    }

    footer h4{
        color:var(--mostaza);
        margin-bottom:12px;
        font-size:18px;
    }

    footer p{
        font-size:15px;
        line-height:1.7;
    }

    .footer-bottom{
        text-align:center;
        margin-top:35px;
        font-size:14px;
        opacity:.85;
    }

    @media(max-width:900px){
        .about{
            grid-template-columns:1fr;
        }

        .title h1{
            font-size:42px;
        }
    }
</style>
</head>

<body>

<header>
    <div class="header-content">
        <div class="logo">
            <img src="{{ asset('logo.png') }}" alt="Las Guatitas del Caballito">
        </div>
        <div class="title">
            <h1>Las Guatitas del Caballito</h1>
            <p>Tradición gastronómica ecuatoriana desde 1996</p>
        </div>
    </div>
</header>

<main>
    <div class="container">
        <section class="about">
            <div>
                <h2>Reseña</h2>
                <p>
                    Desde 1996, Las Guatitas del Caballito han sido un referente de la
                    tradición gastronómica de Riobamba, ofreciendo platos típicos
                    ecuatorianos como guatitas, secos de pollo y chivo, chuletas,
                    caldos y bebidas tradicionales.
                </p>
                <p>
                    Nuestro compromiso es utilizar ingredientes frescos de alta
                    calidad para brindar una experiencia auténtica, cálida y
                    memorable en cada visita.
                </p>
            </div>

            <div class="card-highlight">
                <ul>
                    <li>🍽️ Atención rápida y eficiente</li>
                    <li>🧾 Pedidos centralizados en caja</li>
                    <li>🔥 Cocina tradicional ecuatoriana</li>
                    <li>🏠 Ambiente familiar y acogedor</li>
                </ul>
            </div>
        </section>

        <div class="actions">
            <a href="{{ url('/menu-digital') }}" class="btn btn-menu">
                📖 Ver Menú Digital
            </a>

            <a href="{{ url('/login') }}" class="btn btn-login">
                🔐 Ingresar al Sistema POS
            </a>
        </div>
    </div>
</main>

<footer>
    <div class="footer-content">
        <div>
            <h4>📍 Ubicación</h4>
            <p>Riobamba – Ecuador</p>
        </div>

        <div>
            <h4>📞 Contacto</h4>
            <p>WhatsApp: 099 269 5488<br>
               Facebook: Las Guatitas del Caballito</p>
        </div>

        <div>
            <h4>🏆 Tradición</h4>
            <p>Más de 25 años llevando el verdadero sabor ecuatoriano a tu mesa.</p>
        </div>
    </div>

    <div class="footer-bottom">
        © {{ date('Y') }} Las Guatitas del Caballito · Tradición desde 1996
    </div>
</footer>

</body>
</html>