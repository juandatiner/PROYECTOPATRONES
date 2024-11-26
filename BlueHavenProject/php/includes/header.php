<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlueHaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.3/font/bootstrap-icons.min.css">

    <style>
        /* Estilos del navbar superior */
        .navbar {
            background-color: #106cfc; 
            padding: 0.8rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ffffff;
        }

        .navbar-brand:hover {
            color: #3399ff;
        }

        .navbar-nav .nav-item .nav-link {
            color: #e0f0ff;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-item .nav-link:hover {
            color: #66b2ff;
        }

        .navbar-nav .nav-item .nav-link i {
            font-size: 1.2rem;
        }

        .navbar .navbar-nav .text-white {
            font-size: 1rem;
            color: #e0f0ff;
            font-weight: 500;
        }

        .navbar .navbar-nav .nav-item {
            margin-left: 0.5rem;
        }

        .navbar .bi-power {
            font-size: 1.3rem;
        }

        /* Barra de acceso rápido */
        .quick-access-bar {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1.5rem; /* Espacio entre los íconos */
            flex: 1; /* Ocupa el espacio necesario para centrarse */
        }

        .quick-access-bar a {
            color: #ffffff;
            font-size: 1rem;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: color 0.3s ease;
        }

        .quick-access-bar a:hover {
            color: #66b2ff;
        }

        .quick-access-bar a i {
            font-size: 1.5rem;
            margin-bottom: 0.2rem;
        }

        /* Espaciado para evitar que el contenido se monte */
        body {
            padding-top: 50px;
        }
    </style>
</head>

<body>
    <!-- Navbar superior con barra de acceso rápido completamente centrada -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <!-- Logo alineado a la izquierda -->
        <a class="navbar-brand" href="http://localhost/bluehaven/BlueHavenProject/php/home.php">BlueHaven</a>
        
        <!-- Barra de acceso rápido centrada -->
        <div class="quick-access-bar">
            <a href="http://localhost/bluehaven/BlueHavenProject/php/home.php" title="Inicio">
                <i class="bi bi-house"></i>Inicio
            </a>
            <a href="http://localhost/bluehaven/BlueHavenProject/php/categories/continentes.php" title="Continentes">
                <i class="bi bi-globe"></i>Continentes
            </a>
            <a href="http://localhost/bluehaven/BlueHavenProject/php/categories/peligroExtincion.php" title="Peligro de Extinción">
                <i class="bi bi-exclamation-triangle"></i>Extinción
            </a>
            <a href="http://localhost/bluehaven/BlueHavenProject/php/categories/favoritos.php" title="Favoritos">
                <i class="bi bi-heart"></i>Favoritos
            </a>
        </div>

        <!-- Información de usuario alineada a la derecha -->
        <div>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item d-flex align-items-center">
                    <?php if (isset($_COOKIE['username']) && !empty($_COOKIE['username'])): ?>
                        <span class="text-white me-3">Bienvenido, <?= htmlspecialchars($_COOKIE['username']) ?></span>
                    <?php endif; ?>
                    <a class="nav-link" href="http://localhost/bluehaven/BlueHavenProject/php/logout.php" title="Cerrar sesión">
                        <i class="bi bi-power text-white"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
