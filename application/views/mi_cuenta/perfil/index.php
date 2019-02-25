<?php
$idUser = 1;

$tUser = array('','Normal','Administrador','Super adminitrador');
$query = $this->db->query("SELECT nombre, email, tipoUser
							 FROM tga_user
							WHERE idUser = $idUser;");
if ($query->num_rows() > 0){
    $row         = $query->row();
    $nombre      = $row->nombre;
    $email       = $row->email;
    $tipoUser    = $tUser[$row->tipoUser];
}else{
    exit;
}

?>
<section class="contenido contenido-min perfil">
	<form id="miForm" action="<?php print(base_url());?>mi_cuenta/perfil/actualizar" method="post">
		<input type="hidden" name="todo" value="<?php print(md5($idUser.$nombre.$email.$tipoUser));?>">

		<h2>Mis datos de perfil</h2>
		<p class="dato-preview">ID: <?php print($idUser);?></p>
		<div class="input-group mb-3">
		  <div class="input-group-prepend">
		    <span class="input-group-text" id="basic-addon1">Nombre</span>
		  </div>
		  <input type="text" class="form-control" name="nombre" value="<?php printf($nombre);?>" placeholder="Nombre de usuario" aria-label="Nombre de usuario" aria-describedby="basic-addon1">
		</div>
		<div class="input-group mb-3">
		  <div class="input-group-prepend">
		    <span class="input-group-text" id="basic-addon1">Email</span>
		  </div>
		  <input type="text" class="form-control" name="email" value="<?php echo $email;?>">
		</div>
		<div class="input-group mb-3">
		  <div class="input-group-prepend">
		    <span class="input-group-text" id="basic-addon1">Tipo usuario</span>
		  </div>
		  <input type="text" class="form-control" name="tipoUser" value="<?php print($tipoUser);?>">
		</div>
		<div class="input-group">
		  <div class="input-group-prepend">
		    <span class="input-group-text">Actualizar password</span>
		  </div>
		  <input type="password" aria-label="First name" name="pascode1" class="form-control" placeholder="Password" aria-label="Password">
		  <input type="password" aria-label="Last name" name="pascode2" class="form-control" placeholder="Repetir password" aria-label="Repetir password">
		</div>
		<button type="button" class="btn btn-primary btn-sm" onclick="clicForm();">Actualizar mis datos</button>
	</form>
</section>
<script type="text/javascript">
$(document).ready(function() {
    var options = {
        target:        '#oculto',
        beforeSubmit:  showRequest,
        success:       showResponse,
        timeout:       240000
    };

    $('#miForm').ajaxForm(options);
});

function clicForm(){
	loaderTgaSolutions(1);
	$("#miForm").submit();
}
</script>
