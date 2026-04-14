<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ingreso al Sistema | GuatitasPOS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        :root{
            --vino:#7a2d2d;
            --crema:#faf7f2;
        }

        *{ box-sizing:border-box; }

        body{
            margin:0;
            font-family:'Poppins', sans-serif;
            background:linear-gradient(135deg, var(--vino), #4e1b1b);
            height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
        }

        .login-box{
            background:var(--crema);
            width:100%;
            max-width:420px;
            padding:45px;
            border-radius:18px;
            box-shadow:0 25px 45px rgba(0,0,0,.35);
            text-align:center;
        }

        .login-box img{
            max-width:120px;
            margin-bottom:15px;
        }

        h1{
            color:var(--vino);
            margin-bottom:5px;
        }

        p{
            color:#666;
            margin-bottom:30px;
            font-size:14px;
        }

        .form-group{
            text-align:left;
            margin-bottom:18px;
        }

        label{
            font-size:14px;
            font-weight:600;
            margin-bottom:6px;
            display:block;
        }

        input{
            width:100%;
            padding:14px;
            border-radius:12px;
            border:1px solid #ccc;
            font-size:16px;
        }

        button{
            margin-top:25px;
            width:100%;
            padding:15px;
            border:none;
            border-radius:30px;
            background:var(--vino);
            color:#fff;
            font-size:16px;
            font-weight:600;
            cursor:pointer;
        }

        button:hover{
            background:#5a1f1f;
        }

        .error{
            background:#ffd6d6;
            color:#900;
            padding:10px;
            border-radius:10px;
            font-size:14px;
            margin-bottom:15px;
            display:none;
        }

        footer{
            margin-top:20px;
            font-size:12px;
            color:#555;
        }
    </style>
</head>

<body>

<div class="login-box">
    <img src="{{ asset('logo.png') }}" alt="Guatitas del Caballito">

    <h1>Acceso al Sistema</h1>
    <p>Administración · Caja · Pedidos</p>

    <div id="errorBox" class="error"></div>

    <form id="loginForm">
        <div class="form-group">
            <label>Usuario</label>
            <input type="text" id="usuario" required>
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" id="password" required>
        </div>

        <button type="submit">🔐 Ingresar</button>
    </form>

    <footer>
        GuatitasPOS © {{ date('Y') }}<br>
        Tradición desde 1996
    </footer>
</div>

<script>
/* =====================================================
   🔐 BLOQUEO DE LOGIN SI YA HAY SESIÓN
===================================================== */
const API_URL = "{{ env('API_URL') }}";
const token = localStorage.getItem('token');

if (token) {
    // Si hay token, asumimos sesión válida
    // (la validación real la hace el layout)
    window.location.replace("/dashboard");
}

/* =====================================================
   🔐 LOGIN
===================================================== */
document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const usuario = document.getElementById('usuario').value.trim();
    const password = document.getElementById('password').value.trim();
    const errorBox = document.getElementById('errorBox');

    errorBox.style.display = 'none';

    if (!usuario || !password) {
        errorBox.innerText = '⚠️ Complete todos los campos';
        errorBox.style.display = 'block';
        return;
    }

    try {
        const res = await fetch(API_URL + '/admin/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ usuario, password })
        });

        const data = await res.json();

        if (!res.ok) {
            throw new Error(data.message || 'Credenciales incorrectas');
        }

        // Guardar sesión
        localStorage.setItem('token', data.token);
        localStorage.setItem('admin', JSON.stringify(data.admin));

        // Ir al dashboard
        window.location.replace("/dashboard");

    } catch (err) {
        errorBox.innerText = err.message;
        errorBox.style.display = 'block';
    }
});
</script>

</body>
</html>