<?php
include_once('../includes/config.php');

$tipoc = "Camion";

function extstr($content, $start, $end) {
    if ($content && $start && $end) {
        $r = explode($start, $content);
        if (isset($r[1])) {
            $r = explode($end, $r[1]);
            return $r[0];
        }
        return '';
    }
}

// Borrado de productos si se pasa ?buscar=borrar
if (!empty($_GET["buscar"]) && $_GET["buscar"] == "borrar") {
    $ac = $GLOBALS['pdo']->prepare('DELETE FROM productos WHERE proveedorp = ?');
    $ac->execute(array("hacsallantas"));
    echo "borrar";
} else {
    $username = trim("compras@yantissimo.com");
    $password = trim("llanta33");

    $cookieFile = getcwd() . "/cookieshacsallantas.txt";
    $cabeza = [
        'Host: ventas.hacsallantas.mx',
        'Origin: https://ventas.hacsallantas.mx',
        'Referer: https://ventas.hacsallantas.mx/auth/signin',
    ];

    $buscar = urlencode($_GET["buscar"]);

    if (!empty($buscar)) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://ventas.hacsallantas.mx');
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64)...');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $answer = curl_exec($ch);

        if (curl_error($ch)) {
            echo curl_error($ch);
        }

        $token = extstr($answer, 'type="hidden" name="_token" value="', '"');
        echo $token . "-token";

        if ($token != "") {
            curl_setopt($ch, CURLOPT_URL, 'https://ventas.hacsallantas.mx/auth/signin');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'email' => $username,
                'password' => $password,
                '_token' => $token
            ]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $cabeza);

            $answer0 = curl_exec($ch);

            if (curl_error($ch)) {
                echo curl_error($ch);
            }

            $token = extstr($answer0, '<meta name="csrf-token" content="', '"');
        }

        curl_setopt($ch, CURLOPT_URL, 'https://ventas.hacsallantas.mx/search-articulos?q=' . $buscar . '&status=1&catalogo%5B%5D=AUTO+Y+CAMIONETA');

        $answer1 = curl_exec($ch);

        if (curl_error($ch)) {
            echo curl_error($ch);
        }

        $v = json_decode($answer1, true);

        if (!empty($v)) {
            try {
                foreach ($v["data"] as $k => $obj) {
                    $total = 0;
                    foreach ($obj["existencias"] as $key => $exist) {
                        $total += floatval($exist["existencia"]);
                    }

                    $id = "hacsallantas-" . $obj['id'];
                    $pattern = '/\b\d{3}\/\d{2}[A-Z]\d{2}\b/';
                    preg_match($pattern, $obj["name"], $matches);

                    if (!empty($matches[0])) {
                        $rin = explode("R", $matches[0]);
                        $titc = explode(" ", $obj["name"]);

                        $stmt = $GLOBALS['pdo']->prepare('SELECT claveproveedor,cantidad FROM productos WHERE claveproveedor=?');
                        $stmt->execute(array($id));
                        $pros = $stmt->fetch(PDO::FETCH_ASSOC);

                        $preciomx = 0;
                        if ($obj['price_iva'] > 0) {
                            $preciomx = (float)$obj['price_iva'];
                        }

                        if (!empty($pros["claveproveedor"])) {
                            $ac = $GLOBALS['pdo']->prepare('UPDATE productos SET cantidad=?,precio=?,estadociudad=? WHERE claveproveedor=?');
                            $ac->execute(array($total, $preciomx, "1", $id));
                        } else {
                            $insertar = $GLOBALS['pdo']->prepare('INSERT INTO productos (claveproveedor,productos,cantidad,proveedorp,categoria,idunicoinvetariado,precio,creado,rin,estadociudad,size) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
                            $insertar->execute([
                                $id,
                                $obj["name"],
                                $total ?? 0,
                                "hacsallantas",
                                strtolower($titc[1]),
                                uniqid($titc[1] . "-"),
                                "" . $preciomx . "",
                                date("Y-m-d H:i:s"),
                                $rin[1],
                                "1",
                                $rin[0]
                            ]);
                            $GLOBALS['pdo']->lastInsertId();
                        }
                    }

                    usleep(1);
                }
                echo "</br>ok";
            } catch (PDOException $e) {
                throw new RuntimeException("[" . $e->getCode() . "] : " . $e->getMessage());
            }
        }
    }
}
?>