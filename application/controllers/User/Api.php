<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Api extends REST_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('DAO');
    }

    public function registro_post()
    {
        if (count($this->post())==0 || count($this->post())>5) {
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>null,
                "validations"=>array(
                    "nombres"=>"Requerido,Tiene que ser menor a 80 caracteres",
                    "apellidos"=>"Requerido,Tiene que ser menor a 80 caracteres",
                    "email"=>"Requerido,Tiene que ser un correo valido",
                    "password"=>"Requerido,Tiene que ser mayor a 5 caracteres",
                    "repetir_password"=>"Requerido,Tiene que ser igual al password"
                ),
                "data"=>null
            );
            count($this->post())>5  ? $response["message"]="Demasiaos datos enviados" : $response["message"]="Datos no enviados";

        }else{
            $this->form_validation->set_data($this->post());
            $this->form_validation->set_rules('nombres','Nombres','required|min_length[1]|max_length[80]');
            $this->form_validation->set_rules('nombres','Nombres','required|min_length[1]|max_length[80]');
            $this->form_validation->set_rules('email','Correo','required|valid_email');
            $this->form_validation->set_rules('password','Contrase&ntilde;a','required|min_length[5]');
            $this->form_validation->set_rules('password','Repetir contrase&ntilde;a','required|min_length[5]|matches[password]');
        }
    }

}
