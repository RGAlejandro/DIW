<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 400px;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button[type="submit"] {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }
        button[type="submit"]:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
<?php
    // Inicia la sesión (si aún no está iniciada)
    session_start();

    $captcha = ''; // Inicializamos $captcha fuera del bloque PHP

    // Genera una cadena de letras aleatorias de 8 caracteres
    function generarCaptcha($length = 8) {
        $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $captcha = '';
        $caracteres_length = strlen($caracteres);
        
        for ($i = 0; $i < $length; $i++) {
            $captcha .= $caracteres[rand(0, $caracteres_length - 1)];
        }
        
        return $captcha;
    }

    $captcha = generarCaptcha();

    // Almacena el captcha en una variable de sesión
    $_SESSION['captcha'] = $captcha;
    ?>
<?php
              if(isset($_GET['error']) && $_GET['error']=="email_existente"){
                echo "<div class='alert alert-danger' id='passAlert' role='alert'>Contraseñas NO son Iguales</div>";
                echo
                '<script>
                  setTimeout(function(){
                    document.getElementById("passAlert").remove();
                  },5000)
                </script>';
              }
            ?>    
    <div class="container">
        <h1>Formulario de Registro</h1>
        <form id="registroForm" action="procesar_registro.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>

            <label for="password1">Contraseña:</label>
            <input type="password" id="password1" name="password1" required>

            <label for="password2">Confirmar Contraseña:</label>
            <input type="password" id="password2" name="password2" required>
            
            <div>
            <strong style="font-size: 18px; font-weight: bold; text-shadow: 2px 2px 3px rgba(0,0,0,0.5);">Captcha: <?php echo $captcha; ?></strong>
            </div>

            <label for="captcha">Por favor, ingresa el captcha:</label>
            <input type="text" id="captcha" name="captcha" required>

            <button type="submit">Registrarse</button>
        </form>
    </div>
    
    <script>
        // Obtén una referencia al formulario
        const registroForm = document.getElementById('registroForm');

        // Agrega un evento de escucha para el envío del formulario
        registroForm.addEventListener('submit', function(event) {
            // Evita el envío del formulario por defecto
            event.preventDefault();

            // Realiza tus validaciones aquí
            const nombre = document.getElementById('nombre').value;
            const email = document.getElementById('email').value;
            const password1 = document.getElementById('password1').value;
            const password2 = document.getElementById('password2').value;
            const captchaInput = document.getElementById('captcha').value;
            const captchaStored = '<?php echo $captcha; ?>';

            if (!validateEmail(email)) {
                alert('Por favor, introduce un correo electrónico válido.');
                return;
            }

            if (password1 !== password2) {
                alert('Las contraseñas no coinciden.');
                return;
            }

            if (password1.length < 6) {
                alert('La contraseña debe tener al menos 6 caracteres.');
                return;
            }

            if (captchaInput !== captchaStored) {
                alert('El captcha ingresado no es correcto.');
                return;
            }
            // Si todas las validaciones son exitosas, envía el formulario
            registroForm.submit();
        });

        // Función para validar el formato de correo electrónico
        function validateEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }
    </script>

<script>
    if (typeof error !== 'undefined') {
        document.getElementById('mensajeError').textContent = error;
    }
    if (typeof exito !== 'undefined') {
        document.getElementById('mensajeExito').textContent = exito;
    }
</script>

</body>
</html>
