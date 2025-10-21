<?php
session_start();
include '../../procesos/base.php';
include('../menu/app.php');
$consultaforma = pg_query("SELECT id_cargo, nombre_cargo, sueldo_base, estado FROM cargo; ");
while ($row = pg_fetch_row($consultaforma)) {

    $campo_nombre_forma = $row[0];
}
$mes = date("n");
$mesmenos = $mes - 1;
//print_r($mesmenos . "ee");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ROL PAGOS</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />    
        <link href="../../font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />        
        <link href="../../plugins/icon/ionicons.min.css" rel="stylesheet" type="text/css" />    
        <link href="../../dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <link href="../../dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
        <link href="../../plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />
        <link href="../../plugins/morris/morris.css" rel="stylesheet" type="text/css" />
        <link href="../../plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
        <link href="../../plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
        <link href="../../plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
        <link href="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
        <link href="../../dist/css/alertify.core.css" rel="stylesheet" />
        <link href="../../dist/css/alertify.default.css" id="toggleCSS" rel="stylesheet" />
        <link href="../../dist/css/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css"/>            
        <link href="../../dist/css/ui.jqgrid.css" rel="stylesheet" type="text/css"/> 
    </head>
    <body class="skin-blue">
        <div class="wrapper">
            <?php banner_1(); ?>
            <?php menu_lateral_1(); ?>
            <div class="content-wrapper">
                <section class="content-header">
                    <h1>
                        Rol Pagos
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href=""><i class="fa fa-dashboard"></i> Rol Pagos</a></li>
                        <li class="active">Rol Pagos</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-body">
                                    <div class="row">
                                        <form id="registro_form" name="registro_form" method="post">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-md-2">Fecha :</label>
                                                    <div class="form-group col-md-7 p-0">

                                                        <input type="date" name="fecha_registro"  id="fecha_registro"  class="form-control timepicker"/>

                                                    </div> 
                                                </div> 
                                            </div>
                                            <div class="col-md-4">
                                                <label class="col-md-2">Mes:</label>
                                                <div class="form-group col-md-7 p-0">                                
                                                    <select class="form-control" name="select_mes" id="select_mes">
                                                        <option value="0" id="messi" >SELECCIONE MES... </option>

                                                        <?php
                                                          $consultapro = pg_query("select * from mes_actual");
