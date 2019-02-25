<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
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
        $this->load->view('home/index',$data);

        $this->load->view('post_body');
    }
    public function demo(){
        print("este es un demo numero 1");
    }
    public function demo2(){
        print("este es el demo numero 2");
    }
}
