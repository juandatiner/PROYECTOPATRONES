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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Ícono de búsqueda con un campo siempre visible -->
                    <li class="nav-item">
                        <div class="search-container">
                            <i class="bi bi-search"></i>
                            <input class="form-control" type="text" placeholder="De qué animal quieres conocer..." aria-label="Buscar">
                        </div>
                    </li>

                    <!-- Menú desplegable de usuario -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person"></i> Mi Cuenta
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="profile.php">Mi Perfil</a></li>
                            <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Añadir el JavaScript de Bootstrap al final del body -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
