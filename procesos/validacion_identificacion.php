<?php

function validarIdentificacionEc($identificacion)
{
    if (!validarCodigoProvincia($identificacion)) {
        return false;
    }
    if (strlen($identificacion) == 10) {
        return validarCedula($identificacion);
    } else if (strlen($identificacion) == 13) {
        return validarRuc($identificacion);
    }
    return false;
}
function validarCedula($cedula)
{
    if (strlen($cedula) != 10) {
        return false;
    }
    $sum = 0;
    $sumi = 0;
    for ($i = 0; $i < strlen($cedula) - 2; $i++) {
        if ($i % 2 == 0) {
            $sum += substr($cedula, $i + 1, 1);
        }
    }
    $j = 0;
    while ($j < strlen($cedula) - 1) {
        $b = substr($cedula, $j, 1);
        $b = $b * 2;
        if ($b > 9) {
            $b = $b - 9;
        }
        $sumi += $b;
        $j = $j + 2;
    }
    $t = $sum + $sumi;
    $residuo = $t % 10;
    $res = $residuo == 0 ? 0 : 10 - $residuo;
    $aux = substr($cedula, 9, 9);
    return $res == $aux;
}
function validarRuc($ruc)
{
    if (strlen($ruc) != 13) {
        return false;
    }
    $postf = substr($ruc, 10, 3);
    if ($postf != '001') {
        return false;
    }

    $ced = substr($ruc, 0, 10);
    $cedval = validarCedula($ced);
    if ($cedval) {
        return true;
    }
    $prvpb = substr($ruc, 2, 1);
    if ($prvpb == 9) {
        $secval = substr($ruc, 0, 9);
        $resmod = modulo11($secval);
        $dv = substr($ruc, 9, 1);
        return $dv == $resmod;
    } else if ($prvpb == 6) {
        $secval = substr($ruc, 0, 8);
        $resmod = modulo11($secval);
        $dv = substr($ruc, 8, 1);
        return $dv == $resmod;
    }
    return false;
}
function modulo11($ruc)
{
    $secuencia = "234567";

    $clave = $ruc;
    $isec = 0;
    $sum = 0;
    for ($i = strlen($clave) - 1; $i >= 0; $i--) {
        $coef = $clave[$i] * $secuencia[$isec];
        $sum += $coef;
        $isec++;
        if ($isec > 5) {
            $isec = 0;
        }
    }
    $mod = $sum % 11;
    $res = 11 - $mod;
    if ($res == 10) {
        $res = 1;
    } else {
        if ($res == 11) {
            $res = 0;
        }
    }
    return $res;
}
function validarCodigoProvincia($numeroid)
{
    $codpov = substr($numeroid, 0, 2);
    return $codpov > 0 && $codpov <= 24;
}

if (empty($_POST["identificacion"])) {
    echo json_encode(false);
} else {
    echo json_encode(validarIdentificacionEc($_POST["identificacion"]));
}
