const sqlite3 = require('sqlite3').verbose();
const db = new sqlite3.Database('chat.db');

db.serialize(() => {
    // Asegúrate de que la tabla messages tenga los campos userId y userType además del message
    db.run("CREATE TABLE IF NOT EXISTS messages (id INTEGER PRIMARY KEY AUTOINCREMENT, userId INTEGER, userType TEXT, message TEXT)");
});

module.exports = db;
