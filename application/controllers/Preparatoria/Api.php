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
    //Tb_TipoAlojamientoInstitucion
    function preparatoria_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 2){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(
                    "tipoAlojamiento"=>"La Tipo Alojamiento es requerido",
                    "institucionA" => "La Institucion es requerida"
                )
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('tipoAlojamiento','Tipo Alojamiento','required');
            $this->form_validation->set_rules('institucionA','Institucion','required');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'check the validations',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
            }else{

              $data=array(
                "fkTipoAlojamiento"=>$this->post('tipoAlojamiento'),
                "fkInstitucion"=>$this->post('institucionA')
              );
              $response = $this->DAO->insertData('Tb_TipoAlojamientoInstitucion',$data);

            }
        }

        $this->response($response,200);
    }

    //traer solo los id 
    function tipoAlojamientoInstitucion_get(){
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
                $data = $this->DAO->selectEntity('Tb_TipoAlojamientoInstitucion',array('idTipoAlojamientoInstitucion'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('Tb_TipoAlojamientoInstitucion',null,false);
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

    function preparatoria_put(){
        $data = $this->put();
        $id = $this->get('id');
        $existe = $this->DAO->selectEntity('Tb_TipoAlojamientoInstitucion',array('idTipoAlojamientoInstitucion'=>$id),TRUE);
        if($existe){
            if(count($data) == 0 || count($data) > 2){
                $response = array(
                    "status"=>"error",
                    "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                    "data"=>null,
                    "validations"=>array(
                      "tipoAlojamiento"=>"La Tipo Alojamiento es requerido",
                      "institucionA" => "La Institucion es requerida"
                    )
                );
            }else{
                $this->form_validation->set_data($data);
                $this->form_validation->set_rules('tipoAlojamiento','Tipo de Alojamiento','required');
                $this->form_validation->set_rules('institucionA','institucion','required');
    
                 if($this->form_validation->run()==FALSE){
                    $response = array(
                        "status"=>"error",
                        "message"=>'check the validations',
                        "data"=>null,
                        "validations"=>$this->form_validation->error_array()
                    );
                }else{
    
                    $data=array(
                       "fkTipoAlojamiento"=>$this->put('tipoAlojamiento'),
                       "fkInstitucion"=>$this->put('institucionA')
                    );

                   $response = $this->DAO->updateData('Tb_TipoAlojamientoInstitucion',$data,array('idTipoAlojamientoInstitucion'=>$id));
    
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
        $IdExists = $this->DAO->selectEntity('Tb_TipoAlojamientoInstitucion',array('idTipoAlojamientoInstitucion'=>$id),TRUE);

        if($IdExists){
          $response = $this->DAO->deleteData('Tb_TipoAlojamientoInstitucion',array('idTipoAlojamientoInstitucion'=>$id));
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

    //traer todas las preparatoria y con el idQueune esas tablas
    function preparatoria_get(){
      $id=$this->get('id');
      $id2=$this->get('id2');
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
              $data = $this->DAO->selectEntity('Vw_Prep',array('idTipoAlojamientoInstitucion'=>$id,'idTipoEstudioInstituccion'=>$id2),true);
          }
          else{
              $data = $this->DAO->selectEntity('Vw_Prep',null,false);
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

  function preparatoriaBYinstitucion_get(){
    $id=$this->get('id');
    $id2=$this->get('id2');
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
            $data = $this->DAO->selectEntity('Vw_tipoAlojamiento',array('idInstitucion'=>$id),false);
        }
        else{
            $data = $this->DAO->selectEntity('Vw_tipoAlojamiento',null,false);
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
