const fetch = require('node-fetch');
const { pool } = require('./DB');

// Helper similar to PHP extstr
function extstr(content, start, end) {
  if (!content) return '';
  const idx = content.indexOf(start);
  if (idx === -1) return '';
  const idx2 = content.indexOf(end, idx + start.length);
  if (idx2 === -1) return '';
  return content.substring(idx + start.length, idx2);
}

async function goller(buscar) {
  const username = 'aldo.juarez@yantissimo.com';
  const password = 'AldoJ2024';

  // login
  const loginRes = await fetch('https://7615759.app.netsuite.com/app/login/secure/privatelogin.nl', {
    method: 'POST',
    headers: {
      'User-Agent': 'Mozilla/5.0',
      'Content-Type': 'application/x-www-form-urlencoded',
      'Origin': 'https://7615759.app.netsuite.com',
      'Referer': 'https://7615759.app.netsuite.com/app/login/secure/enterpriselogin.nl',
    },
    body: `c=7615759&email=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}&submitButton=`,
    redirect: 'manual'
  });

  const cookies = loginRes.headers.raw()['set-cookie'] || [];
  const cookieHeader = cookies.map(c => c.split(';')[0]).join('; ');

  const searchRes = await fetch('https://7615759.app.netsuite.com/app/site/hosting/scriptlet.nl?script=1297&deploy=1&compid=7615759&whence=', {
    method: 'POST',
    headers: {
      'User-Agent': 'Mozilla/5.0',
      'Content-Type': 'application/x-www-form-urlencoded',
      'Cookie': cookieHeader,
      'Origin': 'https://7615759.app.netsuite.com',
      'Referer': 'https://7615759.app.netsuite.com/app/login/secure/enterpriselogin.nl',
    },
    body: `submitter=Buscar&custpage_nscs_sf_item_sales_description=${encodeURIComponent(buscar)}`,
  });

  const html = await searchRes.text();

  const rows = Array.from(html.matchAll(/<tr[^>]*>(.*?)<\/tr>/gis)).map(r => r[1]);
  const data = rows.map(row => Array.from(row.matchAll(/<t[dh][^>]*>(.*?)<\/t[dh]>/gis)).map(c => c[1].replace(/<[^>]*>/g, '').trim()));

  // Skip header
  data.shift();

  for (const result of data) {
    if (!result[2]) continue;
    const titulo = result[2];
    const precio = parseFloat(result[4] || '0');
    const cantidades = result.slice(6, 18).map(v => parseFloat(v) || 0);
    const total = cantidades.reduce((a, b) => a + b, 0);
    const porciones = titulo.split(' ');
    const medidas = porciones[1] ? porciones[1].split('R') : ['',''];
    const id = 'goller-' + Math.random().toString(36).slice(2);

    await pool.query(
      `INSERT INTO productos
        (claveproveedor, productos, cantidad, proveedorp, categoria, idunicoinvetariado, precio, creado, rin, estadociudad, size)
       VALUES ($1,$2,$3,$4,$5,$6,$7,NOW(),$8,$9,$10)`,
      [
        id,
        titulo,
        total,
        'Goller',
        porciones[2] ? porciones[2].toLowerCase() : '',
        id,
        precio,
        medidas[1] || '',
        '1',
        medidas[0] || ''
      ]
    );
  }

  console.log('ok');
}

module.exports = { goller };
