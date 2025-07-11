// Node 18+ provides a global fetch API. This script relies on it so no
// additional modules are required.

async function leerHtml(url) {
  const res = await fetch(url, {
    headers: {
      'User-Agent': 'Mozilla/5.0 (Windows NT 6.1; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2'
    },
    redirect: 'follow',
    timeout: 4000
  });
  return await res.text();
}

function separarUltimosDosDigitosRegex(numero, id) {
  const clean = numero.replace(/\s+/g, '');
  const regex = /^(.+?)[rR]?(\d{2})/;
  const matches = clean.match(regex);
  if (matches && matches.length === 3) {
    return [matches[1], matches[2], id];
  }
  throw new Error('Número inválido: ' + numero);
}

(async () => {
  try {
    const urlBase = 'https://distribuidores.carmotion.com.mx/wp-admin/admin-ajax.php?action=wp_ajax_ninja_tables_public_action&table_id=177&target_action=get-all-data&default_sorting=old_first&ninja_table_public_nonce=';
    const [raw1, raw2] = await Promise.all([
      leerHtml(urlBase + '7eaf5b9566&chunk_number=0%27'),
      leerHtml(urlBase + 'a7195de6da&skip_rows=0&limit_rows=0&chunk_number=1')
    ]);
    const v1 = JSON.parse(raw1);
    const v2 = JSON.parse(raw2);
    const combinedArray = v1.concat(v2);
    if (combinedArray.length === 0) {
      console.log('No hay datos para procesar.');
      return;
    }

    let i = 0;
    for (const obj of combinedArray) {
      i++;
      const vermarca = obj.value.b.split(' ');
      const valorSinSimbolo = obj.value.d.replace(/[$,]/g, '');
      const id = 'cm-' + obj.value.___id___;
      const extra = separarUltimosDosDigitosRegex(obj.value.a, id);
      const precioMX = parseFloat(valorSinSimbolo) > 10 ? parseFloat(valorSinSimbolo) : 0;
      console.log(id, obj.value.b, obj.value.c || 0, 'Carmotion', vermarca[1].toLowerCase(), precioMX, extra[1], extra[0]);
    }
    console.log('Total registros:', i);
  } catch (err) {
    if (err.code === 'ENETUNREACH' || err.cause?.code === 'ENETUNREACH') {
      console.error('Network unreachable. Cannot fetch remote data.');
    } else {
      console.error('Error:', err);
    }
  }
})();
