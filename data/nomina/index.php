<?php
session_start();
include('../menu/app.php');
include '../../procesos/base.php';
conectarse();
error_reporting(0);
$consultaforma = pg_query("SELECT id_cargo, nombre_cargo, sueldo_base, estado FROM cargo; ");
while ($row = pg_fetch_row($consultaforma)) {
    $campo_nombre_forma = $row[0];
}
$mes = date("n");
$mesmenos = $mes - 2;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>NOMINA..</title>
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
                        Registro Nomina
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href=""><i class="fa fa-dashboard"></i> Ingresos</a></li>
                        <li class="active">Clientes</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_1" data-toggle="tab">Registro Nomina</a></li>
                                    <li><a href="#tab_2" data-toggle="tab">Crear cargo </a></li> 
                                    <!--<li><a href="#tab_3" id="Anti" data-toggle="tab">Anticipos </a></li>--> 
                                    <!--<li><a href="#tab_4" data-toggle="tab">Multas </a></li>--> 
                                    <li><a href="#tab_5" data-toggle="tab">Parametros Iess </a></li> 
                                    <li><a href="#tab_6" data-toggle="tab">Horas Extras</a></li> 
                                    <!--<li><a href="#tab_7" data-toggle="tab">XIII Y XIV SUELDO</a></li>--> 
                                </ul>   

                                <div class="box-body">
                                    <div class="row">
                                        <form id="nomina_form" name="nomina_form" method="post">
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_1"> 
                                                    <div class="col-mx-12"> 

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Fecha Actual:</label>
                                                                <div class="input-group">
                                                                    <input type="date" name="fecha_actual"  id="fecha_actual" readonly class="form-control timepicker"/>
                                                                    <input type="hidden" name="id_empleadon"  id="id_empleadon" readonly class="form-control">

                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </div>
                                                                </div><!-- /.input group -->
                                                            </div><!-- /.form group -->
                                                        </div>

                                                        <div class="col-md-3">   
                                                            <div class="form-group">
                                                                <label>Fecha Nacimiento:<font color="red">*</font></label>
                                                                <div class="input-group">
                                                                    <input type="date" name="fecha_nacimiento"  id="fecha_nacimiento"  class="form-control timepicker"/>
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3"> 
                                                            <div class="form-group">
                                                                <label>Fecha Ingreso:<font color="red">*</font></label>
                                                                <div class="input-group">
                                                                    <input type="date" name="fecha_ingreso"  id="fecha_ingreso"  class="form-control timepicker"/>
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3"> 
                                                            <div class="form-group">
                                                                <label>Fecha Salida:</label>
                                                                <div class="input-group">
                                                                    <input type="date" name="fecha_salida"  id="fecha_salida"  class="form-control timepicker"/>
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-mx-12">                    
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>RUC/CI: <font color="red">*</font></label>
                                                                <input type="text" name="ruc_ci"  id="ruc_ci" class="form-control" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Teléfono:</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-phone"></i>
                                                                    </div>
                                                                    <input type="text" name="nro_telefono" id="nro_telefono" class="form-control" data-inputmask='"mask": "(999) 999-999"' data-mask/>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>País: <font color="red">*</font></label>
                                                                <input type="text" name="pais_nomina" id="pais_nomina" placeholder="Ingrese un pais" class="form-control" />
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Dirección: <font color="red">*</font></label>
                                                                <input type="text" name="direccion_nomina" id="direccion_nomina" placeholder="Dirección" class="form-control" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Nombre Referencia Personal: <font color="red">*</font></label>
                                                                <input type="text" name="referencia_nomina" id="referencia_nomina" placeholder="Referencia" class="form-control" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Telefono Referencia Personal: <font color="red">*</font></label>
                                                                <input type="text" name="tele_referencia_nomina" id="tele_referencia_nomina" placeholder="Teléfono Referencia" class="form-control" />
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Etnia:<font color="red">*</font> </label>
                                                                <select class="form-control" name="etnia" id="etnia">
                                                                    <option value="0">Seleccione una opción</option>
                                                                    <option value="Mestizo">Mestizo</option>
                                                                    <option value="Blanco">Blanco</option>
                                                                    <option value="Indigena">Indígena</option>
                                                                    <option value="Afroecuatoriano">Afroecuatoriano</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Comentarios:</label>
                                                                <textarea class="form-control" name="notas_nomina" id="notas_nomina" rows="1"></textarea>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Fondo Reserva: <font color="red">*</font></label>
                                                                <select class="form-control" name="fondos_reserva" id="fondos_reserva">
                                                                    <option value="0">Seleccione una opción</option>
                                                                    <option value="SI">SI</option>
                                                                    <option value="NO">NO</option>
                                                                </select>

                                                            </div>
                                                            <div class="form-group">
                                                                <label>Fondos Acumulado o Mensual : <font color="red">*</font></label>
                                                                <select class="form-control" name="fondos_acu_mensual" id="fondos_acu_mensual">
                                                                    <option value="0">Seleccione una opción</option>
                                                                    <option value="fondos_mensual" >MENSUALIZADO</option>
                                                                    <option value="fondos_acumulado">ACUMULADO</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Nombres Completos: <font color="red">*</font></label>
                                                                <input type="text" name="nombres_nomina"  id="nombres_nomina" placeholder="Nombres y Apellidos" class="form-control" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Celular:</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-mobile"></i>
                                                                    </div>
                                                                    <input type="text" name="nro_celular" id="nro_celular" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask/>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Ciudad: <font color="red">*</font></label>
                                                                <input type="text" name="ciudad_nomina" id="ciudad_nomina" class="form-control"/>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>E-mail:</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-envelope"></i>
                                                                    </div>
                                                                    <input type="text" name="email" id="email" placeholder="Email" class="form-control"/>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Cargo:<font color="red">*</font></label>
                                                                <select class="form-control" name="tipo_cargo" id="tipo_cargo">
                                                                    <option   value="0" >SELECCIONE...</option>
                                                                    <?php
                                                                    $consultapro = pg_query("SELECT id_cargo, nombre_cargo, sueldo_base, estado FROM cargo where estado='Activo'");
                                                                    while ($row = pg_fetch_row($consultapro)) {
                                                                        echo "<option id=$row[0] value=$row[0]>$row[1]</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Género: <font color="red">*</font></label>
                                                                <select class="form-control" name="genero" id="genero">
                                                                    <option value="0">Seleccione una opción</option>
                                                                    <option value="Masculino">Masculino</option>
                                                                    <option value="Femenino">Femenino</option>

                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Afiliado: <font color="red">*</font></label>
                                                                <select class="form-control" name="afiliado" id="afiliado">
                                                                    <option value="0">Seleccione una opción</option>
                                                                    <option value="SI">SI</option>
                                                                    <option value="NO">NO</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>APLICA DECIMOS: <font color="red">*</font></label>
                                                                <select class="form-control" name="decimo_si_no" id="decimo_si_no">
                                                                    <option value="0">Seleccione una opción</option>
                                                                    <option value="SI">SI</option>
                                                                    <option value="NO">NO</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Décimo XIII y XIV : <font color="red">*</font></label>
                                                                <select class="form-control" name="decimo" id="decimo">
                                                                    <option value="0">Seleccione una opción</option>
                                                                    <option value="mensual" selected="">MENSUALIZADO</option>
                                                                    <option value="acumulado">ACUMULADO</option>
                                                                </select>
                                                            </div>


                                                            <br>
                                                            <br>
                                                            <br>
                                                            <br>
                                                        </div>
                                                        <br>
                                                        <br>
                                                        <br>

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


                                                    <div class="row">
                                                        <div class="col-mx-12">
                                                            <p>
                                                                <button class="btn bg-olive margin" id='btnGuardar'><i class="fa fa-save"></i> Guardar</button>
                                                                <button class="btn bg-olive margin" id='btnModificar'><i class="fa fa-edit"></i> Modificar</button>
                                                                <button class="btn bg-olive margin" id='btnEliminar'><i class="fa fa-remove"></i> Eliminar</button>
                                                                <button class="btn bg-olive margin" id='btnBuscar'><i class="fa fa-search"></i> Buscar</button>
                                                                <button class="btn bg-olive margin" id='btnNuevo'><i class="fa fa-pencil"></i> Nuevo</button>
                                                            </p> 
                                                        </div> 

                                                    </div>

                                                    <div id="clave_permison" title="PERMISOS">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label class="col-md-6" >Ingrese la clave de seguridad</label>
                                                                <div class="form-group col-md-6 p-0">                                
                                                                    <input type="password" name="claven"  id="claven" required class="form-control" />
                                                                </div> 
                                                            </div> 

                                                        </div>

                                                        <div class="form-actions" align="center">
                                                            <button class="btn btn-primary" id='btnAccedern'><i class="icon-ok"></i> Acceder</button>
                                                            <button class="btn btn-primary" id='btnCancelarn'><i class="icon-remove-sign"></i> Cancelar</button>
                                                        </div>
                                                    </div>
                                                    <div id="seguron">
                                                        <label>Esta seguro de Anular</label>
                                                        <br />
                                                        <div class="form-actions" align="center">
                                                            <button class="btn btn-primary" id='btnAceptarn'><i class="icon-ok"></i> Aceptar</button>
                                                            <button class="btn btn-primary" id='btnSalirn'><i class="icon-remove-sign"></i> Cancelar</button>
                                                        </div>
                                                    </div>


                                                </div>

                                                <div class="tab-pane" id="tab_2" style="height: 300px">                             
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="col-md-4">
                                                                <label>Nombre Cargo: </label>
                                                                <input type="text" name="nombre_cargo"  id="nombre_cargo"  class="form-control" />
                                                                <input type="hidden" name="id_cargo"  id="id_cargo" readonly class="form-control">
                                                            </div> 
                                                            <div class="col-md-2">
                                                                <label>Sueldo: </label>
                                                                <input type="number" name="sueldo_base"  id="sueldo_base"  class="form-control" />
                                                            </div> 
                                                            <div class="col-md-2">
                                                                <label>C.Sectorial: </label>
                                                                <input type="number" name="codigo_sectorial"  id="codigo_sectorial"  class="form-control" />
                                                            </div> 
                                                            <div class="col-md-3">
                                                                <label>S.B.U.: </label>
                                                                <input type="number" name="salario_basico_unificado"  id="salario_basico_unificado"  class="form-control" />
                                                            </div> 
                                                            <br>
                                                            <br>
                                                            <br>
                                                            <br>
                                                            <div class="col-md-12">
                                                                <p>
                                                                    <button class="btn bg-olive margin" id='btnGuardarcargo'><i class="fa fa-save"></i> Guardar</button>
                                                                    <button class="btn bg-olive margin" id='btnModificarcargo'><i class="fa fa-edit"></i> Modificar</button>
                                                                    <button class="btn bg-olive margin" id='btnEliminarcargo'><i class="fa fa-remove"></i> Eliminar</button>
                                                                    <button class="btn bg-olive margin" id='btnBuscarcargo'><i class="fa fa-search"></i> Buscar</button>
                                                                    <!--<button class="btn bg-olive margin" id='btnNuevocargo'><i class="fa fa-pencil"></i> Nuevo</button>-->
                                                                </p> 
                                                            </div>
                                                        </div> 
                                                        <div class="col-md-6">
                                                            <!-- <fieldset> -->
                                                            <table id="list_grid_cargo"></table>
                                                            <div id="pager_grid_cargo"></div>
                                                            <!-- </fieldset>    -->
                                                        </div>  
                                                    </div>
                                                    <div id="cargo" title="Búsqueda" class="">
                                                        <table id="list_cargo"><tr><td></td></tr></table>
                                                        <div id="pager"></div>
                                                    </div>  

                                                    <div id="clave_permisocc" title="PERMISOS">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label class="col-md-6" >Ingrese la clave de seguridad</label>
                                                                <div class="form-group col-md-6 p-0">                                
                                                                    <input type="password" name="clavecc"  id="clavecc" required class="form-control" />
                                                                </div> 
                                                            </div> 

                                                        </div>

                                                        <div class="form-actions" align="center">
                                                            <button class="btn btn-primary" id='btnAccedercc'><i class="icon-ok"></i> Acceder</button>
                                                            <button class="btn btn-primary" id='btnCancelarcc'><i class="icon-remove-sign"></i> Cancelar</button>
                                                        </div>
                                                    </div>
                                                    <div id="segurocc">
                                                        <label>Esta seguro de Anular</label>
                                                        <br />
                                                        <div class="form-actions" align="center">
                                                            <button class="btn btn-primary" id='btnAceptarcc'><i class="icon-ok"></i> Aceptar</button>
                                                            <button class="btn btn-primary" id='btnSalircc'><i class="icon-remove-sign"></i> Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div><!-- /.tab-pane -->

                                                <div class="tab-pane" id="tab_4" style="height: 300px">                             


                                                    <label>BUSCAR NOMINA: </label> 
                                                    <div class="col-mx-8"> 
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Cedula: <font color="red">*</font></label>

                                                                <input name="cedula_empleadom"  id="cedula_empleadom" placeholder="Buscar...."  class="form-control" />

                                                                <input type="hidden" name="id_empleadom"  id="id_empleadom" readonly class="form-control">
                                                                <input type="hidden" name="id_anticipom"  id="id_anticipom" readonly class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-4">
                                                                <label>Nombres Nomina: <font color="red">*</font></label>
                                                                <input name="nombres_empleadom"  id="nombres_empleadom" readonly="" placeholder="Buscar...." class="form-control" />
                                                            </div>
                                                        </div> 
                                                        <div class="form-group">
                                                            <div class="col-md-4">
                                                                <label>Direccion Nomina: <font color="red">*</font></label>
                                                                <input name="direccion_empleadom"  id="direccion_empleadom" readonly=""  class="form-control" />
                                                            </div>
                                                        </div> 
                                                    </div> 
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-md-5">Año:</label>
                                                            <div class="form-group col-md-7 p-0">                                
                                                                <select name="slct_anio_cfm" 
                                                                        id="slct_anio_cfm" 
                                                                        class="form-control">
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-md-5">Mes:</label>
                                                            <div class="form-group col-md-7 p-0">                                
                                                                <select class="form-control" name="select_mesm" id="select_mesm">
                                                                    <option value="0" >SELECCIONE MES... </option>


                                                                    <?php
                                                                    $consultapro = pg_query("select * from mes_actual where id_mes_actual =$mesmenos or id_mes_actual =$mes ");
                                                                    while ($row = pg_fetch_row($consultapro)) {
                                                                        echo "<option id=$row[1] value=$row[1]>$row[1]</option>";
                                                                    }
                                                                    ?>     
                                                                </select> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <div class="col-mx-12">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Fecha Registro:</label>
                                                                <div class="input-group">
                                                                    <input type="date" name="fecha_registrom"  id="fecha_registrom"  class="form-control"/>

                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </div>
                                                                </div> 
                                                            </div> 
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Multa</label>
                                                                <input type="text" name="descripcionm"  id="descripcionm"  class="form-control" />

                                                            </div>  
                                                        </div>
                                                        <div class="col-md-1 ">
                                                            <div class="form-group">
                                                                <label>Valor</label>
                                                                <input type="text" name="valorm"  id="valorm" class="form-control" />

                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 ">
                                                            <div class="form-group">
                                                                <label>TOTAL MES</label>

                                                                <input type="text" name="valor_totalm"  id="valor_totalm" readonly="" class="form-control" />

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div id="grid_container">
                                                            <table id="list_anticipom"></table>
                                                            <div id="pager_anticipom"></div>  
                                                        </div>
                                                    </div>          
                                                    <div class="row">
                                                        <div class="col-mx-12">
                                                        </div>
                                                    </div>      

                                                    <div id="buscar_inventariom" title="BUSCAR INVENTARIO">
                                                        <table id="list22m">
                                                            <tr>
                                                                <td></td>
                                                            </tr>
                                                        </table>
                                                        <div id="pager22m"></div>
                                                    </div>
                                                    <div id="clave_permisom" title="PERMISOS">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label class="col-md-6" >Ingrese la clave de seguridad</label>
                                                                <div class="form-group col-md-6 p-0">                                
                                                                    <input type="password" name="clavem"  id="clavem" required class="form-control" />
                                                                </div> 
                                                            </div> 
                                                        </div>

                                                        <div class="form-actions" align="center">
                                                            <button class="btn btn-primary" id='btnAccederm'><i class="icon-ok"></i> Acceder</button>
                                                            <button class="btn btn-primary" id='btnCancelarm'><i class="icon-remove-sign"></i> Cancelar</button>
                                                        </div>
                                                    </div>
                                                    <div id="segurom">
                                                        <label>Esta seguro de Anular</label>
                                                        <br />
                                                        <div class="form-actions" align="center">
                                                            <button class="btn btn-primary" id='btnAceptarm'><i class="icon-ok"></i> Aceptar</button>
                                                            <button class="btn btn-primary" id='btnSalirm'><i class="icon-remove-sign"></i> Cancelar</button>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-mx-12">
                                                            <p>
                                                                <button class="btn bg-olive margin" id='btnGuardarantm'><i class="fa fa-save"></i> Guardar</button>
                                                                <button class="btn bg-olive margin" id='btnModificarantm'><i class="fa fa-save"></i> Modificar</button>
                                                                <button class="btn bg-olive margin" id='btnNuevoantm'><i class="fa fa-pencil"></i> Nuevo</button>                                       
                                                                <button class="btn bg-olive margin" id='btnAnularantm'><i class="fa fa-remove"></i> Anular</button>
                                                            </p> 
                                                        </div> 
                                                    </div>    
                                                </div> 

                                                <div class="tab-pane" id="tab_5" style="height: 300px">                             
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="col-md-4">
                                                                <label>Descripcion Aporte IESS: </label>
                                                                <input type="text" name="descripcion_iess"  id="descripcion_iess"  class="form-control" />
                                                                <input type="hidden" name="id_parametro_iess"  id="id_parametro_iess" readonly class="form-control">
                                                            </div> 
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>APORTE%: </label>
                                                                    <input type="text" name="valor_aporte"  id="valor_aporte"  class="form-control" />
                                                                </div>
                                                            </div> 
                                                            <br>
                                                            <br>
                                                            <br>
                                                            <br>
                                                            <div class="col-mx-12">
                                                                <p>
                                                                    <button class="btn bg-olive margin" id='btnGuardarva'><i class="fa fa-save"></i> Guardar</button>
                                                                    <button class="btn bg-olive margin" id='btnModificarva'><i class="fa fa-edit"></i> Modificar</button>
                                                                    <!--<button class="btn bg-olive margin" id='btnEliminarva'><i class="fa fa-remove"></i> Eliminar</button>-->
                                                                    <button class="btn bg-olive margin" id='btnBuscarva'><i class="fa fa-search"></i> Buscar</button>
                                                                    <button class="btn bg-olive margin" id='btnNuevova'><i class="fa fa-pencil"></i> Nuevo</button>
                                                                </p> 
                                                            </div> 
                                                        </div>
                                                        <div class="col-md-6">
                                                            <!-- <fieldset> -->
                                                            <table id="list_aporte"></table>
                                                            <div id="pager_aporte"></div>
                                                            <!-- </fieldset>    -->
                                                        </div>  


                                                    </div>
                                                    <div id="aporte_iess" title="Búsqueda" class="">
                                                        <table id="list_va"><tr><td></td></tr></table>
                                                        <div id="pagerva"></div>
                                                    </div>  

                                                </div> 
                                                <div class="tab-pane" id="tab_6" style="height: 300px">                             


                                                    <label>BUSCAR NOMINA: </label> 
                                                    <div class="col-mx-8"> 
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Cedula: <font color="red">*</font></label>

                                                                <input name="cedula_empleadoh"  id="cedula_empleadoh" placeholder="Buscar...."  class="form-control" />

                                                                <input type="hidden" name="id_empleadoh"  id="id_empleadoh" readonly class="form-control">
                                                                <input type="hidden" name="id_anticipoh"  id="id_anticipoh" readonly class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-4">
                                                                <label>Nombres Nomina: <font color="red">*</font></label>
                                                                <input name="nombres_empleadoh"  id="nombres_empleadoh" readonly="" placeholder="Buscar...." class="form-control" />
                                                            </div>
                                                        </div> 
                                                        <div class="form-group">
                                                            <div class="col-md-4">
                                                                <label>Direccion Nomina: <font color="red">*</font></label>
                                                                <input name="direccion_empleadoh"  id="direccion_empleadoh" readonly=""  class="form-control" />
                                                            </div>
                                                        </div> 
                                                    </div> 
                                                    <br>
                                                    <br>
                                                    <br>

                                                    <br>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-md-5">Año:</label>
                                                            <div class="form-group col-md-7 p-0">                                
                                                                <select name="slct_anio_cfh" 
                                                                        id="slct_anio_cfh" 
                                                                        class="form-control">
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-md-5">Mes:</label>
                                                            <div class="form-group col-md-7 p-0">                                
                                                                <select class="form-control" name="select_mesh" id="select_mesh">

                                                                    <option value="0" >SELECCIONE MES... </option>

                                                                    <?php
//                                                                        $consultapro = pg_query("select * from mes_actual where id_mes_actual =$mesmenos or id_mes_actual =$mes ");
                                                                    $consultapro = pg_query("select * from mes_actual  ");
                                                                    while ($row = pg_fetch_row($consultapro)) {
                                                                        echo "<option id=$row[1] value=$row[1]>$row[1]</option>";
                                                                    }
                                                                    ?>     
                                                                </select> 

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <br>
                                                    <br>

                                                    <div class="col-mx-12">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Fecha Registro:</label>
                                                                <div class="input-group">
                                                                    <input type="date" name="fecha_registroh"  id="fecha_registroh"  class="form-control"/>


                                                                </div> 
                                                            </div> 
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>Sueldo </label>
                                                                <input type="text" name="sueldoh"  id="sueldoh" readonly="" class="form-control" />

                                                            </div>  
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Periodo:<font color="red">*</font> </label>
                                                                <select class="form-control" name="periodo" id="periodo">
                                                                    <option value="0">Seleccione una opción</option>
                                                                    <option value="lunes_viernes">Lunes-Viernes</option>
                                                                    <option value="fin_semana">Fin Semana o Feriados</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>hora extra </label>
                                                                <input type="text" name="hora_extra"  id="hora_extra"  class="form-control" />

                                                            </div>  
                                                        </div>

                                                        <div class="col-sm-1">
                                                            <label for="" class="form-label">
                                                                1.25.
                                                            </label>
                                                            <br>
                                                            <input type="checkbox" id="pf_check_unocinco" value="1.25" name="pf_check_50">
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <label for="" class="form-label">
                                                                1.50.
                                                            </label>
                                                            <br>
                                                            <input type="checkbox" id="pf_check_sincuenta" value="1.50" name="pf_check_50">
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <label for="" class="form-label">
                                                                2.
                                                            </label>
                                                            <br>
                                                            <input type="checkbox" id="pf_check_dos" value="2" name="pf_check_50">
                                                        </div>


                                                        <div class="col-md-1 ">
                                                            <div class="form-group">
                                                                <label>Valor</label>

                                                                <input type="text" name="valorh"  id="valorh" class="form-control" />

                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 ">
                                                            <div class="form-group">
                                                                <label>TOTAL MES</label>

                                                                <input type="text" name="valor_totalh" readonly=""  id="valor_totalh" class="form-control" />

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-mx-12">
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>Hora Trabajo</label>
                                                                <input type="text" name="valor_horat" readonly=""  id="valor_horat" class="form-control" />
                                                            </div> 
                                                        </div>

                                                    </div>

                                                    <div class="col-md-12">
                                                        <div id="grid_container">
                                                            <table id="list_anticipoh"></table>
                                                            <div id="pager_anticipoh"></div>  
                                                        </div>
                                                    </div>          
                                                    <div class="row">
                                                        <div class="col-mx-12">
                                                        </div>
                                                    </div>      

                                                    <div id="buscar_inventario" title="BUSCAR INVENTARIO">
                                                        <table id="list22">
                                                            <tr>
                                                                <td></td>
                                                            </tr>
                                                        </table>
                                                        <div id="pager22"></div>
                                                    </div>

                                                    <div id="clave_permisoh" title="PERMISOS">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label class="col-md-6" >Ingrese la clave de seguridad</label>
                                                                <div class="form-group col-md-6 p-0">                                
                                                                    <input type="password" name="claveh"  id="claveh" required class="form-control" />
                                                                </div> 
                                                            </div> 

                                                        </div>

                                                        <div class="form-actions" align="center">
                                                            <button class="btn btn-primary" id='btnAccederh'><i class="icon-ok"></i> Acceder</button>
                                                            <button class="btn btn-primary" id='btnCancelarh'><i class="icon-remove-sign"></i> Cancelar</button>
                                                        </div>
                                                    </div>
                                                    <div id="seguroh">
                                                        <label>Esta seguro de Anular</label>
                                                        <br />
                                                        <div class="form-actions" align="center">
                                                            <button class="btn btn-primary" id='btnAceptarh'><i class="icon-ok"></i> Aceptar</button>
                                                            <button class="btn btn-primary" id='btnSalirh'><i class="icon-remove-sign"></i> Cancelar</button>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-mx-12">
                                                            <p>
                                                                <button class="btn bg-olive margin" id='btnGuardaranth'><i class="fa fa-save"></i> Guardar</button>
                                                                <button class="btn bg-olive margin" id='btnModificaranth'><i class="fa fa-save"></i> Modificar</button>
                                                                <button class="btn bg-olive margin" id='btnNuevoanth'><i class="fa fa-pencil"></i> Nuevo</button>                                       
                                                                <button class="btn bg-olive margin" id='btnAnularanth'><i class="fa fa-remove"></i> Anular</button>
                                                            </p> 
                                                        </div> 
                                                    </div>    
                                                </div> 
                                                <div class="tab-pane" id="tab_7" style="height: 300px">                           
                                                    <label>BUSCAR NOMINA: </label> 
                                                    <div class="col-mx-8"> 
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Cedula: <font color="red">*</font></label>

                                                                <input name="cedula_empleados"  id="cedula_empleados" placeholder="Buscar...."  class="form-control" />

                                                                <input type="hidden" name="id_empleados"  id="id_empleados" readonly class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-4">
                                                                <label>Nombres Nomina: <font color="red">*</font></label>
                                                                <input name="nombres_empleados"  id="nombres_empleados" readonly="" placeholder="Buscar...." class="form-control" />
                                                            </div>
                                                        </div> 
                                                        <div class="form-group">
                                                            <div class="col-md-4">
                                                                <label>Direccion Nomina: <font color="red">*</font></label>
                                                                <input name="direccion_empleados"  id="direccion_empleados" readonly=""  class="form-control" />
                                                            </div>
                                                        </div> 
                                                    </div> 
                                                    <br>
                                                    <br>
                                                    <br>

                                                    <br>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-md-5">Año:</label>
                                                            <div class="form-group col-md-7 p-0">                                
                                                                <select name="slct_anio_cfs" 
                                                                        id="slct_anio_cfs" 
                                                                        class="form-control">
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-md-5">Mes:</label>
                                                            <div class="form-group col-md-7 p-0">                                
                                                                <select class="form-control" name="select_mess" id="select_mess">
                                                                    <option value="0" >SELECCIONE MES... </option>


                                                                    <?php
                                                                    $consultapro = pg_query("select * from mes_actual where id_mes_actual =$mesmenos or id_mes_actual =$mes ");
                                                                    while ($row = pg_fetch_row($consultapro)) {
                                                                        echo "<option id=$row[1] value=$row[1]>$row[1]</option>";
                                                                    }
                                                                    ?>     
                                                                </select> 

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-md-5">Afiliado:</label>
                                                            <div class="form-group col-md-7 p-0">                                
                                                                <input type="text" name="afiliados"  id="afiliados" readonly="" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-md-5">Dias Traba. </label>
                                                            <div class="form-group col-md-7 p-0">              
                                                                <input type="text" name="dias_trabajados"  id="dias_trabajados"  value="30" class="form-control" />
                                                            </div>
                                                        </div>  
                                                    </div>
                                                    <br>
                                                    <br>
                                                    <br>

                                                    <div class="col-mx-12">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Fecha Registro:</label>
                                                                <div class="input-group">
                                                                    <input type="date" name="fecha_registros"  id="fecha_registros"  class="form-control"/>


                                                                </div> 
                                                            </div> 
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Fecha Inicio:</label>
                                                                <div class="input-group">
                                                                    <input type="date" name="fecha_inicio"  id="fecha_inicio"  class="form-control"/>


                                                                </div> 
                                                            </div> 
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Fecha Hasta:</label>
                                                                <div class="input-group">
                                                                    <input type="date" name="fecha_hasta"  id="fecha_hasta"  class="form-control"/>


                                                                </div> 
                                                            </div> 
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>Sueldo </label>
                                                                <input type="text" name="sueldos"  id="sueldos"  class="form-control" />

                                                            </div>  
                                                        </div>



                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>hora extra </label>
                                                                <input type="text" name="hora_extras"  id="hora_extras"  class="form-control" />

                                                            </div>  
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <label for="" class="form-label">
                                                                Dividido.
                                                            </label>
                                                            <br>
                                                            <input type="checkbox" id="pf_check_dividido" value="dividido" name="pf_check_50s">
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <label for="" class="form-label">
                                                                Acumu.
                                                            </label>
                                                            <br>
                                                            <input type="checkbox" id="pf_check_acumulado" value="acumulado" name="pf_check_50s">
                                                        </div>

                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>XIII  </label>
                                                                <input type="text" name="tercer_sueldo"  id="tercer_sueldo"  class="form-control" />

                                                            </div>  
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>XIV  </label>
                                                                <input type="text" name="cuarto_sueldo"  id="cuarto_sueldo"  class="form-control" />

                                                            </div>  
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div id="grid_container">
                                                            <table id="list_anticipos"></table>
                                                            <div id="pager_anticipos"></div>  
                                                        </div>
                                                    </div>          
                                                    <div class="row">
                                                        <div class="col-mx-12">
                                                        </div>
                                                    </div>      

                                                    <div id="buscar_inventario" title="BUSCAR INVENTARIO">
                                                        <table id="list22">
                                                            <tr>
                                                                <td></td>
                                                            </tr>
                                                        </table>
                                                        <div id="pager22"></div>
                                                    </div>

                                                    <div id="clave_permisod" title="PERMISOS">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <label class="col-md-6" >Ingrese la clave de seguridad</label>
                                                                <div class="form-group col-md-6 p-0">                                
                                                                    <input type="password" name="claved"  id="claved" required class="form-control" />
                                                                </div> 
                                                            </div> 

                                                        </div>

                                                        <div class="form-actions" align="center">
                                                            <button class="btn btn-primary" id='btnAccederd'><i class="icon-ok"></i> Acceder</button>
                                                            <button class="btn btn-primary" id='btnCancelard'><i class="icon-remove-sign"></i> Cancelar</button>
                                                        </div>
                                                    </div>
                                                    <div id="segurod">
                                                        <label>Esta seguro de Anular</label>
                                                        <br />
                                                        <div class="form-actions" align="center">
                                                            <button class="btn btn-primary" id='btnAceptard'><i class="icon-ok"></i> Aceptar</button>
                                                            <button class="btn btn-primary" id='btnSalird'><i class="icon-remove-sign"></i> Cancelar</button>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-mx-12">
                                                            <p>
                                                                <button class="btn bg-olive margin" id='btnGuardarantd'><i class="fa fa-save"></i> Guardar</button>
                                                                <button class="btn bg-olive margin" id='btnModificarantd'><i class="fa fa-save"></i> Modificar</button>
                                                                <button class="btn bg-olive margin" id='btnNuevoantd'><i class="fa fa-pencil"></i> Nuevo</button>                                       
                                                                <button class="btn bg-olive margin" id='btnAnularantd'><i class="fa fa-remove"></i> Anular</button>
                                                            </p> 
                                                        </div> 
                                                    </div>    
                                                </div> 

                                            </div><!-- /.tab-pane -->
                                    </div>
                                    </form>
                                </div>

                                <div class="row">
                                    <div id="nominas" title="Búsqueda de Nomina" class="">
                                        <table id="list"><tr><td></td></tr></table>
                                        <div id="pager12"></div>
                                    </div>

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
<script src="nomina.js" type="text/javascript"></script>
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