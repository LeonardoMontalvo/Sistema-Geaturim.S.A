<?php

session_start();
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$id=$_GET['id2'];
$arr_data=array();
$conpunto=1;
$consultapunto=pg_query("select max(id_punto_venta_empresa) from punto_venta_empresa where punto_venta_empresa.id_usuario='$_SESSION[id]'");
while($row=pg_fetch_row($consultapunto))
 {
  $conpunto=$row[0];
 }

$conpuntoresult=1;
$consultapuntoresult=pg_query("select id_punto_venta from punto_venta_empresa where id_punto_venta_empresa='$conpunto'");
while($row=pg_fetch_row($consultapuntoresult))
 {
  $conpuntoresult=$row[0];
 }
$consulta=pg_query("select P.cod_productos, P.codigo, P.articulo, P.stock, D.cantidad, D.precio_venta, D.descuento_venta, D.total_venta, P.iva, P.incluye_iva, P.inventariable from productos P, detalle_proforma D, proforma PR where P.cod_productos = D.cod_productos and PR.id_proforma = D.id_proforma and PR.estado ='Activo'  and D.estado= 'Activo'  and  PR.id_empresa='$conpuntoresult' and D.id_proforma='" . $id . "'");
while($row=pg_fetch_row($consulta))
 {
  $arr_data[]=$row[0];
  $arr_data[]=$row[1];
  $arr_data[]=$row[2];
  $arr_data[]=$row[3];
  $arr_data[]=$row[4];
  $arr_data[]=$row[5];
  $arr_data[]=$row[6];
  $arr_data[]=$row[7];
  $arr_data[]=$row[8];
  $arr_data[]=$row[9];
  $arr_data[]=$row[10];
 }
