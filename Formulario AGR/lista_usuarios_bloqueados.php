<html>
<head>
  <title>Lista de Alumnos</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 14px;
      background-color: #f2f2f2;
    }
    table {
      border-collapse: collapse;
      width: 80%;
      margin: 0 auto;
      background-color: #fff;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    th, td {
      text-align: left;
      padding: 12px;
      border-bottom: 1px solid #ddd;
    }
    th {
      background-color: #4CAF50;
      color: white;
    }
    tr:hover {
      background-color: #f5f5f5;
    }
    /* Estilos para centrar el formulario */
    .center {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    /* Estilos para centrar el botón */
    .center-button {
      text-align: center;
    }
  </style>
</head>
<body>
<?php
  $conexion = mysqli_connect("localhost", "root", "", "usuario") or die("Problemas con la conexión");
  $registros = mysqli_query($conexion, "SELECT Usuario_id, Usuario_nombre, Usuario_fecha_alta, Usuario_email, Usuario_perfil FROM usuarios WHERE Usuario_bloqueado='1'") or die("Problemas en el select:" . mysqli_error($conexion));
?>

<form method="POST" action="desbloquear_usuarios.php">
  <h1 style="text-align:center;">Lista de Alumnos</h1>

  <table>
    <thead>
      <tr>
        <th>Id</th>
        <th>Nombre</th>
        <th>Fecha de Alta</th>
        <th>Correo</th>
        <th>Rol</th>
        <th>Desbloquear</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($reg = mysqli_fetch_array($registros)) { ?>
        <tr>
          <td><?php echo $reg['Usuario_id']; ?></td>
          <td><?php echo $reg['Usuario_nombre']; ?></td>
          <td><?php echo $reg['Usuario_fecha_alta']; ?></td>
          <td><?php echo $reg['Usuario_email']; ?></td>
          <td><?php echo $reg['Usuario_perfil']; ?></td>
          <!-- Agrega un campo oculto para cada ID de usuario -->
          <td><input type="checkbox" name="usuarios_desbloquear[]" value="<?php echo $reg['Usuario_id']; ?>"></td> 
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <div class="center-button">
        <input style="font-size: 16px; background-color: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer;" type="button" value="Seleccionar Todos" onclick="selectAll()">
        <input style="font-size: 16px; background-color: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer;" type="submit" value="Desbloquear Usuarios">
  </div>
  
</form>
  
<script>
  function toggleCheckbox(button) {
    // Encuentra el campo oculto hermano del botón y cambia su valor
    var hiddenInput = button.previousSibling;
    hiddenInput.value = (hiddenInput.value === "1") ? "0" : "1";
  }
</script>
<script>
    function selectAll() {
      var checkboxes = document.querySelectorAll('input[name="usuarios_desbloquear[]"]');
      for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = true;
      }
    }
  </script>
<?php
  mysqli_close($conexion);
?>

</body>
</html>