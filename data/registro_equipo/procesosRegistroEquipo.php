<?php

include '../../procesos/base.php';
conectarse();
session_start();
$data = 1;
error_reporting(0);

if ($_POST['tiporegis'] == "g") {
    $conpunto = 1;
    $consultapunto = pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
    while ($row = pg_fetch_row($consultapunto)) {
        $conpunto = $row[0];
    }

    $conpuntoresult = 1;
    $consultapuntoresult = pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
    while ($row = pg_fetch_row($consultapuntoresult)) {
        $conpuntoresult = $row[0];
    }

    $cont = 0;
   
    $consulta = pg_query("select max(id_registro) from registro_equipo");
    while ($row = pg_fetch_row($consulta)) {
        $cont = $row[0];
    }
    $cont++;
    $extension = explode(".", $_FILES["archivo_ins_1"]["name"]);
    $extension = end($extension);
    $type = $_FILES["archivo_ins_1"]["type"];
    $tmp_name = $_FILES["archivo_ins_1"]["tmp_name"];
    $size = $_FILES["archivo_ins_1"]["size"];
    $nombre = basename($_FILES["archivo_ins_1"]["name"], "." . $extension);
//    echo 'si'.$nombre;
    if ($nombre == "") {
        
        $foto1 = "";
    } else {

        
        $foto1 = (strtotime(date("y-m-d h:m:s"))+1). '.' . $extension;
//        echo 'si1'.$extension;
        move_uploaded_file($_FILES["archivo_ins_1"]["tmp_name"], "fotos_registro_equipos/" . $foto1);
    }
    /////////////////////////////////////////
    $extension2 = explode(".", $_FILES["archivo_ins_2"]["name"]);
    $extension2 = end($extension2);
    $type = $_FILES["archivo_ins_2"]["type"];
    $tmp_name = $_FILES["archivo_ins_2"]["tmp_name"];
    $size = $_FILES["archivo_ins_2"]["size"];
    $nombre2 = basename($_FILES["archivo_ins_2"]["name"], "." . $extension2);
    if ($nombre2 == "") {
        $foto2 = "";
    } else {


        $foto2 = (strtotime(date("y-m-d h:m:s"))+2). '.' . $extension2;
           
        move_uploaded_file($_FILES["archivo_ins_2"]["tmp_name"], "fotos_registro_equipos/" . $foto2);
    }
    /////////////////////////////////////////////////
    $extensio3 = explode(".", $_FILES["archivo_ins_3"]["name"]);
    $extensio3 = end($extensio3);
    $type = $_FILES["archivo_ins_3"]["type"];
    $tmp_name = $_FILES["archivo_ins_3"]["tmp_name"];
    $size = $_FILES["archivo_ins_3"]["size"];
    $nombre3 = basename($_FILES["archivo_ins_3"]["name"], "." . $extensio3);
    if ($nombre3 == "") {
        $foto3 = "";
    } else {
        $foto3 = (strtotime(date("y-m-d h:m:s"))+3). '.' . $extensio3;
        move_uploaded_file($_FILES["archivo_ins_3"]["tmp_name"], "fotos_registro_equipos/" . $foto3);
    }
    //////////////////////////////////////////
    $extensio4 = explode(".", $_FILES["archivo_ins_4"]["name"]);
    $extensio4 = end($extensio4);
    $type = $_FILES["archivo_ins_4"]["type"];
    $tmp_name = $_FILES["archivo_ins_4"]["tmp_name"];
    $size = $_FILES["archivo_ins_4"]["size"];
    $nombre4 = basename($_FILES["archivo_ins_4"]["name"], "." . $extensio4);
    if ($nombre4 == "") {
        $foto4 = "";
    } else {
        $foto4 = (strtotime(date("y-m-d h:m:s"))+4). '.' . $extensio4;
        move_uploaded_file($_FILES["archivo_ins_4"]["tmp_name"], "fotos_registro_equipos/" . $foto4);
    }
    //////////////////////////////////////////////////
    $extension5 = explode(".", $_FILES["archivo_ins_5"]["name"]);
    $extension5 = end($extension5);
    $type = $_FILES["archivo_ins_5"]["type"];
    $tmp_name = $_FILES["archivo_ins_5"]["tmp_name"];
    $size = $_FILES["archivo_ins_5"]["size"];
    $nombre5 = basename($_FILES["archivo_ins_5"]["name"], "." . $extension5);
    if ($nombre5 == "") {
        $foto5 = "";
    } else {
       $foto5 = (strtotime(date("y-m-d h:m:s"))+5). '.' . $extension5;
        move_uploaded_file($_FILES["archivo_ins_5"]["tmp_name"], "fotos_registro_equipos/" . $foto5);
    }


    pg_query("insert into registro_equipo values(
   '$cont',
   '$_POST[colores]',
   '$_POST[marca]',
   '$_POST[txtClienteId]',
   '$_POST[txtSerie]',
   '$_POST[txtObservaciones]',
   '$_POST[txtAccesorios]',
   'Activo',
   '$_SESSION[id]',
   '$_POST[txtIngreso]',
   '$_POST[categoria]',
   '$_POST[txtModelo]',
   '$_POST[txtSalida]',
   '0',
   '',NULL,$conpuntoresult,'$foto1','$foto2','$foto3','$foto4','$foto5')");
    $data = 0;
} else {
    
    
    $extension = explode(".", $_FILES["archivo_ins_1"]["name"]);
    $extension = end($extension);
    $type = $_FILES["archivo_ins_1"]["type"];
    $tmp_name = $_FILES["archivo_ins_1"]["tmp_name"];
    $size = $_FILES["archivo_ins_1"]["size"];
    $nombre = basename($_FILES["archivo_ins_1"]["name"], "." . $extension);
//    echo 'si'.$nombre;
    if ($nombre == "") {
        
        $foto1 = "";
    } else {

          $foto1 = (strtotime(date("y-m-d h:m:s"))+1). '.' . $extension;
        move_uploaded_file($_FILES["archivo_ins_1"]["tmp_name"], "fotos_registro_equipos/" . $foto1);
    }
    /////////////////////////////////////////
    $extension2 = explode(".", $_FILES["archivo_ins_2"]["name"]);
    $extension2 = end($extension2);
    $type = $_FILES["archivo_ins_2"]["type"];
    $tmp_name = $_FILES["archivo_ins_2"]["tmp_name"];
    $size = $_FILES["archivo_ins_2"]["size"];
    $nombre2 = basename($_FILES["archivo_ins_2"]["name"], "." . $extension2);
    if ($nombre2 == "") {
        $foto2 = "";
    } else {


        $foto2 = (strtotime(date("y-m-d h:m:s"))+2). '.' . $extension2;
        move_uploaded_file($_FILES["archivo_ins_2"]["tmp_name"], "fotos_registro_equipos/" . $foto2);
    }
    /////////////////////////////////////////////////
    $extensio3 = explode(".", $_FILES["archivo_ins_3"]["name"]);
    $extensio3 = end($extensio3);
    $type = $_FILES["archivo_ins_3"]["type"];
    $tmp_name = $_FILES["archivo_ins_3"]["tmp_name"];
    $size = $_FILES["archivo_ins_3"]["size"];
    $nombre3 = basename($_FILES["archivo_ins_3"]["name"], "." . $extensio3);
    if ($nombre3 == "") {
        $foto3 = "";
    } else {
            $foto3 = (strtotime(date("y-m-d h:m:s"))+3). '.' . $extensio3;
        move_uploaded_file($_FILES["archivo_ins_3"]["tmp_name"], "fotos_registro_equipos/" . $foto3);
    }
    //////////////////////////////////////////
    $extensio4 = explode(".", $_FILES["archivo_ins_4"]["name"]);
    $extensio4 = end($extensio4);
    $type = $_FILES["archivo_ins_4"]["type"];
    $tmp_name = $_FILES["archivo_ins_4"]["tmp_name"];
    $size = $_FILES["archivo_ins_4"]["size"];
    $nombre4 = basename($_FILES["archivo_ins_4"]["name"], "." . $extensio4);
    if ($nombre4 == "") {
        $foto4 = "";
    } else {
       $foto4 = (strtotime(date("y-m-d h:m:s"))+4). '.' . $extensio4;
        move_uploaded_file($_FILES["archivo_ins_4"]["tmp_name"], "fotos_registro_equipos/" . $foto4);
    }
    //////////////////////////////////////////////////
    $extension5 = explode(".", $_FILES["archivo_ins_5"]["name"]);
    $extension5 = end($extension5);
    $type = $_FILES["archivo_ins_5"]["type"];
    $tmp_name = $_FILES["archivo_ins_5"]["tmp_name"];
    $size = $_FILES["archivo_ins_5"]["size"];
    $nombre5 = basename($_FILES["archivo_ins_5"]["name"], "." . $extension5);
    if ($nombre5 == "") {
        $foto5 = "";
    } else {
        $foto5 = (strtotime(date("y-m-d h:m:s"))+5). '.' . $extension5;
        move_uploaded_file($_FILES["archivo_ins_5"]["tmp_name"], "fotos_registro_equipos/" . $foto5);
    }
    
    if ($_POST['tiporegis'] == "m") {
        
        
         
 
       
       if($foto1!=""){
               pg_query("
   update 
   registro_equipo 
   set id_registro='$_POST[txtRegistro]',
   id_color='$_POST[colores]',
   id_marca='$_POST[marca]',
   id_cliente='$_POST[txtClienteId]',
   nro_serie='$_POST[txtSerie]',
   observaciones='$_POST[txtObservaciones]',
   detalles='$_POST[txtAccesorios]',
   id_usuario='$_SESSION[id]',
   fecha_ingreso='$_POST[txtIngreso]',
   id_tipo_equipo='$_POST[categoria]',
   modelo='$_POST[txtModelo]',
   fecha_salida='$_POST[txtSalida]',imagen1='$foto1'
   where id_registro='$_POST[txtRegistro]'
   ");
          
       }
        if($foto2!=""){
               pg_query("
   update 
   registro_equipo 
   set id_registro='$_POST[txtRegistro]',
   id_color='$_POST[colores]',
   id_marca='$_POST[marca]',
   id_cliente='$_POST[txtClienteId]',
   nro_serie='$_POST[txtSerie]',
   observaciones='$_POST[txtObservaciones]',
   detalles='$_POST[txtAccesorios]',
   id_usuario='$_SESSION[id]',
   fecha_ingreso='$_POST[txtIngreso]',
   id_tipo_equipo='$_POST[categoria]',
   modelo='$_POST[txtModelo]',
   fecha_salida='$_POST[txtSalida]',imagen2='$foto2'
   where id_registro='$_POST[txtRegistro]'
   ");
          
       }
        if($foto3!=""){
               pg_query("
   update 
   registro_equipo 
   set id_registro='$_POST[txtRegistro]',
   id_color='$_POST[colores]',
   id_marca='$_POST[marca]',
   id_cliente='$_POST[txtClienteId]',
   nro_serie='$_POST[txtSerie]',
   observaciones='$_POST[txtObservaciones]',
   detalles='$_POST[txtAccesorios]',
   id_usuario='$_SESSION[id]',
   fecha_ingreso='$_POST[txtIngreso]',
   id_tipo_equipo='$_POST[categoria]',
   modelo='$_POST[txtModelo]',
   fecha_salida='$_POST[txtSalida]',imagen3='$foto3'
   where id_registro='$_POST[txtRegistro]'
   ");
          
       }
        
       
          if($foto4!=""){
               pg_query("
   update 
   registro_equipo 
   set id_registro='$_POST[txtRegistro]',
   id_color='$_POST[colores]',
   id_marca='$_POST[marca]',
   id_cliente='$_POST[txtClienteId]',
   nro_serie='$_POST[txtSerie]',
   observaciones='$_POST[txtObservaciones]',
   detalles='$_POST[txtAccesorios]',
   id_usuario='$_SESSION[id]',
   fecha_ingreso='$_POST[txtIngreso]',
   id_tipo_equipo='$_POST[categoria]',
   modelo='$_POST[txtModelo]',
   fecha_salida='$_POST[txtSalida]',imagen4='$foto4'
   where id_registro='$_POST[txtRegistro]'
   ");
          
       }
    
          if($foto5!=""){
               pg_query("
   update 
   registro_equipo 
   set id_registro='$_POST[txtRegistro]',
   id_color='$_POST[colores]',
   id_marca='$_POST[marca]',
   id_cliente='$_POST[txtClienteId]',
   nro_serie='$_POST[txtSerie]',
   observaciones='$_POST[txtObservaciones]',
   detalles='$_POST[txtAccesorios]',
   id_usuario='$_SESSION[id]',
   fecha_ingreso='$_POST[txtIngreso]',
   id_tipo_equipo='$_POST[categoria]',
   modelo='$_POST[txtModelo]',
   fecha_salida='$_POST[txtSalida]',imagen5='$foto5'
   where id_registro='$_POST[txtRegistro]'
   ");
          
       }
       
                      pg_query("
   update 
   registro_equipo 
   set id_registro='$_POST[txtRegistro]',
   id_color='$_POST[colores]',
   id_marca='$_POST[marca]',
   id_cliente='$_POST[txtClienteId]',
   nro_serie='$_POST[txtSerie]',
   observaciones='$_POST[txtObservaciones]',
   detalles='$_POST[txtAccesorios]',
   id_usuario='$_SESSION[id]',
   fecha_ingreso='$_POST[txtIngreso]',
   id_tipo_equipo='$_POST[categoria]',
   modelo='$_POST[txtModelo]',
   fecha_salida='$_POST[txtSalida]'
   where id_registro='$_POST[txtRegistro]'
   ");
         
   
        
        
        
        $data = 0;
    }
}

echo $data;
