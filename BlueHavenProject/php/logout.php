<?php
// Incluir la conexión a la base de datos desde 'includes/db.php'
include('includes/db.php');

// Iniciar la sesión
session_start();
if (!isset($_SESSION['correo'])) {
    // Si no hay sesión de usuario, redirigir al login
    header("Location: login.php");
    exit();
}

if (isset($_POST['cerrarSesion']) && isset($_SESSION['correo'])) {
    $correo = $_SESSION['correo'];

    // Actualizar el campo sesion_activa a 0 en la base de datos
    $sql_update = "UPDATE usuarios SET sesion_activa = 0 WHERE correo = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("s", $correo);
    $stmt_update->execute();
    $stmt_update->close();

    // Cerrar la sesión del usuario
    session_unset();  // Eliminar todas las variables de sesión
    session_destroy();  // Destruir la sesión

    exit();  // Asegurarse de que no se ejecuta código adicional
}
?>
