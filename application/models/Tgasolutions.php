<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tgasolutions extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	function usuario(){
		if (!isset($_SESSION['idUser'])) {
			?>
			<script type="text/javascript">window.location="<?php print(base_url());?>";</script>
			<?php
		}
	}
	function validar_correo($email){
    	if (preg_match('/^[A-Za-z0-9-_.+%]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/',$email)) {
        	return 1;
        }else{
            return 0;
        }
	}
	function permisos(){

	}
	function rut($r){
		$r  = str_replace(" ", "", $r);
		$r  = str_replace("k", "K", $r);
		$r  = str_replace(".", "", $r);
        $r  = str_replace("-", "", $r);
        if (strlen($r)<8 || strlen($r)>9) {return 0;}
        $dv = substr($r,(strlen($r)-1),1);
        $r  = substr($r,0,(strlen($r)-1));
        $rut= $r;
        if(!is_numeric($r)){return 0;}
        $s=1;
        for($m=0;$r!=0;$r/=10)$s=($s+$r%10*(9-$m++%6))%11;
        $v = chr($s?$s+47:75);
        if ($dv!=$v) {return 0;}
        return 1;
	}
	function sin_tilde($texto){
		$search  = array('Á', 'É', 'Í', 'Ó', 'Ú', 'á', 'é', 'í', 'ó', 'ú');
	    $replace = array('A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u');
	    $subject = $texto;
	    $texto   = str_replace($search, $replace, $subject);
	    return $texto;
	}
    function mayuscula_tilde($texto){
        $search  = array('á', 'é', 'í', 'ó', 'ú');
        $replace = array('Á', 'É', 'Í', 'Ó', 'Ú');
        $subject = $texto;
        $texto   = str_replace($search, $replace, $subject);
        return $texto;
    }
    function minuscula_tilde($texto){
        $search  = array('Á', 'É', 'Í', 'Ó', 'Ú');
        $replace = array('á', 'é', 'í', 'ó', 'ú');
        $subject = $texto;
        $texto   = str_replace($search, $replace, $subject);
        return $texto;
    }
    function minuscula_todos($texto){
        $search  = array('Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ');
        $replace = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
        $subject = $texto;
        $texto   = str_replace($search, $replace, $subject);
        return strtolower($texto);
    }
    function mayuscula_todos($texto){
    	$search  = array('Á', 'É', 'Í', 'Ó', 'Ú');
        $replace = array('A', 'E', 'I', 'O', 'U');
        $subject = $texto;
        $texto = str_replace($search, $replace, $subject);
        $search  = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
        $replace = array('A', 'E', 'I', 'O', 'U', 'Ñ');
        $subject = $texto;
        $texto = str_replace($search, $replace, $subject);
        return strtoupper($texto);
    }
    function primera_mayuscula($texto){
        $uno = mb_substr($texto, 0,1,'UTF-8');
        $dos = mb_substr($texto,1,strlen($texto),'UTF-8');
        $search   = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
        $replace  = array('A', 'E', 'I', 'O', 'U', 'Ñ');
        $replace2 = array('Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ');
        $uno = str_replace($search, $replace, $uno);
        $dos = str_replace($replace2, $search, $dos);
        return strtoupper($uno).strtolower($dos);
    }
    function valida_texto($texto,$cadena){
        //$cadena = abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZáéíóúÁÉÍÓÚ
        $permitidos = "".$cadena."";
        $error=0;
        for ($i=0; $i<strlen($texto); $i++){
            if (strpos($permitidos, substr($texto,$i,1))===false){
            	return array(0,substr($texto,$i,1));
            }
        }
        return array(1);
    }
    function validar_fecha($fecha){
        $anio = substr($fecha, 0, 4);

        $salida = 1;
        if (!is_numeric($anio) || $anio<1901) {
        	$anio = substr($fecha, 6, 4);
        	$salida = 2;
	        if (!is_numeric($anio) || $anio<1901) {
	        	return array(0);
	        }
        }

        if ($salida==1) {
        	$mes = substr($fecha, 5, 2);
        }else if ($salida==2) {
        	$mes = substr($fecha, 3, 2);
        }else{
        	return array(0);
        }

        if ( !is_numeric($mes) || strlen($mes) != 2 || $mes < 1 || $mes > 12 ) {
        	return array(0);
        }

        if ($salida==1) {
        	$dia = substr($fecha, 8, 2);
        }else if ($salida==2) {
        	$dia = substr($fecha, 0, 2);
        }else{
        	return array(0);
        }

        if ( !is_numeric($dia) || strlen($dia) != 2 || $dia < 1 || $dia > 31 ) {
        	return array(0);
        }

        # validar fecha
        if (checkdate($mes,$dia,$anio)!=1) {
        	$salida = 0;
        }
        return array($salida,$anio,$mes,$dia,"$anio-$mes-$dia","$dia-$mes-$anio");
    }
    function historia($idUser,$idMenu,$tipoAccion,$detalle){
        date_default_timezone_set('America/Santiago');
        $time      =  time();
        $fecha     = date ("Y-m-d H:i:s", $time);
        $fechaAnio = date ("Y", $time);
        $fechaMes  = date ("n", $time);

        $sql = "INSERT INTO sis_historia (idUser, idMenu, tipoAccion, fecha, fechaAnio, fechaMes, detalle)
                                  VALUES ($idUser,
                                          $idMenu,
                                          $tipoAccion,
                                          '$fecha',
                                          $fechaAnio,
                                          $fechaMes,
                                          '$detalle');";
        $this->db->query($sql);

    }
}