//                                                        $consultapro = pg_query("select * from mes_actual where id_mes_actual =$mesmenos or id_mes_actual =$mes");
                                                        while ($row = pg_fetch_row($consultapro)) {
                                                            echo "<option id=$row[1] value=$row[1]>$row[1]</option>";
                                                        }
                                                        ?>     
                                                    </select> 

                                                </div>
                                            </div>
                                            <div class="col-md-4">

                                                <label class="col-md-2">Año:</label>
                                                <div class="form-group col-md-5 p-0">                                
                                                    <select name="slct_anio_cf" 
                                                            id="slct_anio_cf" 
                                                            class="form-control">
                                                    </select>
                                                </div>

                                            </div>
                                 
                                            <div class="col-md-1">
                                                <label>Cedula: <font color="red">*</font></label>
                                                <input name="cedula_empleado"  id="cedula_empleado" placeholder="Buscar...."  class="form-control" />
                                                <input type="hidden" name="id_rol"  id="id_rol" readonly class="form-control">
                                                <input type="hidden" name="id_empleado"  id="id_empleado" readonly class="form-control">
                                            </div>
                                          
                                            <div class="col-md-1">
                                                <label>Nomina:</label>
                                                <input class="form-control" name="nombres_empleado" id="nombres_empleado" placeholder="Buscar...." rows="3"></input>
                                            </div>
                                            	      <div class="col-md-1">
                                                <label>Fecha I: </label>
                                                <input name="fecha_ingreso"  id="fecha_ingreso"  readonly=""  class="form-control" />
                                               
                                            </div>
                                            <div class="col-md-1">
                                                <label>S.B.U.:</label>
                                                <input name="sueldo_basico"  id="sueldo_basico" readonly=""  class="form-control" />


                                            </div>
                                            <div class="col-md-1">
                                                <label>Cargo:</label>
                                                <input name="cargo_empleado"  id="cargo_empleado" readonly=""  class="form-control" />


                                            </div>

                                            <div class="col-md-1">
                                                <label>Salario  :</label>
                                                <input name="salario_empleado"  id="salario_empleado" readonly=""  class="form-control" />


                                            </div>
                                            <div class="col-md-1">
                                                <label>Dias Labo: </label>
                                                <input name="dias_trabajados"  id="dias_trabajados"   class="form-control" />


                                            </div>
                                            <div class="col-md-1">
                                                <label>Afiliado: </label>
                                                <input name="esta_afiliado"  id="esta_afiliado"  readonly="" class="form-control" />


                                            </div>
                                              <div class="col-md-1">
                                                <label>Decimo S/N: </label>
                                                <input name="decimo_si_no"  id="decimo_si_no"  readonly="" class="form-control" />
                                            </div>
                                            <div class="col-md-1">
                                                <label>III y IV: </label>
                                                <input name="decimo_rol"  id="decimo_rol"  readonly="" class="form-control" />
                                            </div>
                                            <div class="col-md-1">
                                                <label>F.Reserva: </label>
                                                <input name="fondo_reserva"  id="fondo_reserva"  readonly="" class="form-control" />
                                            </div>
                                            <div class="col-md-1">
                                                <label>F.Mensu.: </label>
                                                <input name="fondos_acu_mensual"  id="fondos_acu_mensual"  readonly="" class="form-control" />
                                            </div>


                                            <br>
                                            <br>
                                            <br>
                                            <br>


                                            <div class="col-mx-12">                    
                                                <div class="col-md-6">
                                                    <label style="color:#0000FF">INGRESOS: </label>
                                                    <br>
                                                    <div class="col-md-4">
                                                        <label >SUELDO PERCIBIDO:<font color="red">*</font> </label>
                                                        <input name="sueldo_percivido"  id="sueldo_percivido"  value="0.00"  class="form-control" />


                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>HORAS EXTRAS: </label>
                                                        <input name="horas_extras"  id="horas_extras" readonly="" value="0.00"  class="form-control" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>APORTE PATRONAL: </label>
                                                        <input name="aporte_patronal"  id="aporte_patronal" readonly="" value="0.00" class="form-control" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>FONDOS DE RESERVA: </label>
                                                        <input name="fondos_recerva"  id="fondos_recerva" readonly="" value="0.00"  class="form-control" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>XIII  SUELDO:</label>
                                                        <input name="tercer_sueldo"  id="tercer_sueldo" readonly="" value="0.00" class="form-control" />
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label>XIV   SUELDO:</label>
                                                        <input name="cuarto_sueldo"  id="cuarto_sueldo" readonly="" value="0.00" class="form-control" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>ALIMENTACION PERSONAL: </label>
                                                        <input name="otros_ingresos"  id="otros_ingresos"  value="0.00" class="form-control" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label style="color:#ff0000">TOTAL NOMINA: </label>
                                                        <input name="total_nomina"  id="total_nomina"   value="0.00" class="form-control" />


                                                    </div>


                                                </div>

                                                <div class="col-md-6">

                                                    <label style="color:#0000FF">DESCUENTOS: </label>
                                                    <br>

                                                    <div class="col-md-4">
                                                        <label>APORTE INDIVIDUAL: </label>
                                                        <input name="aporte_individual"  id="aporte_individual" readonly=""   value="0.00" class="form-control" />
                                                    </div>


                                                    <div class="col-md-4">
                                                        <label>PRESTAMOS QUI. IESS: </label>
                                                        <input name="prestamos_qui_iess"  id="prestamos_qui_iess"  value="0.00" class="form-control" />
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label>CREDITO PERSONAL: </label>
                                                        <input name="credito_personal"  id="credito_personal"   value="0.00" class="form-control" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>ANTICIPOS</label>
                                                        <input name="anticipos_consumos"  id="anticipos_consumos"   value="0.00" class="form-control" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>FALTANTES DE CAJA: </label>
                                                        <input name="faltantes_caja"  id="faltantes_caja" readonly="" value="0.00"  class="form-control" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>MULTAS: </label>
                                                        <input name="multas"  id="multas" readonly="" value="0.00"  class="form-control" />
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label>OTROS DESCUENTOS: </label>
                                                        <input name="otros_descuentos"  id="otros_descuentos" value="0.00"  class="form-control" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label style="color:#ff0000">TOTAL DEDUCCION: </label>
                                                        <input name="total_deduccion"  id="total_deduccion"  value="0.00" class="form-control" />
                                                    </div>
                                                    <br>

                                                    <div class="col-md-4">
                                                        <label>NETO RECIBIR: </label>
                                                        <input name="neto_recibir"  id="neto_recibir" readonly="" value="0.00" class="form-control" />
                                                    </div>

                                                </div>
                                            </div>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <hr>


                                            <div class="row">
                                                <div class="col-mx-12">
                                                    <div class="col-md-3">
                                                        <label class="col-md-4">Forma pago:<font color="red">*</font></label>
                                                        <div class="form-group col-md-5 p-0">
                                                            <select class="form-control" name="forma_pago" id="forma_pago">
                                                                <option value="0">...SELECCIONE..</option>
                                                                <option value="CONTADO">EFECTIVO</option>
                                                                <option value="CHEQUE">CHEQUE</option>                                                              
                                                                <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                                                <option value="CXP">CUENTA POR PAGAR</option>
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="col-md-4">Seleccione Cta Contable: </label>
                                                            <div class="form-group col-md-4 p-0">
                                                                <input type="text" name="cuenta_contable"  id="cuenta_contable"  class="form-control" disabled="disabled" />
                                                                <input type="hidden" name="idCuenta"  id="idCuenta" />
                                                            </div>
                                                            <div class="form-group col-md-4 p-0">
                                                                <button class="btn btn-secondary" id="btnCuenta" name="btnCuenta" disabled="disabled">Seleccionar Cuenta</button>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="col-md-4">Nro. Docu:</label>
                                                            <div class="form-group col-md-4 p-0">
                                                                <input type="text" name="cheque_tarjeta" id="cheque_tarjeta" class="form-control" disabled="disabled" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>    

                                            </div>

                                            <button class="btn bg-olive margin" id='btnAgregar'><i class="fa fa-save"></i> Agregar</button>
                                            <button class="btn bg-olive margin" id='btnOmpriR'><i class="fa fa-save"></i> Imprimir Roles</button>


                                            <input type="hidden" name="nomina_mes" id="nomina_mes" ><span></span><br/><br/>

                                            <input type="hidden" name="clic_agregar" id="clic_agregar" ><span></span><br/><br/>

                                        </form>
                                    </div>

                                    <div class="row">
                                        <br>
                                        <div class="col-md-12">
                                            <div id="grid_container">
                                                <table id="list_rol"></table>
                                                <div id="pager_rol"></div>  
                                            </div>
                                        </div>


                                        <div class="col-mx-12">                    
                                            <div class="col-md-6">
                                                <label style="color:#0000FF">TOTALES: </label>
                                                <label style="color:#0000FF">INGRESOS: </label>
                                                <br>
                                                <div class="col-md-3">
                                                    <label>SUELDO PERCI.: </label>
                                                    <input name="sueldo_percividot"  id="sueldo_percividot" readonly="" value="0.00"  class="form-control" />


                                                </div>
                                                <div class="col-md-3">
                                                    <label>HORAS EXTRAS: </label>
                                                    <input name="horas_extrast"  id="horas_extrast" readonly="" value="0.00"  class="form-control" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label>OTROS INGRE.: </label>
                                                    <input name="otros_ingresost"  id="otros_ingresost"  readonly="" value="0.00" class="form-control" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label>FONDOS R.EMPLE </label>
                                                    <input name="fondos_recervat"  id="fondos_recervat" readonly="" value="0.00"  class="form-control" />


                                                </div>
                                                <div class="col-md-3">
                                                    <label>APORTE PATRO.: </label>
                                                    <input name="aporte_patronalt"  id="aporte_patronalt" readonly="" value="0.00" class="form-control" />


                                                </div>

                                                <div class="col-md-3">
                                                    <label>XIII  SUELDO:</label>
                                                    <input name="tercer_sueldot"  id="tercer_sueldot"  readonly="" value="0.00" class="form-control" />


                                                </div>

                                                <div class="col-md-3">
                                                    <label>XIV   SUELDO:</label>
                                                    <input name="cuarto_sueldot"  id="cuarto_sueldot"  readonly="" value="0.00" class="form-control" />


                                                </div>

                                                <div class="col-md-3">
                                                    <label style="color:#ff0000">TOTAL NOMINA: </label>
                                                    <input name="total_nominat"  id="total_nominat" readonly=""  value="0.00" class="form-control" />


                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label style="color:#0000FF">TOTALES: </label>
                                                <label style="color:#0000FF">DESCUENTOS: </label>
                                                <br>

                                                <div class="col-md-3">
                                                    <label>APORTE PERSO.: </label>
                                                    <input name="aporte_individualt"  id="aporte_individualt" readonly=""  value="0.00" class="form-control" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label>ANTICIPOS: </label>
                                                    <input name="anticipos_consumost"  id="anticipos_consumost" readonly="" value="0.00" class="form-control" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label>FALTANTES CAJA: </label>
                                                    <input name="faltantes_cajat"  id="faltantes_cajat" readonly="" value="0.00"  class="form-control" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label>MULTAS: </label>
                                                    <input name="multast"  id="multast" readonly="" readonly="" value="0.00"  class="form-control" />
                                                </div>

                                                <div class="col-md-3">
                                                    <label>PRESTA. Q. IESS: </label>
                                                    <input name="prestamos_qui_iesst"  id="prestamos_qui_iesst"  readonly="" value="0.00" class="form-control" />
                                                </div>

                                                <div class="col-md-3">
                                                    <label>CREDITO PER.: </label>
                                                    <input name="credito_personalt"  id="credito_personalt"  readonly="" value="0.00" class="form-control" />
                                                </div>

                                                <div class="col-md-3">
                                                    <label>OTROS DESCU.: </label>
                                                    <input name="otros_descuentost"  id="otros_descuentost"  readonly="" value="0.00"  class="form-control" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label >TOTAL DEDU.: </label>
                                                    <input name="total_deducciont"  id="total_deducciont" readonly=""  value="0.00" class="form-control" />
                                                </div>
                                                <br>

                                                <div class="col-md-3">
                                                    <label style="color:#ff0000">NETO A PAGAR: </label>
                                                    <input name="neto_recibirt"  id="neto_recibirt" readonly="" value="0.00" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-mx-12">
                                            <p>
                                                <button class="btn bg-olive margin" id='btnGuardar'><i class="fa fa-save"></i> Guardar</button>
                                                <!--<button class="btn bg-olive margin" id='btnModificar'><i class="fa fa-edit"></i> Modificar</button>-->
                                                <button class="btn bg-olive margin" id='btnEliminar'><i class="fa fa-remove"></i> Eliminar</button>
                                                <button class="btn bg-olive margin" id='btnBuscar'><i class="fa fa-search"></i> Buscar</button>
                                                <button class="btn bg-olive margin" id='btnNuevo'><i class="fa fa-pencil"></i> Nuevo</button>
