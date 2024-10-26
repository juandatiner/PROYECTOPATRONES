<?php
// Incluir la conexión a la base de datos desde 'includes/db.php'
include('../includes/db.php');

// Verificar si el usuario está logueado
session_start();
if (!isset($_SESSION['correo'])) {
    header("Location: ../login.php");
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

// Redireccionar según el estado de la sesión
if ($sesion_activa == 0) {
    header("Location: ../login.php");
    exit();
}

if ($usuario_nuevo == 1) {
    header("Location: ../survey.php");
    exit();
}

// Mostrar el contenido de la página principal
include('../includes/header.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Continentes - BlueHaven</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/stylesContinentes.css"> 
</head>
<body>
    
    <div class="container-continents">
        <h2>Explora los Continentes</h2>
        <div class="row justify-content-center">
            <!-- Continentes -->
            <?php
            $continentes = [
                ["nombre" => "África", "imagen" => "africa.jpg", "descripcion" => "Descubre la riqueza marina en las costas africanas.", "pagina" => "continentes/africa.php"],
                ["nombre" => "América", "imagen" => "america.jpg", "descripcion" => "Explora la vida marina a lo largo de las Américas.", "pagina" => "continentes/america.php"],
                ["nombre" => "Asia", "imagen" => "asia.jpg", "descripcion" => "Los misterios marinos de Asia te esperan.", "pagina" => "continentes/asia.php"],
                ["nombre" => "Europa", "imagen" => "europa.jpg", "descripcion" => "Descubre las aguas frías y ricas de Europa.", "pagina" => "continentes/europa.php"],
                ["nombre" => "Oceanía", "imagen" => "oceania.jpg", "descripcion" => "La vida marina vibrante de Oceanía.", "pagina" => "continentes/oceania.php"],
            ];

            foreach ($continentes as $continente) {
                echo "<div class='continent-card' onclick=\"navigateTo('{$continente['pagina']}')\">";
                echo "<img src='../../images/{$continente['imagen']}' alt='{$continente['nombre']}'>";
                echo "<div class='card-body'>";
                echo "<h5>{$continente['nombre']}</h5>";
                echo "<p>{$continente['descripcion']}</p>";
                echo "</div></div>";
            }
            ?>
        </div>
    </div>

    <script>
    let navegandoIntencionalmente = false;

    // Función para cerrar sesión
    function cerrarSesion() {
        return new Promise((resolve) => {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "http://localhost/bluehaven/BlueHavenProject/php/logout.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    resolve(); // Resuelve la promesa después de cerrar sesión
                }
            };
            xhr.send("cerrarSesion=1");
        });
    }

    // Función de navegación
    function navigateTo(page) {
        // Marcar como navegación intencional
        navegandoIntencionalmente = true;
        cerrarSesion().then(() => {
            window.location.href = page;
        });
    }

    // Escuchar clicks en enlaces y botones
    document.addEventListener('click', function(e) {
        if (e.target.tagName === 'A' || e.target.closest('a')) {
            navegandoIntencionalmente = true; // Marcar como navegación intencional
        }
    });

    // Capturar el cierre de la pestaña o ventana
    window.addEventListener('beforeunload', function (e) {
        if (!navegandoIntencionalmente) {
            cerrarSesion();
        }
    });

    // Marcar como navegación intencional al cargar la página
    window.addEventListener('load', function() {
        navegandoIntencionalmente = true; // Al cargar la página, consideramos que la navegación fue intencional
    });

    // Verificar si el usuario está navegando intencionalmente
    window.addEventListener('popstate', function () {
        navegandoIntencionalmente = true; // Si vuelve a una página anterior
    });
</script>

    <?php include('../includes/footer.php'); ?>
</body>
</html>

