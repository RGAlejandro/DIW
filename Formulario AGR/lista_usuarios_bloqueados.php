<html>
<head>
  <title>Lista de Alumnos</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
<!-- LISTA DESPEGABLE PARTE PHP-->  

<?php
$provincia_seleccionada = $_POST['provincia'];
echo "Provincia seleccionada: " . $provincia_seleccionada;

$consulta = "SELECT u.Usuario_id, u.Usuario_nombre, u.Usuario_fecha_alta, u.Usuario_email, u.Usuario_perfil, p.Provincia
           FROM usuarios u
           INNER JOIN provincias p ON u.Usuario_provincia = p.idProvincia";

if ($provincia_seleccionada !== 'todas') {
    $consulta .= " WHERE u.Usuario_provincia = '$provincia_seleccionada'";
}
echo "Consulta SQL: " . $consulta;
?>

<?php
// Establece la conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "usuario") or die("Problemas con la conexión");

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $filtro = $_POST['filtro'];
  $provincia_seleccionada = $_POST['provincia'];

  $consulta = "SELECT u.Usuario_id, u.Usuario_nombre, u.Usuario_fecha_alta, u.Usuario_email, u.Usuario_perfil, p.Provincia
               FROM usuarios u
               INNER JOIN provincias p ON u.Usuario_provincia = p.idProvincia
               WHERE 1"; // Inicia con una cláusula WHERE verdadera

  if ($provincia_seleccionada !== 'todas') {
    $consulta .= " AND u.Usuario_provincia = '$provincia_seleccionada'";
  }

  if ($filtro === 'bloqueados') {
    $consulta .= " AND u.Usuario_bloqueado = 1";
  } elseif ($filtro === 'desbloqueados') {
    $consulta .= " AND u.Usuario_bloqueado = 0";
  }

  $consulta .= " ORDER BY p.Provincia";

  $registros = mysqli_query($conexion, $consulta) or die("Problemas en el select:" . mysqli_error($conexion));
}
?>


<form method="POST" action="lista_usuarios_bloqueados.php">
  <label for="filtro">Filtrar por:</label>
  <select name="filtro" id="filtro">
    <option value="todos">Todos</option>
    <option value="bloqueados">Bloqueados</option>
    <option value="desbloqueados">Desbloqueados</option>
  </select>
  <select name="provincia" id="provincia">
    <option value="todas">Todas las provincias</option>
    <?php
    // Consulta para obtener todas las provincias registradas en la BBDD y ordenar alfabéticamente por nombre de provincia
    $provincia_query = "SELECT DISTINCT Usuario_provincia, Provincia FROM usuarios INNER JOIN provincias ON usuarios.Usuario_provincia = provincias.idProvincia ORDER BY Provincia";
    $provincias_result = mysqli_query($conexion, $provincia_query) or die("Problemas en la consulta:" . mysqli_error($conexion));

    while ($provincia = mysqli_fetch_array($provincias_result)) {
      echo '<option value="' . $provincia['Usuario_provincia'] . '">' . $provincia['Provincia'] . '</option>';
    }
    ?>
  </select>
  <input type="submit" value="Filtrar">
</form>
  
<form method="POST" action="desbloquear_usuarios.php">
  <h1 style="text-align:center;">Lista de Alumnos</h1>

  <table>
    <thead>
      <tr>
        <th>Id</th>
        <th>Nombre</th>
        <th>Fecha de Alta</th>
        <th>Correo</th>
        <th>Provincia</th>
        <th>Rol</th>
        <th>Accionar</th>
      </tr>
    </thead>
    <tbody>
      <?php 

    $registros = mysqli_query($conexion, $consulta) or die("Problemas en el select:" . mysqli_error($conexion));

    while ($reg = mysqli_fetch_array($registros)) { ?>
        <tr>
          <td><?php echo $reg['Usuario_id']; ?></td>
          <td><?php echo $reg['Usuario_nombre']; ?></td>
          <td><?php echo $reg['Usuario_fecha_alta']; ?></td>
          <td><?php echo $reg['Usuario_email']; ?></td>
          <td><?php echo $reg['Provincia']; ?></td>
          <td><?php echo $reg['Usuario_perfil']; ?></td>
          <!-- Agrega un campo oculto para cada ID de usuario -->
          <td><input type="checkbox" name="usuarios_Estado[]" value="<?php echo $reg['Usuario_id']; ?>"></td> 
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <div class="center-button">
        <input style="font-size: 16px; background-color: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer;" type="button" value="Seleccionar Todos" onclick="selectAll()">
        <input style="font-size: 16px; background-color: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer;" type="submit" value="Desbloquear Usuarios">
  </div>
  <p><a class="btn btn-secondary" href="paginaAdmin.php">Volver al Menú Principal</a></p>

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
      var checkboxes = document.querySelectorAll('input[name="usuarios_Estado[]"]');
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