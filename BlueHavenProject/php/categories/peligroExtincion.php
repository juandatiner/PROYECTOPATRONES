<?php
// Incluir la conexión a la base de datos desde 'includes/db.php'
include('../includes/db.php');

// Verificar si el usuario está logueado
session_start();
if (!isset($_SESSION['correo'])) {
    header("Location: http://localhost/bluehaven/BlueHavenProject/php/login.php");
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
    header("Location: http://localhost/bluehaven/BlueHavenProject/php/login.php");
    exit();
}

if ($usuario_nuevo == 1) {
    header("Location: http://localhost/bluehaven/BlueHavenProject/php/survey.php");
    exit();
}


// Obtener filtros seleccionados
$tamaño_animal = isset($_GET['tamaño_animal']) ? $_GET['tamaño_animal'] : '';
$tipo_alimentacion = isset($_GET['tipo_alimentacion']) ? $_GET['tipo_alimentacion'] : '';
$rol_ecologico = isset($_GET['rol_ecologico']) ? $_GET['rol_ecologico'] : '';
$metodo_reproduccion = isset($_GET['metodo_reproduccion']) ? $_GET['metodo_reproduccion'] : '';
$habitat_principal = isset($_GET['habitat_principal']) ? $_GET['habitat_principal'] : '';

// Construir la consulta SQL con filtros dinámicos
$sql_animales = "SELECT nombre, tamaño_animal, tipo_alimentacion, rol_ecologico, metodo_reproduccion, habitat_principal, en_peligro_extincion 
                 FROM animales 
                 WHERE en_peligro_extincion = 1";

// Agregar condiciones de filtros seleccionados
if ($tamaño_animal) {
    $sql_animales .= " AND tamaño_animal = '$tamaño_animal'";
}
if ($tipo_alimentacion) {
    $sql_animales .= " AND tipo_alimentacion = '$tipo_alimentacion'";
}
if ($rol_ecologico) {
    $sql_animales .= " AND rol_ecologico = '$rol_ecologico'";
}
if ($metodo_reproduccion) {
    $sql_animales .= " AND metodo_reproduccion = '$metodo_reproduccion'";
}
if ($habitat_principal) {
    $sql_animales .= " AND habitat_principal = '$habitat_principal'";
}

$result = $conn->query($sql_animales);

