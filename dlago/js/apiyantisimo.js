const fetch = require('node-fetch');
const { pool } = require('./DB');

async function apiyantisimo() {
  const res = await fetch('https://api.yantissimo.com/inventory');
  const data = await res.json();
  for (const obj of data) {
    const id = `yantissimo-${obj.id}`;
    const precio = parseFloat(obj.price) || 0;
    await pool.query(
      `INSERT INTO productos
        (claveproveedor, productos, cantidad, proveedorp, categoria, idunicoinvetariado, precio, creado, rin, estadociudad, size)
       VALUES ($1,$2,$3,$4,$5,$6,$7,NOW(),$8,$9,$10)
       ON CONFLICT (claveproveedor) DO UPDATE SET cantidad=EXCLUDED.cantidad, precio=EXCLUDED.precio, estadociudad='1'`,
      [id, obj.name, obj.quantity, 'Yantissimo', obj.brand.toLowerCase(), id, precio, obj.rim, '1', obj.size]
    );
  }
  console.log('ok');
}

module.exports = { apiyantisimo };