<!--                                                <button class="btn bg-olive margin" id='btnImprimir'><i class="fa fa-print"></i> Imprimir</button>-->
                                                <!--<button class="btn bg-olive margin" id='btnAtras'><i class="fa fa-backward"></i> Atras</button>-->
                                                <!--<button class="btn bg-olive margin" id='btnAdelante'>Adelante <i class="fa fa-forward"></i></button>-->
                                            </p> 
                                        </div> 
                                        <div id="buscar_rol_pagos" title="BUSCAR ">
                                            <table id="list2"><tr><td></td></tr></table>
                                            <div id="pager2"></div>
                                        </div>
                                        <div id="cuentas" title="Búsqueda Plan de Cuentas" class="">
                                            <table id="list4"><tr><td></td></tr></table>
                                            <div id="pager4"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?php footer(); ?>
        </div>

        <script src="../../plugins/jQuery/jQuery-2.1.3.min.js"></script>
        <script src="../../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../../plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
        <script src="../../plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
        <script src="../../plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
        <script src="../../plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
        <script src="../../plugins/colorpicker/bootstrap-colorpicker.min.js" type="text/javascript"></script>
        <script src="../../plugins/timepicker/bootstrap-timepicker.min.js" type="text/javascript"></script>
        <script src="../../plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="../../plugins/iCheck/icheck.min.js" type="text/javascript"></script>
        <script src='../../plugins/fastclick/fastclick.min.js'></script>
        <script src="rol_pagos.js" type="text/javascript"></script>
        <script src="../../dist/js/app.min.js" type="text/javascript"></script>
        <script src="../../dist/js/validCampoFranz.js" type="text/javascript" ></script>
        <script src="../../dist/js/alertify.min.js" type="text/javascript"></script>
        <script src="../../dist/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
        <script src="../../dist/js/jquery.jqGrid.src.js" type="text/javascript"></script>
        <script src="../../dist/js/grid.locale-es.js" type="text/javascript"></script>
        <link href="../../dist/css/style.css" rel="stylesheet" type="text/css"/>     
        <script src="../../dist/js/ventana_reporte.js" type="text/javascript"></script>

    </body>
</html>