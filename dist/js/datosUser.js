$(document).on("ready",inicio);
var dialogos2=
{
    autoOpen:false,
    resizable:false,    
    width:400,
    height:420,
    modal:true,
};
function inicio()
{
	 $("#btnModUser").button({
     icons: {
        primary: "ui-icon-disk"
      },    
    });
     $("#btnModSes").button({
     icons: {
        primary: "ui-icon-close"
      },    
  });
     $("#btnModSes").click(function (){
      $("#modUser").dialog("close");
      limpiar();
     });
	$("#modUser").dialog(dialogos2);
	$("#modSession").click(function (){
		$("#modUser").dialog("open");
		 $.ajax({                
           	type: "POST",
            dataType: 'json',
            url: "../procesos/cargaUser.php",
            data :"id_registro="+$("#txtRegistro").val(),
            success: function(response) {
            	var data=response;
            	$("#userNombre").val(data[1]);
				$("#userApellido").val(data[2]);
				$("#userNick").val(data[9]);
				$("#userCi").val(data[3]);
				$("#userTelefono").val(data[4]);
				$("#userCelular").val(data[5]);
				$("#userEmail").val(data[7]);
				$("#userDirección").val(data[8]);
				$("#userPass").val(data[6]);
            }
        });
	});
	$("#btnModUser").on("click",modificarSession);
	$("#CerrarSession").on("click",cerrarSession);
  $("#split1 a").on("click",Defecto);
	$( "#rerun" )
      		.button()
      		.click(function() {
        	//alert( "Running the last action" );
      		})
      		.next()
        		.button({
          		text: false,
          		icons: {
            	primary: "ui-icon-triangle-1-s"
          	}
        })
        .click(function() {
          var menu = $( this ).parent().next().show().position({
            my: "left top",
            at: "left bottom",
            of: this
          });
          $( document ).one( "click", function() {
            menu.hide();
          });
          return false;
        })
        .parent()
          .buttonset()
          .next()
            .hide()
            .menu();
			/////
}

function cerrarSession (){
	window.location.href="index.php";
}
function Defecto(e){
	e.preventDefault();
}
function modificarSession(){
	$.ajax({                
        type: "POST",
        url: "../procesos/procesosUser.php",
        data :"userNombre="+$("#userNombre").val()+"&userApellido="+$("#userApellido").val()+"&userNick="+$("#userNick").val()+"&userCi="+$("#userCi").val()+"&userTelefono="+$("#userTelefono").val()+"&userCelular="+$("#userCelular").val()+"&userEmail="+$("#userEmail").val()+"&userDirección="+$("#userDirección").val()+"&userPass="+$("#userPass").val(),
        success: function(data) {
        	val=data;
            if(val==0)
            {
                alert("Datos Modificados"); 
                $("#modUser").dialog("close");  
                limpiar();                            
            }   
            if(val==1)
            {
                alert("Error.. durante el proceso");
            }   
        }
    });   
}
function limpiar(){
	$("#userNombre").val("");
	$("#userApellido").val("");
	$("#userNick").val("");
	$("#userCi").val("");
	$("#userTelefono").val("");
	$("#userCelular").val("");
	$("#userEmail").val("");
	$("#userDirección").val("");
	$("#userPass").val("");
}