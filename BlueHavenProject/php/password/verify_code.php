<?php
session_start();
$message = "";

// Función para validar la contraseña
function esContrasenaSegura($contrasena) {
    return strlen($contrasena) >= 8 &&        // Longitud mínima de 8 caracteres
           preg_match('/[A-Z]/', $contrasena) &&  // Al menos una letra mayúscula
           preg_match('/[a-z]/', $contrasena) &&  // Al menos una letra minúscula
           preg_match('/[0-9]/', $contrasena) &&  // Al menos un número
           preg_match('/[\W]/', $contrasena);     // Al menos un carácter especial
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo_ingresado = htmlspecialchars($_POST['code']);
    $nueva_contrasena = htmlspecialchars($_POST['new_password']);

    // Validar la nueva contraseña
    if (!esContrasenaSegura($nueva_contrasena)) {
        $message = "La contraseña no cumple con los requisitos de seguridad.";
    } else {
        $nueva_contrasena_hashed = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
        
        // Verificar si el código coincide
        if ($codigo_ingresado === $_SESSION['codigo_recuperacion']) {
            // Conexión incluida desde el archivo db.php
            include '../includes/db.php';

            // Actualizar la contraseña en la base de datos
            $correo = $_SESSION['correo_recuperacion'];
            $sql = "UPDATE usuarios SET contrasena = ? WHERE correo = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $nueva_contrasena_hashed, $correo);

            if ($stmt->execute()) {
                $message = "Contraseña actualizada exitosamente. Puedes iniciar sesión.";
                // Limpiar sesión
                unset($_SESSION['codigo_recuperacion']);
                unset($_SESSION['correo_recuperacion']);
                // Redirigir a la página de inicio de sesión (opcional)
                header("Location: ../login.php");
                exit();
            } else {
                $message = "Error al actualizar la contraseña. Intente nuevamente.";
            }

            $stmt->close();
            $conn->close();
        } else {
            $message = "El código ingresado es incorrecto.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Código</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            font-size: 16px;
            height: 100vh; /* Ocupa toda la altura de la ventana */
            display: flex; /* Usar flexbox para centrar el contenido */
            flex-direction: column; /* Coloca los elementos en una columna */
            justify-content: center; /* Centra verticalmente */
        }
        .container {
            max-width: 600px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .alert {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .mb-3 {
            margin-bottom: 15px;
        }
        .form-control {
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            font-size: 14px; 
        }
        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 18px; 
        }
        .btn:hover {
            background-color: #0056b3;
        }
        
        .navbar {
            background-color: #106cfc; /* Tono de azul claro */
        }
        .navbar-brand, .nav-link {
            color: #ffffff; /* Color blanco para el texto */
        }
        .navbar-brand:hover, .nav-link:hover {
            color: #e0e0e0; /* Color de texto al pasar el ratón */
        }
    </style>
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="http://localhost/bluehaven/BlueHavenProject/php/login.php">BlueHaven</a>
        </div>
    </nav>
    
    <div class="container mt-5">
        <h2 class="text-center">Verificar Código</h2>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <?php if (!empty($message)) { echo "<div class='alert'>$message</div>"; } ?>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="code" class="form-label">Código de Recuperación</label>
                        <input type="text" class="form-control" id="code" name="code" placeholder="Ingrese el código recibido" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Ingrese su nueva contraseña" required>
                    </div>
                    <button type="submit" class="btn">Actualizar Contraseña</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

