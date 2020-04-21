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
    function preparatoria_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEnt('Tb_Preparatoria',array('idPreparatoria'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEnt('Tb_Preparatoria'),
            );
        }
        $this->response($response,200);
    }


    function preparatoria_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 3){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(
                      "nombrePreparatoria"=>"The name is required",
                      "fundacionPreparatoria"=>"The fundation is required",
                      "statusPreparatoria"=>"The status is required"
                )
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('nombrePreparatoria','nombrePreparatoria','required');
            $this->form_validation->set_rules('fundacionPreparatoria','fundacionPreparatoria','required');
            $this->form_validation->set_rules('statusPreparatoria','statusPreparatoria','callback_check_status');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'check the validations',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{

               $data=array(
                   "nombre_Preparatoria"=>$this->post('nombrePreparatoria'),
                   "fundacion_Preparatoria"=>$this->post('fundacionPreparatoria'),
                   "status_Preparatoria"=>$this->post('statusPreparatoria'),
               );
               $response = $this->DAO->insertData('Tb_Preparatoria',$data);

             }
        }

        $this->response($response,200);
    }

    function preparatoria_put($id=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectEntity('Tb_Preparatoria',array('idPreparatoria'=>$id),TRUE);
        if($Eixist){
          if(count($data) == 0 || count($data) > 4){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                  "data"=>null,
                  "validations"=>array(
                    "nombrePreparatoria"=>"The name is required",
                    "fundacionPreparatoria"=>"The fundation is required",
                    "statusPreparatoria"=>"The status is required"
                  )
              );
          }else{
              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('nombrePreparatoria','nombrePreparatoria','required');
              $this->form_validation->set_rules('fundacionPreparatoria','fundacionPreparatoria','required');
              $this->form_validation->set_rules('statusPreparatoria','statusPreparatoria','callback_check_status');

             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'Too many data received',
                      "data"=>null,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{
               $data = array(
                 "nombre_Preparatoria"=>$this->put('nombrePreparatoria'),
                 "fundacion_Preparatoria"=>$this->put('fundacionPreparatoria'),
                 "status_Preparatoria"=>$this->put('statusPreparatoria'),
               );
               $response = $this->DAO->updateData('Tb_Preparatoria',$data,array('idPreparatoria'=>$id));
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



    public function preparatoria_delete(){
          $id = $this->get('id');
      if ($id) {
        $IdExists = $this->DAO->selectEntity('Tb_Preparatoria',array('idPreparatoria'=>$id),TRUE);

        if($IdExists){
          $response = $this->DAO->deleteData('Tb_Preparatoria',array('idPreparatoria'=>$id));
        }else{
          $response = array(
            "status"=>"error",
            "status_code"=>409,
            "message"=>"Id doesn't exists",
            "validations"=>null,
            "data"=>null
          );
        }
      } else {
        $response = array(
          "status"=>"error",
          "status_code"=>409,
          "message"=>"Id wasn't sent",
          "validations"=>array(
            "id"=>"Required (get)",

          ),
          "data"=>null
        );
      }

      $this->response($response,$response['status_code']);
    }



    /**exta validations**/

    function check_status($str){
      if(strlen($str)>=1){
        if($str == "Activo"){
          return TRUE;
        }elseif($str == "Inactivo"){
          return TRUE;
        }elseif ($str == "Pendiente") {
          return TRUE;
        }else{
          $this->form_validation->set_message('check_status','The status must be Activo Inactivo or Pending');
          return FALSE;
        }
      }else{
        $this->form_validation->set_message('check_status','The {field} must contain 1 character');
          return FALSE;
      }
    }


}
