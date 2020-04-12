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
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_Agentes',array('idAgente'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_Agentes'),
            );
        }
        $this->response($response,200);
    }

    function agente_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 12){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(
                  "nombres"=>"Requerido,Tiene que ser menor a 80 caracteres",
                  "apellidos"=>"Requerido,Tiene que ser menor a 80 caracteres",
                  "email"=>"Requerido,Tiene que ser un correo valido",
                  "genero"=>"Opcional,Eso tiene que se 'Femenino' o Masculino'",
                  "typeOauth"=>"Requerido,Eso tiene que ser 'Registro','Facebook' or 'Google'",
                  "token"=>"Opcional, Dependiendo del tipo de registro",
                  "urlFoto"=>"Opcional, Dependiendo del tipo de registro",
                  "numeroEmp"=>"Opcional, Tiene que se unico",
                  "puesto"=>"Opcional, Se requiere el puesto"
                )
            );
            count($this->post())>9  ? $response["message"]="Demasiados datos enviados" : $response["message"]="Datos no enviados";

        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('nombres','Nombres','required|min_length[1]|max_length[80]');
            $this->form_validation->set_rules('apellidos','Apellidos','required|min_length[1]|max_length[80]');
            $this->form_validation->set_rules('email','Correo','required|valid_email|is_unique[Tb_Usuarios.emailUsuario]');
            $this->form_validation->set_rules('genero','Genero','callback_check_gender');
            $this->form_validation->set_rules('typeOauth','Tipo de registro','callback_check_typoOauth');

            if($this->post('typeOauth')!="Registro"){
              $this->form_validation->set_rules('token','Token Red Social','required');
              $this->form_validation->set_rules('urlFoto','Url Foto','required|valid_url');
              $this->form_validation->set_rules('password','Contraseña','callback_check_noRequerido');
              $this->form_validation->set_rules('numeroEmp','numeroEmp','callback_check_numUnique');
              $this->form_validation->set_rules('puesto','Puesto','required');
            }

             if($this->form_validation->run()==FALSE){
               $response=array(
                   "status"=>"error",
                   "status_code"=>409,
                   "message"=>"Revisa las validaciones",
                   "validations"=>$this->form_validation->error_array(),
                   "data"=>null
               );
             }else{
               if($this->post('typeOauth')=="Registro"){
                   $this->load->library('bcrypt');
                   $uniquePassword= $this->unique_code(6);
                   $password=$this->bcrypt->hash_password($uniquePassword);
               }

               $itemExist=$this->DAO->selectEntity('Tb_Usuarios',array('emailUsuario'=>$this->post('email')),true);
               if($itemExist){
                   $response=array(
                       "status"=>"error",
                       "status_code"=>409,
                       "message"=>"El usuario ".$this->post('email')." ya existe revisa tu cuenta por favor",
                       "data"=>null
                   );
               }else{
                   $data['Person']=array(
                       "firstNamePersona"=>$this->post('nombres'),
                       "lastNamePersona"=>$this->post('apellidos'),
                       "generoPersona"=>$this->post('genero')
                   );

                   $data['Usuario']=array(
                       "emailUsuario"=>$this->post('email'),
                       "typeOauthUsuario"=>$this->post('typeOauth'),
                       "tokenPasswordUser"=>$this->post('token')
                   );
                   $data['Agente']=array(
                       "numeroEmpleado"=>$this->post('numeroEmp'),
                       "puestoAgente"=>$this->post('puesto')
                   );

                   if($this->post('typeOauth')=="Registro"){
                       $data['Usuario']['passwordUsuario']=$password;
                   }

                   $data['image']['urlImagen']=$this->post('urlFoto');

                   $emailExplode = explode("@",$this->post('email'));

                   if($emailExplode[1]=="anglolatinoedu.com"){
                       $data['Usuario']['typeUsuario']="Agente";
                       $data['Usuario']['statusUsuario']="Activo";
                   }else if($emailExplode[0]=="admin"){
                       $data['Usuario']['typeUsuario']="Admin";
                       $data['Usuario']['statusUsuario']="Activo";
                   }
                   else{
                       $data['Usuario']['typeUsuario']="Aspirante";
                   }

                   $dataUser['persona']=$this->post('nombres').' '.$this->post('apellidos');

                   $response = $this->UserDAO->registrarAgente($data);
                   //$response['data']=$uniquePassword;
                   $dataUser['password']=$uniquePassword;
                   if($response['status']=="success"){
                       $this->templateEmail($data['Usuario']['emailUsuario'],$data['Person']['firstNamePersona'],'Welcome',$dataUser,'email_bienvenida');
                   }
               }

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
          $this->form_validation->set_message('check_numUnique','The {field}already exists.');

            return FALSE;

        } else {
          return TRUE;
        }

      } else {
        $this->form_validation->set_message('check_numUnique','The {field} must content somenthing');
          return FALSE;
      }
    }

}
