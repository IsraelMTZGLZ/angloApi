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

    function tipoCampamento_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 2){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(
                    "nombreTC"=>"El nombre es requerido",
                    "abreviacionTC" => "La abreviacion es requerida"
                )
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('nombreTC','Nombre','required');
            $this->form_validation->set_rules('abreviacionTC','Abreviacion','required');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'check the validations',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{

               $data=array(
                   "nombreTipoCampamento"=>$this->post('nombreTC'),
                   "abreviacionTipoCampamento"=>$this->post('abreviacionTC')
               );
               $response = $this->DAO->insertData('Tb_TipoCampamento',$data);

             }
        }

        $this->response($response,200);
    }

    function tipoCampamento_get(){
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
                $data = $this->DAO->select('Tb_TipoCampamento',array('idTipoCampamento'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('Tb_TipoCampamento',null,false);
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
    function tipoCampamentoselected_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Tb_TipoCampamento',array('idTipoCampamento'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_TipoCampamento'),
            );
        }
        $this->response($response,200);
    }
    function tipoCampamento_put(){
        $data = $this->put();
        $id = $this->get('id');
        $existe = $this->DAO->selectEntity('Tb_TipoCampamento',array('idTipoCampamento'=>$id),TRUE);
        if($existe){
            if(count($data) == 0 || count($data) > 2){
                $response = array(
                    "status"=>"error",
                    "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                    "data"=>null,
                    "validations"=>array(
                        "nombreTC"=>"El nombre es requerido",
                        "abreviacionTC" => "La abreviacion es requerida"
                    )
                );
            }else{
                $this->form_validation->set_data($data);
                $this->form_validation->set_rules('nombreTC','Nombre','required');
                $this->form_validation->set_rules('abreviacionTC','Abreviacion','required');

                 if($this->form_validation->run()==FALSE){
                    $response = array(
                        "status"=>"error",
                        "message"=>'check the validations',
                        "data"=>null,
                        "validations"=>$this->form_validation->error_array()
                    );
                }else{

                    $data=array(
                       "nombreTipoCampamento"=>$this->put('nombreTC'),
                       "abreviacionTipoCampamento"=>$this->put('abreviacionTC')
                    );

                   $response = $this->DAO->updateData('Tb_TipoCampamento',$data,array('idTipoCampamento'=>$id));

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

    public function tipoCampamento_delete(){
        $id = $this->get('id');
      if ($id) {
        $IdExists = $this->DAO->selectEntity('Tb_TipoCampamento',array('idTipoCampamento'=>$id),TRUE);

        if($IdExists){
          $response = $this->DAO->deleteData('Tb_TipoCampamento',array('idTipoCampamento'=>$id));
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
