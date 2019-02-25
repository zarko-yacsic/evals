
<section class="contenido contenido-plat-eva">
	<h2>Plataforma de evaluaciones</h2>
	<p>Seleccionar un archivo .xlsx desde su equipo</p>

	<form id="formSubirArchivo">
		<div class="subirArchivo">
			<div class="input-group mb-3">
				<div class="input-group-prepend cursor">
					<span class="input-group-text" id="inputGroupFileAddon01" onclick="subirArchivo();">Subir</span>
				</div>
				<div class="custom-file">
					<input type="file" class="custom-file-input archivo" name="archivo" id="upload_xls" onchange="seleccionarExcel(this);" aria-describedby="inputGroupFileAddon01">
					<label class="custom-file-label archivoTxt archivoTxt-1" for="upload_xls">Cargar archivo</label>
				</div>
			</div>
		</div>
		<div id="seleccionarInicio">
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<label class="input-group-text" for="sel_col_inicio">Columna inicio</label>
				</div>
				<select id="sel_col_inicio" class="custom-select" name="sel_col_inicio" onchange="seleccionarColumnaInicio(this);">
				</select>
			</div>
		</div>
		<div id="crearDataTemporal">
			<button type="button" class="btn btn-danger btn-sm" onclick="crearPreguntas();">Crear Preguntas</button>
		</div>
	</form>

	<form id="formCrearPreguntas">
		<input type="hidden" name="hf_upload_dir" id="hf_upload_dir" value="" />
		<input type="hidden" name="hf_archivo" id="hf_archivo" value="" />
		<input type="hidden" name="hf_col_inicio" id="hf_col_inicio" value="0" />
	</form>
</section>


<!-- Modal -->
<div class="modal fade" id="modalProceso_msg" tabindex="-1" role="dialog" aria-labelledby="modalProceso_msgLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalProceso_msgLabel"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal"></button>
			</div>
		</div>
	</div>
</div>



<style type="text/css">
	#formSubirArchivo { width:660px;}
	#seleccionarInicio, #crearDataTemporal, #mensaje { display: none;}
</style>


<script type="text/javascript">

	function subirArchivo(){
		if ($('#formSubirArchivo label.archivoTxt-1').html() != 'Cargar archivo'){
			loaderTgaSolutions(1);
			$('#formSubirArchivo #seleccionarInicio #sel_col_inicio').empty();
			$('#formSubirArchivo').submit();
		}
	}


	function llenarSelectInicio(upload_dir, archivo){
		$.ajax({
			url: 'Plataforma_evaluaciones/obtener_columnas_excel',
			type: 'GET',
			data: {
				xls_dir : upload_dir,
				xls_file : archivo
			},
			success: function(data){
				var json = eval('(' + data + ')');
				if(json.status == 'SUCCESS'){
					$('#sel_col_inicio').append($('<option>', { value: 0, text: '-Seleccionar-'}));
					var letras = json.columnas_xls;
					for (a=0; a < letras.length; a++){
						$('#sel_col_inicio').append($('<option>', { value: parseInt(a + 1) + '|' + letras[a], text: letras[a]}));
					}
	        	}
			}
		});
	}


	function seleccionarColumnaInicio(mySelect){
		var columna = $(mySelect).val();
		columna = columna.split('|');
		if(columna == 0){
			$('#formCrearPreguntas #hf_col_inicio').val('0');
			$('#crearDataTemporal').css('display', 'none');
			console.log('Esta columna no existe...');
		}
		else{
			$('#formCrearPreguntas #hf_col_inicio').val(columna[0]);
			$('#crearDataTemporal').css('display', 'block');
		}
	}

	
	function crearPreguntas(){
		if (parseInt($('#formCrearPreguntas #hf_col_inicio').val()) != 0){
			loaderTgaSolutions(1);
			$('#formCrearPreguntas').submit();
		}
	}


	function modalMensajeError(myModal, titulo, mensaje, txt_boton){
		$(myModal + ' .modal-header h5.modal-title').html(titulo);
		$(myModal + ' .modal-body').html(mensaje);
		$(myModal + ' .modal-footer button').html(txt_boton);
		$(myModal).modal();
	}


	function reestablecerFormulario(){
		$('#formSubirArchivo label.archivoTxt-1').html('Cargar archivo');
		$('#formSubirArchivo input#upload_xls').removeAttr('value');
		$('#formSubirArchivo #seleccionarInicio').css('display', 'none');
		$('#formSubirArchivo #seleccionarInicio #sel_col_inicio').empty();
		$('#formSubirArchivo #crearDataTemporal').css('display', 'none');
		$('#formCrearPreguntas #hf_upload_dir').val('');
		$('#formCrearPreguntas #hf_archivo').val('');
		$('#formCrearPreguntas #hf_col_inicio').val('0');
	}


	function seleccionarExcel(myUploader){
		var texto = $(myUploader).val();
		$('#formSubirArchivo label.archivoTxt-1').html(texto);
	}


	$(document).ready(function(){
	    var options1 = {
	        target : '',
	        url : 'Plataforma_evaluaciones/subir_excel',
	        type : 'post',
	        dataType : 'json',
	        beforeSubmit : function(){},
	        success : function(data){
	        	var json = JSON.stringify(data);
				json = eval( '(' + json + ')' );
				loaderTgaSolutions(0);
	        	if(json.status == 'SUCCESS'){
	        		llenarSelectInicio(json.upload_dir, json.archivo_xlsx);
	        		$('#seleccionarInicio').css('display', 'block');
	        		$('#crearDataTemporal').css('display', 'none');
	        		$('#formCrearPreguntas #hf_upload_dir').val(json.upload_dir);
	        		$('#formCrearPreguntas #hf_archivo').val(json.archivo_xlsx);
	        		
	        	}
	        	if(json.status == 'ERROR'){
	        		reestablecerFormulario();
	        		modalMensajeError('#modalProceso_msg', json.titulo, json.mensaje, 'Continuar');
	        	}
	    	}
	    };
	    $('#formSubirArchivo').submit(function(){
	        $(this).ajaxSubmit(options1);
	        return false;
	    });
	});


	$(document).ready(function(){
	    var options = {
	        target : '',
	        url : 'Plataforma_evaluaciones/crear_preguntas',
	        type : 'post',
	        dataType : 'json',
	        beforeSubmit : function(){},
	        success : function(data){
	        	var json = JSON.stringify(data);
				json = eval( '(' + json + ')' );
				loaderTgaSolutions(0);
	        	if(json.status == 'SUCCESS'){
	        		reestablecerFormulario();
	        		modalMensajeError('#modalProceso_msg', json.titulo, json.mensaje, 'Finalizar');
	        	}
	    	}
	    };
	    $('#formCrearPreguntas').submit(function(){
	        $(this).ajaxSubmit(options);
	        return false;
	    });
	});


</script>