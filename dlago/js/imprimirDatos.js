const mysql = require("mysql2");
const crypto = require("crypto");
require("dotenv").config();

const MYSQL_HOST = process.env.MYSQL_HOST;
const MYSQL_NAME = process.env.MYSQL_NAME;
const MYSQL_USER = process.env.MYSQL_USER;
const MYSQL_PASSWORD = process.env.MYSQL_PASSWORD;

const connection = mysql.createConnection({
  host: MYSQL_HOST,
  port: 3306, // opcional si usas el puerto por defecto
  database: MYSQL_NAME,
  user: MYSQL_USER,
  password: MYSQL_PASSWORD, // Asegúrate de tener la variable de entorno configurada
});

connection.connect((err) => {
  if (err) {
    console.error("Error al conectar con MySQL:", err.stack);
    return;
  }
  console.log("Conectado a MySQL con id " + connection.threadId);
  // Ejemplo de SELECT * FROM productos
  connection.query("SELECT * FROM productos", async (err, results) => {
    if (err) {
      console.error("Error al ejecutar la consulta:", err);
      return;
    }
    console.log("Productos encontrados:", results.length);
    if (results.length === 0) {
      console.log("No hay productos disponibles.");
    } else {
      (async () => {
        for (const producto of results) {
          console.log(
            producto.claveproveedor,
            producto.productos,
            producto.cantidad || 0,
            producto.proveedorp,
            producto.categoria,
            producto.id,
            parseFloat(producto.precio) > 10 ? parseFloat(producto.precio) : 0,
            producto.rin || "0",
            producto.size || "default"
          );
          await new Promise((res) => setTimeout(res, 1));
        }
      })();
    }
  });
});

module.exports = connection;
