<?php
// Incluir los archivos necesarios
include('Database.php');
include('SessionManager.php');
include('SessionManagerFactory.php');

// Crear una instancia del gestor de sesiones usando el Factory Method
$sessionManager = SessionManagerFactory::create();

// Verificar si el usuario está logueado
if (!$sessionManager->isUserLoggedIn()) {
    $sessionManager->redirectTo("login.php");
}

// Obtener los datos de sesión del usuario
$correo = $_SESSION['correo'];
$sessionData = $sessionManager->getSessionData($correo);

// Si no se encuentra el usuario o la sesión no está activa
if (!$sessionData || $sessionData['sesion_activa'] == 0) {
    $sessionManager->redirectTo("login.php");
}

// Verificar si el usuario ya completó la encuesta
if ($sessionData['usuario_nuevo'] == 1) {
    $sessionManager->redirectTo("survey.php");
}

// Mostrar la página principal
include('includes/header.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <link rel="stylesheet" href="../css/stylesHome.css">
</head>
<body>

    <!-- Sección del Video -->
    <section class="hero-video">
        <iframe 
            src="https://www.youtube.com/embed/6XX3o_iH8Ps?autoplay=1&mute=1&loop=1&playlist=6XX3o_iH8Ps&controls=0"
            frameborder="0"
            allow="autoplay; encrypted-media"
            allowfullscreen>
        </iframe>
    </section>

    <!-- Galería de Imágenes Dinámica -->
    <section id="gallery" class="gallery-section">
        <div class="container text-center">
            <h2>Galería Fotográfica</h2>
            <div id="galleryCarousel" class="carousel slide gallery-carousel" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="../images/clownfish.jpg" class="d-block" alt="Pez Payaso">
                    </div>
                    <div class="carousel-item">
                        <img src="../images/turtle.jpg" class="d-block" alt="Tortuga Marina">
                    </div>
                    <div class="carousel-item">
                        <img src="../images/shark.jpg" class="d-block" alt="Tiburón">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Sección de Categorías -->
    <section>
        <div class="container text-center">
            <h2>Categorías</h2>
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card category-card">
                        <img src="../images/continents.jpg" class="card-img-top" alt="Continentes">
                        <div class="card-body">
                            <h5 class="card-title">Continentes</h5>
                            <p class="card-text">Explora la vida marina en los diferentes continentes del mundo.</p>
                            <a href="categories/continentes.php" class="btn btn-primary">Explorar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card category-card">
                        <img src="../images/peligro_Extincion.png" class="card-img-top" alt="Animales en Peligro">
                        <div class="card-body">
                            <h5 class="card-title">Peligro de Extinción</h5>
                            <p class="card-text">Conoce más sobre los animales marinos en peligro de extinción.</p>
                            <a href="categories/peligroExtincion.php" class="btn btn-primary">Explorar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card category-card">
                        <img src="../images/favorites.jpg" class="card-img-top" alt="Favoritos">
                        <div class="card-body">
                            <h5 class="card-title">Favoritos</h5>
                            <p class="card-text">Encuentra aquí todos los animales marinos que más te gustan.</p>
                            <a href="categories/favoritos.php" class="btn btn-primary">Explorar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include('includes/footer.php'); ?>

    
    <script>
    // Variable para indicar si el usuario está navegando
    var navegandoIntencionalmente = false;

    // Lista de páginas a las que se considera navegación intencional
    var paginasValidas = ["categories/continentes.php", "categories/favoritos.php", "categories/peligroExtincion.php"];

    // Escuchar los clicks en los enlaces
    document.addEventListener('click', function(e) {
        // Verificar si el click fue en un enlace (a)
        if (e.target.tagName === 'A') {
            var url = e.target.getAttribute('href'); // Obtener la URL del enlace
            // Verificar si la URL está en la lista de páginas válidas
            if (paginasValidas.some(pagina => url.includes(pagina))) {
                navegandoIntencionalmente = true; // Marcar como navegación intencional
            }
        }
    });

    // JavaScript para capturar el cierre de la pestaña o ventana
    window.addEventListener('beforeunload', function(e) {
        if (!navegandoIntencionalmente) {
            // Llamar a la función que cerrará la sesión
            cerrarSesion();

            // No mostramos el cuadro de confirmación al usuario (modern browsers lo ignoran)
            e.preventDefault();
            e.returnValue = ''; // Algunas navegadores pueden requerir esto
        }
    });

    function cerrarSesion() {
        // Enviar petición AJAX al servidor para cerrar la sesión
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "logout.php", true); // Enviar la petición al script PHP
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("cerrarSesion=1"); // Enviar una variable para que PHP sepa que debe cerrar la sesión
    }

    // Evitar que se considere como navegación intencional al recargar la página
    window.addEventListener('load', function() {
        navegandoIntencionalmente = true; // Al cargar la página, consideramos que la navegación fue intencional
    });
    </script>
<?php
?>
