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

    function aspiranteUniversidades_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) >3){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No se recibio datos' : 'Demasiados datos recibidos',
                "data"=>null,
                "validations"=>array(
                    "aspirante"=>"El aspirante es requerido",
                    "eau" => "La eau es requerida",
                    "facultad" => "La facultad es requerida"
                )
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('aspirante','Aspirante FK','required');
            $this->form_validation->set_rules('eau','Estudios Universidad','required');
            $this->form_validation->set_rules('facultad','Facultad','required');

            if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'Revisa las validaciones',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
            }else{

                $data=array(
                   "fkAspirante"=>$this->post('aspirante'),
                   "estudiosAspiranteUniversidad"=>$this->post('eau'),
                   "fkFacultad"=>$this->post('facultad')
                );

                $response = $this->DAO->insertData('Tb_AspiranteUniversidades',$data);

            }
        }

        $this->response($response,200);
    }

    function aspiranteUniversidadesBYAspirante_get(){
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
                $data = $this->DAO->selectEntity('Vw_AspiranteUniversidad',array('fkAspirante'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('Vw_AspiranteUniversidad',null,false);
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

    //insertar en la mini tabla de aspirante universidad y los campos faltantes
    function aspiranteUniversidadesFacultades_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) >3){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No se recibio datos' : 'Demasiados datos recibidos',
                "data"=>null,
                "validations"=>array(
                    "instituciones"=>"Las instituciones son requeridas",
                    "aspiranteUniversidad" => "La fk aspirante universidad es requerida",
                    "anioMes" => "El año y mes es requerido"
                )
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('instituciones[]','Instituciones','required');
            $this->form_validation->set_rules('aspiranteUniversidad','Aspirante Universidad','required');
            $this->form_validation->set_rules('anioMes','año y mes','required');

            if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'Revisa las validaciones',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
            }else{
                $data=array(
                    "anioMesIngreso"=>$this->post('anioMes')
                );

                $this->db->trans_begin();

                $this->db->where(array('idAspiranteUniversidad'=>$this->post('aspiranteUniversidad')));
                $this->db->update('Tb_AspiranteUniversidades',$data);
                if($this->db->error()['message']){
                    $responseDB['message'] = $this->db->error()['message'];
                }

                for ($i=0; $i < count($this->post('instituciones')); $i++) { 
                    $dataIn = array(
                        "fkAspiranteUniversidad"=>$this->post('aspiranteUniversidad'),
                        "fkInstitucion"=>$this->post('instituciones')[$i]
                    );
                    $this->db->insert('Tb_InstitucionAspiranteUniversidades',$dataIn);
                    if($this->db->error()['message']){
                        $responseDB['message'] = $this->db->error()['message'];
                    }
                }

                if ($this->db->trans_status() == FALSE) {
                    $this->db->trans_rollback();
                    $response=array(
                        "status"=>"error",
                        "status_code"=>409,
                        "message"=>$responseDB['message'],
                        "data"=>$dataIn
                    );
                  }
                  else{
                      $this->db->trans_commit();
                      $response=array(
                        "status"=>"success",
                        "status_code"=>201,
                        "message"=>"Articulo Creado Exitosamente",
                        "data"=>null,
                        "password"=>null
                      );
                  }
            }
        }

        $this->response($response,200);
    }

}
