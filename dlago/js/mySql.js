const mysql = require("mysql");

const connection = mysql.createConnection({
  host: "localhost",
  database: "u825684599_werbung",
  user: "u825684599_werbung",
  password: "uA/JdJtza3",
});

connection.connect((err) => {
  if (err) {
    console.error("Error al conectar con MySQL:", err.stack);
    return;
  }
  console.log("Conectado a MySQL con id " + connection.threadId);
});

module.exports = connection;
