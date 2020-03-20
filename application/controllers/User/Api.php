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

    //registro de cualquier tipo de usuario
    public function registro_post()
    {
        if (count($this->post())==0 || count($this->post())>9) {
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>null,
                "validations"=>array(
                    "nombres"=>"Requerido,Tiene que ser menor a 80 caracteres",
                    "apellidos"=>"Requerido,Tiene que ser menor a 80 caracteres",
                    "email"=>"Requerido,Tiene que ser un correo valido",
                    "password"=>"Opcional,Tiene que ser mayor a 5 caracteres y Dependiendo del tipo de registro",
                    "repetir_password"=>"Opcional,Tiene que ser igual al password y Dependiendo del tipo de registro",
                    "genero"=>"Opcional,Eso tiene que se 'Femenino' o Masxulino'",
                    "typeOauth"=>"Requerido,Eso tiene que ser 'Registro','Facebook' or 'Google'",
                    "token"=>"Opcional, Dependiendo del tipo de registro",
                    "urlFoto"=>"Opcional, Dependiendo del tipo de registro"
                ),
                "data"=>null
            );
            count($this->post())>9  ? $response["message"]="Demasiados datos enviados" : $response["message"]="Datos no enviados";

        }else{
            $this->form_validation->set_data($this->post());
            $this->form_validation->set_rules('nombres','Nombres','required|min_length[1]|max_length[80]');
            $this->form_validation->set_rules('apellidos','Apellidos','required|min_length[1]|max_length[80]');
            $this->form_validation->set_rules('email','Correo','required|valid_email|is_unique[Tb_Usuarios.emailUsuario]');
            $this->form_validation->set_rules('genero','Genero','callback_check_gender');
            $this->form_validation->set_rules('typeOauth','Tipo de registro','callback_check_typoOauth');

            if($this->post('typeOauth')=="Registro"){
                $this->form_validation->set_rules('password','Contraseña','required|min_length[5]');
                $this->form_validation->set_rules('repetir_password','Repetir contraseña','required|min_length[5]|matches[password]');
                $this->form_validation->set_rules('token','Token Red Social','callback_check_noRequerido');
                $this->form_validation->set_rules('urlFoto','Token Red Social','callback_check_noRequerido');
            }else{
                $this->form_validation->set_rules('token','Token Red Social','required');
                $this->form_validation->set_rules('urlFoto','Url Foto','required|valid_url');
                $this->form_validation->set_rules('password','Contraseña','callback_check_noRequerido');
            }

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
                if($this->post('typeOauth')=="Registro"){
                    $this->load->library('bcrypt');
                    $password=$this->bcrypt->hash_password($this->post('password'));
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

                    if($this->post('typeOauth')=="Registro"){
                        $data['Usuario']['passwordUsuario']=$password;
                    }

                    $data['image']['urlImagen']=$this->post('urlFoto');

                    $emailExplode = explode("@",$this->post('email'));

                    if($emailExplode[1]=="anglolatinoedu.com"){
                        $data['Usuario']['typeUsuario']="Agente";
                        $data['Usuario']['statusUsuario']="Inactivo";
                    }else if($emailExplode[0]=="admin"){
                        $data['Usuario']['typeUsuario']="Admin"; 
                        $data['Usuario']['statusUsuario']="Inactivo"; 
                    }
                    else{
                        $data['Usuario']['typeUsuario']="Aspirante";  
                    }
                    
                    $response = $this->UserDAO->registrar($data);
                }
            }
        }

        $this->response($response,200);
    }

    //login forma nativa desde la pagina web
    public function loginNativo_post()
    {
        if (count($this->post())==0 || count($this->post())>2) {
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>null,
                "validations"=>array(
                    "email"=>"Requerido, email valido",
                    "password"=>"required, minimo 5 caracteres"
                ),
                "data"=>null
            );
            count($this->post())>2  ? $response["message"]="Demasiados datos enviados" : $response["message"]="Datos no enviados";
        }else{
            $this->form_validation->set_data($this->post());
            $this->form_validation->set_rules('email','Correo','required|valid_email');
            $this->form_validation->set_rules('password','Contraseña','required|min_length[5]');

            if ($this->form_validation->run()==false) {
                $response=array(
                      "status"=>"error",
                      "status_code"=>409,
                      "message"=>"check the validations",
                      "validations"=>$this->form_validation->error_array(),
                      "data"=>null
                  ); 
            }
            else{
                $this->load->library('bcrypt');
                $itemExist=$this->DAO->selectEntity('Tb_Usuarios',array('emailUsuario'=>$this->post('email')),true);
                if ($itemExist) {
                    if ($itemExist->typeOauthUsuario=='Registro') {
                        if ($itemExist->statusUsuario=="Activo"){
                            if ($this->bcrypt->check_password($this->post('password'), $itemExist->passwordUsuario )) {
                        
                                if ($itemExist->typeUsuario=="Agente") {
                                    $vista=null;
                                }else if($itemExist->typeUsuario=="Admin"){
                                    $vista=null;
                                }else if($itemExist->typeUsuario=="Aspirante"){
                                    $vista=null;
                                }else {
                                    $vista=null;
                                }
        
                                if ($vista) {
                                    $item2Exist=$this->DAO->selectEntity($vista,array('email'=>$this->post('email')),true);
                                    $response = array(
                                        "status"=>"success",
                                        "status_code"=>"201",
                                        "message"=>"Informacion Cargada Correctamente",
                                        "data"=>$item2Exist
                                    );
                                }else{
                                    $response = array(
                                        "status"=>"error",
                                        "status_code"=>"201",
                                        "message"=>"Usuario con problemas",
                                        "data"=>null 
                                    );
                                }
                                    
                                
                            }
                            else{
                                $response = array(
                                    "status"=>"error",
                                    "status_code"=>"201",
                                    "message"=>"Email y/o contraseña incorrecta",
                                    "data"=>null 
                                );
                            }
                        }else{
                            $response = array(
                                "status"=>"error",
                                "status_code"=>"201",
                                "message"=>"El usuario no tiene permisos",
                                "data"=>null 
                            );
                        }
                        
                    }else{
                        $response=array(
                            "status"=>"error",
                            "status_code"=>409,
                            "message"=>"El usuario ".$itemExist->emailUsuario. " fue creado via ".$itemExist->typeOauthUsuario,
                            "data"=>null
                        ); 
                    }
                    
                    
                }else{
                    $response = array(
                        "status"=>"error",
                        "status_code"=>"201",
                        "message"=>"Email y/o contraseña incorrecta",
                        "data"=>null 
                    );
                }
            }
        }
        $this->response($response,200);
    }

    //login por cuenta google o facebook
    public function loginPlus_post()
    {
        if (count($this->post())==0 || count($this->post())>2) {
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>null,
                "validations"=>array(
                    "email"=>"requerido, email valido",
                    "token"=>"required"
                ),
                "data"=>null
            );
            count($this->post())>2  ? $response["message"]="Demasiados datos enviados" : $response["message"]="Datos no enviados";
        }else{
            $this->form_validation->set_data($this->post());
            $this->form_validation->set_rules('email','Correo','required|valid_email');
            $this->form_validation->set_rules('token','Token Usuario','required');

            if ($this->form_validation->run()==false) {
                $response=array(
                      "status"=>"error",
                      "status_code"=>409,
                      "message"=>"check the validations",
                      "validations"=>$this->form_validation->error_array(),
                      "data"=>null
                  ); 
            }
            else{
                $itemExist=$this->DAO->selectEntity('Tb_Usuarios',array('tokenPasswordUser'=>$this->post('token'),'emailUsuario'=>$this->post('email')),true);
                if ($itemExist) {
                    if ($itemExist->typeOauthUsuario!='Registro') {
                        
                        if ($itemExist->typeUsuario=="Agente") {
                            $vista=null;
                        }else if($itemExist->typeUsuario=="Admin"){
                            $vista=null;
                        }else if($itemExist->typeUsuario=="Aspirante"){
                            $vista=null;
                        }else {
                            $vista=null;
                        }

                        if ($vista) {
                            $item2Exist=$this->DAO->selectEntity($vista,array('email'=>$itemExist->emailUsuario),true);
                            $response = array(
                                "status"=>"success",
                                "status_code"=>"201",
                                "message"=>"Informacion Cargada Correctamente",
                                "data"=>$item2Exist
                            );
                        }else{
                            $response = array(
                                "status"=>"error",
                                "status_code"=>"201",
                                "message"=>"Usuario con problemas",
                                "data"=>null 
                            );
                        }
                                
                            
                        
                    }else{
                        $response=array(
                            "status"=>"error",
                            "status_code"=>409,
                            "message"=>"El usuario ".$this->post('email')." no fue creado por esta red social",
                            "data"=>null
                        ); 
                    }
                    
                    
                }else{
                    $response = array(
                        "status"=>"error",
                        "status_code"=>"201",
                        "message"=>"Correo no encontrado",
                        "data"=>null
                    );
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
    
    

}
