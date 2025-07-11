const fetch = require('node-fetch');
const { pool } = require('./DB');

async function curl(marca) {
  if (marca === 'borrar') {
    await pool.query('DELETE FROM productos WHERE proveedorp = $1', ["D'LAGO"]);
    console.log('borrar');
    return;
  }
  const username = 'YANTISSIMO';
  const password = '2057013';
  const cookies = [];

  const loginRes = await fetch('http://delago1.dyndns.org/INTERNA2/SisInv.asp', {
    method: 'POST',
    headers: {
      'User-Agent': 'Mozilla/5.0',
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `usr=${username}&pssw=${password}`,
    redirect: 'manual'
  });
  cookies.push(...(loginRes.headers.raw()['set-cookie'] || []));

  const invRes = await fetch('http://delago1.dyndns.org/INTERNA2/CteExtJ.asp', {
    method: 'POST',
    headers: {
      'User-Agent': 'Mozilla/5.0',
      'Content-Type': 'application/x-www-form-urlencoded',
      'Cookie': cookies.map(c => c.split(';')[0]).join('; ')
    },
    body: `usr=${username}&pssw=${password}&Marca=${marca}&Gama=&Busca=&Ancho=&Alto=-1&Rin=`
  });
  const html = await invRes.text();
  const match = html.match(/e="1">\+<\/font><\/div><\/td> -->([\s\S]*?)<\/table><FONT/);
  if (!match) return;
  const table = '<table>' + match[1];
  const rows = Array.from(table.matchAll(/<tr[^>]*>(.*?)<\/tr>/gis)).map(r => r[1]);
  const data = rows.map(row => Array.from(row.matchAll(/<t[dh][^>]*>(.*?)<\/t[dh]>/gis)).map(c => c[1].replace(/<[^>]*>/g, '').trim()));
  for (const row of data) {
    if (!row[0]) continue;
    const id = row[0];
    const rin = (row[1].match(/R(\d+)/) || [,''])[1];
    await pool.query(
      `INSERT INTO productos (claveproveedor, productos, rincondlago, calvillodlago, cantidad, cedisjaliscodlago, proveedorp, categoria, idunicoinvetariado, creado, estadociudad)
       VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,NOW(),'1')
       ON CONFLICT (claveproveedor) DO UPDATE SET cantidad=EXCLUDED.cantidad, rincondlago=EXCLUDED.rincondlago, calvillodlago=EXCLUDED.calvillodlago, cedisjaliscodlago=EXCLUDED.cedisjaliscodlago`,
      [row[0], row[1], row[2], row[3], row[4], row[6], "D'LAGO", marca.toLowerCase(), id]
    );
  }
  console.log('ok');
}

module.exports = { curl };
