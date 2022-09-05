<?php

session_start();
include '../../procesos/base.php';
include('../menu/app.php'); 
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>.:ARCHIVOS DE EXCEL:.</title>
        
        
     <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes"> 
        <link rel="stylesheet" type="text/css" href="../css/buttons.css"/>
        <link rel="stylesheet" type="text/css" href="../css/jquery-ui-1.10.4.custom.css"/>    
        <link rel="stylesheet" type="text/css" href="../css/normalize.css"/>    
        <link rel="stylesheet" type="text/css" href="../css/ui.jqgrid.css"/> 
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/bootstrap-responsive.min.css" rel="stylesheet">
        <link href="../css/font-awesome.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/alertify.core.css" />
        <link rel="stylesheet" href="../css/alertify.default.css" id="toggleCSS" />
        <link href="../css/link_top.css" rel="stylesheet" />
        <link href="../css/sm-core-css.css" rel="stylesheet" type="text/css" />
        <link href="../css/sm-blue/sm-blue.css" rel="stylesheet" type="text/css" />
        
            <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />    
    <link href="../../font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />        
    <link href="../../plugins/icon/ionicons.min.css" rel="stylesheet" type="text/css" />    
    <link href="../../dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link href="../../dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

    
    <link href="../../plugins/morris/morris.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <link href="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
    <link href="../../dist/css/alertify.core.css" rel="stylesheet" />
    <link href="../../dist/css/alertify.default.css" id="toggleCSS" rel="stylesheet" />
    <link href="../../dist/css/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css"/>            
    <link href="../../dist/css/ui.jqgrid.css" rel="stylesheet" type="text/css"/> 
    <link href="../../plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />
     
        
        <script type="text/javascript"src="../../dist/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="../../dist/js/bootstrap.js"></script>
        <script type="text/javascript" src="../../dist/js/jquery-loader.js"></script>
        <script type="text/javascript" src="../../dist/js/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="../../dist/js/jquery-ui-1.10.4.custom.min.js"></script>
        <script type="text/javascript" src="../../dist/js/grid.locale-es.js"></script>
        <script type="text/javascript" src="../../dist/js/jquery.jqGrid.src.js"></script>
        <script type="text/javascript" src="../../dist/js/buttons.js" ></script>
        <script type="text/javascript" src="../../dist/js/validCampoFranz.js" ></script>
        <script type="text/javascript" src="../../dist/js/datosUser.js"></script>
        <script type="text/javascript" src="../../dist/js/archivo_excel.js"></script>
        <script type="text/javascript" src="../../dist/js/ventana_reporte.js"></script>
        <script type="text/javascript" src="../../dist/js/guidely/guidely.min.js"></script>
        <script type="text/javascript" src="../../dist/js/easing.js" ></script>
        <script type="text/javascript" src="../../dist/js/jquery.ui.totop.js" ></script>
        <script type="text/javascript" src="../../dist/js/jquery.smartmenus.js"></script>
        <script type="text/javascript" src="../../dist/js/alertify.min.js"></script>
    </head>

    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>

                  
                </div> 
            </div> 
        </div> 

       

        <div class="main">
            <div class="main-inner">
                <div class="container">
                    <div class="row">
                        <div class="span12">      		
                            <div class="widget ">
                                <div class="widget-header">
                                    <i class="icon-upload"></i>
                                    <h3>ARCHIVOS EXCEL</h3>
                                </div> <!-- /widget-header -->

                                <div class="widget-content">

                                    <div class="alert alert-info">
                                        <h4>Recomendaciones</h4>  
                                        <br />
                                        <strong>Poner tipos numéricos y texto en las celdas que corresponde caso contrario saldra error de sintaxis.</strong>
                                        <br />
                                        <strong>Tener en cuenta de no repetir los articulos.</strong>
                                    </div>

                                    <div class="tabbable" id="centro">
                                        <form id="formulario_excel" name="formulario_excel" method="post" class="form">
                                            <fieldset>
                                                <table cellpadding="2" border="0" style="margin-left: 10px;">
                                                    <tr>
                                                        <td><label for="archivo_excel" style="width: 20%">Seleccione: </label></td>   
                                                        <td><input type="file" name="archivo_excel" id="archivo_excel" class="campo" readonly style="width: 500px"/></td>
                                                        <td><button class="btn btn-primary" id='btnGuardarCargar'><i class="icon-save"></i> Guardar y Cargar</button></td>
                                                    </tr>  
                                                </table>  
                                            </fieldset>
                                        </form>
                                    </div>
                                    <div style="width:100%;height:300px;border:solid 0px;border-color:rgb(204, 201, 201);overflow:scroll;">
                                        <table style="width:100%;" class="table table-bordered table-hover table-condensed" id="tabla_excel" >
                                            <thead>
                                                <tr>
                                                    <th style="width:20%;">Codigo</th>
                                                    <th style="width:40%;">Articulo</th>
                                                    <th style="width:35%;">Estado</th>
                                                    <th style="width:5%;"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>

                                                </tr>
                                        </table>
                                    </div>  
                                </div> 
                            </div>
                            
                            
                            
                            
                        </div> 
                    </div> 
                </div> 
            </div> 
        </div>
        <script type="text/javascript" src="../../dist/js/base.js"></script>
        <script type="text/javascript" src="../../dist/js/jquery.ui.datepicker-es.js"></script>

       
    </body>
</html>