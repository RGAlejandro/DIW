<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página del Usuario</title>
    <link rel="stylesheet" href="estilos/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="estilos/estilos.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDesJUSEExzoHDeDykc0Ju6y1fEJQdw_U&callback=inicializarMapa" async defer></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #333;
            color: white;
            padding: 10px;
        }

        .titulo {
            font-size: 24px;
            margin: 0;
        }

        .enlace {
            color: white;
            text-decoration: none;
            margin-right: 20px;
        }

        .enlace:hover {
            text-decoration: underline;
        }

        .contenido {
            padding: 20px;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
        }

        .usuario-foto {
            border-radius: 50%;
            width: 400px;
            height: 400px;
            margin-top: 20px;
        }

        .usuario-email {
            font-size: 24px;
            margin-top: 20px;
        }

        .formulario {
            padding: 20px;
            margin: 20px 0;
            background-color: #f7f7f7;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .formulario label {
            font-size: 16px;
            font-weight: bold;
        }

        .formulario input[type="file"] {
            margin: 10px 0;
            padding: 5px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .formulario .btn-actualizar {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .formulario .btn-actualizar:hover {
            background-color: #555;
        }

        .btn-editar {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: block;
            margin-top: 20px;
            cursor: pointer;
        }

        .btn-editar:hover {
            background-color: #555;
        }

        .footer {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
<?php
  session_start();

  if(!isset($_SESSION['email'])) {
    header('Location:formularioLogin.php');
  }

  $conexion = mysqli_connect("localhost", "root", "", "usuario") 
  or die("Problemas con la conexión");

  $email=$_SESSION['email'];

  $busqueda=mysqli_query($conexion,"SELECT * FROM usuarios WHERE Usuario_email='$email'")
  or die("Problemas en el select" . mysqli_error($conexion));
  
  if(mysqli_num_rows($busqueda) != 0){
          $row = mysqli_fetch_assoc($busqueda); 
          $foto = base64_encode($row['Usuario_fotografia']);
          $nombre = $row['Usuario_nombre'];
          $apellido1 = $row['Usuario_apellido1'];
          $apellido2 = $row['Usuario_apellido2'];
  }
?>
<script>
    function inicializarMapa() {
        var ubicacion = { lat: 0, lng: 0 }; // Puedes inicializar con coordenadas iniciales o dejar en blanco para usar la geolocalización.
        var mapa = new google.maps.Map(document.getElementById('mapa'), {
            zoom: 12,
            center: ubicacion
        });

        // Puedes personalizar el marcador del mapa
        var marcador = new google.maps.Marker({
            position: ubicacion,
            map: mapa,
            title: 'Ubicación Actual'
        });

        // Usar geolocalización para obtener ubicación actual
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var ubicacion_actual = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                mapa.setCenter(ubicacion_actual);
                marcador.setPosition(ubicacion_actual);
            }, function () {
                // Manejar errores de geolocalización
            });
        } else {
            // El navegador no admite geolocalización
        }
    }
</script>
    <header class="header">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-4 text-center">
                <h1 class="titulo">Perfil de <?php echo $nombre." ".$apellido1." ".$apellido2; ?></h1>
                </div>
                <div class="col-md-4 text-end">
                    <a class="fs-6 fw-bold text-uppercase enlace" href="paginaUsuario.php">Inicio</a>
                </div>
                <div class="col-md-4 text-end">
                    <a class="fs-6 fw-bold text-uppercase enlace" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <!-- NADA MAS ENTRAR -->
    <main class="container p-2 contenido">
    <div class="row g-0">
        <div class="col-md-4 col-sm-6 col-xs-12">
            <!-- Colocar aquí la imagen del usuario -->
        </div>
        <div class="col-md-8 col-sm-6 col-xs-12">
            <!-- <p class="usuario-email"><?php echo $nombre; ?></p> -->
            <div class="col-md-4 col-sm-6 col-xs-12">
                <img class="usuario-foto" src="data:image/png;base64,<?php echo $foto; ?>" alt="usuario">
            </div>        
            <!-- Botones para cambiar entre opciones en una lista vertical -->
            
            <div class="mb-3">
                <a class="btn btn-primary btn-block mb-2" href="editarPerfil.php">Perfil</a>
                <a class="btn btn-primary btn-block mb-2" href="cambiarFoto.php">Cambiar Foto de Perfil</a>
                <a class="btn btn-primary btn-block mb-2" href="cambiarContraseña.php">Cambiar Contraseña</a>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <button class="btn btn-primary btn-block" id="ubicacionBtn">Obtener Ubicación</button>
        <p id="ubicacionInfo"></p>
    </div>
    <div id="mapa" style="width: 100%; height: 400px;"></div>

</main>

</body>
</html>
