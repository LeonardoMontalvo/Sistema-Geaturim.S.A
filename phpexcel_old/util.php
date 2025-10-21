<?php

function totalIngresos($idcontrato) {
    $totali = 0;
    $sql = "SELECT co.id_contrato, sum(co.valor)as total
  FROM contrato_operacion co
  inner join contrato_alquiler_vehiculo_trasporte cat
  on co.id_contrato=cat.id_contrato
  where co.estado='Activo'
  and co.id_contrato=$idcontrato
  and (co.accion='a'or co.accion='i')
  group by co.id_contrato;";
    $consulta = pg_query($sql);
    if (pg_num_rows($consulta) > 0) {
        return pg_fetch_all($consulta)[0]['total'];
    }
    return $totali;
}

function totalAbonos($idcontrato) {
    $totala = 0;
    $sql = "SELECT co.id_contrato, sum(co.valor)as total
  FROM contrato_operacion co
  inner join contrato_alquiler_vehiculo_trasporte cat
  on co.id_contrato=cat.id_contrato
  where co.estado='Activo'
  and co.id_contrato=$idcontrato
  and co.accion='a'
  group by co.id_contrato;";
    $consulta = pg_query($sql);
    if (pg_num_rows($consulta) > 0) {
        return pg_fetch_all($consulta)[0]['total'];
    }
    return $totala;
}

function totalEgresos($idcontrato) {
    $totale = 0;
    $sql = "SELECT co.id_contrato, sum(co.valor)as total
  FROM contrato_operacion co
  inner join contrato_alquiler_vehiculo_trasporte cat
  on co.id_contrato=cat.id_contrato
  where co.estado='Activo'
  and co.id_contrato=$idcontrato
  and co.accion='e'
  group by co.id_contrato;";
    $consulta = pg_query($sql);
    if (pg_num_rows($consulta) > 0) {
        return pg_fetch_all($consulta)[0]['total'];
    }
    return $totale;
}

function vehiculosContrato($idcontrato) {
    $vehiculos = '';
    $sql = "SELECT placa
    FROM contrato_vehiculo cv
    inner join contrato_alquiler_vehiculo_vehiculo cav
    on cv.id_vehiculo=cav.id_vehiculo
    inner join contrato_alquiler_vehiculo_trasporte cat
    on cat.id_contrato=cav.id_contrato
    where cav.id_contrato=$idcontrato
    ";
    $consulta = pg_query($sql);
    if (pg_num_rows($consulta) > 0) {
        $vehiculosr = pg_fetch_all($consulta);
        foreach ($vehiculosr as $key => $v) {
            $vehiculos .= $v['placa'] . ", ";
        }
    }
    $vehiculos = substr($vehiculos, 0, -2);
    return $vehiculos;
}

function utilidad($idcontrato){
    return totalIngresos($idcontrato)-totalEgresos($idcontrato);
}

