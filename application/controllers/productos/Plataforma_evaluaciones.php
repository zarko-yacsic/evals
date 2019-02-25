<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Plataforma_evaluaciones extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->library('spreadsheet_reader');
        $this->load->library('user_agent');
        $this->load->database('evaluaciones');
        $this->load->dbforge();
        $this->load->helper('cookie');
        session_start();
    }


    public function index(){
        $this->load->view('pre_body');
        $data["hoja"] = "home";
        $this->load->view('header',$data);
        $data["sasas"] = "asasas";
        $this->load->view('productos/plataforma_evaluaciones/index',$data);
        $this->load->view('post_body');
    }


    public function subir_excel(){
        $fileName = $_FILES['archivo']['name'];
        $fileSize = $_FILES['archivo']['size'];
        $target_dir = 'excel';
        $target_file = $_SERVER['DOCUMENT_ROOT'] . '/' . $target_dir . '/' . basename($fileName);
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $mensaje = 'Error al subir archivo Excel seleccionado.';
        $status = 'ERROR';
        $archivo_xlsx = '';
        if(move_uploaded_file($_FILES['archivo']['tmp_name'], $target_file)){
            $archivo_xlsx = md5($target_file) . '.xlsx';
            $target_file_new = $_SERVER['DOCUMENT_ROOT'] . '/' . $target_dir . '/' . $archivo_xlsx;
            if(rename($target_file, $target_file_new)){
                $mensaje = 'Se ha subido correctamente el archivo Excel seleccionado.';
                $status = 'SUCCESS';
            }
        }
        $data = array(
            'status' => $status,
            'titulo' => 'Plataforma de evaluaciones',
            'mensaje' => $mensaje,
            'archivo_xlsx' => $archivo_xlsx,
            'upload_dir' => $target_dir
        );
        $output = json_encode($data);
        echo $output;
    }


    public function obtener_columnas_excel(){
        if(isset($_GET['xls_dir']) && isset($_GET['xls_file'])){
            $xls_dir = $_GET['xls_dir'];
            $xls_file = $_GET['xls_file'];
            $xls_url = $xls_dir . '/' . $xls_file;
            $xls_reader = new SpreadsheetReader($xls_url);
            $a = 0; $b = 0; $totalColumnas = 0;
            $data = array();

            foreach ($xls_reader as $row){
                $a++;
                $b = 0;
                $totalColumnas = count($row)+1;
                for ($i = 0; $i < count($row); $i++){
                    $b++;
                    $data[$a][$b] = $row[$i];
                }
            }
            $columnas = $totalColumnas;

            /* Con esto se evitan errores de undefined offset */
            if (!isset($data[1][$columnas]) || !$data[1][$columnas]){
                $data[1][$columnas] = '';
            }
            if (!isset($data[2][$columnas]) || !$data[2][$columnas]){
                $data[2][$columnas] = '';
            }
            if ($data[1][$columnas] == '' && $data[2][$columnas] == ''){
                $columnas = $columnas - 1;
            }
            
            $letras = preg_split("/[\,]+/", 'X,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z');
            $columnas_excel = array(); $d = 0; $e = 0; $h = 0;
            for ($b = 1; $b <= $columnas; $b++){
                $d ++;
                if ($d > 26){
                    $e ++;
                    $d = 1;
                }
                if ($e == 0){
                    $columnas_excel[$h] = $letras[$d];
                }
                else{
                    $columnas_excel[$h] = $letras[$e] . $letras[$d];
                }
            $h++;
            }
            
            if(count($columnas_excel) > 0){
                $mensaje = 'Obteniendo nombres de las columnas del Excel...';
                $json_output = array('status' => 'SUCCESS', 'mensaje' => $mensaje, 'columnas_xls' => $columnas_excel);
            }
            else{
                $mensaje = 'Error al obtener nombres de las columnas del Excel.';
                $json_output = array('status' => 'ERROR', 'mensaje' => $mensaje);
            }
            $output = json_encode($json_output);
            echo $output;
        }
    }


    public function crear_preguntas(){
        $columna_inicio = trim($_POST['hf_col_inicio']);
        
        if (is_numeric($columna_inicio)){
            $archivo = $_POST['hf_archivo'];
            $upload_dir = $_POST['hf_upload_dir'];
            $idUser = $_SESSION['idUser'];
            $cadena = '';
            $ID_EVALUACION = 0;

            $preguntaUno   = $columna_inicio;
            $data          = array();
            $pregunta      = array();
            $pregunta1     = array();
            $pregunta2     = array();
            $a             = 0;
            $b             = 0;
            $Reader = new SpreadsheetReader($upload_dir . '/' . $archivo);
            
            foreach ($Reader as $Row){
                $a ++;
                $b = 0;
                $totalColumnas = count($Row)+1;
                for ($i=0; $i < count($Row); $i++){
                    $b ++;
                    $data[$a][$b] = $Row[$i];
                }
            }
            $ancho   = $totalColumnas;
            $alto    = count($data);

            if((!isset($data[1][$ancho]) || empty($data[1][$ancho])) && (!isset($data[2][$ancho]) || empty($data[2][$ancho]))){
                $ancho = $ancho - 1;
            }

            # ======================================
            # Sacamos valores de campos
            # ======================================
            for ($a = 1; $a <= 2; $a++){
                for ($i = $preguntaUno; $i <= $ancho; $i++){
                    if ($data[1][$i] != ''){
                        $pregunta1[$i] = 1;
                    }
                    else{
                        $pregunta1[$i] = 0;
                    }
                    if ($data[2][$i] != ''){
                        $pregunta2[$i] = 1;
                    }
                    else{
                        $pregunta2[$i] = 0;
                    }
                }
            }
            $c                  = 0;
            $d                  = 0;
            $nomP               = '';
            $tipoP              = array();
            $preguntaX          = array();
            $preguntaN          = array();
            $preguntaSN         = array();
            $preguntaNom        = array();
            $subPreguntaNom     = array();
            $preguntaNomText    = array();
            $subPreguntaNomText = array();

            for ($i=$preguntaUno;$i<=$ancho;$i++){
                $viene = 0;
                if ($pregunta1[$i]<$ancho){
                    $viene = 1;
                }
                $valor              = $data[2][$i];
                $valor              = $this->limpiar_carcteres($valor);
                $valor              = utf8_decode($valor);
                $subPreguntaNom[$i] = $valor;
                $valor = $i + $viene;

                if(!isset($pregunta1[$valor]) || empty($pregunta1[$valor])){
                    $pregunta1[$valor] = '';
                }
                if (($pregunta1[$i] == 1 && $pregunta1[$valor] == 1) || $i == $ancho){
                    $d = 0;
                    $c ++;
                    $tipoP[$i]      = 1;
                    $preguntaX[$i]  = 'P' . $c;
                    $preguntaN[$i]  = $c;
                    $preguntaSN[$i] = 0;
                    $valor              = $data[1][$i];
                    $valor              = $this->limpiar_carcteres($valor);
                    $valor              = utf8_decode($valor);
                    $nomP               = $valor;
                    $preguntaNom[$i]    = $nomP;
                }
                else if ($pregunta1[$i] == 1){
                    $d = 0;
                    $c ++;
                    $d ++;
                    $tipoP[$i]       = 2;
                    $preguntaX[$i]   = 'P' . $c . '_' . $d;
                    $preguntaN[$i]   = $c;
                    $preguntaSN[$i]  = $d;
                    $valor           = $data[1][$i];
                    $valor           = $this->limpiar_carcteres($valor);
                    $valor           = utf8_decode($valor);
                    $nomP            = $valor;
                    $preguntaNom[$i] = $nomP;
                }
                else{
                    $d ++;
                    $tipoP[$i]       = 2;
                    $preguntaX[$i]   = 'P' . $c . '_' . $d;
                    $preguntaN[$i]   = $c;
                    $preguntaSN[$i]  = $d;
                    $preguntaNom[$i] = $this->limpiar_carcteres($nomP);
                }
            }

            $e = 0;
            $nomP = '';
            for ($i=1;$i<$preguntaUno;$i++){
                $e ++;
                $tipoP[$i]      = 0;
                $preguntaX[$i]  = 'M' . $e;
                $preguntaN[$i]  = 0;
                $preguntaSN[$i] = 0;
                $valor          = $data[1][$i];

                if ($valor==''){
                    $preguntaNom[$i] = $nomP.' '.utf8_decode($this->limpiar_carcteres($data[2][$i]));
                }
                else{
                    $valor           = str_replace("'", "´", $valor);
                    $valor           = str_replace('"', "´", $valor);
                    $valor           = str_replace('Ñ', "N", $valor);
                    $valor           = str_replace('ñ', "n", $valor);
                    $valor           = str_replace('á', "a", $valor);
                    $valor           = str_replace('é', "e", $valor);
                    $valor           = str_replace('í', "i", $valor);
                    $valor           = str_replace('ó', "o", $valor);
                    $valor           = str_replace('ú', "u", $valor);
                    $valor           = utf8_decode($valor);
                    $nomP            = $valor;
                    $preguntaNom[$i] = $nomP;
                    $r = $i+1;
                    if ($data[1][$r]==''){
                        $preguntaNom[$i] = $nomP.' '.utf8_decode($this->limpiar_carcteres($data[2][$i]));
                    }
                }
                $valor              = $data[2][$i];
                $valor              = str_replace("'", "´", $valor);
                $valor              = str_replace('"', "´", $valor);
                $valor              = str_replace('Ñ', "N", $valor);
                $valor              = str_replace('ñ', "n", $valor);
                $valor              = str_replace('á', "a", $valor);
                $valor              = str_replace('é', "e", $valor);
                $valor              = str_replace('í', "i", $valor);
                $valor              = str_replace('ó', "o", $valor);
                $valor              = str_replace('ú', "u", $valor);
                $valor              = $this->limpiar_carcteres($valor);
                $valor              = utf8_decode($valor);
                $subPreguntaNom[$i] = $valor;
            }

            # ==============================
            # Info por columnas
            # ==============================
            $preguntaLargo  = array();
            for ($a = 3; $a <= $alto; $a++) {
                for ($i=1; $i <= $ancho; $i++) {
                    if(!isset($data[$a][$i]) || empty($data[$a][$i])){
                        $data[$a][$i] = '';
                    }
                    if(!isset($preguntaLargo[$i]) || empty($preguntaLargo[$i])){
                        $preguntaLargo[$i] = strlen(utf8_decode($data[$a][$i]));
                    }
                    if ((strlen(utf8_decode($data[$a][$i])) > $preguntaLargo[$i])){
                        $preguntaLargo[$i] = strlen(utf8_decode($data[$a][$i]));
                    }
                }
            }

            $campos = '';
            for ($i = 1; $i <= $ancho; $i++) {
                $campoNom = $preguntaX[$i];
                $largoCampo = $preguntaLargo[$i];
                if ($largoCampo <= 250){
                    $campos = $campos."`$campoNom` varchar($largoCampo) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,";
                }
                else{
                    $campos = $campos."`$campoNom` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,";
                }
            }

            # =================================================
            # Crear tabla
            # =================================================
            $tabla_temp_01 = 'e_temporal_' . $idUser;
            $tabla_temp_02 = 'e_temporal_preguntas_' . $idUser;
            $tabla_temp_03 = 'e_temporal2_' . $idUser;
            
            // Borrar temporales si existieran...
            $drop_table_1 = $this->db->query("DROP TABLE IF EXISTS " . $tabla_temp_01 . ";");
            $drop_table_2 = $this->db->query("DROP TABLE IF EXISTS " . $tabla_temp_02 . ";");
            $drop_table_3 = $this->db->query("DROP TABLE IF EXISTS " . $tabla_temp_03 . ";");

            // Crear $ tabla_temp_01...
            $create_table_1 = $this->db->query("CREATE TABLE `$tabla_temp_01`(
                `idArmado` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, 
                `idEncuesta` int(11) UNSIGNED NULL DEFAULT NULL, 
                $campos 
                `guardaRegistro` int(1) UNSIGNED NULL DEFAULT 0, 
                `validaParseo` int(1) UNSIGNED NULL DEFAULT 0, 
                `pasarMatriz` int(1) UNSIGNED NULL DEFAULT 0, 
                PRIMARY KEY (`idArmado`) USING BTREE, 
                INDEX `idEncuesta`(`idEncuesta`) USING BTREE, 
                INDEX `guardaRegistro`(`guardaRegistro`) USING BTREE, 
                INDEX `validaParseo`(`validaParseo`) USING BTREE, 
                INDEX `pasarMatriz`(`pasarMatriz`) USING BTREE) 
                ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;");

            if(!$create_table_1){
                echo 'Error al crear la tabla ' . $tabla_temp_01 . ' :<br>';
                echo '<pre>';
                print_r($this->db->error());
                echo '</pre>';
                exit;
            }

            // Crear $ tabla_temp_02...
            $create_table_2 = $this->db->query("CREATE TABLE `$tabla_temp_02`(
                `idPreguntas` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, 
                `nomCampo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, 
                `numCampo` int(11) UNSIGNED NULL DEFAULT NULL, 
                `tipo` int(1) UNSIGNED NULL DEFAULT 0, 
                `numpregunta` int(11) UNSIGNED NULL DEFAULT 0, 
                `numSubPregunta` int(11) UNSIGNED NULL DEFAULT 0, 
                `pregunta` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, 
                `item` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, 
                `preguntaParseada` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, 
                `itemParseado` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, 
                `idPreguntaE` int(11) UNSIGNED NOT NULL DEFAULT 0, 
                `idPregunta` int(11) UNSIGNED NULL DEFAULT 0, 
                `idCategoria` int(11) UNSIGNED NULL DEFAULT 0, 
                `clavePregunta` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, 
                `claveItem` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, 
                `validarPregunta` int(1) UNSIGNED NOT NULL DEFAULT 0, 
                `validarRespuesta` int(1) UNSIGNED NULL DEFAULT 0, 
                PRIMARY KEY (`idPreguntas`) USING BTREE, 
                UNIQUE INDEX `nomCampo`(`nomCampo`) USING BTREE, 
                INDEX `numCampo`(`numCampo`) USING BTREE, 
                INDEX `numpregunta`(`numpregunta`) USING BTREE, 
                INDEX `numSubPregunta`(`numSubPregunta`) USING BTREE, 
                INDEX `idCategoria`(`idCategoria`) USING BTREE, 
                INDEX `idPregunta`(`idPregunta`) USING BTREE, 
                INDEX `idSubPregunta`(`idPreguntaE`) USING BTREE, 
                INDEX `tipo`(`tipo`) USING BTREE, 
                INDEX `validarPregunta`(`validarPregunta`) USING BTREE, 
                INDEX `clave`(`clavePregunta`, `claveItem`) USING BTREE, 
                INDEX `validarRespuesta`(`validarRespuesta`) USING BTREE) 
                ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;");

            if(!$create_table_2){
                echo 'Error al crear la tabla ' . $tabla_temp_02 . ' :<br>';
                echo '<pre>';
                print_r($this->db->error());
                echo '</pre>';
                exit;
            }

            // Crear $ tabla_temp_03...
            $create_table_3 = $this->db->query("CREATE TABLE `$tabla_temp_03`(
                `idArmado` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, 
                `idEncuesta` int(11) UNSIGNED NULL DEFAULT NULL, 
                $campos 
                `guardaRegistro` int(1) UNSIGNED NULL DEFAULT 0, 
                `validaParseo` int(1) UNSIGNED NULL DEFAULT 0, 
                `pasarMatriz` int(1) UNSIGNED NULL DEFAULT 0, 
                PRIMARY KEY (`idArmado`) USING BTREE, 
                INDEX `idEncuesta`(`idEncuesta`) USING BTREE, 
                INDEX `guardaRegistro`(`guardaRegistro`) USING BTREE, 
                INDEX `validaParseo`(`validaParseo`) USING BTREE, 
                INDEX `pasarMatriz`(`pasarMatriz`) USING BTREE) 
                ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;");

            if(!$create_table_3){
                echo 'Error al crear la tabla ' . $tabla_temp_03 . ' :<br>';
                echo '<pre>';
                print_r($this->db->error());
                echo '</pre>';
                exit;
            }

            # =================================================
            # Insertar campos en las tablas
            # =================================================
            $insert_1 = '';
            $totalInsert_1 = 0;
            $f = 0;
            for ($a = 3; $a <= $alto; $a++){
                $f++;
                $campito = '';
                for ($i = 1; $i <= $ancho; $i++){
                    $valor = utf8_decode($data[$a][$i]);
                    $valor = str_replace("'", "´", $valor);
                    $valor = str_replace('"', "´", $valor);
                    $campito = $campito . ",'" . $valor . "'";
                }
                $insert_1 = $this->db->query("INSERT INTO $tabla_temp_01 VALUES ($f, $ID_EVALUACION $campito, 0, 0, 0);");
                if($insert_1){
                    $totalInsert_1++;
                }
            }

            # ==============================
            # Valor de los campos
            # ==============================
            $insert_2 = '';
            $totalInsert_2 = 0;
            for ($i = 1; $i <= $ancho; $i++){
                $insert_2 = $this->db->query("INSERT INTO $tabla_temp_02 (numCampo, nomCampo, tipo, numpregunta, numSubPregunta, pregunta, item, 
                    preguntaParseada, itemParseado, clavePregunta, claveItem) VALUES (
                    $i, '" . $preguntaX[$i] . "', " . $tipoP[$i] . ", " . $preguntaN[$i] . ", " . $preguntaSN[$i] . ", '" . $preguntaNom[$i] . "', 
                    '" . $subPreguntaNom[$i] . "', '" . $preguntaNom[$i] . "', '" . $subPreguntaNom[$i] . "', '" . md5($preguntaNom[$i]) . "', 
                    '" . md5($preguntaNom[$i] . $subPreguntaNom[$i]) . "');");
                if($insert_2){
                    $totalInsert_2++;
                }
            }
            $status = 'SUCCESS';
            $mensaje = 'Se ha creado correctamente una nueva evaluación.';
            $data = array('status' => $status, 'titulo' => 'Plataforma de evaluaciones', 'mensaje' => $mensaje);
            $output = json_encode($data);
            echo $output;
        }
    }


    private function limpiar_carcteres($valor){
        $valor = str_replace("'", "´", $valor);
        $valor = str_replace('"', "´", $valor);
        for ($i=0;$i<12;$i++){
            $valor = str_replace("´´", "´", $valor);
            $valor = str_replace('  ', " ", $valor);
            $valor = str_replace('  ', " ", $valor);
            $valor = str_replace('__', "_", $valor);
            $valor = str_replace('__', "_", $valor);
            $valor = str_replace('--', "_", $valor);
            $valor = str_replace('Ñ', "N", $valor);
            $valor = str_replace('ñ', "n", $valor);
            $valor = str_replace('á', "a", $valor);
            $valor = str_replace('é', "e", $valor);
            $valor = str_replace('í', "i", $valor);
            $valor = str_replace('ó', "o", $valor);
            $valor = str_replace('ú', "u", $valor);
        }
        return $valor;
    }

}
?>