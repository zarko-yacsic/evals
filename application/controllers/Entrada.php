<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entrada extends CI_Controller {
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
    }
    public function index(){
        if (isset($_SESSION['idUser'])) {
            ?>
            <script type="text/javascript">window.location="<?php print(base_url());?>home";</script>
            <?php
            exit;
        }

        $this->load->view('pre_body');

        $data["sasas"] = "asasas";
        $this->load->view("entrada",$data);

        $this->load->view('post_body');
    }
    public function login(){
        $email  = trim($this->input->post('email'));
        $pass   = md5(trim($this->input->post('password'))."+alfonsito");

        if ($this->Tgasolutions->validar_correo($email)==0) {
            ?>
            <script type="text/javascript">
            mensajesTgaSolutions(3,"Correo mal ingresado","Se encontro un detalle en el correo se sugiere volver a escribir.");
            </script>
            <?php
             exit;
        }

        $query = $this->db->query("SELECT idUser
                                     FROM tga_user
                                    WHERE passcode = '$pass'
                                      AND email    = '$email';");
        if ($query->num_rows() > 0){
            $row                 = $query->row();
            $_SESSION['idUser']  = $row->idUser;
            ?>
            <script type="text/javascript">window.location="<?php print(base_url());?>home";</script>
            <?php
            exit;
        }else{
            ?>
            <script type="text/javascript">
            mensajesTgaSolutions(3,"Usuario no encontrado","El usuario no fue encontrado, puede que el email o el password esten mal escritos.");
            </script>
            <?php
             exit;
        }
        # --------
    }
    public function salir(){
        session_destroy();
        ?>
        <script type="text/javascript">window.location="<?php print(base_url());?>";</script>
        <?php
    }
}
