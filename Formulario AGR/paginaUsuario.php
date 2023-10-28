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
        .usuario-foto {
            border-radius: 50%;
            width: 33px;
            height: 33px;
            
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Pagina del Usuario</h1>
    </div>
    <div class="menu">
        <a href="#">Inicio</a>
        <a href="#">Acerca de</a>
        <a href="#">Servicios</a>
        <a href="#" onclick="toggleContactPopup()">Contacto</a>
    </div>
    <?php
    // Inicia la sesión al principio del script
    session_start();

    // Verifica si el usuario no ha iniciado sesión
    if (!isset($_SESSION['email'])) {
        header('Location: formularioLogin.php');
        exit(); // Importante: sal del script después de redirigir
    }
    
    // Conecta a la base de datos
    $conexion = mysqli_connect("localhost", "root", "", "usuario") 
        or die("Problemas con la conexión");
    
    $email = $_SESSION['email'];
    
    $busqueda = mysqli_query($conexion, "SELECT * FROM usuarios WHERE Usuario_email='$email'")
        or die("Problemas en la consulta: " . mysqli_error($conexion));
      
    if (mysqli_num_rows($busqueda) != 0) {
        $row = mysqli_fetch_assoc($busqueda); 
        $foto = base64_encode($row['Usuario_fotografia']);
        $nombre = $row['Usuario_nombre'];
    }
    
?>

<button class="login-button" onclick="toggleLoginPopup()">
    <img class="usuario-foto" src="data:image/png;base64,<?php echo $foto; ?>" alt="usuario">
</button>
    
    <div class="login-popup-container" id="loginPopupContainer">
        <div class="login-popup" id="loginPopup">
            <h2>Perfil de <?php echo $nombre; ?></h2>
            <form id="loginForm" action="editarUsuario.php" method="POST">

            <button type="submit">Editar Perfil</button>
            </form>
            <div class="register-link">
    <script>
    // Función para mostrar el cuadro de información del usuario
    function mostrarInformacionUsuario() {
        var modal = document.getElementById("informacionUsuario");
        modal.style.display = "block";
    }

    // Función para cerrar el cuadro de información del usuario
    function cerrarInformacionUsuario() {
        var modal = document.getElementById("informacionUsuario");
        modal.style.display = "none";
    }
    </script>              
   
            </div>
        </div>
    </div>
    <div class="contact-popup-container" id="contactPopupContainer" style="display: none;">
    <div class="contact-popup container" id="contactPopup">
        <h2 class="mb-3">Contacto</h2>
        <form id="contactForm" action="contacto.php" method="POST">
            <div class="form-group">
                <label for="contactEmail">Correo Electrónico</label>
                <input type="text" class="form-control" id="contactEmail" name="contactEmail" placeholder="Correo Electrónico" value="<?php echo $_SESSION['email']; ?>" readonly>
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
