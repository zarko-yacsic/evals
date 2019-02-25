<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proyectos extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->library('user_agent');
        $this->load->database();
        $this->load->helper('cookie');
        session_start();
    }
    public function index(){
        $this->load->view('pre_body');

        $data["hoja"] = "home";
        $this->load->view('header',$data);


        $query  = $this->db->query("SELECT idInmobiliaria,nombre 
                                                FROM tga_inmobiliarias 
                                            WHERE estado = 1");
        $data['inmobiliarias']  = $query->result();
        $this->load->view('tga_global/proyectos/index',$data);
        $this->load->view('post_body');
    }
    public function cargaProyectos(){
        $idInmobiliaria = $this->input->post('idInmobiliaria');
        $query              = $this->db->query("SELECT idProyectos,nombre 
                                            FROM tga_proyectos 
                                    WHERE idInmobiliaria  = ".$idInmobiliaria);
        $proyectos          = $query->result();
        foreach ($proyectos as $key) {?>
            <div onclick="muestraProyecto(<?php echo $key->idProyectos?>);"><?php echo $key->nombre?></div>
        <?php }
    }
    public function muestraProyectos(){
        $accionForm             = $this->input->post('accionForm');
        $idProyecto             = $this->input->post('idProyecto');
        $queryVal           = $this->db->query("SELECT `nombre`,`idRegion`,`idComuna`,`direccion`,
                                                        `url`,`latitud`,`longitud`,`imagen`,`estado`,
                                                        `tipoConstruccion`,`idInmobiliaria`,`financiamiento` 
                                                FROM tga_proyectos 
                                                        WHERE idProyectos = '".$idProyecto."';");
        echo json_encode($queryVal->result());
    }
    public function guardaProyectos(){
        $accionForm             = $this->input->post('accionForm');
        $idPortal               = $this->input->post('idPortal');
        $nombreProyecto         = trim($this->input->post('nombreProyecto'));
        $regiones               = $this->input->post('regiones');
        $comunas                = $this->input->post('comunas');
        $direccion              = trim($this->input->post('direccion'));
        $url                    = trim($this->input->post('url'));
        $latitud                = trim($this->input->post('latitud'));
        $longitud               = trim($this->input->post('longitud'));
        $imagen                 = trim($this->input->post('imagen'));
        $estado                 =  $this->input->post('estado');
        $tipoConstruccion       =  $this->input->post('tipoConstruccion');
        $idInmobiliaria         = $this->input->post('idInmobiliaria');
        $tipoFinanciamiento     = $this->input->post('tipoFinanciamiento');
        $idProyecto             = $this->input->post('idProyecto');
        if ($nombreProyecto == '') {?>
            <script type="text/javascript">
                mensajesTgaSolutions(3,"Error","falta nombre");
            </script>
        <?php 
            exit;
        }
        if ($regiones == '') {?>
            <script type="text/javascript">
                mensajesTgaSolutions(3,"Error","falta region");
            </script>
        <?php 
            exit;
        }if ($comunas == '') {?>
            <script type="text/javascript">
                mensajesTgaSolutions(3,"Error","falta comuna");
            </script>
        <?php 
            exit;
        }
        if ($direccion == '') {?>
            <script type="text/javascript">
                mensajesTgaSolutions(3,"Error","falta direccion");
            </script>
        <?php 
            exit;
        }

        
        if ($url == '') {?>
            <script type="text/javascript">
                mensajesTgaSolutions(3,"Error","falta url");
            </script>
        <?php 
            exit;
        }
            if ($latitud == '') {?>
            <script type="text/javascript">
                mensajesTgaSolutions(3,"Error","falta latitud");
            </script>
        <?php 
            exit;
        }
        if ($longitud == '') {?>
            <script type="text/javascript">
                mensajesTgaSolutions(3,"Error","falta longitud");
            </script>
        <?php 
            exit;
        }
        if ($imagen == '') {?>
            <script type="text/javascript">
                mensajesTgaSolutions(3,"Error","falta imagen");
            </script>
        <?php 
            exit;
        }

        if (!is_numeric($estado) && $estado > 2) {?>
            <script type="text/javascript">
                mensajesTgaSolutions(3,"Error","estado incorrecto");
            </script>
        <?php 
            exit;
        }

        if (!is_numeric($tipoConstruccion) && $tipoConstruccion > 2) {?>
            <script type="text/javascript">
                mensajesTgaSolutions(3,"Error","tipo Construccion incorrecto");
            </script>
        <?php 
            exit;
        }

        if (!is_numeric($tipoFinanciamiento) && $tipoFinanciamiento > 2) {?>
            <script type="text/javascript">
                mensajesTgaSolutions(3,"Error","tipo Financiamiento incorrecto");
            </script>
        <?php 
            exit;
        }
        if ($accionForm == 1) {

            $queryVal       = $this->db->query("SELECT 1 FROM tga_proyectos WHERE nombre = '".$nombreProyecto."';");
            $tieneNombre    = $queryVal->num_rows();
            if ($tieneNombre == 1) {?>
                <script type="text/javascript">
                    mensajesTgaSolutions(3,"Error","Nombre ya existe");
                </script>
            <?php 
                exit;
            }
            $query = $this->db->query("INSERT INTO tga_proyectos (`nombre`,`idRegion`,`idComuna`,`direccion`,`url`,`latitud`,`longitud`,`imagen`,`estado`,`tipoConstruccion`,`idInmobiliaria`,`financiamiento`)
                                            VALUES ('$nombreProyecto','$regiones','$comunas','$direccion','$url','$latitud','$longitud','$imagen',$estado,$tipoConstruccion,$idInmobiliaria,$tipoFinanciamiento);");
            $query = true;
            if ($query) {?>
                <script type="text/javascript">
                    mensajesTgaSolutions(0,"Felicidades","Proyecto Insertado");
                    selectInmobiliaria(<?php echo $idInmobiliaria;?>)
                </script>
            <?php
            }
        }else{
            $query = $this->db->query("UPDATE  tga_proyectos SET `nombre` = '$nombreProyecto',`idRegion` = '$regiones',`idComuna` = '$comunas',`direccion` = '$direccion',`url` = '$url',`latitud` = '$latitud',`longitud` = '$longitud',`imagen` = '$imagen',`estado` = $estado,`tipoConstruccion` = $tipoConstruccion,`idInmobiliaria` = $idInmobiliaria,`financiamiento` = $tipoFinanciamiento
                                    WHERE idProyectos = $idProyecto;");
            if ($query) {?>
                <script type="text/javascript">
                    mensajesTgaSolutions(0,"Felicidades","Proyecto Actualizado");
                    selectInmobiliaria(<?php echo $idInmobiliaria;?>)
                </script>
            <?php }
        }
    }
}
