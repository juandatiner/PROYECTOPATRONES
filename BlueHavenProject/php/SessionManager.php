<?php

class SessionManager
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function isUserLoggedIn(): bool
    {
        session_start();
        return isset($_SESSION['correo']);
    }

    public function getSessionData(string $correo): ?array
    {
        $sql = "SELECT sesion_activa, usuario_nuevo FROM usuarios WHERE correo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->bind_result($sesion_activa, $usuario_nuevo);
        if ($stmt->fetch()) {
            $stmt->close();
            return ['sesion_activa' => $sesion_activa, 'usuario_nuevo' => $usuario_nuevo];
        }
        $stmt->close();
        return null;
    }

    public function redirectTo(string $url): void
    {
        header("Location: $url");
        exit();
    }
}


/*
 * Aplicación de los Principios SOLID
 * 
 * SRP (Single Responsibility Principle):
 * - La clase SessionManager tiene una única responsabilidad: gestionar las sesiones y la lógica de autenticación del usuario.
 * - El script principal ahora solo contiene la lógica para mostrar la página, delegando las tareas específicas a la clase.
 * 
 * OCP (Open/Closed Principle):
 * - La clase SessionManager puede extenderse fácilmente, por ejemplo, para agregar nuevas validaciones (como permisos de usuario o roles) sin modificar su lógica existente.
 * - Si se necesita agregar más redirecciones o lógicas adicionales, se puede hacer dentro de esta clase sin tocar el código principal.
 */
