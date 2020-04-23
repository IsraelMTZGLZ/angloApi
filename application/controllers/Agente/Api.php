<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Api extends REST_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('DAO');
        $this->load->model('UserDAO');
    }

    function agente_get(){
        $id=$this->get('id');
        if (count($this->get())>1) {
            $response = array(
                "status" => "error",
                "status_code" => 409,
                "message" => "Demasiados datos enviados",
                "validations" =>array(
                        "id"=>"Envia Id (get) para obtener un especifico articulo o vacio para obtener todos los articulos"
                ),
                "data"=>null
            );
        }else{
            if ($id) {
                $data = $this->DAO->selectEntity('Vw_Agente',array('usuario'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('Vw_Agente',null,false);
            }
            if ($data) {
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "Articulo Cargado correctamente",
                    "validations" =>null,
                    "data"=>$data
                );
            }else{
                $response = array(
                    "status" => "error",
                    "status_code" => 409,
                    "message" => "No se recibio datos",
                    "validations" =>null,
                    "data"=>null
                );
            }
        }
        $this->response($response,200);
    }

    function agenteByIdAgente_get(){
        $id=$this->get('id');
        if (count($this->get())>1) {
            $response = array(
                "status" => "error",
                "status_code" => 409,
                "message" => "Demasiados datos enviados",
                "validations" =>array(
                        "id"=>"Envia Id (get) para obtener un especifico articulo o vacio para obtener todos los articulos"
                ),
                "data"=>null
            );
        }else{
            if ($id) {
                $data = $this->DAO->selectEntity('Vw_Agente',array('agente'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('Vw_Agente',null,false);
            }
            if ($data) {
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "Articulo Cargado correctamente",
                    "validations" =>null,
                    "data"=>$data
                );
            }else{
                $response = array(
                    "status" => "error",
                    "status_code" => 409,
                    "message" => "No se recibio datos",
                    "validations" =>null,
                    "data"=>null
                );
            }
        }
        $this->response($response,200);
    }

    function agente_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 4){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(
                  "persona"=>"Requerido,It has to exist",
                  "genero"=>"Opcional,Eso tiene que se 'Femenino' o Masculino'",
                  "numeroEmp"=>"Requerido, Tiene que se unico",
                  "puesto"=>"Requerido, Se requiere el puesto"
                )
            );
            count($this->post())>4  ? $response["message"]="Demasiados datos enviados" : $response["message"]="Datos no enviados";

        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('persona','Fk persona','callback_check_persona');
            $this->form_validation->set_rules('genero','Genero','callback_check_gender');
            $this->form_validation->set_rules('numeroEmp','Numero empleado','callback_check_numUnique');
            $this->form_validation->set_rules('puesto','Puesto','required');
            
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
    
                $data['Agente']=array(
                    "numeroEmpleado"=>$this->post('numeroEmp'),
                    "puestoAgente"=>$this->post('puesto'),
                    "fkPersona"=>$this->post('persona')
                );
    
                
                $response = $this->DAO->updateData('Tb_Personas',$data['Persona'],array("idPersona"=>$this->post('persona')));
                $response = $this->DAO->insertData('Tb_Agentes',$data['Agente']);

            }
            
             
        }

        $this->response($response,200);
    }

    //cambiar status agnete
    public function agenteStatus_put()
    {
        $data = $this->put();

        if(count($data) == 0 || count($data) > 1){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No datos recibidos' : 'Demasiados datos recibidos',
                "data"=>null,
                "validations"=>array(
                  "usuario"=>"Requerido,It has to exist"
                )
            );
            count($this->put())>1  ? $response["message"]="Demasiados datos enviados" : $response["message"]="Datos no enviados";

        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('usuario','Usuario','callback_check_usuario');
            
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
                $item = $this->DAO->selectEntity('Vw_Agente',array('usuario'=>$this->put('usuario')),true);

                if($item->statusU == 'Activo'){
                    $statusChange='Inactivo';
                }else{
                    $statusChange='Activo';
                }

                $data=array(
                    "statusUsuario"=>$statusChange
                );
    
    
                
                $response = $this->DAO->updateData('Tb_Usuarios',$data,array("idUsuario"=>$this->put('usuario')));

            }
            
             
        }

        $this->response($response,200);
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

    public function check_typoOauth($str){
        if (!$str) {
            $this->form_validation->set_message('check_typoOauth','El {field} campo tiene que se la palabra word "Facebook", "Google" or "Registro"');
            return false;
        }

        switch ($str) {
            case 'Facebook':
                return true;
                break;
            case 'Google':
                return true;
                break;
            case 'Registro':
                return true;
                break;
            default:
                $this->form_validation->set_message('check_typoOauth','El {field} campo tiene que se la palabra word "Facebook", "Google" or "Registro"');
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

    function check_persona($str){
        if (!$str) {
            $this->form_validation->set_message('check_persona','El {field} campo es requerido');
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

    function check_usuario($str){
        if (!$str) {
            $this->form_validation->set_message('check_usuario','El {field} campo es requerido');
            return false;
        }
        
        $itemExist=$this->DAO->selectEntity('Tb_Usuarios',array('idUsuario'=>$str),true);
        if (!$itemExist) {
            $this->form_validation->set_message('check_usuario','El {field} campo no existe');
            return false;
        }else{
            return true;
        } 
        
    }

    public function unique_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16,36), 0, $limit);
    }

    public function templateEmail($to,$name,$subject,$data=null,$vista)
    {
        $this->load->library('encryption');
        $this->load->library('email');

        $email_settings =  $this->DAO->selectEntity('Tb_config',null,true);
        if($email_settings){
            $config['protocol'] = $email_settings->email_protocol;
            $config['smtp_host'] = "ssl://".$email_settings->email_host;
            $config['smtp_user'] = $email_settings->email_send;
            $config['smtp_pass'] = $this->encryption->decrypt($email_settings->email_pass);
            $config['smtp_port'] = $email_settings->email_port;
            $config['charset'] = "utf-8";
            $config['mailtype'] = "html";
            $this->email->initialize($config);

            $this->email->set_newline("\r\n");

            $this->email->from($email_settings->email_send,$email_settings->from_email);
            $this->email->to($to,$name);
            $this->email->subject($subject);
            $msg = $this->load->view($vista,$data,true);
            $this->email->message($msg);
            if($this->email->send()){

            }else{

            }
        }
    }

    function check_numUnique($str){
      if ( strlen($str)>=1) {
        $agenteExists = $this->DAO->selectEntity('Tb_Agentes',array('numeroEmpleado'=>$str),TRUE);
        if ($agenteExists) {
          $this->form_validation->set_message('check_numUnique','El {field} ya existe.');

            return FALSE;

        } else {
          return TRUE;
        }

      } else {
        $this->form_validation->set_message('check_numUnique','El {field} deberia contener algo');
          return FALSE;
      }
    }

}
