const { execSync } = require("child_process");
const fs = require("fs");

const USER = "compras@yantissimo.com";
const PASS = "llanta33";

async function scrape(buscar = "215/55") {
  const cookieFile = "/tmp/hacsacookies_scraper.txt";

  console.log("1) Solicitando página de login con curl...");
  const loginPage = execSync(
    `curl -s -c ${cookieFile} https://ventas.hacsallantas.mx`
  );
  const tokenMatch = loginPage
    .toString()
    .match(/name="_token" value="([^"]+)"/);
  if (!tokenMatch) throw new Error("No se encontró el token");
  const token = tokenMatch[1];
  console.log("Token obtenido:", token);

  console.log("2) Autenticando con curl...");
  const loginCmd = `curl -s -b ${cookieFile} -c ${cookieFile} -d "email=${USER}&password=${PASS}&_token=${token}" -H "Content-Type: application/x-www-form-urlencoded" -H "Origin: https://ventas.hacsallantas.mx" -H "Referer: https://ventas.hacsallantas.mx/auth/signin" https://ventas.hacsallantas.mx/auth/signin`;
  const loginRes = execSync(loginCmd);
  console.log("Respuesta de login:", loginRes.toString());

  const cookieHeader = fs
    .readFileSync(cookieFile, "utf8")
    .split("\n")
    .filter((line) => line && !line.startsWith("#"))
    .map((line) => line.split("\t"))
    .map((parts) => parts[5] + "=" + parts[6])
    .join("; ");
  console.log("Cookies actuales:", cookieHeader);

  console.log("3) Consultando API con curl...");
  const url = `https://ventas.hacsallantas.mx/search-articulos?q=${encodeURIComponent(
    buscar
  )}&status=1&catalogo%5B%5D=AUTO+Y+CAMIONETA`;
  const data = execSync(`curl -s -b ${cookieFile} "${url}"`);
  console.log("Respuesta de búsqueda (primeros 200 caracteres):");
  console.log(data.toString().slice(0, 200));
}

if (require.main === module) {
  const arg = process.argv[2] || "215/55";
  scrape(arg).catch((err) => {
    console.error("Error", err);
  });
}
