<?php

class SessionManagerFactory
{
    public static function create(): SessionManager
    {
        $db = Database::getInstance();
        return new SessionManager($db->getConnection());
    }
}
/*
Patrón Factory Method
El patrón Factory Method es útil para crear objetos de forma flexible. Lo usaremos para crear la instancia de SessionManager, dependiendo de la conexión a la base de datos.

Modificar el gestor de sesiones con Factory Method
Crea una clase que implemente el Factory Method para gestionar instancias de SessionManager:
Uso del Factory Method:
Ventaja: Facilita la creación de instancias, desacoplando la lógica de inicialización de la clase principal.
*/