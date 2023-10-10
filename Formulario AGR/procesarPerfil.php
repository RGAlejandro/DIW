<!-- <?php
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: menu.php');
    exit(); // Asegurarse de que el script se detenga después de redireccionar
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = mysqli_connect("localhost", "root", "", "usuario") or die("Problemas con la conexión");

    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $apellido1 = $_POST['apellido1'];
    $apellido2 = $_POST['apellido2'];
    $email = $_SESSION['email'];

    // Actualizar los datos en la base de datos
    $actualizar = mysqli_query($conexion, "UPDATE usuarios SET Usuario_nombre='$nombre', Usuario_apellido1='$apellido1', Usuario_apellido2='$apellido2' WHERE Usuario_email='$email'") or die("Problemas en la actualización: " . mysqli_error($conexion));

    if ($actualizar) {
        // La actualización fue exitosa
        header('Location: editarPerfil.php?actu=1');
    } else {
        // Hubo un error en la actualización
        header('Location: editarPerfil.php?actu=0');
    }

    mysqli_close($conexion);
} else {
    // Redirigir si el método de solicitud no es POST
    header('Location: editarPerfil.php');
}
?>

 -->

 <?php
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: menu.php');
    exit;
}

$conexion = mysqli_connect("localhost", "root", "", "usuario") 
    or die("Problemas con la conexión");

$email = $_SESSION['email'];

$busqueda = mysqli_query($conexion, "SELECT * FROM usuarios WHERE Usuario_email='$email'")
    or die("Problemas en el select" . mysqli_error($conexion));

if (mysqli_num_rows($busqueda) != 0) {
    $row = mysqli_fetch_assoc($busqueda);
    $foto = base64_encode($row['Usuario_fotografia']);
    $nombre = $row['Usuario_nombre'];
    $apellido1 = $row['Usuario_apellido1'];
    $apellido2 = $row['Usuario_apellido2'];
}

$nombreError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevoNombre = $_POST["nombre"];
    
    if (empty($nuevoNombre)) {
        header('Location: menu.php');
    } else {
        // Aquí puedes realizar la actualización en la base de datos
        $updateQuery = "UPDATE usuarios SET Usuario_nombre='$nombre', Usuario_apellido1='$apellido1', Usuario_apellido2='$apellido2' WHERE Usuario_email='$email'" or die("Problemas en la actualización: " . mysqli_error($conexion));
        
        if (mysqli_query($conexion, $updateQuery)) {
            // La actualización se realizó correctamente
            $nombre = $nuevoNombre;
        } else {
            // Hubo un error al actualizar
            $nombreError = "Error al actualizar el nombre. Por favor, inténtelo de nuevo.";
        }
    }
}
?>

