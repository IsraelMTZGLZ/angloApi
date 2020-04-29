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
    //Tb_InstitucionFacultad
    function universidad_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 2){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(
                    "facultad"=>"La facultad es requerido",
                    "institucion" => "La institucion es requerida"
                )
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('facultad','Facultad','required');
            $this->form_validation->set_rules('institucion','Institucion','required');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'check the validations',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
            }else{

              $data=array(
                "fkFacultad"=>$this->post('facultad'),
                "fkInstitucion"=>$this->post('institucion')
              );
              $response = $this->DAO->insertData('Tb_InstitucionFacultad',$data);

            }
        }

        $this->response($response,200);
    }

    //traer solo los id 
    function InstitucionFacultad_get(){
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
                $data = $this->DAO->selectEntity('InstitucionFacultad',array('idInstitucionFacultad'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('InstitucionFacultad',null,false);
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

    function universidad_put(){
        $data = $this->put();
        $id = $this->get('id');
        $existe = $this->DAO->selectEntity('Tb_InstitucionFacultad',array('idInstitucionFacultad'=>$id),TRUE);
        if($existe){
            if(count($data) == 0 || count($data) > 2){
                $response = array(
                    "status"=>"error",
                    "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                    "data"=>null,
                    "validations"=>array(
                        "facultad"=>"La facultad es requerido",
                        "institucion" => "La institucion es requerida"
                    )
                );
            }else{
                $this->form_validation->set_data($data);
                $this->form_validation->set_rules('facultad','Facultad','required');
                $this->form_validation->set_rules('institucion','institucion','required');
    
                 if($this->form_validation->run()==FALSE){
                    $response = array(
                        "status"=>"error",
                        "message"=>'check the validations',
                        "data"=>null,
                        "validations"=>$this->form_validation->error_array()
                    );
                }else{
    
                    $data=array(
                       "fkFacultad"=>$this->put('facultad'),
                       "fkInstitucion"=>$this->put('institucion')
                    );

                   $response = $this->DAO->updateData('Tb_InstitucionFacultad',$data,array('idInstitucionFacultad'=>$id));
    
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

    public function universidad_delete(){
        $id = $this->get('id');
      if ($id) {
        $IdExists = $this->DAO->selectEntity('Tb_InstitucionFacultad',array('idInstitucionFacultad'=>$id),TRUE);

        if($IdExists){
          $response = $this->DAO->deleteData('Tb_InstitucionFacultad',array('idInstitucionFacultad'=>$id));
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

    //traer todas las universidades y con el idQueune esas tablas
    function universidad_get(){
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
              $data = $this->DAO->selectEntity('Vw_Uni',array('idInstitucionFacultad'=>$id),true);
          }
          else{
              $data = $this->DAO->selectEntity('Vw_Uni',null,false);
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

    public function universidadOrdenByInstitucion_get()
    {
        if (count($this->get())>1) {
            $response = array(
                "status" => "error",
                "status_code" => 409,
                "message" => "Too many data was sent",
                "validations" =>array(
                        "id"=>"send Id (get) to get specific item or empty to get all items "
                ),
                "data"=>null
            );
        }else{
            
            $query = $this->db->query("select * from Vw_Uni group by idInstitucion")->result();
            

            if ($query) {
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "Articulo cargado correctamente",
                    "validations" =>null,
                    "data"=>$query
                );
            }else{
                $response = array(
                    "status" => "error",
                    "status_code" => 409,
                    "message" => "No datos proveidos",
                    "validations" =>null,
                    "data"=>null
                );
            }
            
            
        }
        $this->response($response,200);
    }

  function universidadByInstitucion_get(){
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
            $data = $this->DAO->selectEntity('Vw_Uni',array('idInstitucion'=>$id),false);
        }else{
            $data= null;
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
