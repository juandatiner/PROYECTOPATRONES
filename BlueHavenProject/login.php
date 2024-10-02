<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bluehaven_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['username'];
    $contrasena = $_POST['password'];

    // Buscar el usuario por correo
    $sql = "SELECT contrasena FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    
    if ($stmt->fetch()) {
        // Verificar la contraseña
        if (password_verify($contrasena, $hashed_password)) {
            echo "Inicio de sesión exitoso.";
            // Redirigir al index o al perfil del usuario
            header("Location: index.html");
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Correo electrónico no encontrado.";
    }

    $stmt->close();
}

$conn->close();
?>
