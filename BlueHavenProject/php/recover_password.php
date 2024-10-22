<?php
session_start();
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanear y validar entrada
    $correo = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Conexión incluida desde el archivo db.php
    include 'includes/db.php';

    // Verificar si el correo existe
    $sql = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generar código de verificación
        $codigo = bin2hex(random_bytes(4)); // Genera un código aleatorio
        // Guardar código en la sesión
        $_SESSION['codigo_recuperacion'] = $codigo;
        $_SESSION['correo_recuperacion'] = $correo;

        // Enviar el correo (asegúrate de que tu servidor esté configurado para enviar correos)
        $to = $correo;
        $subject = "Recuperación de Contraseña";
        $message = "Su código de recuperación es: $codigo";
        $headers = "From: no-reply@bluehaven.com";

        if (mail($to, $subject, $message, $headers)) {
            header("Location: verify_code.php");
            exit();
        } else {
            $message = "Error al enviar el correo. Intente nuevamente.";
        }
    } else {
        $message = "El correo no está registrado.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Recuperar Contraseña</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php if (!empty($message)) { echo "<div class='alert alert-danger'>$message</div>"; } ?>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Ingrese su correo electrónico" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Enviar Código</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
