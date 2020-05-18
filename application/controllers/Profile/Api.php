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

    //cabir la foto del usuario en base al id del usuario
    public function photoUser_post()
    {
        $id=$this->get('id');
        $photoId = $this->get('photo');
        
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Personas',array('idPersona'=>$id),true);
            if ($userExist) {

                $config =array(
                    "upload_path"=>"PhotoUser",
                    "allowed_types"=>"png|jpg|jpeg",
                    "file_name"=>$id,
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('PhotoUser'))
                {
                    $response=array(
                        "status"=>"error",
                        "status_code"=>409,
                        "message"=>"La subida fallo",
                        "validations"=>$this->upload->display_errors(),
                        "data"=>$this->post()
                    ); 
                }
                else
                {

                    $data = array(
                        "extImagen"=>$this->upload->data()['file_ext'],
                        "urlImagen"=>base_url('PhotoUser/').$id.$this->upload->data()['file_ext'],
                        "typeImagen"=>$this->upload->data('file_type')
                    );

                    if($photoId){
                        $response = $this->DAO->updateData('Tb_Imagenes',$data,array('idImagen'=>$photoId));
                    }else{
                        $response = $this->DAO->insertData('Tb_Imagenes',$data,true);
                        $dataPerson = array(
                            "photoPersona"=>$response['data']
                        );
                        $this->DAO->updateData('Tb_Personas',$dataPerson,array('idPersona'=>$id));
                    }

                    
                    if($response['status']=="success"){
                        $response['message']= "Documento subido correctamente";
                    }
                }
            }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"id does not exist",
                    "validations"=>array(
                        "id"=>"required (get)"
                    ),
                    "data"=>null
                );
            }
        }else{
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>"id was not sent",
                "validations"=>array(
                    "id"=>"required (get)"
                ),
                "data"=>null
            );
        }
        $this->response($response,200);
    }

    //cambiar contrasenia del usuario
    function passwordChange_post(){
        $id=$this->get('id');
        $data = $this->post();

        if(count($data) == 0 || count($data) > 3){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No se recibio datos' : 'Demasiados datos recibidos',
                "data"=>null,
                "validations"=>array(
                    "passwordActual"=>"El Password Actual es requerido",
                    "passwordNuevo"=>"El Password Nuevo es requerido",
                    "passwordNuevoR" => "El Password Nuevo Repetido es requerido"
                )
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('passwordActual','Password Actual','required|min_length[5]');
            $this->form_validation->set_rules('passwordNuevo','Password Nuevo','required|min_length[5]');
            $this->form_validation->set_rules('passwordNuevoR','Password Nuevo Repetido','required|min_length[5]|matches[passwordNuevo]');

             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'Revisa las validaciones',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
            }else{
                $this->load->library('bcrypt');

                $itemExist=$this->DAO->selectEntity('Tb_Usuarios',array('idUsuario'=>$id),true);
                
                if($this->bcrypt->check_password($this->post('passwordActual'), $itemExist->passwordUsuario)){
                    $hash = $this->bcrypt->hash_password($this->post('passwordNuevo'));
                    
                    $data=array(
                        "passwordUsuario"=>$hash,
                    );
     
                    $response = $this->DAO->updateData('Tb_Usuarios',$data,array('idUsuario'=>$id));
                    if($response['status']=="success"){
                        $response['message']="Contraseña actualizada correctamente";
                    }
                }else{
                    $response = array(
                        "status"=>"error",
                        "message"=>'La contraseña actual no coincide',
                        "data"=>null
                    );
                }

            }
        }

        $this->response($response,200);
    }


    
}
