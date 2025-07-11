const fetch = require('node-fetch');
const { pool } = require('./DB');

async function curlprecio(cvl) {
  const res = await fetch(`http://delago1.dyndns.org/INTERNA2/PreciosCteExterno.asp?clv=${cvl}&usr=YANTISSIMO`, {
    headers: { 'User-Agent': 'Mozilla/5.0' }
  });
  const html = await res.text();
  const tableMatch = html.match(/<table[^>]*>([\s\S]*?)<\/table>/i);
  if (!tableMatch) return;
  const rows = Array.from(tableMatch[1].matchAll(/<tr[^>]*>(.*?)<\/tr>/gis)).map(r => r[1]);
  const data = rows.map(row => Array.from(row.matchAll(/<t[dh][^>]*>(.*?)<\/t[dh]>/gis)).map(c => c[1].replace(/<[^>]*>/g,'').trim()));
  for (const row of data) {
    if (row.length < 2) continue;
    const precio = parseFloat(row[1].replace(/[^0-9.]/g,'')) || 0;
    await pool.query('UPDATE productos SET precio=$1 WHERE claveproveedor=$2', [precio, cvl]);
  }
  console.log('ok');
}

module.exports = { curlprecio };
