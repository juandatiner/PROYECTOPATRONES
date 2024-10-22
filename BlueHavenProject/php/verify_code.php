<?php
session_start();
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo_ingresado = htmlspecialchars($_POST['code']);
    $nueva_contrasena = password_hash(htmlspecialchars($_POST['new_password']), PASSWORD_DEFAULT);

    // Verificar si el código coincide
    if ($codigo_ingresado === $_SESSION['codigo_recuperacion']) {
        // Conexión incluida desde el archivo db.php
        include 'includes/db.php';

        // Actualizar la contraseña en la base de datos
        $correo = $_SESSION['correo_recuperacion'];
        $sql = "UPDATE usuarios SET contrasena = ? WHERE correo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nueva_contrasena, $correo);

        if ($stmt->execute()) {
            $message = "Contraseña actualizada exitosamente. Puedes iniciar sesión.";
            // Limpiar sesión
            unset($_SESSION['codigo_recuperacion']);
            unset($_SESSION['correo_recuperacion']);
        } else {
            $message = "Error al actualizar la contraseña. Intente nuevamente.";
        }

        $stmt->close();
        $conn->close();
    } else {
        $message = "El código ingresado es incorrecto.";
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
