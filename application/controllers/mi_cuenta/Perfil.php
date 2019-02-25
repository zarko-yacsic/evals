<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfil extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->library('user_agent');
        $this->load->database();
        $this->load->helper('cookie');
        session_start();

        $this->load->model('Tgasolutions');
        # ------------------ -------------------
        $this->Tgasolutions->usuario();
        $this->Tgasolutions->permisos();
    }
    public function index(){
        $this->load->view('pre_body');

        $data["hoja"] = "home";
        $this->load->view('header',$data);

        $data["sasas"] = "asasas";
         $this->load->view('mi_cuenta/perfil/index',$data);

        $this->load->view('post_body');
    }
    public function actualizar(){
        $idUser      =    $_SESSION['idUser'];
        $nombre      =    trim($this->input->post('nombre'));
        $pascode1    =    trim($this->input->post('pascode1'));
        $pascode2    =    trim($this->input->post('pascode2'));

        # Validar el nombre
        if (strlen($nombre)<5) {
            ?>
            <script type="text/javascript">
            mensajesTgaSolutions(3,"Nombre","Tiene que ingresar su nombre y apellido.");
            </script>
            <?php
            exit;
        }

        $resultado = $this->Tgasolutions->valida_texto($nombre,"abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ");
        if ($resultado[0]==0) {
            ?>
            <script type="text/javascript">
            mensajesTgaSolutions(3,"Nombre","Solo se permiten letras y espacios (a-Z) para escribir el nombre y apellido.<br><br>El carácter no permitido es: <?php echo $resultado[1];?>");
            </script>
            <?php
            exit;
        }

        # Validar el password
        $pascode = "";
        if (strlen($pascode1) > 0) {
            if ($pascode1 != $pascode2) {
                ?>
                <script type="text/javascript">
                mensajesTgaSolutions(3,"PASSWORD","Los dos password ingresados tienen que ser iguales, vualva a escribir nuevamente los password.");
                </script>
                <?php
                exit;
            }
            $pascode = md5($pascode1."+alfonsito");
            $pascode = ", passcode  = '$pascode'";
            if (strlen($pascode1) < 7) {
                ?>
                <script type="text/javascript">
                mensajesTgaSolutions(3,"PASSWORD","El password tiene que tener minimo 6 caracteres.");
                </script>
                <?php
                exit;
            }
        }

        $SQL = " UPDATE tga_user
                  SET nombre = '$nombre'
                      $pascode
                WHERE idUser = $idUser;";
        if ($this->db->query($SQL)===true) {
            ?>
            <script type="text/javascript">
            mensajesTgaSolutions(4,"DATOS GUARDADOS","Los datos fueron guardados.");
            </script>
            <?php
            exit;
        }else{
            ?>
            <script type="text/javascript">
            mensajesTgaSolutions(3,"ERROR","Lo sentimos vuelva a intentar.");
            </script>
            <?php
            exit;
        }
    }
}
