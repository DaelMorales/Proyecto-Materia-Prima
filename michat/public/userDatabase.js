const mysql = require('mysql');

const connection = mysql.createConnection({
    host: 'localhost',  // Cambia esto a la dirección de tu servidor MySQL
    user: 'root',  // Tu usuario de MySQL
    password: '',  // Tu contraseña de MySQL
    database: 'fepi'  // Nombre de tu base de datos
});

connection.connect((err) => {
    if (err) {
        console.error('Error conectando a la base de datos de fepi: ' + err.stack);
        return;
    }
    console.log('Conectado a la base de datos de fepi con ID ' + connection.threadId);
});

module.exports = connection;
