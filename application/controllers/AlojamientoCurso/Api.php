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
    //Tb_alojamientoCurso
    function alojamientoCurso_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 2){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(
                    "tipoalojamiento"=>"La facultad es requerido",
                    "institucion" => "La institucion es requerida"
                )
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('tipoalojamiento','Tipoalojamiento','callback_check_tipoalojamiento');
            $this->form_validation->set_rules('institucion','Institucion','callback_check_institucion');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'check the validations',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
            }else{

              $data=array(
                "fkTipoAlojamiento"=>$this->post('tipoalojamiento'),
                "fkInstitucion"=>$this->post('institucion')
              );
              $response = $this->DAO->insertData('Tb_AlojamientoCurso',$data);

            }
        }

        $this->response($response,200);
    }

    //traer solo los id
    function alojamientoCurso_get(){
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
                $data = $this->DAO->selectEntity('Tb_AlojamientoCurso',array('idAlojamientoCampamento'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('Tb_AlojamientoCurso',null,false);
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

    function alojamientoCurso_put(){
        $data = $this->put();
        $id = $this->get('id');
        $existe = $this->DAO->selectEntity('Tb_AlojamientoCurso',array('idAlojamientoCampamento'=>$id),TRUE);
        if($existe){
            if(count($data) == 0 || count($data) > 2){
                $response = array(
                    "status"=>"error",
                    "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                    "data"=>null,
                    "validations"=>array(
                      "tipoalojamiento"=>"La facultad es requerido",
                      "institucion" => "La institucion es requerida"
                    )
                );
            }else{
                $this->form_validation->set_data($data);
                $this->form_validation->set_rules('tipoalojamiento','Tipoalojamiento','callback_check_tipoalojamiento');
                $this->form_validation->set_rules('institucion','Institucion','callback_check_institucion');

                 if($this->form_validation->run()==FALSE){
                    $response = array(
                        "status"=>"error",
                        "message"=>'check the validations',
                        "data"=>null,
                        "validations"=>$this->form_validation->error_array()
                    );
                }else{

                    $data=array(
                      "fkTipoAlojamiento"=>$this->put('tipoalojamiento'),
                      "fkInstitucion"=>$this->put('institucion')
                    );

                   $response = $this->DAO->updateData('Tb_AlojamientoCurso',$data,array('idAlojamientoCampamento'=>$id));

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

    public function alojamientoCurso_delete(){
        $id = $this->get('id');
      if ($id) {
        $IdExists = $this->DAO->selectEntity('Tb_AlojamientoCurso',array('idAlojamientoCampamento'=>$id),TRUE);

        if($IdExists){
          $response = $this->DAO->deleteData('Tb_AlojamientoCurso',array('idAlojamientoCampamento'=>$id));
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

    //traer todas las edadesInstitucioneses y con el idQueune esas tablas


  //extra validations

  function check_tipoalojamiento($str){
    if ( strlen($str)>=1) {
      $Exists = $this->DAO->selectEntity('Tb_TipoAlojamiento',array('idTipoAlojamiento'=>$str),TRUE);
      if ($Exists) {
        return TRUE;
      } else {
      $this->form_validation->set_message('check_tipoalojamiento','The edad does not exist.');

        return FALSE;
      }

    } else {
      $this->form_validation->set_message('check_tipoalojamiento','The edad must have characters in length');
        return FALSE;
    }
  }

  function check_institucion($str){
    if ( strlen($str)>=1) {
      $Exists = $this->DAO->selectEntity('Tb_Institucion',array('idInstitucion'=>$str),TRUE);
      if ($Exists) {
        return TRUE;
      } else {
      $this->form_validation->set_message('check_institucion','The institution does not exist.');

        return FALSE;
      }

    } else {
      $this->form_validation->set_message('check_institucion','The institution must have characters in length');
        return FALSE;
    }
  }


}
