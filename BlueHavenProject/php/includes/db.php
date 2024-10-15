<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "bluehaven_db"; 

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    error_log("Conexión fallida: " . $conn->connect_error);
    die("Error de conexión. Intenta nuevamente más tarde.");
}

?>