echo json_encode($arr_data);
?>
 if (cantidad >= parseInt($("#cantidad_producto_promo").val())) {
            console.log("cantidad >= cantidad producto");
            $.getJSON("comprobar_promo.php?cod_producto=" + $("#cod_producto_tem").val(), function (data) {
                var val = data.length;
                console.log("DATA 1" );
                if (val != "") {

                    var can1 = 0;
                    var result = 0;
                    var iva1 = 0;

                    for (var i = 0; i < val; i = i + 8) {
                        console.log("DATA 2" + data[i + 3]);
                        multi = data[i + 3] * parseFloat(data[i + 4]);

                        total = parseFloat(multi);
                        var id = data[i];

                      
                            if (data[i + 5] == "Si") {
                                   console.log("DATA 3" + data[i + 5]);

                                suma = parseFloat(data[i + 3]);
                                suma = Number(suma.toFixed(2));
                                multi = data[i + 3] * parseFloat(data[i + 4]);

                                total = parseFloat(multi);
                                iva1 = (total * calculoIVA) / 100;

                                iva_pventa = iva1 + parseFloat(total);

                                result = numFormatter(2).format(iva_pventa);
                            } else {
                                  console.log("DATA 4" );
                                suma = parseFloat(data[i + 3]);
                                suma = Number(suma.toFixed(2));
                                result = numFormatter(2).format(iva_pventa);
                            }
                        
                    multi = parseFloat(data[i + 3]) * parseFloat(data[i + 4]);
                    //                            console.log("entro15" + multi);
                    total = parseFloat(multi);
                    iva1 = (total * calculoIVA) / 100;
                    iva_pventa = iva1 + parseFloat(total);
                    //                            console.log("entro15" + iva_pventa);
                    result = numFormatter(2).format(iva_pventa);

                    var item1 = val.length + 1;
                    console.log("DATA 6"+result);
                    var datarow = {
                        id_list: item1,
                        cod_producto: data[i],
                        codigo: data[i + 1],
                        detalle: data[i + 2],
                        cantidad: data[i + 3],
                        precio_u: total,
                        descuento: desc,
                        cal_des: resultado,
                        total: total,
                        precio_ux: data[i + 4],
                        descuentox: parseFloat(desc).toFixed(4),
                        cal_desx: resultado.toFixed(4),
                        totalx: total.toFixed(4),
                        iva: data[i + 5],

                        pendiente: numFormatter(2).format(result),
                        incluye: data[i + 6],
                    };

                    promo = jQuery("#list").jqGrid("addRowData", item1, datarow);
                    limpiar_campos();
                    //                        $("#cantidad_producto_promo").val("0");
   }
                    var subtotal = 0;
                    var sub = 0;
                    var sub1 = 0;
                    var sub2 = 0;
                    var iva = 0;
                    var iva1 = 0;
                    var iva2 = 0;
                    var suma_total = 0;
                    // fin
                    var fil = jQuery("#list").jqGrid("getRowData");
                    for (var t = 0; t < fil.length; t++) {
                        var dd = fil[t];

                        if (dd["iva"] == "Si") {

                            if (dd["incluye"] == "No") {
                                subtotal = dd["total"];
                                sub1 = subtotal;
                                iva1 = (sub1 * calculoIVA) / 100;

                                subtotal0 = parseFloat(subtotal0) + 0;
                                subtotal12 = parseFloat(subtotal12) + parseFloat(sub1);
                                subtotal_total =
                                        parseFloat(subtotal0) + parseFloat(subtotal12);
                                descu_total =
                                        parseFloat(descu_total) + parseFloat(dd["cal_des"]);
                                iva12 = parseFloat(iva12) + parseFloat(iva1);

                                subtotal0 = parseFloat(subtotal0);
                                subtotal12 = parseFloat(subtotal12);
                                subtotal_total = parseFloat(subtotal_total);
                                iva12 = parseFloat(iva12);
                                descu_total = parseFloat(descu_total);
                                suma_total = suma_total + dd["cantidad"];
                            } else {
                                if (dd["incluye"] == "Si") {
                                    subtotal = dd["total"];
                                    sub2 = subtotal / (calculoIVA / 100 + 1);
                                    iva2 = sub2 * (calculoIVA / 100);

                                    subtotal0 = parseFloat(subtotal0) + 0;
                                    subtotal12 = parseFloat(subtotal12) + parseFloat(sub2);
                                    subtotal_total =
                                            parseFloat(subtotal0) + parseFloat(subtotal12);
                                    iva12 = parseFloat(iva12) + parseFloat(iva2);
                                    descu_total = parseFloat(descu_total) + dd["cal_des"];

                                    subtotal0 = parseFloat(subtotal0);
                                    subtotal12 = parseFloat(subtotal12);
                                    subtotal_total = parseFloat(subtotal_total);
                                    iva12 = parseFloat(iva12);
                                    descu_total = parseFloat(descu_total);
                                    suma_total = suma_total + dd["cantidad"];
                                }
                            }
                        } else {
                            if (dd["iva"] == "No") {

                                subtotal = dd["total"];
                                sub = subtotal;

                                subtotal0 = parseFloat(subtotal0) + parseFloat(sub);
                                subtotal12 = parseFloat(subtotal12) + 0;
                                subtotal_total =
                                        parseFloat(subtotal0) + parseFloat(subtotal12);
                                iva12 = parseFloat(iva12) + 0;
                                descu_total =
                                        parseFloat(descu_total) + parseFloat(dd["cal_des"]);

                                subtotal0 = parseFloat(subtotal0);
                                subtotal12 = parseFloat(subtotal12);
                                subtotal_total = parseFloat(subtotal_total);
                                iva12 = parseFloat(iva12);
                                descu_total = parseFloat(descu_total);
                                suma_total = suma_total + dd["cantidad"];
                            }
                        }
                    }
                    total_total = parseFloat(total_total) + (parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva12));
                    total_total = parseFloat(total_total);

                    var item = filas.length + 1;
                    $("#total_p").val(subtotal0);
                    $("#total_p2").val(subtotal12);
                    $("#sub").val(subtotal_total);
                    $("#iva").val(iva12);
                    $("#desc").val(descu_total);
                    $("#tot").val(total_total);
                    $("#total_px").val(subtotal0.toFixed(4));
                    $("#total_p2x").val(subtotal12.toFixed(4));
                    $("#subx").val(subtotal_total.toFixed(4));
                    $("#ivax").val(iva12.toFixed(4));
                    $("#descxax").val(descu_total.toFixed(4));
                    $("#totx").val(total_total.toFixed(2));
                    $("#items").val(item);
                    $("#num").val(suma_total);
                    $("#codigo_barras").focus();
                    $("#valor_factura").val(total_total.toFixed(2));
                }
         

            });
        } else {
            limpiar_campos();
        }