<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Encabezado y estilos CSS aquí -->
</head>
<body>
    <div class="container">
        <!-- Caja de texto grande como enlace -->
        <div id="cajaTextoGrande" style="border: 2px solid red; padding: 20px; text-align: center; cursor: pointer;">
            <p>Lamentamos decirle que su cuenta está bloqueada al haber perdido tus 3 intentos</p>
            <p>Contacte con un administrador para que le vuelva a habilitar la cuenta.</p>
        </div>
    </div>

    <!-- Script JavaScript para redirigir al menú -->
    <script>
        const cajaTextoGrande = document.getElementById('cajaTextoGrande');

        // Agrega un evento a la caja de texto grande para redirigir al menú
        cajaTextoGrande.addEventListener('click', function() {
            // Aquí puedes establecer la URL de la página de menú
            window.location.href = 'menu.php';
        });
    </script>
</body>
</html>
