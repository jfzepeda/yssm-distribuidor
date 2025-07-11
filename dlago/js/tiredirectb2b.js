const fetch = require('node-fetch');
const { pool } = require('./DB');

async function tiredirect() {
  const res = await fetch('https://tiredirectb2b.com.mx/wbsTDApp/General/datas?UserID=2373&Password=AJU15019830GJ%7');
  const objs = await res.json();
  const rows = objs.objects.ResponseRow || [];
  await pool.query('DELETE FROM productos WHERE proveedorp = $1', ['Tire Direct']);
  for (const obj of rows) {
    const id = obj.SKU;
    const precio = parseFloat(obj.FS) * parseFloat(obj.TC);
    await pool.query(
      `INSERT INTO productos
        (claveproveedor, productos, cantidad, proveedorp, categoria, idunicoinvetariado, precio, creado, rin, estadociudad)
       VALUES ($1,$2,$3,$4,$5,$6,$7,NOW(),$8,$9)
       ON CONFLICT (claveproveedor) DO UPDATE SET cantidad=EXCLUDED.cantidad, precio=EXCLUDED.precio, estadociudad='1'`,
      [id, `${obj.Marca} ${obj.Modelo} ${obj.Descripcion}`, obj.Existencia, 'Tire Direct', obj.Marca.toLowerCase(), id, Math.round(precio), obj.Rin, '1']
    );
  }
  console.log('ok');
}

module.exports = { tiredirect };
