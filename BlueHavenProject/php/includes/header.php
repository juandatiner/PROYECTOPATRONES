<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlueHaven - Descubre la Vida Marina</title>
    <link rel="stylesheet" href="../css/styles.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.3/font/bootstrap-icons.min.css">
    <style>
        /* Estilo personalizado para la barra de búsqueda */
        .search-container {
            display: flex;
            align-items: center;
            background-color: #007bff; /* Fondo azul */
            padding: 2px 15px; /* Espaciado interno */
            border-radius: 25px; /* Borde redondeado */
            width: 100%; /* Asegura que ocupe todo el espacio disponible */
        }

        /* Lupa dentro del campo de búsqueda */
        .search-container .bi-search {
            color: white;
            margin-right: 10px; /* Espacio entre lupa y campo */
            cursor: pointer;
        }

        /* Estilos para el input */
        .search-container input {
            border: none;
            border-radius: 25px;
            padding: 5px 20px;
            width: 400px; /* Mayor tamaño del campo */
            background-color: #fff; /* Fondo blanco dentro del campo */
        }

        /* Ajustar el padding del input para que no se superponga la lupa */
        .search-container input::placeholder {
            color: #bbb; /* Color del texto guía */
        }
    </style>
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">BlueHaven</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item d-flex align-items-center">
                        <?php if (isset($_COOKIE['username']) && !empty($_COOKIE['username'])): ?>
                            <span class="text-white me-3">Bienvenido, <?= htmlspecialchars($_COOKIE['username']) ?></span>
                        <?php else: ?>
                            <span class="text-white me-3">Bienvenido, Invitado</span>
                        <?php endif; ?>
                        <a class="nav-link" href="logout.php" title="Cerrar sesión">
                            <i class="bi bi-power text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Añadir el JavaScript de Bootstrap al final del body -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
