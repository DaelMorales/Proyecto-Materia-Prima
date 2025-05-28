const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const path = require('path');
const db = require('./public/database.js');  // Importar la configuración de la base de datos de mensajes
const userDb = require('./public/userDatabase.js');  // Importar la configuración de la base de datos de usuarios y proveedores
const cookieParser = require('cookie-parser');

const app = express();
const server = http.createServer(app);
const io = socketIo(server);

// Configurar el middleware para parsear cookies
app.use(cookieParser());

// Servir archivos estáticos desde el directorio 'public'
app.use(express.static('public'));

// Cargar mensajes desde la base de datos cuando un usuario se conecta
io.on('connection', (socket) => {
    console.log('Nuevo usuario conectado');

    // Obtener el correo del usuario desde las cookies
    const cookies = socket.handshake.headers.cookie.split(';').reduce((acc, cookie) => {
        const [key, value] = cookie.split('=').map(c => c.trim());
        acc[key] = decodeURIComponent(value);
        return acc;
    }, {});

    const userEmail = cookies['correo'];

    if (!userEmail) {
        console.log('Correo de usuario no encontrado en las cookies');
        return;
    }

    // Primero, intentar obtener el usuario de la tabla `usuarios`
    userDb.query("SELECT id, nombre FROM usuarios WHERE correo = ?", [userEmail], (err, result) => {
        if (err) {
            throw err;
        }
        if (result.length > 0) {
            // Usuario encontrado en la tabla `usuarios`
            const user = result[0];
            iniciarChat(user, 'user');
        } else {
            // No encontrado en la tabla `usuarios`, intentar en la tabla `proveedores`
            userDb.query("SELECT idProv AS id, nombre FROM proveedores WHERE correo = ?", [userEmail], (err, result) => {
                if (err) {
                    throw err;
                }
                if (result.length > 0) {
                    // Usuario encontrado en la tabla `proveedores`
                    const user = result[0];
                    iniciarChat(user, 'proveedor');
                } else {
                    console.log('Correo no encontrado en ninguna tabla');
                }
            });
        }
    });

    function iniciarChat(user, userType) {
        // Cargar mensajes para el usuario
        db.all("SELECT userId, userType, message FROM messages", (err, rows) => {
            if (err) {
                throw err;
            }
            rows.forEach((row) => {
                // Consultar el nombre del usuario o proveedor basado en el ID y tipo
                const query = row.userType === 'user' ?
                    "SELECT nombre FROM usuarios WHERE id = ?" :
                    "SELECT nombre FROM proveedores WHERE idProv = ?";

                userDb.query(query, [row.userId], (err, result) => {
                    if (err) {
                        throw err;
                    }
                    if (result.length > 0) {
                        const user = result[0];
                        socket.emit('chat message', { userId: row.userId, userType: row.userType, userName: user.nombre, message: row.message });
                    }
                });
            });
        });

        socket.on('chat message', (data) => {
            // Guardar mensaje en la base de datos de SQLite
            db.run("INSERT INTO messages (userId, userType, message) VALUES (?, ?, ?)", user.id, userType, data.message);

            // Enviar mensaje a todos los clientes conectados
            io.emit('chat message', { userId: user.id, userType: userType, userName: user.nombre, message: data.message });
        });

        socket.on('disconnect', () => {
            console.log('Usuario desconectado');
        });
    }
});

const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
    console.log(`Servidor corriendo en http://localhost:${PORT}`);
});
