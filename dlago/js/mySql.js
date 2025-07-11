const mysql = require("mysql2");
const crypto = require("crypto");
require("dotenv").config();
const {
  pool,
} = require("/Users/juanfelipezepeda/Developer/YSSM/backend-yssm/src/db.js");
const client = pool;

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
      for (const producto of results) {
        try {
          await client.query(
            `INSERT INTO productos
              (claveproveedor, productos, cantidad, proveedorp, categoria, idunicoinvetariado, precio, creado, rin, estadociudad, size)
            VALUES
              ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)`,
            [
              producto.claveproveedor,
              producto.productos,
              producto.cantidad,
              producto.proveedorp,
              producto.categoria,
              crypto.randomUUID(),
              producto.precio,
              producto.creado,
              producto.rin,
              "1",
              producto.size,
            ]
          );
          console.log(`Inserted producto ID ${producto.id}`);
        } catch (error) {
          console.error("Error inserting producto:", error);
        }
      }
    }
  });
});

module.exports = connection;
