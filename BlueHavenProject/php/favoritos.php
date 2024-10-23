<?php
// Incluir la conexión a la base de datos desde 'includes/db.php'
include('includes/db.php');

// Verificar si el usuario está logueado
session_start();
if (!isset($_SESSION['correo'])) {
    // Si no hay sesión de usuario, redirigir al login
    header("Location: login.php");
    exit();
}

$correo = $_SESSION['correo'];

// Verificar si la sesión está activa
$sql_check_sesion = "SELECT sesion_activa, usuario_nuevo FROM usuarios WHERE correo = ?";
$stmt_check_sesion = $conn->prepare($sql_check_sesion);
$stmt_check_sesion->bind_param("s", $correo);
$stmt_check_sesion->execute();
$stmt_check_sesion->bind_result($sesion_activa, $usuario_nuevo);
$stmt_check_sesion->fetch();
$stmt_check_sesion->close();

// Verificar si la sesión está activa (sesion_activa = 1)
if ($sesion_activa == 0) {
    // Redirigir al login si la sesión no está activa
    header("Location: login.php");
    exit();
}

?>

    

    <script>
        // JavaScript para capturar el cierre de la pestaña o ventana
    window.addEventListener('beforeunload', function (e) {
        // Llamar a la función que cerrará la sesión
        cerrarSesion();
        
        // No mostramos el cuadro de confirmación al usuario (modern browsers lo ignoran)
        e.preventDefault();
        e.returnValue = ''; // Algunas navegadores pueden requerir esto
    });

    function cerrarSesion() {
        // Enviar petición AJAX al servidor para cerrar la sesión
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "logout.php", true);  // Enviar la petición al script PHP
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("cerrarSesion=1");  // Enviar una variable para que PHP sepa que debe cerrar la sesión
    }

    </script>

    <?php include('includes/footer.php'); ?>

<?php
?>
