const fetch = require('node-fetch');
const { pool } = require('./DB');

async function hacsallantas(buscar) {
  const username = 'compras@yantissimo.com';
  const password = 'llanta33';
  const cookieJar = [];

  const first = await fetch('https://ventas.hacsallantas.mx', {
    headers: {
      'User-Agent': 'Mozilla/5.0',
    },
  });
  cookieJar.push(...(first.headers.raw()['set-cookie'] || []));
  const token = (await first.text()).match(/name="_token" value="([^"]+)"/);
  if (!token) throw new Error('No token');

  const loginRes = await fetch('https://ventas.hacsallantas.mx/auth/signin', {
    method: 'POST',
    headers: {
      'User-Agent': 'Mozilla/5.0',
      'Content-Type': 'application/x-www-form-urlencoded',
      'Cookie': cookieJar.map(c => c.split(';')[0]).join('; '),
      'Origin': 'https://ventas.hacsallantas.mx',
      'Referer': 'https://ventas.hacsallantas.mx/auth/signin',
    },
    body: new URLSearchParams({ email: username, password, _token: token[1] }),
    redirect: 'manual'
  });
  cookieJar.push(...(loginRes.headers.raw()['set-cookie'] || []));

  const dataRes = await fetch(`https://ventas.hacsallantas.mx/search-articulos?q=${encodeURIComponent(buscar)}&status=1&catalogo%5B%5D=AUTO+Y+CAMIONETA`, {
    headers: {
      'User-Agent': 'Mozilla/5.0',
      'Cookie': cookieJar.map(c => c.split(';')[0]).join('; ')
    }
  });
  const json = await dataRes.json();
  for (const obj of json.data || []) {
    const id = 'hacsallantas-' + obj.id;
    let total = 0;
    for (const ex of obj.existencias) total += parseFloat(ex.existencia) || 0;
    const match = obj.name.match(/(\d{3}\/\d{2}[A-Z]\d{2})/);
    if (!match) continue;
    const rin = match[1].split('R')[1];
    const precio = parseFloat(obj.price_iva) || 0;
    await pool.query(
      `INSERT INTO productos
        (claveproveedor, productos, cantidad, proveedorp, categoria, idunicoinvetariado, precio, creado, rin, estadociudad, size)
       VALUES ($1,$2,$3,$4,$5,$6,$7,NOW(),$8,$9,$10)
       ON CONFLICT (claveproveedor) DO UPDATE SET cantidad=EXCLUDED.cantidad, precio=EXCLUDED.precio, estadociudad='1'`,
      [id, obj.name, total, 'hacsallantas', obj.name.split(' ')[1].toLowerCase(), id, precio, rin, '1', match[1]]
    );
  }

  console.log('ok');
}

module.exports = { hacsallantas };
