const fetch = require("node-fetch").default;
const {
  pool,
} = require("/Users/juanfelipezepeda/Developer/YSSM/backend-yssm/src/db.js");

async function hacsallantas(buscar) {
  const username = "compras@yantissimo.com";
  const password = "llanta33";
  const cookieJar = [];

  const first = await fetch("https://ventas.hacsallantas.mx", {
    headers: {
      "User-Agent": "Mozilla/5.0",
    },
  });
  cookieJar.push(...(first.headers.raw()["set-cookie"] || []));
  console.debug("Paso 1: GET inicial completado. Cookies:", cookieJar);
  const html = await first.text();
  const tokenMatch = html.match(/name="_token"\s+value="([^"]+)"/i);
  if (!tokenMatch) throw new Error("No _token encontrado");
  const token = tokenMatch[1];
  console.debug("Paso 2: Token CSRF obtenido:", token);

  console.debug("Paso 3: Iniciando login con CSRF token");
  const loginRes = await fetch("https://ventas.hacsallantas.mx/auth/signin", {
    method: "POST",
    headers: {
      "User-Agent": "Mozilla/5.0",
      "Content-Type": "application/x-www-form-urlencoded",
      Cookie: cookieJar.map((c) => c.split(";")[0]).join("; "),
      Origin: "https://ventas.hacsallantas.mx",
      Referer: "https://ventas.hacsallantas.mx/auth/signin",
    },
    body: new URLSearchParams({ email: username, password, _token: token }),
    redirect: "follow",
  });
  cookieJar.push(...(loginRes.headers.raw()["set-cookie"] || []));
  console.debug("Login completado. Cookies actualizadas:", cookieJar);

  // Extraer XSRF token de la cookie
  const xsrfCookie = cookieJar.find((c) => c.startsWith("XSRF-TOKEN="));
  const xsrfToken =
    xsrfCookie && xsrfCookie.split("=")[1]
      ? decodeURIComponent(xsrfCookie.split("=")[1].split(";")[0])
      : null;
  console.debug("Paso 3b: XSRF token obtenido de la cookie:", xsrfToken);

  console.debug("Paso 4: Realizando búsqueda con término:", buscar);
  const searchUrl =
    "https://ventas.hacsallantas.mx/search-articulos?" +
    new URLSearchParams({
      q: buscar,
      status: 1,
      "catalogo[]": "AUTO Y CAMIONETA",
    });
  console.debug("Paso 4b: URL de búsqueda construida:", searchUrl);

  const dataRes = await fetch(searchUrl, {
    headers: {
      "User-Agent": "Mozilla/5.0",
      Accept: "application/json, text/plain, */*",
      "X-Requested-With": "XMLHttpRequest",
      "X-XSRF-TOKEN": xsrfToken || "",
      Cookie: cookieJar.map((c) => c.split(";")[0]).join("; "),
    },
  });
  console.debug(
    "Paso 4c: Respuesta de búsqueda recibida. Status:",
    dataRes.status,
    "Content-Type:",
    dataRes.headers.get("content-type")
  );
  if (dataRes.headers.get("content-type")?.includes("html")) {
    console.debug(
      "Respuesta de búsqueda recibida. Status:",
      dataRes.status,
      "Content-Type:",
      dataRes.headers.get("content-type")
    );
    throw new Error("Sesión inválida: recibido HTML en vez de JSON");
  }
  const json = await dataRes.json();
  if (!json || !json.data || json.data.length === 0) {
    console.debug(
      "Respuesta JSON completa (sin datos o formato inesperado):",
      JSON.stringify(json, null, 2)
    );
  }
  console.debug(
    "Paso 5: JSON parseado. Número de resultados:",
    Array.isArray(json.data) ? json.data.length : 0
  );
  if (!json || !json.data) {
    throw new Error("No se encontraron datos o formato inesperado");
  }
  if (json.data.length === 0) {
    console.log("No hay datos para procesar.");
    return;
  }
  console.debug(`Datos obtenidos: ${json}`);
  for (const obj of json.data || []) {
    const id = "hacsallantas-" + obj.id;
    let total = 0;
    for (const ex of obj.existencias) total += parseFloat(ex.existencia) || 0;
    const match = obj.name.match(/(\d{3}\/\d{2}[A-Z]\d{2})/);
    if (!match) continue;
    const rin = match[1].split("R")[1];
    const precio = parseFloat(obj.price_iva) || 0;
    // await pool.query(
    //   `INSERT INTO productos
    //     (claveproveedor, productos, cantidad, proveedorp, categoria, idunicoinvetariado, precio, creado, rin, estadociudad, size)
    //    VALUES ($1,$2,$3,$4,$5,$6,$7,NOW(),$8,$9,$10)
    //    ON CONFLICT (claveproveedor) DO UPDATE SET cantidad=EXCLUDED.cantidad, precio=EXCLUDED.precio, estadociudad='1'`,
    //   [
    //     id,
    //     obj.name,
    //     total,
    //     "hacsallantas",
    //     obj.name.split(" ")[1].toLowerCase(),
    //     id,
    //     precio,
    //     rin,
    //     "1",
    //     match[1],
    //   ]
    // );
    console.debug(
      id,
      obj.name,
      total,
      "hacsallantas",
      obj.name.split(" ")[1].toLowerCase(),
      id,
      precio,
      rin,
      "1",
      match[1]
    );
  }

  console.log("ok");
}

hacsallantas("205/55")
  .then(() => console.log("Proceso completado"))
  .catch((err) => console.error("Error:", err));

module.exports = { hacsallantas };
