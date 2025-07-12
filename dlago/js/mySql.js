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
      const columns = [
        "claveproveedor",
        "productos",
        "cantidad",
        "proveedorp",
        "categoria",
        "idunicoinvetariado",
        "precio",
        "creado",
        "rin",
        "estadociudad",
        "size",
      ];
      const chunkSize = 1000;
      for (let i = 0; i < results.length; i += chunkSize) {
        const chunk = results.slice(i, i + chunkSize);
        const placeholders = [];
        const values = [];
        chunk.forEach((producto, j) => {
          const baseIndex = j * columns.length;
          placeholders.push(
            `($${baseIndex + 1}, $${baseIndex + 2}, $${baseIndex + 3}, $${
              baseIndex + 4
            }, $${baseIndex + 5}, $${baseIndex + 6}, $${baseIndex + 7}, $${
              baseIndex + 8
            }, $${baseIndex + 9}, $${baseIndex + 10}, $${baseIndex + 11})`
          );
          values.push(
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
            producto.size
          );
        });
        const sql = `
          INSERT INTO productos (${columns.join(", ")})
          VALUES ${placeholders.join(", ")}
        `;
        try {
          await client.query("BEGIN");
          await client.query(sql, values);
          await client.query("COMMIT");
          console.log(
            `Inserted ${chunk.length} productos in bulk (batch ${
              i / chunkSize + 1
            })`
          );
        } catch (error) {
          await client.query("ROLLBACK");
          console.error("Error inserting productos in bulk:", error);
          // Mostrar objeto con error
        }
      }
    }
  });
});

module.exports = connection;
