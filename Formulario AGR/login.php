<?php
  session_start();
  
  $conexion = mysqli_connect("localhost", "root", "", "usuario") 
    or die("Problemas con la conexión");

  $email= $_POST['email'];
  $contraseña = $_POST['password'];
  $captchaInput = $_POST["captcha"];
  $captchaStored = $_SESSION['captcha'];

         
  
  $busqueda=mysqli_query($conexion,"SELECT * FROM usuarios WHERE Usuario_email='$email'")
  or die("Problemas en el select" . mysqli_error($conexion));

  if(mysqli_num_rows($busqueda) != 0){
          
          $row = mysqli_fetch_assoc($busqueda); 

          $bloqueo=$row['Usuario_bloqueado'];
          $dbEmail=$row['Usuario_email'];
          $dbContraseña=$row['Usuario_clave'];
          $dbPerfilUsuario=$row['Usuario_perfil'];
          if ($captchaInput !== $captchaStored) {
            // Manejo de errores: Captcha incorrecto
            $numIntentos=$row['Usuario_numero_intentos'];
              $intentosFallidos=$numIntentos;
              $intentosFallidos++;
              $sqlUpdate = "UPDATE usuarios SET Usuario_numero_intentos='$intentosFallidos' WHERE Usuario_email='$email'";
              mysqli_query($conexion, $sqlUpdate);
              if ($intentosFallidos >= 3) {
                $sqlBloquearUsuario = "UPDATE usuarios SET Usuario_bloqueado=1 WHERE Usuario_email='$email'";
                mysqli_query($conexion, $sqlBloquearUsuario);
              }
              if($bloqueo==1){
                header('Location:usuarioBloqueado.php');
                exit();
              }
            header("Location:inicio_sesion.php");
            exit();
        }
          if($bloqueo==1){
            header('Location:usuarioBloqueado.php');
            exit();
          }
          else{
            if (password_verify($contraseña, $dbContraseña)) {
              $Intentos=0;
              $sqlUpdate = "UPDATE usuarios SET Usuario_numero_intentos='$Intentos' WHERE Usuario_email='$email'";
              mysqli_query($conexion, $sqlUpdate);
              $_SESSION['email']=$email;
              
              if($dbPerfilUsuario=="usuario"){
                header('Location:paginaUsuario.php');
                exit();
              }
              if($dbPerfilUsuario=="admin"){
                header('Location:paginaAdmin.php');
                exit();
              }
              
              //header('Location:formularioLogin.php?login=1');
          }else{
              $numIntentos=$row['Usuario_numero_intentos'];
              $intentosFallidos=$numIntentos;
              $intentosFallidos++;
              $sqlUpdate = "UPDATE usuarios SET Usuario_numero_intentos='$intentosFallidos' WHERE Usuario_email='$email'";
              mysqli_query($conexion, $sqlUpdate);
              if ($intentosFallidos >= 3) {
                $sqlBloquearUsuario = "UPDATE usuarios SET Usuario_bloqueado=1 WHERE Usuario_email='$email'";
                mysqli_query($conexion, $sqlBloquearUsuario);
              }
              header('Location:inicio_sesion.php');
              exit();
          }
          }
          

      
  }else{
      header('Location:inicio_sesion.php');
      exit();
  }
  mysqli_close($conexion);

?>