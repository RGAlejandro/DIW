<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Página con Inicio de Sesión y Registro</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }
        .header {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .menu {
            background-color: #666;
            color: white;
            padding: 10px;
        }
        .menu a {
            margin-left: 10px;
            text-decoration: none;
            color: #fff;
            font-weight: bold;
        }
        .login-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-popup-container {
            position: absolute;
            top: 70px;
            right: 20px;
        }
        .login-popup {
            display: none;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            width: 250px;
        }
        .login-popup h2 {
            margin-top: 0;
        }
        .login-popup input {
            display: block;
            margin: 10px 0;
            padding: 5px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .login-popup button {
            background-color: #333;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-popup button:hover {
            background-color: #555;
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php
    session_start();

    // Función para generar un captcha aleatorio
    function generarCaptcha($length = 8) {
        $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $captcha = '';
        $caracteres_length = strlen($caracteres);
        
        for ($i = 0; $i < $length; $i++) {
            $captcha .= $caracteres[rand(0, $caracteres_length - 1)];
        }
        
        return $captcha;
    }

    // Genera y almacena el captcha en una variable de sesión
    $_SESSION['captcha'] = generarCaptcha();

    // Resto de la lógica para el inicio de sesión
    // ...

    ?>
    <div class="header">
        <h1>Título de la Página</h1>
    </div>
    <div class="menu">
        <a href="#">Inicio</a>
        <a href="#">Acerca de</a>
        <a href="#">Servicios</a>
        <a href="#" onclick="toggleContactPopup()">Contacto</a>
    </div>
    <button class="login-button" onclick="toggleLoginPopup()">Iniciar Sesión</button>
    
    <div class="login-popup-container" id="loginPopupContainer">
        <div class="login-popup" id="loginPopup">
            <h2>Iniciar Sesión</h2>
            <form id="loginForm" action="login.php" method="POST">
            <input type="text" id="email" name="email" placeholder="Correo Electrónico">
            <input type="password" id="password" name="password" placeholder="Contraseña">
            <div>
            <strong style="font-size: 18px; font-weight: bold; text-shadow: 2px 2px 3px rgba(0,0,0,0.5);">Captcha: <?php echo $_SESSION['captcha']; ?></strong>
            </div>

            <label for="captcha">Por favor, ingresa el captcha:</label>
            <input type="text" id="captcha" name="captcha" required>
            <button type="submit">Iniciar Sesion</button>
            </form>
            <div class="register-link">
                  
    <script>
        function redirigirARegistro() {
            window.location.href = "registro.php";
        }
    </script>
                ¿No tienes una cuenta?<button onclick="redirigirARegistro()">Registrar</button>
            </div>
        </div>
    </div>

    <div class="contact-popup-container" id="contactPopupContainer" style="display: none;">
    <div class="contact-popup container" id="contactPopup">
        <h2 class="mb-3">Contacto</h2>
        <form id="contactForm" action="contacto.php" method="POST">
            <div class="form-group">
                <label for="contactEmail">Correo Electrónico</label>
                <input type="text" class="form-control" id="contactEmail" name="contactEmail" placeholder="Correo Electrónico" required>
            </div>
            <div class="form-group">
                <label for="incidencia">Descripción de la incidencia</label>
                <textarea class="form-control" id="incidencia" name="incidencia" placeholder="Descripción de la incidencia" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
    </div>
    <script>
        function toggleLoginPopup() {
            var popup = document.getElementById("loginPopup");
            var popupContainer = document.getElementById("loginPopupContainer");
            if (popup.style.display === "block") {
                popup.style.display = "none";
            } else {
                popup.style.display = "block";
                popupContainer.style.display = "block";
            }
        }
    </script>
    <script>
    function toggleContactPopup() {
        var contactPopup = document.getElementById("contactPopup");
        var contactPopupContainer = document.getElementById("contactPopupContainer");
        
        var loginPopup = document.getElementById("loginPopup");
        var loginPopupContainer = document.getElementById("loginPopupContainer");
        
        // Oculta el formulario de inicio de sesión si está abierto
        if (loginPopup.style.display === "block") {
            loginPopup.style.display = "none";
            loginPopupContainer.style.display = "none";
        }
        
        // Muestra/oculta el formulario de contacto
        if (contactPopup.style.display === "block") {
            contactPopup.style.display = "none";
            contactPopupContainer.style.display = "none";
        } else {
            contactPopup.style.display = "block";
            contactPopupContainer.style.display = "block";
        }
    }
</script>

</body>
</html>
