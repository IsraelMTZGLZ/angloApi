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

    function aspirante_post(){
        if (count($this->post())==0 || count($this->post())>5) {
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>null,
                "validations"=>array(
                    "persona"=>"Requerido,Tiene que exisir",
                    "telefono"=>"Requerido,Tiene que ser menor a 20 caracteres",
                    "fecha"=>"Requerido,Tiene que ser una fecha valida",
                    "genero"=>"Opcional,Eso tiene que se 'Femenino' o Masculino'",
                    "ciudad"=>"Requerido",
                ),
                "data"=>null
            );
            count($this->post())>5  ? $response["message"]="Demasiados datos enviados" : $response["message"]="Datos no enviados";

        }else{
            $this->form_validation->set_data($this->post());
            $this->form_validation->set_rules('persona','Fk persona','callback_check_persona');
            $this->form_validation->set_rules('telefono','Telefono','required|min_length[1]|max_length[20]');
            $this->form_validation->set_rules('fecha','Fecha de nacimiento','required');
            $this->form_validation->set_rules('genero','Genero','callback_check_gender');
            $this->form_validation->set_rules('ciudad','Ciudad de origen','required');

            if ($this->form_validation->run()==false) {
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Revisa las validaciones",
                    "validations"=>$this->form_validation->error_array(),
                    "data"=>null
                ); 
            }
            else{
               
                $data['Persona']=array(
                    "generoPersona"=>$this->post('genero')
                );

                $data['Aspirante']=array(
                    "fechaNacimientoAspirante"=>$this->post('fecha'),
                    "telefonoAspirante"=>$this->post('telefono'),
                    "ciudadAspirante"=>$this->post('ciudad'),
                    "fkPersona"=>$this->post('persona')
                );

                $response = $this->DAO->insertData('Tb_Aspirantes',$data['Aspirante']);
                $response = $this->DAO->updateData('Tb_Personas',$data['Persona'],array("idPersona"=>$this->post('persona')));

            }
        }
        $this->response($response,200);
    }

    function aspiranteEleccion_post(){
        if (count($this->post())==0 || count($this->post())>2) {
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>null,
                "validations"=>array(
                    "aspirante"=>"Requerido",
                    "institucion"=>"Requerido"
                ),
                "data"=>null
            );
            count($this->post())>2  ? $response["message"]="Demasiados datos enviados" : $response["message"]="Datos no enviados";

        }else{
            $this->form_validation->set_data($this->post());
            $this->form_validation->set_rules('aspirante','id Aspirante','callback_check_institucion');
            $this->form_validation->set_rules('institucion','institucion Universidad','required');
            
            if ($this->form_validation->run()==false) {
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Revisa las validaciones",
                    "validations"=>$this->form_validation->error_array(),
                    "data"=>null
                ); 
            }
            else{
               
                $data=array(
                    "programaDeInteres"=>$this->post('institucion')
                );

                $response = $this->DAO->updateData('Tb_Aspirantes',$data,array("idAspirante"=>$this->post('aspirante')));

            }
        }
        $this->response($response,200);
    }

    function check_persona($str){
        if (!$str) {
            $this->form_validation->set_message('check_persona','The {field} campo es requerido');
            return false;
        }
        
        $itemExist=$this->DAO->selectEntity('Tb_Personas',array('idPersona'=>$str),true);
        if (!$itemExist) {
            $this->form_validation->set_message('check_persona','The {field} campo no existe');
            return false;
        }else{
            return true;
        } 
        
    }

    function check_institucion($str){
        if (!$str) {
            $this->form_validation->set_message('check_institucion','The {field} campo es requerido');
            return false;
        }
        
        $itemExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$str),true);
        if (!$itemExist) {
            $this->form_validation->set_message('check_institucion','The {field} campo no existe');
            return false;
        }else{
            return true;
        } 
        
    }

    function check_gender($str){
        if (!$str) {
            return true;
        }

        switch ($str) {
            case 'Femenino':
                return true;
                break;
            case 'Masculino':
                return true;
                break;
            default:
                $this->form_validation->set_message('check_gender','The {field} campo tiene que ser la palabra "Femenino" or "Masculino"');
                return false;
                break;
        }

    }

    public function check_noRequerido($str){
        if (!$str) {
            return true;
        }else{
            $this->form_validation->set_message('check_noRequerido','El {field} campo no es requerido');
            return false;
        }

    }

}
