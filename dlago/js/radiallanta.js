const fetch = require('node-fetch');
const { pool } = require('./DB');

async function radiallanta() {
  const username = 'colima';
  const password = 'colima#1971';
  const cookies = [];

  const loginRes = await fetch('https://m.radialllantas.com/login', {
    method: 'POST',
    headers: {
      'User-Agent': 'Mozilla/5.0',
      'Content-Type': 'application/x-www-form-urlencoded',
      'X-Requested-With': 'XMLHttpRequest',
      'Origin': 'https://m.radialllantas.com',
      'Referer': 'https://m.radialllantas.com/'
    },
    body: new URLSearchParams({ user: username, password })
  });
  cookies.push(...(loginRes.headers.raw()['set-cookie'] || []));

  const searchRes = await fetch('https://m.radialllantas.com/buscar', {
    method: 'POST',
    headers: {
      'User-Agent': 'Mozilla/5.0',
      'Content-Type': 'application/x-www-form-urlencoded',
      'Cookie': cookies.map(c => c.split(';')[0]).join('; ')
    },
    body: 'marca=&linea=&rin=&serie=&ancho=&rango=&codigo=&buscar=1'
  });
  const html = await searchRes.text();

  const rows = Array.from(html.matchAll(/<tr[^>]*>(.*?)<\/tr>/gis)).map(r => r[1]);
  const data = rows.map(row => Array.from(row.matchAll(/<t[dh][^>]*>(.*?)<\/t[dh]>/gis)).map(c => c[1].replace(/<[^>]*>/g, '').trim()));
  data.shift();

  for (const row of data) {
    const marca = row[0].split(' ').pop();
    const precio = parseFloat(row[5].replace(/[$,]/g, '')) || 0;
    const id = `rd-${row[1]}`;
    await pool.query(
      `INSERT INTO productos
        (claveproveedor, productos, cantidad, proveedorp, categoria, idunicoinvetariado, precio, creado, rin, estadociudad, size)
       VALUES ($1,$2,$3,$4,$5,$6,$7,NOW(),$8,$9,$10)
       ON CONFLICT (claveproveedor) DO UPDATE SET cantidad=EXCLUDED.cantidad, precio=EXCLUDED.precio, estadociudad='1'`,
      [id, row[2].replace(/[^a-zA-Z0-9\/()\s]/g,''), row[4], 'Radial_llanta', marca.toLowerCase(), id, precio, row[8], '1', `${row[6]}${row[7]}`]
    );
  }

  console.log('ok');
}

module.exports = { radiallanta };
