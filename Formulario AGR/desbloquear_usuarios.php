<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $conexion = mysqli_connect("localhost", "root", "", "usuario") or die("Problemas con la conexión");
  
  if (isset($_POST['usuarios_desbloquear']) && is_array($_POST['usuarios_desbloquear'])) {
    foreach ($_POST['usuarios_desbloquear'] as $usuario_id) {
      $usuario_id = intval($usuario_id);
      // Aquí debes ejecutar una consulta SQL para desbloquear al usuario con el ID $usuario_id
      $query = "UPDATE usuarios SET Usuario_bloqueado='0', Usuario_numero_intentos='0' WHERE Usuario_id=$usuario_id";
      
      if (mysqli_query($conexion, $query)) {
        echo "Usuario desbloqueado con éxito.";
      } else {
        echo "Error al desbloquear usuario: " . mysqli_error($conexion);
      }
    }
  } else {
    echo "No se seleccionaron usuarios para desbloquear.";
  }

  mysqli_close($conexion);
}
?>
