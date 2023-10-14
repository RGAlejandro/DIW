<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $conexion = mysqli_connect("localhost", "root", "", "usuario") or die("Problemas con la conexión");
  
  if (isset($_POST['usuarios_Estado']) && is_array($_POST['usuarios_Estado'])) {
    foreach ($_POST['usuarios_Estado'] as $usuario_id) {
      $usuario_id = intval($usuario_id);
      // Consulta para obtener el estado actual del usuario
      $query = "SELECT Usuario_bloqueado FROM usuarios WHERE Usuario_id=$usuario_id";
      $result = mysqli_query($conexion, $query);

      if ($result && $row = mysqli_fetch_array($result)) {
        $estado_actual = $row['Usuario_bloqueado'];

        // Determina la acción a realizar (desbloquear o bloquear) según el estado actual
        $nuevo_estado = ($estado_actual == 0) ? 1 : 0;

        // Actualiza el estado del usuario en la base de datos
        $update_query = "UPDATE usuarios SET Usuario_bloqueado='$nuevo_estado', Usuario_numero_intentos='0' WHERE Usuario_id=$usuario_id";
      
        if (mysqli_query($conexion, $update_query)) {
          echo "Usuario ";
          echo ($nuevo_estado == 0) ? "desbloqueado" : "bloqueado";
          echo " con éxito.";
        } else {
          echo "Error al ";
          echo ($nuevo_estado == 0) ? "desbloquear" : "bloquear";
          echo " usuario: " . mysqli_error($conexion);
        }
      }
    }
  } else {
    echo "No se seleccionaron usuarios para desbloquear/bloquear.";
  }

  mysqli_close($conexion);
}
?>