// Incluir el encabezado de la página
include('../includes/header.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>África</title>
    <link rel="stylesheet" href="http://localhost/bluehaven/BlueHavenProject/css/stylesContinentesUnitarios.css">
    <link rel="stylesheet" href="http://localhost/bluehaven/BlueHavenProject/css/animalStyles.css">
</head>
<body>
    <div class="main-container">
        <!-- Título de la página -->
        <h1>Animales marinos en peligro de extinción</h1>
        
        <!-- Menú de filtros a la izquierda -->
        <div class="filter-menu">
            <h2>Filtrar Animales</h2>
            <form method="GET" action="">
                <label for="tamaño_animal">Tamaño del Animal</label>
                <select name="tamaño_animal" id="tamaño_animal">
                    <option value="" <?= $tamaño_animal == '' ? 'selected' : '' ?>>Todos</option>
                    <option value="pequeño" <?= $tamaño_animal == 'pequeño' ? 'selected' : '' ?>>Pequeño</option>
                    <option value="mediano" <?= $tamaño_animal == 'mediano' ? 'selected' : '' ?>>Mediano</option>
                    <option value="grande" <?= $tamaño_animal == 'grande' ? 'selected' : '' ?>>Grande</option>
                </select>

                <label for="tipo_alimentacion">Tipo de Alimentación</label>
                <select name="tipo_alimentacion" id="tipo_alimentacion">
                    <option value="" <?= $tipo_alimentacion == '' ? 'selected' : '' ?>>Todos</option>
                    <option value="carnivoro" <?= $tipo_alimentacion == 'carnivoro' ? 'selected' : '' ?>>Carnívoro</option>
                    <option value="herbivoro" <?= $tipo_alimentacion == 'herbivoro' ? 'selected' : '' ?>>Herbívoro</option>
                    <option value="omnivoro" <?= $tipo_alimentacion == 'omnivoro' ? 'selected' : '' ?>>Omnívoro</option>
                    <option value="filtrador" <?= $tipo_alimentacion == 'filtrador' ? 'selected' : '' ?>>Filtrador</option>
                </select>

                <label for="rol_ecologico">Rol Ecológico</label>
                <select name="rol_ecologico" id="rol_ecologico">
                    <option value="" <?= $rol_ecologico == '' ? 'selected' : '' ?>>Todos</option>
                    <option value="depredador" <?= $rol_ecologico == 'depredador' ? 'selected' : '' ?>>Depredador</option>
                    <option value="presa" <?= $rol_ecologico == 'presa' ? 'selected' : '' ?>>Presa</option>
                    <option value="descomponedor" <?= $rol_ecologico == 'descomponedor' ? 'selected' : '' ?>>Descomponedor</option>
                    <option value="simbiotico" <?= $rol_ecologico == 'simbiotico' ? 'selected' : '' ?>>Simbiótico</option>
                </select>

                <label for="metodo_reproduccion">Método de Reproducción</label>
                <select name="metodo_reproduccion" id="metodo_reproduccion">
                    <option value="" <?= $metodo_reproduccion == '' ? 'selected' : '' ?>>Todos</option>
                    <option value="oviparo" <?= $metodo_reproduccion == 'oviparo' ? 'selected' : '' ?>>Ovíparo</option>
                    <option value="viviparo" <?= $metodo_reproduccion == 'viviparo' ? 'selected' : '' ?>>Vivíparo</option>
                    <option value="ovoviviparo" <?= $metodo_reproduccion == 'ovoviviparo' ? 'selected' : '' ?>>Ovovivíparo</option>
                </select>

                <label for="habitat_principal">Hábitat Principal</label>
                <select name="habitat_principal" id="habitat_principal">
                    <option value="" <?= $habitat_principal == '' ? 'selected' : '' ?>>Todos</option>
                    <option value="oceano" <?= $habitat_principal == 'oceano' ? 'selected' : '' ?>>Océano</option>
                    <option value="manglar" <?= $habitat_principal == 'manglar' ? 'selected' : '' ?>>Manglar</option>
                    <option value="coral" <?= $habitat_principal == 'coral' ? 'selected' : '' ?>>Coral</option>
                    <option value="playa" <?= $habitat_principal == 'playa' ? 'selected' : '' ?>>Playa</option>
                </select>

                <label for="en_peligro_extincion">En Peligro de Extinción</label>
                <select name="en_peligro_extincion" id="en_peligro_extincion">
                    <option value="" <?= $en_peligro_extincion == '' ? 'selected' : '' ?>>Todos</option>
                    <option value="1" <?= $en_peligro_extincion === "1" ? 'selected' : '' ?>>Sí</option>
                    <option value="0" <?= $en_peligro_extincion === "0" ? 'selected' : '' ?>>No</option>
                </select>

                <button type="submit">Aplicar Filtros</button>
            </form>
        </div>

        <!-- Contenedor de animales -->
        <div class="animal-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="animal-card">
                        <div class="card-inner">
                            <div class="card-front">
                                <div class="heart-icon" onclick="toggleHeart()">
                                    <img id="heartIcon" src="../../images/corazon_blanco.jpg" onclick="toggleHeart(this);" alt="Corazón" /> <!-- aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa-->
                                </div>
                                <?php if ($row['en_peligro_extincion']): // Mostrar mensaje "Cuidame" si está en peligro ?>
                                    <div class="cuidame-message">¡Cuídame!</div>
                                <?php endif; ?>

                                <?php
                            $image_base_path = "../../images/" . strtolower(str_replace(' ', '_', $row['nombre']));
                            $image_path = '';

                            // Verificar si la imagen con extensión .jpg, .gif o .jfif existe
                            if (file_exists($image_base_path . ".jpg")) {
                                $image_path = $image_base_path . ".jpg";
                            } elseif (file_exists($image_base_path . ".gif")) {
                                $image_path = $image_base_path . ".gif";
                            } elseif (file_exists($image_base_path . ".jfif")) {
                                $image_path = $image_base_path . ".jfif";
                            } elseif (file_exists($image_base_path . ".jpeg")) {
                                $image_path = $image_base_path . ".jpeg";
                            } elseif (file_exists($image_base_path . ".png")) {
                                $image_path = $image_base_path . ".png";
                            } else {
                                // Imagen de respaldo si no se encuentra ninguna de las anteriores
                                $image_path = "../../images/default.jpg";
                            }
                            ?>

                            <img src="<?php echo $image_path; ?>" alt="<?php echo $row['nombre']; ?>" class="animal-image">
                                <h3><?php echo ucfirst($row['nombre']); ?></h3>
                            </div>
                            <div class="card-back">
                                <p><strong>Tamaño:</strong> <?php echo ucfirst($row['tamaño_animal']); ?></p>
                                <p><strong>Alimentación:</strong> <?php echo ucfirst($row['tipo_alimentacion']); ?></p>
                                <p><strong>Rol Ecológico:</strong> <?php echo ucfirst($row['rol_ecologico']); ?></p>
                                <p><strong>Método de Reproducción:</strong> <?php echo ucfirst($row['metodo_reproduccion']); ?></p>
                                <p><strong>Hábitat Principal:</strong> <?php echo ucfirst($row['habitat_principal']); ?></p>
                                <?php if ($row['en_peligro_extincion']): ?>
                                    <p><strong>Estado:</strong> En Peligro de Extinción</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No se encontraron animales que coincidan con los filtros seleccionados.</p>
            <?php endif; ?>
        </div>
    </div>
    <script>
    // JavaScript para manejar el efecto de voltear la tarjeta
    document.querySelectorAll('.animal-card').forEach(card => {
        // Evento para la tarjeta
        card.addEventListener('click', (event) => {
            // Verificar si el clic fue en el corazón
            if (!event.target.classList.contains('heart-icon')) {
                card.querySelector('.card-inner').classList.toggle('flipped');
            }
        });

        // Obtener el ícono del corazón dentro de la tarjeta
        const heartIcon = card.querySelector('.heart-icon');

        // Verificar si el ícono del corazón existe antes de agregar el evento
        if (heartIcon) {
            // Evento para el corazón
            heartIcon.addEventListener('click', (event) => {
                event.stopPropagation(); // Detener la propagación del clic hacia la tarjeta
                toggleHeart(heartIcon); // Llama a la función para cambiar la imagen del corazón
            });
        }
    });

    // Función para alternar el corazón
    function toggleHeart(heartIcon) {
        // Verifica si la fuente del corazón incluye el color blanco
        console.log("Heart icon clicked!"); // Para verificar si se llama la función
        if (heartIcon.src.includes("corazon_blanco.jpg")) {
            heartIcon.src = "../../images/corazon_rojo.jpg"; 
        } else {
            heartIcon.src = "../../images/corazon_blanco.jpg"; 
        }
    }
    
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

