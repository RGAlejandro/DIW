<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: formularioLogin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = mysqli_connect("localhost", "root", "", "usuario") or die("Problemas con la conexión");

    $email = $_SESSION['email'];
    $contraseña_actual = $_POST['contraseña-actual'];
    $contraseña_nueva = $_POST['contraseña-nueva'];

    // Consulta para obtener la contraseña actual hasheada del usuario
    $consulta = mysqli_query($conexion, "SELECT Usuario_clave FROM usuarios WHERE Usuario_email='$email'") or die("Problemas en la consulta: " . mysqli_error($conexion));

    if (mysqli_num_rows($consulta) > 0) {
        $fila = mysqli_fetch_assoc($consulta);
        $contraseña_hash = $fila['Usuario_clave'];

        // Verificar si la contraseña actual ingresada coincide con la almacenada en la base de datos
        if (password_verify($contraseña_actual, $contraseña_hash)) {
            // Verificar si la nueva contraseña cumple con los requisitos (mínimo 6 caracteres)
            if (strlen($contraseña_nueva) >= 6) {
                // Hashear la nueva contraseña
                $contraseña_nueva_hash = password_hash($contraseña_nueva, PASSWORD_DEFAULT);

                // Actualizar la contraseña hasheada en la base de datos
                $actualizar = mysqli_query($conexion, "UPDATE usuarios SET Usuario_clave='$contraseña_nueva_hash' WHERE Usuario_email='$email'") or die("Problemas en la actualización: " . mysqli_error($conexion));

                if ($actualizar) {
                    // La actualización fue exitosa
                    header('Location: cambiarContraseña.php?actu=1');
                    exit();
                } else {
                    // Hubo un error en la actualización
                    header('Location: cambiarContraseña.php?actu=0');
                    exit();
                }
            } else {
                // La nueva contraseña no cumple con los requisitos
                header('Location: cambiarContraseña.php?error=contraseña_corta');
                exit();
            }
        } else {
            // La contraseña actual ingresada es incorrecta
            header('Location: cambiarContraseña.php?error=contraseña_incorrecta');
            exit();
        }
    } else {
        // No se encontró un usuario con el email proporcionado
        header('Location: cambiarContraseña.php?error=usuario_no_encontrado');
        exit();
    }

    mysqli_close($conexion);
} else {
    // Redirigir si el método de solicitud no es POST
    header('Location: cambiarContraseña.php');
    exit();
}
?>
