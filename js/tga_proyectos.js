$(document).ready(function() {
    var options = {
        target:        '#salida',
        beforeSubmit:  showRequest,
        success:       showResponse,  // post-submit callback ,
        type:      		'post'       // 'get' or 'post', override for form's 'method' attribute
    };
    $('#formInmobiliaria').ajaxForm(options);



    var options = {
        target:        '#salida',
        beforeSubmit:  showRequest,
        success:       showResponse,  // post-submit callback ,
        type:      		'post'       // 'get' or 'post', override for form's 'method' attribute
    };
    $('#formProyecto').ajaxForm(options);
});
var map;
function insertaProyecto(){
	$('#btnProyectos').text('Insertar');
	$("#formProyecto")[0].reset();
	$('#accionForm').val(1);
}
function selectInmobiliaria(id){
	if (id > 0) {
		$('#idInmobiliaria').val(id);
	    loaderTgaSolutions(1);
	    $("#formInmobiliaria").submit();	
	}else{
		$('#salida').html('');
		$("#formProyecto")[0].reset();
	};
};
function guardaProyecto(){
	var msj 			= '';
	var idInmobiliaria 	= $('#idInmobiliaria').val();
	var accion 		= $('#accionForm').val();
	if (validaForm()) {
		if (accion == 1) {
			msj = "¡Deseas agregar este proyecto?";
		}else{
			msj = "¡Deseas actualizar este proyecto?";
		};
	    var r = confirm(msj);
	    if (r != true) { 
	        selectInmobiliaria(idInmobiliaria);
	    }else{
		    loaderTgaSolutions(1);
		    $("#formProyecto").submit();
			setTimeout(function(){
				window.lat 		= $('#latitud').val();
				window.long 	= $('#longitud').val();
				initMap();
			}, 1500);	
	    };
	};
};
function muestraProyecto(id){
	$('#btnProyectos').text('Actualizar');
	$('#accionForm').val(2);
	$('#idProyecto').val(id);
	$('#actualizaBtn').attr('onclick','muestraProyecto(' + id +')');
	$("body").loadTgaSol({url: 'tga_global/Proyectos/muestraProyectos',
           						idProyecto: id,
           						valor1: 2,
                   				salida: "oculto"});
	setTimeout(function(){
		var obj = jQuery.parseJSON(tgaSolution.data);
		$('#nombreProyecto').val(obj[0].nombre);
		$('#regiones').val(obj[0].idRegion);
		$('#regiones').trigger('change');
		$('#comunas').val(obj[0].idComuna);
		$('#direccion').val(obj[0].direccion);
		$('#url').val(obj[0].url);
		$('#latitud').val(obj[0].latitud);
		$('#longitud').val(obj[0].longitud);
		$('#imagen').val(obj[0].imagen);
		$('#estado').val(obj[0].estado);
		$('#imagenProy').attr("src",obj[0].imagen);
		window.lat 		= obj[0].latitud;
		window.long 	= obj[0].longitud;
		initMap();
		$("#tipoConstruccion" + obj[0].tipoConstruccion).attr('checked', true);
		$('#tipoFinanciamiento' + obj[0].financiamiento).attr('checked', true);
	}, 1000);
};
function showResponse(responseText, statusText, xhr, $form)  {
};
function validaForm(){
	var nombreProyecto		=	$('#nombreProyecto').val();
	var regiones			=	$('#regiones').val();
	var comunas				=	$('#comunas').val();
	var direccion			=	$('#direccion').val();
	var url					=	$('#url').val();
	var imagen				=	$('#imagen').val();
	var latitud				=	$('#latitud').val();
	var longitud			=	$('#longitud').val();
	var estado				=	$('#estado').val();
	var tipoConstruccion	=	$('input[name=tipoConstruccion]').val();
	var tipoFinanciamiento	=	$('input[name=tipoFinanciamiento]').val();
	var idInmobiliaria 		= 	$('#idInmobiliaria').val();
	if (idInmobiliaria == '') {
        mensajesTgaSolutions(3,"Error","Seleccione inmobiliaria");
     	return false;
    };
	if (nombreProyecto == '') {
        mensajesTgaSolutions(3,"Error","falta nombre");
     	return false;
    };
    if (regiones == '') {
    	mensajesTgaSolutions(3,"Error","falta region");
    	return false;
    };
    if (comunas == '') {
    	mensajesTgaSolutions(3,"Error","falta comuna");
    	return false;
    };
    if (direccion == '') {
        mensajesTgaSolutions(3,"Error","falta direccion");
    	return false;
    };
    if (url == '') {
        mensajesTgaSolutions(3,"Error","falta url");
    	return false;
    };
    
    if (imagen == '') {
        mensajesTgaSolutions(3,"Error","falta imagen");
    	return false;
    };
    if (latitud == '') {
        mensajesTgaSolutions(3,"Error","falta latitud");
    	return false;
    };
    if (longitud == '') {
        mensajesTgaSolutions(3,"Error","falta longitud");
    	return false;
    };
	if (!$.isNumeric(estado) && estado > 2) {
        mensajesTgaSolutions(3,"Error","estado incorrecto");
    	return false; 
    };
	if (!$.isNumeric(tipoConstruccion) && tipoConstruccion > 2) {
        mensajesTgaSolutions(3,"Error","tipo Construccion incorrecto");
        return false;
    };
	if (!$.isNumeric(tipoFinanciamiento) && tipoFinanciamiento > 2) {
        mensajesTgaSolutions(3,"Error","tipo Financiamiento incorrecto");
        return false;
    };
    return true;
};
function initMap() {
    var myLatLng = {lat: Number(window.lat), lng: Number(window.long)};

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 18,
      center: myLatLng
    });

    var marker = new google.maps.Marker({
      position: myLatLng,
      map: map
    });
}