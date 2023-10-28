<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: formularioLogin.php');
    exit();
}

$conexion = mysqli_connect("localhost", "root", "", "usuario") or die("Problemas con la conexión");

if (isset($_GET['id'])) {
    $idIncidencia = $_GET['id'];

    // Eliminar la incidencia de la base de datos
    $deleteQuery = "DELETE FROM incidencias WHERE id = $idIncidencia";

    if (mysqli_query($conexion, $deleteQuery)) {
        header('Location: atender_incidencias.php');
        exit();
    } else {
        echo "Error al eliminar la incidencia: " . mysqli_error($conexion);
    }
}

$consultaIncidencias = mysqli_query($conexion, "SELECT * FROM incidencias") or die("Problemas en la consulta: " . mysqli_error($conexion));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Atender Incidencias</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Lista de Incidencias</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha de Alta</th>
                    <th>Correo del Usuario</th>
                    <th>Mensaje</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($consultaIncidencias)) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['fechaAlta']}</td>";
                    echo "<td>{$row['correoUsuario']}</td>";
                    echo "<td>{$row['mensaje']}</td>";
                    echo "<td>
                        <form action='atender_incidencias.php' method='GET'>
                            <input type='hidden' name='id' value='{$row['id']}'>
                            <button type='submit' class='btn btn-primary'>Atender</button>
                        </form>
                    </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <p><a class="btn btn-secondary" href="paginaAdmin.php">Volver al Menú Principal</a></p>
    </div>
    
</body>
</html>
