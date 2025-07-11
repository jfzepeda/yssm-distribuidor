// index.js
const fetch = require("node-fetch").default;
// const { pool } = require("./db");
const {
  pool,
} = require("/Users/juanfelipezepeda/Developer/YSSM/backend-yssm/src/db.js");

// Función equivalente a extstr en PHP
function extstr(content, start, end) {
  if (!content || !start || !end) return "";
  const parts = content.split(start);
  if (parts[1]) {
    return parts[1].split(end)[0];
  }
  return "";
}

// Lee HTML/JSON de una URL
async function leerHtml(url) {
  const res = await fetch(url, {
    headers: {
      "User-Agent":
        "Mozilla/5.0 (Windows NT 6.1; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2",
    },
    redirect: "follow",
    timeout: 4000,
  });
  return await res.text();
}

// Separa los últimos dos dígitos con regex
function separarUltimosDosDigitosRegex(numero, id) {
  // Remove all whitespace
  const clean = numero.replace(/\s+/g, "");
  // Match everything before an optional 'R' and capture the final two digits as rim size
  const regex = /^(.+?)[rR]?(\d{2})/;
  const matches = clean.match(regex);
  if (matches && matches.length === 3) {
    // matches[1] is the tire section (e.g., '33X12.5'), matches[2] is the rim (e.g., '18')
    return [matches[1], matches[2], id];
  }
  throw new Error("Número inválido: " + numero);
}

(async () => {
  const client = pool;

  try {
    // Descarga y parsea las dos "páginas" de datos
    const urlBase =
      "https://distribuidores.carmotion.com.mx/wp-admin/admin-ajax.php?action=wp_ajax_ninja_tables_public_action&table_id=177&target_action=get-all-data&default_sorting=old_first&ninja_table_public_nonce=";
    const [raw1, raw2] = await Promise.all([
      leerHtml(urlBase + "7eaf5b9566&chunk_number=0%27"),
      leerHtml(urlBase + "a7195de6da&skip_rows=0&limit_rows=0&chunk_number=1"),
    ]);
    const v1 = JSON.parse(raw1);
    const v2 = JSON.parse(raw2);
    const combinedArray = v1.concat(v2);

    if (combinedArray.length === 0) {
      console.log("No hay datos para procesar.");
      return;
    }

    // console.log(`Datos obtenidos: ${combinedArray} registros`);

    // Borra productos antiguos de Carmotion
    // await client.query("DELETE FROM productos WHERE proveedorp = $1", [
    //   "Carmotion",
    // ]);

    // Inserta cada registro
    let i = 0;
    for (const obj of combinedArray) {
      i++;
      const vermarca = obj.value.b.split(" ");
      const valorSinSimbolo = obj.value.d.replace(/[$,]/g, "");
      const id = "cm-" + obj.value.___id___;
      const extra = separarUltimosDosDigitosRegex(obj.value.a, id);
      const precioMX =
        parseFloat(valorSinSimbolo) > 10 ? parseFloat(valorSinSimbolo) : 0;

      // await client.query(
      //   `INSERT INTO productos
      //     (claveproveedor, productos, cantidad, proveedorp, categoria, idunicoinvetariado, precio, creado, rin, estadociudad, size)
      //    VALUES
      //     ($1, $2, $3, $4, $5, $6, $7, NOW(), $8, $9, $10)`,
      //   [
      //     id,
      //     obj.value.b,
      //     obj.value.c || 0,
      //     "Carmotion",
      //     vermarca[1].toLowerCase(),
      //     require("crypto").randomUUID(),
      //     precioMX,
      //     extra[1], // rin
      //     "1", // estadociudad fijo
      //     extra[0], // size
      //   ]
      // );
      console.debug(
        id,
        obj.value.b,
        obj.value.c || 0,
        "Carmotion",
        vermarca[1].toLowerCase(),
        require("crypto").randomUUID(),
        precioMX,
        extra[1], // rin
        "1", // estadociudad fijo
        extra[0] // size
      );
      // pequeños delays si los necesitas
      await new Promise((res) => setTimeout(res, 1));
    }
    console.log(i);

    console.log("ok");
  } catch (err) {
    console.error("Error:", err);
  } finally {
    // if (client) client.release();
  }
})();
