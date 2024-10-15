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
    $sql = "SELECT contrasena, usuario_nuevo FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->bind_result($hashed_password, $usuario_nuevo);

    if ($stmt->fetch()) {
        // Verificar la contraseña
        if (password_verify($contrasena, $hashed_password)) {
            // Iniciar sesión en el servidor
            $_SESSION['loggedin'] = true;
            $_SESSION['correo'] = $correo;

            // Depuración: Imprimir los valores de la sesión
            echo "<pre>";
            var_dump($_SESSION); 
            echo "</pre>";

            echo "Usuario encontrado y contraseña verificada. Usuario nuevo: " . $usuario_nuevo . "<br>";

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
            $message = "Credenciales incorrectas. Por favor, intente de nuevo.";
        }
    } else {
        $message = "Credenciales incorrectas. Por favor, intente de nuevo.";
    }

    $stmt->close();
    $conn->close();
}
?>

<?php include 'includes/header.php'; ?>

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
        </div>
    </div>
</div>

<!-- Formulario de Registro Rápido -->
<div class="container mt-5">
    <h2 class="text-center">Crear una Cuenta</h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <button class="btn btn-light border w-100 mb-3" onclick="window.location.href='register.php';">
                <i class="bi bi-envelope"></i> Continuar con Correo Electrónico
            </button>
            <p class="text-center">Al crear una cuenta, aceptas los <a href="#">Términos y condiciones</a> y la <a href="#">Política de privacidad</a>.</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
