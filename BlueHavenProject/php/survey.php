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

// Verificar si el usuario ya ha completado la encuesta
$sql_check = "SELECT usuario_nuevo FROM usuarios WHERE correo = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $correo);
$stmt_check->execute();
$stmt_check->bind_result($usuario_nuevo);
$stmt_check->fetch();
$stmt_check->close();

if ($usuario_nuevo == 0) {
    // Si el usuario ya completó la encuesta, redirigir al home
    header("Location: home.php");
    exit();
}

// Procesar el formulario de la encuesta
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar y obtener las respuestas de la encuesta (valores seleccionados)
    $tamaño_animal = htmlspecialchars($_POST['question1'] ?? '', ENT_QUOTES, 'UTF-8');
    $tipo_alimentacion = htmlspecialchars($_POST['question2'] ?? '', ENT_QUOTES, 'UTF-8');
    $rol_ecologico = htmlspecialchars($_POST['question3'] ?? '', ENT_QUOTES, 'UTF-8');
    $metodo_reproduccion = htmlspecialchars($_POST['question4'] ?? '', ENT_QUOTES, 'UTF-8');
    $habitat_principal = htmlspecialchars($_POST['question5'] ?? '', ENT_QUOTES, 'UTF-8');

    // Verificar que todas las preguntas tengan una respuesta
    if (!empty($tamaño_animal) && !empty($tipo_alimentacion) && !empty($rol_ecologico) && !empty($metodo_reproduccion) && !empty($habitat_principal)) {
        // Insertar las respuestas en la base de datos
        $sql_insert = "INSERT INTO respuestas_encuesta (correo, tamaño_animal, tipo_alimentacion, rol_ecologico, metodo_reproduccion, habitat_principal) 
                       VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssssss", $correo, $tamaño_animal, $tipo_alimentacion, $rol_ecologico, $metodo_reproduccion, $habitat_principal);

        if ($stmt_insert->execute()) {
            // Si la encuesta se guarda correctamente, actualizar la columna 'usuario_nuevo' a 0 (false) para este usuario
            $sql_update = "UPDATE usuarios SET usuario_nuevo = FALSE WHERE correo = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("s", $correo);
            $stmt_update->execute();
            $stmt_update->close();
            
             // Obtener el id del usuario basado en el correo
            $sql_user_id = "SELECT id FROM usuarios WHERE correo = ?";
            $stmt_user_id = $conn->prepare($sql_user_id);
            $stmt_user_id->bind_param("s", $correo);
            $stmt_user_id->execute();
            $stmt_user_id->bind_result($user_id);
            $stmt_user_id->fetch();
            $stmt_user_id->close();

            // Buscar animales que cumplan con las preferencias
            $sql_select_animals = "SELECT id FROM animales 
                                WHERE (
                                        (tamaño_animal = ?) +
                                        (tipo_alimentacion = ?) +
                                        (rol_ecologico = ?) +
                                        (metodo_reproduccion = ?) +
                                        (habitat_principal = ?)
                                    ) >= 3";  // Aquí filtramos solo animales que cumplen con 3 o más condiciones
            $stmt_select_animals = $conn->prepare($sql_select_animals);
            $stmt_select_animals->bind_param("sssss", $tamaño_animal, $tipo_alimentacion, $rol_ecologico, $metodo_reproduccion, $habitat_principal);
            $stmt_select_animals->execute();
            $result = $stmt_select_animals->get_result();
            
            // Insertar cada animal encontrado en la tabla favoritos
            $sql_insert_favorite = "INSERT IGNORE INTO favoritos (user_id, animal_id) VALUES (?, ?)";
            $stmt_insert_favorite = $conn->prepare($sql_insert_favorite);
            
            while ($row = $result->fetch_assoc()) {
                $animal_id = $row['id'];
                $stmt_insert_favorite->bind_param("ii", $user_id, $animal_id);
                $stmt_insert_favorite->execute();
            }
            
            // Cerrar declaraciones
            $stmt_insert_favorite->close();
            $stmt_select_animals->close();

            // Redirigir con mensaje de éxito
            echo "<script>
                    alert('¡Gracias por completar la encuesta!');
                    window.location.href = 'home.php';
                </script>";
        } else {
            echo "Error al guardar las respuestas: " . $stmt_insert->error;
        }

        $stmt_insert->close();
    } else {
        // Si faltan preguntas, mostrar un mensaje de error
        echo "<script>
                alert('Por favor, responde todas las preguntas.');
                window.history.back();
            </script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta sobre Vida Marina - BlueHaven</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <div class="container mt-5 pt-5">
        <h2 class="text-center mb-5">🌊 Encuesta Interactiva sobre Vida Marina 🌊</h2>
        <form id="surveyForm" action="survey.php" method="post">
            <!-- Pregunta 1 -->
            <div class="mb-4">
                <h5>1. ¿Cuál es el tamaño de tu animal favorito?</h5>
                <div class="btn-group d-flex" role="group">
                    <input type="radio" class="btn-check" name="question1" id="option1-1" value="pequeno" autocomplete="off">
                    <label class="btn btn-outline-primary flex-fill" for="option1-1"><i class="bi bi-dash"></i> Pequeño</label>

                    <input type="radio" class="btn-check" name="question1" id="option1-2" value="mediano" autocomplete="off">
                    <label class="btn btn-outline-primary flex-fill" for="option1-2"><i class="bi bi-circle"></i> Mediano</label>

                    <input type="radio" class="btn-check" name="question1" id="option1-3" value="grande" autocomplete="off">
                    <label class="btn btn-outline-primary flex-fill" for="option1-3"><i class="bi bi-circle-fill"></i> Grande</label>
                </div>
            </div>

            <!-- Pregunta 2 -->
            <div class="mb-4">
                <h5>2. ¿Qué tipo de alimentación tiene?</h5>
                <div class="btn-group d-flex" role="group">
                    <input type="radio" class="btn-check" name="question2" id="option2-1" value="carnivoro" autocomplete="off">
                    <label class="btn btn-outline-success flex-fill" for="option2-1"><i class="fas fa-tooth"></i> Carnívoro</label>

                    <input type="radio" class="btn-check" name="question2" id="option2-2" value="herbivoro" autocomplete="off">
                    <label class="btn btn-outline-success flex-fill" for="option2-2"><i class="bi bi-flower1"></i> Herbívoro</label>

                    <input type="radio" class="btn-check" name="question2" id="option2-3" value="omnivoro" autocomplete="off">
                    <label class="btn btn-outline-success flex-fill" for="option2-3"><i class="bi bi-egg-fried"></i> Omnívoro</label>

                    <input type="radio" class="btn-check" name="question2" id="option2-4" value="filtrador" autocomplete="off">
                    <label class="btn btn-outline-success flex-fill" for="option2-4"><i class="bi bi-droplet-fill"></i> Filtrador</label>
                </div>
            </div>

            <!-- Pregunta 3 -->
            <div class="mb-4">
                <h5>3. ¿Cuál es su rol ecológico?</h5>
                <div class="btn-group d-flex" role="group">
                    <input type="radio" class="btn-check" name="question3" id="option3-1" value="depredador" autocomplete="off">
                    <label class="btn btn-outline-info flex-fill" for="option3-1"><i class="bi bi-shield-fill-check"></i> Depredador</label>

                    <input type="radio" class="btn-check" name="question3" id="option3-2" value="presa" autocomplete="off">
                    <label class="btn btn-outline-info flex-fill" for="option3-2"><i class="fas fa-fish"></i> Presa</label>

                    <input type="radio" class="btn-check" name="question3" id="option3-3" value="descomponedor" autocomplete="off">
                    <label class="btn btn-outline-info flex-fill" for="option3-3"><i class="bi bi-recycle"></i> Descomponedor</label>

                    <input type="radio" class="btn-check" name="question3" id="option3-4" value="simbiotico" autocomplete="off">
                    <label class="btn btn-outline-info flex-fill" for="option3-4"><i class="fas fa-water"></i> Simbiótico</label>
                </div>
            </div>

            <!-- Pregunta 4 -->
            <div class="mb-4">
                <h5>4. ¿Cómo se reproduce?</h5>
                <div class="btn-group d-flex" role="group">
                    <input type="radio" class="btn-check" name="question4" id="option4-1" value="oviparo" autocomplete="off">
                    <label class="btn btn-outline-warning flex-fill" for="option4-1"><i class="bi bi-egg-fill"></i> Ovíparo</label>

                    <input type="radio" class="btn-check" name="question4" id="option4-2" value="viviparo" autocomplete="off">
                    <label class="btn btn-outline-warning flex-fill" for="option4-2"><i class="fas fa-baby"></i> Vivíparo</label>

                    <input type="radio" class="btn-check" name="question4" id="option4-3" value="ovoviviparo" autocomplete="off">
                    <label class="btn btn-outline-warning flex-fill" for="option4-3"><i class="fas fa-egg"></i> Ovovivíparo</label>
                </div>
            </div>

            <!-- Pregunta 5 -->
            <div class="mb-4">
                <h5>5. ¿Cuál es su hábitat principal?</h5>
                <div class="btn-group d-flex" role="group">
                    <input type="radio" class="btn-check" name="question5" id="option5-1" value="oceano" autocomplete="off">
                    <label class="btn btn-outline-danger flex-fill" for="option5-1"><i class="bi bi-water"></i> Océano</label>

                    <input type="radio" class="btn-check" name="question5" id="option5-2" value="manglar" autocomplete="off">
                    <label class="btn btn-outline-danger flex-fill" for="option5-2"><i class="bi bi-tree-fill"></i> Manglar</label>

                    <input type="radio" class="btn-check" name="question5" id="option5-3" value="coral" autocomplete="off">
                    <label class="btn btn-outline-danger flex-fill" for="option5-3"><i class="bi bi-flower3"></i> Arrecife de Coral</label>

                    <input type="radio" class="btn-check" name="question5" id="option5-4" value="playa" autocomplete="off">
                    <label class="btn btn-outline-danger flex-fill" for="option5-4"><i class="bi bi-sun"></i> Playa</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Enviar Encuesta</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


