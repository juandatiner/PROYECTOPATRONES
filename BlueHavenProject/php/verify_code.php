<?php
session_start();
$message = "";

// URL de la página de origen que permitimos
$pagina_origen = 'http://localhost/bluehaven/BlueHavenProject/php/recover_password.php';

// Verificar si la página fue accedida desde la página de origen
if (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] !== $pagina_origen) {
    // Si no es de la página de origen, redirigir
    header("Location: login.php");
    exit();
}

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
            include 'includes/db.php';

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
                header("Location: login.php");
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
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Verificar Código</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php if (!empty($message)) { echo "<div class='alert alert-danger'>$message</div>"; } ?>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="code" class="form-label">Código de Recuperación</label>
                        <input type="text" class="form-control" id="code" name="code" placeholder="Ingrese el código recibido" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Ingrese su nueva contraseña" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Actualizar Contraseña</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
