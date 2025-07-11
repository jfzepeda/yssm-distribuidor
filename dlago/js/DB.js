// db.js
const { Pool } = require("pg");
require("dotenv").config();

// Verificar que las variables de entorno estén definidas
const requiredEnvVars = [
  "PG_USER",
  "PG_HOST",
  "PG_DATABASE",
  "PG_PASSWORD",
  "PG_PORT",
];

const missingVars = requiredEnvVars.filter((varName) => !process.env[varName]);

if (missingVars.length > 0) {
  console.error("Faltan variables de entorno:", missingVars.join(", "));
  process.exit(1);
}

const pool = new Pool({
  user: process.env.PG_USER,
  host: process.env.PG_HOST,
  database: process.env.PG_DATABASE,
  password: process.env.PG_PASSWORD,
  port: process.env.PG_PORT || 5432,
  ssl:
    process.env.NODE_ENV === "production"
      ? {
          rejectUnauthorized: false,
        }
      : false,
  connectionTimeoutMillis: 5000, // timeout de 5 segundos
  idleTimeoutMillis: 30000, // timeout de conexiones inactivas
});

module.exports = {
  pool,
};
