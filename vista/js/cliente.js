/**
 * 
 * Mejora la interaccion del usuario con e frmulario de consultas
 * @author Eduardo Villta <ev_villota@hotmail.com> <@eduardouio>
 * @access publico
 * @copyright (C) 2012 CendeNdt <info@cendendt.com.ec>
 * @license Propiedad de Cendendt Derechos reservados
 * @version 1.0
 *
 */


$(document).ready(function(){

$("#op1").click(function(){	
	$("#ajax").slideUp('fast');	
	$(	"#ajax").load("../vista/template/cliente/registro.html");
	$("#ajax").slideDown('slow');
});

$("#op2").click(function(){	
	$("#ajax").slideUp('fast');	
	$("#ajax").load("../vista/template/cliente/cedula.html");
	$("#ajax").slideDown('slow');
	
}); 

$("#op3").click(function(){	
	$("#ajax").slideUp('fast');	
	$("#ajax").load("../vista/template/cliente/apellidos.html");
	$("#ajax").slideDown('slow');
	
}); 
});
