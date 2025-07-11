const fetch = require('node-fetch');
const { pool } = require('./DB');

async function apiprueba(pagina) {
  const url = `http://yantissimo.ddns.net:9091/exsim/servicios/metodo/ARTICULOS/75Cv04CyLxV6x5QP8j9CQnCp9d9M3d/1000/${pagina}/0/0/`;
  const res = await fetch(url, { headers: { 'User-Agent': 'Mozilla/5.0' } });
  const text = await res.text();
  const json = JSON.parse(text.replace(/\\\"/g, ''));
  for (const ver1 of json) {
    if (!ver1.precios || !ver1.precios[0]) continue;
    const line = ver1.linea;
    if (line !== 'AUTO Y CAMIONETA') continue;
    let total = 0;
    total += parseFloat(ver1.existencia[10].existencia) || 0;
    total += parseFloat(ver1.existencia[8].existencia) || 0;
    total += parseFloat(ver1.existencia[0].existencia) || 0;
    total += parseFloat(ver1.existencia[1].existencia) || 0;
    total += parseFloat(ver1.existencia[7].existencia) || 0;
    total += parseFloat(ver1.existencia[6].existencia) || 0;
    total += parseFloat(ver1.existencia[19].existencia) || 0;
    const medidas = ver1.nombre.split(' ');
    const rin = medidas[1] ? medidas[1].replace('R','') : '';
    const categoria = medidas[2] || '';
    await pool.query(
      `INSERT INTO productos
        (claveproveedor, productos, cantidad, proveedorp, categoria, idunicoinvetariado, precio, creado, rin, estadociudad, size)
       VALUES ($1,$2,$3,$4,$5,$6,$7,NOW(),$8,$9,$10)
       ON CONFLICT (claveproveedor) DO UPDATE SET cantidad=EXCLUDED.cantidad, precio=EXCLUDED.precio, estadociudad='1'`,
      [ver1.clave, ver1.nombre, total, 'Yantissimo', categoria.toLowerCase(), ver1.id, parseFloat(ver1.precios[0].precio) || 0, rin, '1', medidas[0]]
    );
  }
}

module.exports = { apiprueba };
