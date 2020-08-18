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
    public function registro_post(){
        if (count($this->post())==0 || count($this->post())>7) {
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>null,
                "validations"=>array(
                    "nombres"=>"Requerido,Tiene que ser menor a 80 caracteres",
                    "apellidos"=>"Requerido,Tiene que ser menor a 80 caracteres",
                    "email"=>"Requerido,Tiene que ser un correo valido",
                    "genero"=>"Opcional,Eso tiene que se 'Femenino' o Masculino'",
                    "typeOauth"=>"Requerido,Eso tiene que ser 'Registro','Facebook' or 'Google'",
                    "token"=>"Opcional, Dependiendo del tipo de registro",
                    "urlFoto"=>"Opcional, Dependiendo del tipo de registro"
                ),
                "data"=>null
            );
            count($this->post())>7  ? $response["message"]="Demasiados datos enviados" : $response["message"]="Datos no enviados";

        }else{
            $this->form_validation->set_data($this->post());
            $this->form_validation->set_rules('nombres','Nombres','required|min_length[1]|max_length[80]');
            $this->form_validation->set_rules('apellidos','Apellidos','required|min_length[1]|max_length[80]');
            $this->form_validation->set_rules('email','Correo','required|valid_email|is_unique[Tb_Usuarios.emailUsuario]');
            $this->form_validation->set_rules('genero','Genero','callback_check_gender');
            $this->form_validation->set_rules('typeOauth','Tipo de registro','callback_check_typoOauth');

            if($this->post('typeOauth')!="Registro"){
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

                    if($this->post('typeOauth')=="Registro"){
                        $data['Usuario']['passwordUsuario']=$password;
                    }

                    $data['image']['urlImagen']=$this->post('urlFoto');

                    $emailExplode = explode("@",$this->post('email'));

                    if($emailExplode[1]=="anglolatinoedu.com"){
                        $data['Usuario']['typeUsuario']="Agente";
                        $data['Usuario']['statusUsuario']="Pendiente";
                    }else if($emailExplode[0]=="admin"){
                        $data['Usuario']['typeUsuario']="Admin";
                        $data['Usuario']['statusUsuario']="Pendiente";
                    }
                    else{
                        $data['Usuario']['typeUsuario']="Aspirante";
                    }

                    $dataUser['persona']=$this->post('nombres').' '.$this->post('apellidos');

                    $response = $this->UserDAO->registrar($data);
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
    // this is a temporal post
    public function registroo_post(){
        if (count($this->post())==0 || count($this->post())>7) {
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>null,
                "validations"=>array(
                    "nombres"=>"Requerido,Tiene que ser menor a 80 caracteres",
                    "apellidos"=>"Requerido,Tiene que ser menor a 80 caracteres",
                    "email"=>"Requerido,Tiene que ser un correo valido",
                    "password"=>"Requerido,Tiene que ser un password valido",
                    "genero"=>"Opcional,Eso tiene que se 'Femenino' o Masculino'",
                    "typeOauth"=>"Requerido,Eso tiene que ser 'Registro','Facebook' or 'Google'",
                    "token"=>"Opcional, Dependiendo del tipo de registro",
                    "urlFoto"=>"Opcional, Dependiendo del tipo de registro"
                ),
                "data"=>null
            );
            count($this->post())>7  ? $response["message"]="Demasiados datos enviados" : $response["message"]="Datos no enviados";

        }else{
            $this->form_validation->set_data($this->post());
            $this->form_validation->set_rules('nombres','Nombres','required|min_length[1]|max_length[80]');
            $this->form_validation->set_rules('apellidos','Apellidos','required|min_length[1]|max_length[80]');
            $this->form_validation->set_rules('email','Correo','required|valid_email|is_unique[Tb_Usuarios.emailUsuario]');
            $this->form_validation->set_rules('password','Contraseña','required|min_length[5]|max_length[80]');
            $this->form_validation->set_rules('genero','Genero','callback_check_gender');
            $this->form_validation->set_rules('typeOauth','Tipo de registro','callback_check_typoOauth');

            if($this->post('typeOauth')!="Registro"){
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
                    $uniquePassword= $this->unique_code(6);
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
                        $data['Usuario']['statusUsuario']="Pendiente";
                    }else if($emailExplode[0]=="admin"){
                        $data['Usuario']['typeUsuario']="Admin";
                        $data['Usuario']['statusUsuario']="Pendiente";
                    }
                    else{
                        $data['Usuario']['typeUsuario']="Aspirante";
                    }

                    $dataUser['persona']=$this->post('nombres').' '.$this->post('apellidos');

                    $response = $this->UserDAO->registrar($data);
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
    //cambiar contraseña
    public function cambiarPassword_post()
    {
        if (count($this->post())==0 || count($this->post())>1) {
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>null,
                "validations"=>array(
                    "email"=>"Requerido,Tiene que ser un correo valido"
                ),
                "data"=>null
            );
            count($this->post())>1  ? $response["message"]="Demasiados datos enviados" : $response["message"]="Datos no enviados";

        }else{
            $this->form_validation->set_data($this->post());
            $this->form_validation->set_rules('email','Correo','callback_email_check');

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

                $this->load->library('bcrypt');
                $uniquePassword= $this->unique_code(6);
                $password=$this->bcrypt->hash_password($uniquePassword);


                $item=$this->DAO->selectEntity('Tb_Usuarios',array('emailUsuario'=>$this->post('email')),true);

                if($item->typeOauthUsuario=="Registro"){

                    $data=array(
                        "passwordUsuario"=>$password
                    );

                    $itemPersona=$this->DAO->selectEntity('Tb_Personas',array('idPersona'=>$item->fkPersona),true);

                    $dataUser['persona']=$itemPersona->firstNamePersona.' '.$itemPersona->lastNamePersona;

                    $response = $this->DAO->updateData('Tb_Usuarios',$data,array("idUsuario"=>$item->idUsuario));

                    $dataUser['password']=$uniquePassword;
                    if($response['status']=="success"){
                        //test
                        //$this->templateEmail('hectori.um.15@gmail.com','hector urias','Change Password',$dataUser,'email_password');
                        //real
                        $this->templateEmail($item->emailUsuario,$itemPersona->firstNamePersona,'Change Password',$dataUser,'email_password');
                    }
                }else{
                    $response=array(
                        "status"=>'error',
                        "status_code"=>409,
                        "message"=>"El correo ".$item->emailUsuario." fue creado via ".$item->typeOauthUsuario.", no es posible cambiar la contraseña",
                        "validations"=>null,
                        "data"=>null
                    );
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
                                    $vista='Vw_Agente';
                                }else if($itemExist->typeUsuario=="Admin"){
                                    $vista='Vw_Admin';
                                }else if($itemExist->typeUsuario=="Aspirante"){
                                    $vista='Vw_Aspirante';
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
                                    "data"=>$this->bcrypt->check_password($this->post('password'), $itemExist->passwordUsuario)
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
                            $vista='Vw_Agente';
                        }else if($itemExist->typeUsuario=="Admin"){
                            $vista='Vw_Admin';
                        }else if($itemExist->typeUsuario=="Aspirante"){
                            $vista='Vw_Aspirante';
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

    public function unique_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16,36), 0, $limit);
    }

    public function email_check($str){
        if (!$str) {
            $this->form_validation->set_message('email_check','El {field} campo es requerido');
            return false;
        }else{
            $itemExist=$this->DAO->selectEntity('Tb_Usuarios',array('emailUsuario'=>$str),true);
            if (!$itemExist) {
                $this->form_validation->set_message('email_check','El {field} no existe en el sistema');
                return false;
            }else{
                return true;
            }
        }
    }

    function editPerson_put(){
        $data = $this->put();
        $id = $this->get('id');
        $existe = $this->DAO->selectEntity('Tb_Personas',array('idPersona'=>$id),TRUE);
        if($existe){
            if(count($data) == 0 || count($data) > 2){
                $response = array(
                    "status"=>"error",
                    "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                    "data"=>null,
                    "validations"=>array(
                        "nombre"=>"El nombre es requerido",
                        "Apellidos" => "Los apellidos son requeridos"
                    )
                );
            }else{
                $this->form_validation->set_data($data);
                $this->form_validation->set_rules('nombre','Nombre','required');
                $this->form_validation->set_rules('apellidos','Apellidos','required');

                 if($this->form_validation->run()==FALSE){
                    $response = array(
                        "status"=>"error",
                        "message"=>'check the validations',
                        "data"=>null,
                        "validations"=>$this->form_validation->error_array()
                    );
                 }else{

                   $data=array(
                       "firstNamePersona"=>$this->put('nombre'),
                       "lastNamePersona"=>$this->put('apellidos')
                   );
                   $response = $this->DAO->updateData('Tb_Personas',$data,array('idPersona'=>$id));

                 }
            }
        }else{
            $response = array(
            "status"=>"error",
            "message"=> "check the id",
            "data"=>null,
            );
        }


        $this->response($response,200);
    }

    public function templateEmail($to,$name,$subject,$data=null,$vista)
    {
		$headers = array(
			'Authorization: Bearer ',
			'Content-Type: application/json'
		);

		$data = array(
			"personalizations" => array(
				array(
					"to" => array(
						array(
							"email" => $to,
							"name" => $name
						)
					),
					"dynamic_template_data"=> array (

						"password" => $data['password'],
						"name" => $to

					)

				)
			),
			"from" => array(
				"email" => "study@anglopageone.com",
				"name"=>"Anglo Latino Education Partnership"
			),
			"reply_to"=> array(
				"email"=>"study@anglolatinoedu.com",
				"name"=>"Anglo Latino Education Partnership"
			),
			"template_id"=> "d-386031cf274443729e178fff7da5392b"
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		curl_close($ch);

    }

}
