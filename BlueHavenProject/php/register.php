<?php
include('includes/db.php'); // Incluir la conexión a la base de datos

$message = "";

// Función para validar la contraseña
function esContrasenaSegura($contrasena) {
    return strlen($contrasena) >= 8 &&        // Longitud mínima de 8 caracteres
           preg_match('/[A-Z]/', $contrasena) &&  // Al menos una letra mayúscula
           preg_match('/[a-z]/', $contrasena) &&  // Al menos una letra minúscula
           preg_match('/[0-9]/', $contrasena) &&  // Al menos un número
           preg_match('/[\W]/', $contrasena);     // Al menos un carácter especial
}

// Procesar el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['firstName'];
    $apellido = $_POST['lastName'];
    $correo = $_POST['email'];
    $contrasena = $_POST['password'];

    // Verificar si el correo ya existe
    $sql = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "El correo ya está registrado. Intenta con otro.";
        echo "<script>
            alert('$message');
            window.location.href = 'register.php';  // Redirige a la página de registro
        </script>";
    } else {
        // Verificar si la contraseña es segura
        if (!esContrasenaSegura($contrasena)) {
            $message = "La contraseña no es segura. Debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas, números y un carácter especial.";
            echo "<script>
                alert('$message');
                window.location.href = 'register.php';  // Redirige a la página de registro
            </script>";
        } else {
            // Cifrar la contraseña antes de almacenarla
            $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

            // Insertar el nuevo usuario en la base de datos
            $sql = "INSERT INTO usuarios (nombre, apellido, correo, contrasena) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $nombre, $apellido, $correo, $hashed_password);

            if ($stmt->execute()) {
                $message = "Usuario creado con éxito";
                echo "<script>
                    alert('$message');
                    window.location.href = 'login.php';  // Redirige a la página de login
                </script>";
            } else {
                $message = "Error al crear el usuario.";
                echo "<script>
                    alert('$message');
                    window.location.href = 'register.php';  // Redirige a la página de registro
                </script>";
            }
        }
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - BlueHaven</title>
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.3/font/bootstrap-icons.min.css">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">BlueHaven</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-search"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php"><i class="bi bi-person"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Formulario de Registro -->
    <div class="container mt-5 pt-5">
        <h2 class="text-center">Crear una Cuenta</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="register.php" method="post">
                    <div class="mb-3">
                        <label for="first-name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="first-name" name="firstName" placeholder="Ingrese su nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="last-name" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="last-name" name="lastName" placeholder="Ingrese su apellido" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Ingrese su correo electrónico" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Cree una contraseña" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="confirm-password" name="confirmPassword" placeholder="Confirme su contraseña" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                </form>
                <p class="text-center mt-3">¿Ya tienes una cuenta? <a href="login.php">Iniciar sesión</a></p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación del formulario antes de enviar
        document.querySelector('form').addEventListener('submit', function(event) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (password !== confirmPassword) {
                alert("Las contraseñas no coinciden. Por favor, inténtalo de nuevo.");
                event.preventDefault(); // Evita que el formulario sea enviado si las contraseñas no coinciden
            }
        });
    </script>
</body>
</html>
