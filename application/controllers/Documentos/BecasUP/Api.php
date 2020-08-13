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

    function becas_get(){
        $id=$this->get('id');
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
                $data = $this->DAO->selectEntity('Tb_DocBecasUP',array('fkAspirante'=>$id),false);
            }
            else{
                $data = $this->DAO->selectEntity('Tb_DocBecasUP',null,false);
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

    function dropboxBecas_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 20){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No se recibio datos' : 'Demasiados datos recibidos',
                "data"=>null,
                "validations"=>null
            );
        }else{
            $capertaExist=$this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$this->post('fkAspirante')),true);

            $data= array(
                "idDocumento"=>$this->post('idDocumento'),
                "nameDocumento"=>$this->post('nameDocumento'),
                "sizeDocumento"=>$this->post('sizeDocumento'),
                "pathDisplayDocumento"=>$this->post('pathDisplayDocumento'),
                "pathLowerDocumento"=>$this->post('pathLowerDocumento'),
                "contentHashDocumento"=>$this->post('contentHashDocumento'),
                "clientModifiedDocumento"=>$this->post('clientModifiedDocumento'),
                "nameCarpeta"=>$capertaExist->nameCarpeta,
                "pathDisplayCarpeta"=>$capertaExist->pathDisplayCarpeta,
                "pathLowerCarpeta"=>$capertaExist->pathLowerCarpeta,
                "fkAspirante"=>$this->post('fkAspirante'),
                "descDocumento"=>$this->post('descDocumento')
            );

            $response = $this->DAO->insertData('Tb_DocBecasUP',$data);
             
        }

        $this->response($response,200);
    }

    function statuschange_post(){
        $data = $this->post();
        $id = $this->get('id');

        if(count($data) == 0 || count($data) > 20){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No se recibio datos' : 'Demasiados datos recibidos',
                "data"=>null,
                "validations"=>null
            );
        }else{

            $data= array(
                "statusDocumento"=>$this->post('status'),
                "motivo" => $this->post('motivo')
            );

            $response =  $this->DAO->updateData('Tb_DocBecasUP',$data,array('idReal'=>$id));

             
        }

        $this->response($response,200);
    }

}
