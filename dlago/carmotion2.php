<?php

if (! function_exists('ceiling')) {
    /**
     * Round $number up to the nearest multiple of $significance.
     */
    function ceiling($number, $significance = 1)
    {
        if (! is_numeric($number) || ! is_numeric($significance)) {
            return false;
        }
        return ceil($number / $significance) * $significance;
    }
}

$preciobd             = 1961.63;
$iva                  = 1.16;
$calculo              = $preciobd * $iva;
$precioAcondicion     = round($calculo, 2);
$porcentaje           = 0;
$precioCondicionFinal = $precioAcondicion;

if ($precioAcondicion > 0 && $precioAcondicion < 500) {
    $porcentaje           = 1.16;
    $precioCondicionFinal *= $porcentaje;

} elseif ($precioAcondicion >= 500 && $precioAcondicion < 700) {
    $porcentaje           = 1.26;
    $precioCondicionFinal *= $porcentaje;

} elseif ($precioAcondicion >= 700 && $precioAcondicion < 800) {
    $porcentaje           = 1.27;
    $precioCondicionFinal *= $porcentaje;

} elseif ($precioAcondicion >= 800 && $precioAcondicion < 1200) {
    $porcentaje           = 1.28;
    $precioCondicionFinal *= $porcentaje;

} elseif ($precioAcondicion >= 1200 && $precioAcondicion < 1700) {
    $porcentaje           = 1.28;
    $precioCondicionFinal *= $porcentaje;

} elseif ($precioAcondicion >= 1700 && $precioAcondicion < 1800) {
    $porcentaje           = 1.28;
    $precioCondicionFinal *= $porcentaje;

} elseif ($precioAcondicion >= 1800 && $precioAcondicion < 2000) {
    $porcentaje           = 1.28;
    $precioCondicionFinal *= $porcentaje;

} elseif ($precioAcondicion >= 2000 && $precioAcondicion < 2200) {
    $porcentaje           = 1.28;
    $precioCondicionFinal *= $porcentaje;

} elseif ($precioAcondicion >= 2200 && $precioAcondicion < 2500) {
    $porcentaje           = 1.27;
    $precioCondicionFinal *= $porcentaje;

} elseif ($precioAcondicion >= 2500 && $precioAcondicion < 2900) {
    $porcentaje           = 1.25;
    $precioCondicionFinal *= $porcentaje;

} elseif ($precioAcondicion >= 2900 && $precioAcondicion < 3600) {
    $porcentaje           = 1.23;
    $precioCondicionFinal *= $porcentaje;

} elseif ($precioAcondicion >= 3600 && $precioAcondicion < 4000) {
    $porcentaje           = 1.22;
    $precioCondicionFinal *= $porcentaje;

} elseif ($precioAcondicion >= 4000 && $precioAcondicion < 5000) {
    $porcentaje           = 1.21;
    $precioCondicionFinal *= $porcentaje;

} elseif ($precioAcondicion >= 5000 && $precioAcondicion < 6000) {
    $porcentaje           = 1.20;
    $precioCondicionFinal *= $porcentaje;

} else {
    $porcentaje           = 1.17;
    $precioCondicionFinal *= $porcentaje;
}

echo ceiling($precioCondicionFinal, 10);