const fetch = require("node-fetch").default;
const {
  pool,
} = require("/Users/juanfelipezepeda/Developer/YSSM/backend-yssm/src/db.js");

async function grupotersa() {
  const username = "administracion@yantissimo.com";
  const password = ")9Jx19PW";
  const cookies = [];

  console.log("Iniciando login...");
  const loginRes = await fetch(
    "http://www.asociados.grupotersa.com.mx/yokozuna/auth/login",
    {
      method: "POST",
      headers: {
        "User-Agent": "Mozilla/5.0",
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `username=${encodeURIComponent(
        username
      )}&password=${encodeURIComponent(password)}`,
    }
  );
  cookies.push(...(loginRes.headers.raw()["set-cookie"] || []));
  const { token } = await loginRes.json();
  console.log("Login exitoso, token obtenido");

  console.log("Obteniendo inventario...");
  const res = await fetch(
    "http://www.asociados.grupotersa.com.mx/api/inventory",
    {
      headers: {
        "User-Agent": "Mozilla/5.0",
        Cookie: cookies.map((c) => c.split(";")[0]).join("; "),
        Authorization: `Bearer ${token}`,
        Accept: "application/json",
      },
    }
  );
  const arr = await res.json();
  console.log(`Total de productos obtenidos: ${arr.length}`);

  for (const obj of arr) {
    const id = `gt-${obj.id}`;
    const precio = parseFloat(obj.price) || 0;
    // await pool.query(
    //   `INSERT INTO productos
    //     (claveproveedor, productos, cantidad, proveedorp, categoria, idunicoinvetariado, precio, creado, rin, estadociudad, size)
    //    VALUES ($1,$2,$3,$4,$5,$6,$7,NOW(),$8,$9,$10)
    //    ON CONFLICT (claveproveedor) DO UPDATE SET cantidad=EXCLUDED.cantidad, precio=EXCLUDED.precio, estadociudad='1'`,
    //   [
    //     id,
    //     `${obj.brand} ${obj.series}`,
    //     obj.fullInventory,
    //     "Grupo Tersa",
    //     obj.brand.toLowerCase(),
    //     id,
    //     precio,
    //     obj.rim,
    //     "1",
    //     obj.size,
    //   ]
    // );
    console.debug(
      id,
      `${obj.brand} ${obj.series}`,
      obj.fullInventory,
      "Grupo Tersa",
      obj.brand.toLowerCase(),
      id,
      precio,
      obj.rim,
      "1",
      obj.size
    );
  }
  console.log("Procesamiento completado");
}

grupotersa();

module.exports = { grupotersa };
