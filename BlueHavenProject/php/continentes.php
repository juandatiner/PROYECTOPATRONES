<?php
// Incluir la conexión a la base de datos desde 'includes/db.php'
include('includes/db.php');

// Verificar si el usuario está logueado
session_start();
if (!isset($_SESSION['correo'])) {
    // Si no hay sesión de usuario, redirigir al login
    header("Location: login.php");
    exit();
}

$correo = $_SESSION['correo'];

// Verificar si la sesión está activa
$sql_check_sesion = "SELECT sesion_activa, usuario_nuevo FROM usuarios WHERE correo = ?";
$stmt_check_sesion = $conn->prepare($sql_check_sesion);
$stmt_check_sesion->bind_param("s", $correo);
$stmt_check_sesion->execute();
$stmt_check_sesion->bind_result($sesion_activa, $usuario_nuevo);
$stmt_check_sesion->fetch();
$stmt_check_sesion->close();

// Verificar si la sesión está activa (sesion_activa = 1)
if ($sesion_activa == 0) {
    // Redirigir al login si la sesión no está activa
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Continentes - BlueHaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: Arial, sans-serif;
        }

        .container-continents {
            padding: 50px;
            text-align: center;
            margin-bottom: 100px; /* Asegurar que el footer no se superponga */
        }

        .container-continents h2 {
            margin-bottom: 50px;
            font-size: 4rem; /* Título más grande */
            color: #005f73;
        }

        .row {
            display: flex;
            justify-content: center; /* Alinear en el centro */
            flex-wrap: wrap; /* Permitir que las tarjetas se envuelvan */
        }

        .continent-card {
            flex: 0 0 30%; /* Asegurarse de que cada tarjeta ocupe el 30% del ancho */
            margin: 20px; /* Espacio entre las tarjetas */
            height: 400px; /* Altura fija para todas las tarjetas */
            display: flex; /* Hacer flex para alinear el contenido */
            flex-direction: column; /* Colocar el contenido en columna */
            justify-content: space-between; /* Espaciado entre el contenido */
            border-radius: 20px; /* Bordes redondeados */
            background: #4a4a4a; /* Fondo de la tarjeta */
            color: white; /* Color del texto */
            position: relative; /* Para el posicionamiento absoluto */
        }

        .continent-card img {
            width: 100%;
            height: 60%; /* Altura de la imagen */
            border-radius: 20px 20px 0 0; /* Bordes redondeados en la parte superior */
            object-fit: cover; /* Asegurarse de que la imagen cubra el área */
        }

        .continent-card .card-body {
            padding: 10px;
            text-align: center;
        }

        .continent-card .btn {
            background-color: #0a9396;
            border: none;
            transition: background-color 0.3s;
        }

        .continent-card .btn:hover {
            background-color: #94d2bd;
        }
    </style>
</head>
<body>

    <div class="container-continents">
        <h2>Explora los Continentes</h2>
        <div class="row justify-content-center">
            <!-- Continente África -->
            <div class="continent-card" onclick="navigateTo('africa.php')">
                <img src="images/africa.jpg" alt="África">
                <div class="card-body">
                    <h5>África</h5>
                    <p>Descubre la riqueza marina en las costas africanas.</p>
                </div>
            </div>

            <!-- Continente América -->
            <div class="continent-card" onclick="navigateTo('america.php')">
                <img src="images/america.jpg" alt="América">
                <div class="card-body">
                    <h5>América</h5>
                    <p>Explora la vida marina a lo largo de las Américas.</p>
                </div>
            </div>

            <!-- Continente Asia -->
            <div class="continent-card" onclick="navigateTo('asia.php')">
                <img src="images/asia.jpg" alt="Asia">
                <div class="card-body">
                    <h5>Asia</h5>
                    <p>Los misterios marinos de Asia te esperan.</p>
                </div>
            </div>

            <!-- Continente Europa -->
            <div class="continent-card" onclick="navigateTo('europa.php')">
                <img src="images/europa.jpg" alt="Europa">
                <div class="card-body">
                    <h5>Europa</h5>
                    <p>Descubre las aguas frías y ricas de Europa.</p>
                </div>
            </div>

            <!-- Continente Oceanía -->
            <div class="continent-card" onclick="navigateTo('oceania.php')">
                <img src="images/oceania.jpg" alt="Oceanía">
                <div class="card-body">
                    <h5>Oceanía</h5>
                    <p>La vida marina vibrante de Oceanía.</p>
                </div>
            </div>

            <!-- Continente Antártida -->
            <div class="continent-card" onclick="navigateTo('antartida.php')">
                <img src="images/antartida.jpg" alt="Antártida">
                <div class="card-body">
                    <h5>Antártida</h5>
                    <p>Los misterios congelados del continente blanco.</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Función para redirigir a la página del continente correspondiente
        function navigateTo(page) {
            window.location.href = page;
        }
    </script>

    <?php include('includes/footer.php'); ?>

</body>
</html>

<script>
    // JavaScript para capturar el cierre de la pestaña o ventana
    window.addEventListener('beforeunload', function (e) {
        // Llamar a la función que cerrará la sesión
        cerrarSesion();
        
        // No mostramos el cuadro de confirmación al usuario (modern browsers lo ignoran)
        e.preventDefault();
        e.returnValue = ''; // Algunas navegadores pueden requerir esto
    });

    function cerrarSesion() {
        // Enviar petición AJAX al servidor para cerrar la sesión
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "logout.php", true);  // Enviar la petición al script PHP
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("cerrarSesion=1");  // Enviar una variable para que PHP sepa que debe cerrar la sesión
    }
</script>
