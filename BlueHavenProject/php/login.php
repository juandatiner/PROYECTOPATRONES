<?php
session_start();

$message = "";

// Procesar el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanear y validar entradas
    $correo = filter_var($_POST['username'], FILTER_SANITIZE_EMAIL);
    $contrasena = htmlspecialchars($_POST['password']);

    // Conexión incluida desde el archivo db.php
    include 'includes/db.php'; 

    // Buscar el usuario por correo
    $sql = "SELECT nombre, contrasena, usuario_nuevo FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->bind_result($nombre_usuario, $hashed_password, $usuario_nuevo);

    if ($stmt->fetch()) {
        // Verificar la contraseña
        if (password_verify($contrasena, $hashed_password)) {
            // Iniciar sesión en el servidor
            $_SESSION['loggedin'] = true;
            $_SESSION['correo'] = $correo;

            // Establecer la cookie con el nombre del usuario
            setcookie('username', $nombre_usuario, time() + (86400 * 30), "/"); // 30 días

            // Cerrar la consulta anterior antes de realizar una nueva
            $stmt->close(); 

            // Actualizar la columna sesion_activa a 1 cuando el usuario inicia sesión
            $update_sql = "UPDATE usuarios SET sesion_activa = 1 WHERE correo = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("s", $correo);

            if ($update_stmt->execute()) {
                // Verificar si el usuario es nuevo
                if ($usuario_nuevo) {
                    // Redirigir a la encuesta si es la primera vez
                    echo "Redirigiendo a la encuesta...<br>";
                    header("Location: survey.php");
                    exit();
                } else {
                    // Redirigir a home si no es la primera vez
                    echo "Redirigiendo a home...<br>";
                    header("Location: home.php");
                    exit();
                }
            } else {
                // Manejar errores en la actualización de sesion_activa
                $message = "Error al actualizar el estado de sesión activa.";
            }

            $update_stmt->close();

        } else {
            $message = "Credenciales incorrectas. Por favor, intente de nuevo.";
        }
    } else {
        $message = "Credenciales incorrectas. Por favor, intente de nuevo.";
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlueHaven - Descubre la Vida Marina</title>
    <link rel="stylesheet" href="../css/styles.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.3/font/bootstrap-icons.min.css">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">BlueHaven</a>
        </div>
    </nav>

    
</body>

<!-- Formulario de Iniciar Sesión -->
<div class="container mt-5 pt-5">
    <h2 class="text-center">Inicia Sesión</h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php if (!empty($message)) { echo "<div class='alert alert-danger'>$message</div>"; } ?>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Correo Electrónico</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Ingrese su correo electrónico" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese su contraseña" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
            </form>
            <p class="text-center mt-3"><a href="recover_password.php">¿Olvidaste tu contraseña?</a></p>
        </div>
    </div>
</div>

<!-- Formulario de Registro Rápido -->
<div class="container mt-5">
    <h2 class="text-center">Crear una Cuenta</h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <button class="btn btn-light border w-100 mb-3" onclick="window.location.href='register.php';">
                Continuar con Correo Electrónico
            </button>
            <p class="text-center">Al crear una cuenta, aceptas los <a href="../html/terms.html">Términos y condiciones</a> y la <a href="../html/privacy.html">Política de privacidad</a>.</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
