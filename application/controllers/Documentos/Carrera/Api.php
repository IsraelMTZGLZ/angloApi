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

    public function carreraBoleta_post()
    {
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);
            if ($userExist) {

                $carpeta = 'Documentos/Carrera/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"Documentos/Carrera/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>"boleta",
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('Boleta'))
                {
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Upload fails",
                    "validations"=>$this->upload->display_errors(),
                    "data"=>$this->post()
                ); 
                }
                else
                {
                    $data = array(
                        "nombreDocumento"=>$this->upload->data('file_name'),
                        "extDocumento"=>$this->upload->data()['file_ext'],
                        "urlDocumento"=>'/Documentos/Carrera/'.$id.'/'.$this->upload->data('file_name'),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "fkAspirante"=>$id,
                        "tipo"=>"Boleta",
                        "statusDocumento"=>"Pendiente"
                    );

                    $response = $this->DAO->insertData('Tb_Documentos',$data);
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

    public function carreraCarta_post()
    {
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);
            if ($userExist) {

                $carpeta = 'Documentos/Carrera/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }
                $config =array(
                    "upload_path"=>"Documentos/Carrera/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>"cartaMotivo",
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('CartaMotivo'))
                {
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Upload fails",
                    "validations"=>$this->upload->display_errors(),
                    "data"=>$this->post()
                ); 
                }
                else
                {
                    $data = array(
                        "nombreDocumento"=>$this->upload->data('file_name'),
                        "extDocumento"=>$this->upload->data()['file_ext'],
                        "urlDocumento"=>'/Documentos/Carrera/'.$id.'/'.$this->upload->data('file_name'),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "fkAspirante"=>$id,
                        "tipo"=>"CartaMotivo",
                        "statusDocumento"=>"Pendiente"
                    );

                    $response = $this->DAO->insertData('Tb_Documentos',$data);
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

    public function carreraPasaporte_post()
    {
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);
            if ($userExist) {

                $carpeta = 'Documentos/Carrera/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"Documentos/Carrera/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>"pasaporte",
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('Pasaporte'))
                {
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Upload fails",
                    "validations"=>$this->upload->display_errors(),
                    "data"=>$this->post()
                ); 
                }
                else
                {
                    $data = array(
                        "nombreDocumento"=>$this->upload->data('file_name'),
                        "extDocumento"=>$this->upload->data()['file_ext'],
                        "urlDocumento"=>'/Documentos/Carrera/'.$id.'/'.$this->upload->data('file_name'),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "fkAspirante"=>$id,
                        "tipo"=>"Pasaporte",
                        "statusDocumento"=>"Pendiente"
                    );

                    $response = $this->DAO->insertData('Tb_Documentos',$data);
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

    function carreraByAspirante_get(){
        $id=$this->get('id');
        $tipo=$this->get('tipo');
        if (count($this->get())>2) {
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
                $data = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id,'tipo'=>$tipo),true);
            }
            else{
                $data = $this->DAO->selectEntity('Tb_Documentos',null,false);
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
    
}
