<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlueHaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.3/font/bootstrap-icons.min.css">

    <style>
        .navbar {
            background-color: #106cfc; 
            padding: 0.8rem 1rem;
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

        body {
            padding-top: 70px; /* Espacio para que el contenido no se monte en el navbar */
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="http://localhost/bluehaven/BlueHavenProject/php/home.php">BlueHaven</a>
            <div class="collapse navbar-collapse" id="navbarNav">
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
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
