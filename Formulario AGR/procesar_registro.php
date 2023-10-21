<?php
// Verificamos si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibimos los datos del formulario
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $password1 = $_POST["password1"]; 
    $password2 = $_POST["password2"]; 
    $captchaInput = $_POST["captcha"]; // Captcha
    $provincia = $_POST['provincia'];

    // Realiza las validaciones necesarias (puedes agregar más según tus requisitos)
    if (empty($nombre) || empty($email) || empty($password1) || empty($password2) || empty($captchaInput)) {
        // Manejo de errores: Campos obligatorios no completados
        header("Location: registro.html?error=campos_vacios");
        exit();
    }

    // Validar el formato del correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Manejo de errores: Correo electrónico inválido
        header("Location: registro.html?error=email_invalido");
        exit();
    }
    // Verificar si las contraseñas coinciden
    if ($password1 !== $password2) {
        // Manejo de errores: Las contraseñas no coinciden
        header("Location: registro.html?error=contraseñas_no_coinciden");
        exit();
    }

    if (strlen($password1) < 6) {
        // Manejo de errores: Contraseña demasiado corta
        header("Location: registro.html?error=contraseña_corta");
        exit();
    }
    // Verificar si el captcha ingresado coincide con el captcha almacenado en la sesión
    session_start();
    $captchaStored = $_SESSION['captcha'];

    if ($captchaInput !== $captchaStored) {
        // Manejo de errores: Captcha incorrecto
        header("Location: registro.html?error=captcha_incorrecto");
        exit();
    }
    // Puedes realizar más validaciones aquí, como verificar si el correo ya existe en la base de datos, etc.

    // Si todas las validaciones son exitosas, puedes realizar el proceso de registro (por ejemplo, almacenar los datos en una base de datos)
    
    // Ejemplo de almacenamiento en una base de datos (debes configurar la conexión a tu base de datos)
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "usuarios";


    $conn = new mysqli($servername, $username, $password, $dbname);

    $hash_contraseña = password_hash($password1, PASSWORD_DEFAULT);
        $bloqueado = 0;
        $token = md5(uniqid());
        $fechaAlta = date('Y-m-d H:i:s');
        
        //$_SESSION['Usuario_perfil'] = 'usuario'; // Establecer el perfil del usuario como "usuario"
        $perfil_Usuario ="usuario";

        $imagen_temporal = 'imagenes/icon-user.png';
        $imagen_usuario = addslashes(file_get_contents($imagen_temporal));
        
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    // Verificar si el correo electrónico ya existe en la base de datos
    $busquedaEmail=mysqli_query($conn,"SELECT Usuario_email FROM usuarios WHERE Usuario_email='$email'")
        or die("Problemas en el select" . mysqli_error($conn));

    if (mysqli_num_rows($busquedaEmail) > 0) {
        // El correo electrónico ya existe en la base de datos, muestra un mensaje de error
        $error = "El correo electrónico ya está registrado.";
        
        // Redirigir de nuevo a la página de registro con el mensaje de error
        
        header("Location: registro.php?error=email_existente");
        exit();
    } else {
        // El correo electrónico no existe en la base de datos, procede con el registro
        $sql_insert = "INSERT INTO usuarios (Usuario_nombre, Usuario_email, Usuario_clave, Usuario_bloqueado, Usuario_token_aleatorio, Usuario_fecha_alta, Usuario_fotografia, Usuario_perfil, Usuario_provincia) 
              VALUES 
              ('$nombre','$email','$hash_contraseña','$bloqueado','$token','$fechaAlta','$imagen_usuario','$perfil_Usuario','$provincia')"
              or die("Problemas en el select" . mysqli_error($conexion));

        if ($conn->query($sql_insert) === TRUE) {
            // Registro exitoso, redirigir al usuario a una página de éxito
            $_SESSION['perfil_usuario'] = 'usuario'; // Establecer el perfil del usuario como "usuario"

            header("Location: registro_exitoso.html");
            exit();
        } else {
            // Manejo de errores: Error en la base de datos
            header("Location: registro.php?error=bd_error");
            exit();
        }
    }

    $conn->close();

    // Aquí deberías manejar el almacenamiento en la base de datos según tu configuración.

    // Por ahora, redirigimos al usuario a una página de éxito (puedes crear una página "registro_exitoso.html" para esto).
    header("Location: registro_exitoso.html");
    exit();
} else {
    // Si no se envió el formulario por el método POST, redirigir a la página de registro.
    header("Location: registro.php");
    exit();
}
?>
