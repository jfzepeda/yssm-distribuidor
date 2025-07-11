<?php
include_once '../includes/config.php';

/**
 * Extracts the substring between two delimiters.
 *
 * @param string $content The full string to search.
 * @param string $start   The starting delimiter.
 * @param string $end     The ending delimiter.
 * @return string         The substring between $start and $end, or an empty string.
 */
function extstr($content, $start, $end)
{
    if ($content && $start && $end) {
        $parts = explode($start, $content);
        if (isset($parts[1])) {
            $segments = explode($end, $parts[1]);
            return $segments[0];
        }
    }

    return '';
}

/**
 * Fetches HTML content via cURL.
 *
 * @param string $url The URL to fetch.
 * @return string     The response body.
 */
function LeerHtml($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) ' .
        'Gecko/20090729 Firefox/3.5.2 GTB5');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 4);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

// Fetch product data from Tire Direct API
$datos = LeerHtml(
    'https://tiredirectb2b.com.mx/wbsTDApp/General/datas?UserID=2373&Password=AJU15019830GJ%7'
);
$objs  = json_decode($datos, true);

if (!empty($objs)) {
    print_r($objs['objects']['ResponseRow']);

    // Delete existing entries for this provider
    $deleteStmt = $GLOBALS['pdo']->prepare(
        'DELETE FROM productos WHERE proveedorp = ?'
    );
    $deleteStmt->execute(['Tire Direct']);

    try {
        foreach ($objs['objects']['ResponseRow'] as $obj) {
            // Check if product already exists
            $checkStmt = $GLOBALS['pdo']->prepare(
                'SELECT claveproveedor, cantidad FROM productos WHERE claveproveedor = ?'
            );
            $checkStmt->execute([$obj['SKU']]);
            $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

            // Calculate price in MXN
            $preciomx = (float) $obj['FS'] * (float) $obj['TC'];

            // Insert new product
            $insertStmt = $GLOBALS['pdo']->prepare(
                'INSERT INTO productos
                 (claveproveedor, productos, cantidad, proveedorp, categoria,
                  idunicoinvetariado, precio, creado, rin, estadociudad)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );

            $insertStmt->execute([
                $obj['SKU'],
                $obj['Marca'] . ' ' . $obj['Modelo'] . ' ' . $obj['Descripcion'],
                $obj['Existencia'],
                'Tire Direct',
                strtolower($obj['Marca']),
                uniqid($obj['Marca'] . '-'),
                round($preciomx),
                date('Y-m-d H:i:s'),
                $obj['Rin'],
                '1'
            ]);

            $GLOBALS['pdo']->lastInsertId();
            usleep(1);
        }
    } catch (PDOException $e) {
        throw new RuntimeException('[' . $e->getCode() . '] : ' . $e->getMessage());
    }
}

/*
 // Previous D'LAGO import logic (commented out)
 try {
     $stmt = $GLOBALS['pdo']->prepare(
         'SELECT claveproveedor, cantidad FROM productos WHERE claveproveedor = ?'
     );
     $stmt->execute([$ver[0]]);
     $pros = $stmt->fetch(PDO::FETCH_ASSOC);

     $updateStmt = $GLOBALS['pdo']->prepare(
         'UPDATE productos SET
             rincondlago = ?,
             calvillodlago = ?,
             cantidad = ?,
             cedisjaliscodlago = ?
          WHERE claveproveedor = ? AND cantidad <> ?'
     );
     $updateStmt->execute([
         $ver[2],
         $ver[3],
         $ver[4],
         $ver[6],
         $ver[0],
         $ver[4]
     ]);

     $insertDStmt = $GLOBALS['pdo']->prepare(
         'INSERT INTO productos
          (claveproveedor, productos, rincondlago, calvillodlago, cantidad,
           cedisjaliscodlago, proveedorp, categoria, idunicoinvetariado, creado)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
     );
     $insertDStmt->execute([
         $ver[0],
         $ver[1],
         $ver[2],
         $ver[3],
         $ver[4],
         $ver[6],
         "D'LAGO",
         strtolower($_GET['Marca']),
         uniqid($_GET['Marca'] . '-'),
         date('Y-m-d H:i:s')
     ]);

     $GLOBALS['pdo']->lastInsertId();
 } catch (PDOException $e) {
     throw new RuntimeException('[' . $e->getCode() . '] : ' . $e->getMessage());
 }
*/
