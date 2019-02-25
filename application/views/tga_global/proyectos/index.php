
<section class="contenido contenido-proyectos" style="width:30%;float: left;">
	<h2>Proyectos</h2>
    <form name="formInmobiliaria" id="formInmobiliaria" action="<?php echo base_url();?>tga_global/Proyectos/cargaProyectos">
	    <select name="idInmobiliaria" onchange="selectInmobiliaria(this.value)">
	    	<option selected="selected">Seleccione Inmobiliaria</option>
	    	<?php foreach ($inmobiliarias as $key) {?>
	    		<option value="<?php echo $key->idInmobiliaria;?>"><?php echo $key->nombre;?></option>
	    	<?php } ?>
	    </select>
    </form>
    <div id="salida"></div>
</section>
<section class="contenido contenido-form"  style="width:70%;float: right;">
    <div class ="contenidoFormulario">
    	<div onclick="insertaProyecto();">Insertar</div>
    	<div id="actualizaBtn">Actualizar</div>
    	<form name="formProyecto" id="formProyecto" action="<?php echo base_url();?>tga_global/Proyectos/guardaProyectos" type="POST">
	    	<span>Proyecto</span><br>
	    	<input type="hidden" name="idInmobiliaria" id="idInmobiliaria">
	    	<input type="hidden" name="accionForm" id="accionForm" value="1">
	    	<input type="hidden" name="idProyecto" id="idProyecto">
	    	<input type="" name="nombreProyecto" id="nombreProyecto" placeholder="nombreProyecto"> <br>
	    	<select id="regiones" name="regiones" id="regiones">
				<option value="0" selected="selected">Selecciones Region</option>
				<option value="1">1 Tarapaca</option>
				<option value="2">2 Antofagasta</option>
				<option value="3">3 Atacama</option>
				<option value="4">4 Coquimbo</option>
				<option value="5">5 Valparaiso</option>
				<option value="6">6 O'Higgins</option>
				<option value="7">7 Maule</option>
				<option value="8">8 Bio - Bio</option>
				<option value="9">9 Araucania</option>
				<option value="10">10 Los Lagos</option>
				<option value="11">11 Aisen</option>
				<option value="12">12 Magallanes Y Antartica</option>
				<option value="13">13 Metropolitana</option>
				<option value="14">14 Los Rios</option>
				<option value="15">15 Arica y Parinacota</option>
			</select><br>
			<select id="comunas" name="comunas" id="comunas">
				<option value="0" selected="selected">Selecciones Comuna</option>
				<option value="345">Comuna 1</option>
				<option value="1">Comuna 2</option>
				<option value="4">Comuna 3</option>
				<option value="123">Comuna 4</option>
				<option value="122">Comuna 5</option>
				<option value="6">Comuna 6</option>
				<option value="7">Comuna 7</option>
			</select><br>
	    	<input type="" name="direccion" placeholder="direccion" id="direccion"><br>
	    	<input type="" name="url" placeholder="url" id="url"><br>
	    	<input type="" name="imagen" placeholder="imagen" id="imagen"><br>
	    	<input type="" name="latitud" placeholder="latitud" id="latitud"><br>
	    	<input type="" name="longitud" placeholder="longitud" id="longitud"><br>
	    	<select name="estado" id="estado">
	    		<option value="0">Seleccionar estado</option>
	    		<option value="2">Activo</option>
	    		<option value="1">Desactivo</option>
	    	</select> <br>
	    	<p>tipo de construcción</p>
	    	<input type="radio" name="tipoConstruccion" value="1" id="tipoConstruccion1"> Depto
	    	<input type="radio" name="tipoConstruccion" value="2" id="tipoConstruccion2"> Casa
	    	<input type="radio" name="tipoConstruccion" value="3" id="tipoConstruccion3"> Oficina
	    	<p>financiamiento</p>
	    	<input type="radio" name="tipoFinanciamiento" value="1" id="tipoFinanciamiento1"> Con subsidio
	    	<input type="radio" name="tipoFinanciamiento" value="2" id="tipoFinanciamiento2"> Sin subsidio <br><br>
	    	<div onclick="guardaProyecto();" class="btn btn-warning btn-sm" id="btnProyectos">Insertar</div>
    	</form>
    	 <style>
		      /* Always set the map height explicitly to define the size of the div
		       * element that contains the map. */
		      #map {
		        height: 500px;
		        width: 500px;
		      }
		    </style>
    	<div id="map"></div>
    	<img id="imagenProy"></img>
    </div>
</section>
<script type="text/javascript" src="../js/tga_proyectos.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAj4QQJZwz_-3v_mPzjZQPQd9sPUybz_VU&callback=initMap"
    async defer></script>