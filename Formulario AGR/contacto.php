<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correoUsuario = $_POST["contactEmail"];
    $mensaje = $_POST["incidencia"];

    // Conecta a la base de datos
    $conexion = mysqli_connect("localhost", "root", "", "usuario");

    // Verifica si el correo del usuario existe
    $consulta = "SELECT * FROM usuarios WHERE Usuario_email = '$correoUsuario'";
    $resultado = mysqli_query($conexion, $consulta);
    
    if (!$resultado) {
        die("Error en la consulta: " . mysqli_error($conexion));
    }

    if (mysqli_num_rows($resultado) == 1) {
        // El correo existe, procede a insertar la incidencia
        $fechaAlta = date("Y-m-d H:i:s"); // Obtiene la fecha y hora actual

        $insertarIncidencia = "INSERT INTO incidencias (fechaAlta, correoUsuario, mensaje) VALUES ('$fechaAlta', '$correoUsuario', '$mensaje')";
        if (mysqli_query($conexion, $insertarIncidencia)) {
            // La incidencia se ha guardado correctamente
            echo "Incidencia registrada con éxito.";
        } else {
            echo "Error al registrar la incidencia: " . mysqli_error($conexion);
        }
    } else {
        // El correo no existe en la base de datos de usuarios
        echo "El correo proporcionado no existe en la base de datos.";

        header("refresh:2;url=menu.php");
        exit();
    }

    // Cierra la conexión a la base de datos
    mysqli_close($conexion);
}
?> 
