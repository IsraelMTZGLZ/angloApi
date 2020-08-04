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

    function becasAdd_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 12){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>null
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('pais','Pais','required');
            $this->form_validation->set_rules('nombre','Nombre','required');
            $this->form_validation->set_rules('link','Link','required');

            if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'check the validations',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
            }else{

                $data=array(
                    "paisBeca"=>$this->post('pais'),
                    "nombreBeca"=>$this->post('nombre'),
                    "linkBeca"=>$this->post('link'),
                    "aperturaFechaBeca"=>$this->post('apertura'),
                    "cierreFcehaBeca"=>$this->post('cierre'),
                    "periodoEvaluacionBeca"=>$this->post('periodoEvaluacion'),
                    "procesoAsignacionBeca"=>$this->post('asignacion'),
                    "montoBeca"=>$this->post('monto'),
                    "programaBeca"=>$this->post('programa'),
                    "descripcionBeca"=>$this->post('descripcion'),
                    "convenioBeca"=>$this->post('convenio'),
                    "resultadosBecas"=>$this->post('resultados')
                );

                $response = $this->DAO->insertData('Tb_Becas',$data);

            }
        }

        $this->response($response,200);
    }

    function becas_get(){
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
                $data = $this->DAO->selectEntity('Tb_Becas',array('idBeca'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('Tb_Becas',null,false);
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

    function becasPUT_put(){
        $id = $this->get('id');
        $data = $this->put();

        if(count($data) == 0 || count($data) > 12){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>null
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('paisPut','Pais','required');
            $this->form_validation->set_rules('nombrePut','Nombre','required');
            $this->form_validation->set_rules('linkPut','Link','required');

            if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'check the validations',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
            }else{

                $data=array(
                    "paisBeca"=>$this->put('paisPut'),
                    "nombreBeca"=>$this->put('nombrePut'),
                    "linkBeca"=>$this->put('linkPut'),
                    "aperturaFechaBeca"=>$this->put('aperturaPut'),
                    "cierreFcehaBeca"=>$this->put('cierrePut'),
                    "periodoEvaluacionBeca"=>$this->put('periodoEvaluacionPut'),
                    "procesoAsignacionBeca"=>$this->put('asignacionPut'),
                    "montoBeca"=>$this->put('montoPut'),
                    "programaBeca"=>$this->put('programaPut'),
                    "descripcionBeca"=>$this->put('descripcionPut'),
                    "convenioBeca"=>$this->put('convenioPut'),
                    "resultadosBecas"=>$this->put('resultadosPut')
                );

                $response = $this->DAO->updateData('Tb_Becas',$data,array('idBeca'=>$id));

            }
        }

        $this->response($response,200);
    }

    public function becasQuitar_delete(){
        $id = $this->get('id');
      if ($id) {
        $IdExists = $this->DAO->selectEntity('Tb_Becas',array('idBeca'=>$id),TRUE);

        if($IdExists){
          $response = $this->DAO->deleteData('Tb_Becas',array('idBeca'=>$id));
        }else{
          $response = array(
            "status"=>"error",
            "status_code"=>409,
            "message"=>"Id no existe",
            "validations"=>null,
            "data"=>null
          );
        }
      } else {
        $response = array(
          "status"=>"error",
          "status_code"=>409,
          "message"=>"Id no fue encontrado",
          "validations"=>array(
            "id"=>"Required (get)",
          ),
          "data"=>null
        );
      }

      $this->response($response,200);
    }

}
