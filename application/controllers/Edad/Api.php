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



function universidadByInstitucion_get(){
    $id = $this->get('id');
    if($id){
         $response = array(
            "status"=>"success",
            "message"=> '',
            "data"=>$this->DAO->selectEnt('Vw_Uni',array('idInstitucion'=>$id)),
        );
    }else{
        $response = array(
            "status"=>"success",
            "message"=> '',
            "data"=>$this->DAO->selectEnt('Vw_Uni'),
        );
    }
    $this->response($response,200);
}



    function edad_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEnt('Tb_Edades',array('idEdad'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEnt('Tb_Edades'),
            );
        }
        $this->response($response,200);
    }

    function edad_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 3){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(
                    "nombre"=>"El nombre es requerido",
                    "abreviacion" => "La abreviacion es requerida",
                    "edad" => "La abreviacion es requerida"
                )
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('nombre','Nombre','required');
            $this->form_validation->set_rules('abreviacion','Abreviacion','required');
            $this->form_validation->set_rules('edad','Edad','required');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'check the validations',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{

               $data=array(
                   "nombreEdad"=>$this->post('nombre'),
                   "abreviacionEdad"=>$this->post('abreviacion'),
                   "edadEdad"=>$this->post('edad')
               );
               $response = $this->DAO->insertData('Tb_Edades',$data);

             }
        }

        $this->response($response,200);
    }



    function edad_put(){
        $data = $this->put();
        $id = $this->get('id');
        $existe = $this->DAO->selectEntity('Tb_Edades',array('idEdad'=>$id),TRUE);
        if($existe){
            if(count($data) == 0 || count($data) > 3){
                $response = array(
                    "status"=>"error",
                    "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                    "data"=>null,
                    "validations"=>array(
                      "nombre"=>"El nombre es requerido",
                      "abreviacion" => "La abreviacion es requerida",
                      "edad" => "La abreviacion es requerida"
                    )
                );
            }else{
                $this->form_validation->set_data($data);
                $this->form_validation->set_rules('nombre','Nombre','required');
                $this->form_validation->set_rules('abreviacion','Abreviacion','required');
                $this->form_validation->set_rules('edad','Edad','required');

                 if($this->form_validation->run()==FALSE){
                    $response = array(
                        "status"=>"error",
                        "message"=>'check the validations',
                        "data"=>null,
                        "validations"=>$this->form_validation->error_array()
                    );
                }else{

                    $data=array(
                      "nombreEdad"=>$this->put('nombre'),
                      "abreviacionEdad"=>$this->put('abreviacion'),
                      "edadEdad"=>$this->put('edad')
                    );

                   $response = $this->DAO->updateData('Tb_Edades',$data,array('idEdad'=>$id));

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

    public function alojamiento_delete(){
        $id = $this->get('id');
      if ($id) {
        $IdExists = $this->DAO->selectEntity('Tb_TipoAlojamiento',array('idTipoAlojamiento'=>$id),TRUE);

        if($IdExists){
          $response = $this->DAO->deleteData('Tb_TipoAlojamiento',array('idTipoAlojamiento'=>$id));
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

    public function edad_delete(){
        $id = $this->get('id');
      if ($id) {
        $IdExists = $this->DAO->selectEntity('Tb_Edades',array('idEdad'=>$id),TRUE);

        if($IdExists){
          $response = $this->DAO->deleteData('Tb_Edades',array('idEdad'=>$id));
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
