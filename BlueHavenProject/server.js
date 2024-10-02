const express = require('express');
const mysql = require('mysql');
const app = express();

// Crear conexión a la base de datos
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'tu_contraseña',
    database: 'BlueHavenDB'
});

// Conectar a MySQL
db.connect((err) => {
    if (err) {
        console.log('Error conectando a MySQL:', err);
        return;
    }
    console.log('Conectado a MySQL');
});

// Ruta para probar la conexión
app.get('/', (req, res) => {
    res.send('Servidor y base de datos conectados correctamente');
});

// Servidor escuchando
app.listen(3000, () => {
    console.log('Servidor corriendo en http://localhost:3000');
});
