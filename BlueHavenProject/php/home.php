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

// Verificar si el usuario ya ha completado la encuesta
if ($usuario_nuevo == 1) {
    // Si el usuario es nuevo (usuario_nuevo = 1), redirigir a la encuesta
    header("Location: survey.php");
    exit();
} else {
    // Si ya completó la encuesta, mostrar el contenido de la página principal
    include('includes/header.php');
    ?>

    <header class="hero-section">
        <div class="hero-video">
            <iframe width="100%" height="100%" src="https://www.youtube.com/embed/6XX3o_iH8Ps?autoplay=1&mute=1&loop=1&playlist=6XX3o_iH8Ps" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <div class="hero-overlay">
            <h1>Bienvenido a BlueHaven</h1>
            <button onclick="scrollToGallery()" class="btn btn-outline-light mt-3">Explorar</button>
        </div>
    </header>

    <!-- Galería de Imágenes Dinámica -->
    <section id="gallery" class="gallery-section">
        <div class="container text-center">
            <h2>Galería Fotográfica</h2>
            <div id="galleryCarousel" class="carousel slide gallery-carousel" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="images/clownfish.jpg" class="d-block w-100" alt="Pez Payaso">
                    </div>
                    <div class="carousel-item">
                        <img src="images/turtle.jpg" class="d-block w-100" alt="Tortuga Marina">
                    </div>
                    <div class="carousel-item">
                        <img src="images/shark.jpg" class="d-block w-100" alt="Tiburón">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Sección de Categorías -->
    <section class="categories-section">
        <div class="container text-center">
            <h2>Categorías</h2>
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card category-card">
                        <img src="images/continents.jpg" class="card-img-top" alt="Continentes">
                        <div class="card-body">
                            <h5 class="card-title">Continentes</h5>
                            <p class="card-text">Explora la vida marina en los diferentes continentes del mundo.</p>
                            <a href="continents.php" class="btn btn-primary">Explorar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card category-card">
                        <img src="images/endangered.jpg" class="card-img-top" alt="Animales en Peligro">
                        <div class="card-body">
                            <h5 class="card-title">Peligro de Extinción</h5>
                            <p class="card-text">Conoce más sobre los animales marinos en peligro de extinción.</p>
                            <a href="endangered.php" class="btn btn-primary">Explorar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card category-card">
                        <img src="images/favorites.jpg" class="card-img-top" alt="Favoritos">
                        <div class="card-body">
                            <h5 class="card-title">Favoritos</h5>
                            <p class="card-text">Encuentra y guarda tus animales marinos favoritos.</p>
                            <a href="favorites.php" class="btn btn-primary">Explorar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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

    <?php include('includes/footer.php'); ?>

<?php
}
?>
