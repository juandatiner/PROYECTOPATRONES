<?php
session_start();
$message = "";

// Cargar autoload de Composer para usar PHPMailer
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanear y validar entrada
    $correo = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Conexión incluida desde el archivo db.php
    include '../includes/db.php';

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

        // Crear una instancia de PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuraciones del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'bluehavenrecovery@gmail.com';
            $mail->Password = 'xrugjcepzqauxrfu';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Destinatarios
            $mail->setFrom('no-reply@bluehaven.com', 'BlueHaven');
            $mail->addAddress($correo);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = "Recuperacion de Contrasena";
            $mail->Body = "Su codigo de recuperacion es: <b>$codigo</b><br>Por favor, utilicelo para restablecer su contrasena.";

            // Enviar correo
            $mail->send();
            header("Location: verify_code.php");
            exit();
        } catch (Exception $e) {
            $message = "Error al enviar el correo: {$mail->ErrorInfo}";
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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh; /* Ocupa toda la altura de la ventana */
            display: flex; /* Usar flexbox para centrar el contenido */
            flex-direction: column; /* Coloca los elementos en una columna */
            justify-content: center; /* Centra verticalmente */
        }
        .container {
            margin-top: 100px;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-bottom: 20px;
        }
        .alert {
            margin-bottom: 20px;
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
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
