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
    public function aspiranteCero_get(){
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
                $data = $this->DAO->selectEntity('Vw_InfoStatusCero',array('usuario'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('Vw_InfoStatusCero',null,false);
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
    public function aspiranteUno_get(){
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
                $data = $this->DAO->selectEntity('Vw_InfoStatusUno',array('usuario'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('Vw_InfoStatusUno',null,false);
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
    public function aspiranteDos_get(){
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
                $data = $this->DAO->selectEntity('Vw_InfoStatusUno',array('usuario'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('Vw_InfoStatusUno',null,false);
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
    function inglesGeneral_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Tb_Aspirante_Ingles_C_A_I',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_Aspirante_Ingles_C_A_I'),
            );
        }
        $this->response($response,200);
    }


        function institucionBysteps_get(){
            $idTw = $this->get('idTw');
            $idTh = $this->get('idTh');
            if($idTw){
                 $response = array(
                    "status"=>"success",
                    "message"=> '',
                    "data"=>$this->DAO->selectEntityIngles('Vw_InglesInst',array('idTipoCurso'=>$idTw),array('idTipoAlojamiento'=>$idTh)),
                );
            }else{
                $response = array(
                    "status"=>"success",
                    "message"=> '',
                    "data"=>$this->DAO->selectEntityIngles('Vw_InglesInst'),
                );

            }
            $this->response($response,200);
        }

        function englishnewInstselected_get(){
            $id = $this->get('id');
            if($id){
                 $response = array(
                    "status"=>"success",
                    "message"=> '',
                    "data"=>$this->DAO->select('Vw_InfoEnglishInsSelect',array('idAspirante'=>$id)),
                );
            }else{
                $response = array(
                    "status"=>"success",
                    "message"=> '',
                    "data"=>$this->DAO->selectEntity('Vw_InfoEnglishInsSelect'),
                );
            }
            $this->response($response,200);
        }
        function recomendtion_get(){
            $id = $this->get('id');
            if($id){
                 $response = array(
                    "status"=>"success",
                    "message"=> '',
                    "data"=>$this->DAO->select('Tb_RecomendacionAspirante',array('fkAspirante'=>$id)),
                );
            }else{
                $response = array(
                    "status"=>"success",
                    "message"=> '',
                    "data"=>$this->DAO->selectEntity('Tb_RecomendacionAspirante'),
                );
            }
            $this->response($response,200);
        }
        function englishinfoSteps_get(){
            $id = $this->get('id');
            if($id){
                 $response = array(
                    "status"=>"success",
                    "message"=> '',
                    "data"=>$this->DAO->select('Vw_InfoEnglishApirante',array('idAspirante'=>$id)),
                );
            }else{
                $response = array(
                    "status"=>"success",
                    "message"=> '',
                    "data"=>$this->DAO->selectEntity('Vw_InfoEnglishApirante'),
                );
            }
            $this->response($response,200);
        }

        function englishnewInst_get(){
            $id = $this->get('id');
            if($id){
                 $response = array(
                    "status"=>"success",
                    "message"=> '',
                    "data"=>$this->DAO->select('Tb_Institucion',array('idInstitucion'=>$id)),
                );
            }else{
                $response = array(
                    "status"=>"success",
                    "message"=> '',
                    "data"=>$this->DAO->selectEntity('Tb_Institucion'),
                );
            }
            $this->response($response,200);
        }
        function englishnewStudents_get(){
            $id = $this->get('id');
            if($id){
                 $response = array(
                    "status"=>"success",
                    "message"=> '',
                    "data"=>$this->DAO->select('Vw_InfoEnglish',array('idAspirante'=>$id)),
                );
            }else{
                $response = array(
                    "status"=>"success",
                    "message"=> '',
                    "data"=>$this->DAO->selectEntity('Vw_InfoEnglish'),
                );
            }
            $this->response($response,200);
        }

    function aspiranteIngles_C_A_post($id=null){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 11){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(
                      "aspirante"=>"Required, The name is required",
                      "campamento"=>"Optional, the url is optional",
                      "alojamiento"=>"Required, The type of campus ir required",
                )
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('aspirante','aspirante','required');
            $this->form_validation->set_rules('campamento','campamento','required');
            $this->form_validation->set_rules('alojamiento','alojamiento','required');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'check the validations',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{

               $data=array(
                   "fkAspirante"=>$this->post('aspirante'),
                   "fkCurso"=>$this->post('campamento'),
                   "fkAlojamiento"=>$this->post('alojamiento')
               );
               $response = $this->DAO->insertData('Tb_Aspirante_Ingles_C_A_I',$data);

             }
        }

        $this->response($response,200);
    }

    function aspiranteIngles_C_A_put($id=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectEntity('Tb_Aspirante_Ingles_C_A_I',array('fkAspirante'=>$id),TRUE);
        if($Eixist){
          if(count($data) == 0 || count($data) > 12){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data),
                  "data"=>null,
                  "validations"=>array(
                    "campusone"=>"Required, The name is required",
                    "campustwo"=>"Required, The ubication is required",
                    "campusthree"=>"Optional, the url is optional",
                    "mesanio"=>"Optional, the url is optional",
                  )
              );
          }else{
              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('campusone','campusone','required');
              $this->form_validation->set_rules('campustwo','campustwo','required');
              $this->form_validation->set_rules('campusthree','campusthree','required');
              $this->form_validation->set_rules('mesanio','Mes y A','required');

             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'data received',
                      "data"=>$data,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{
               $data = array(
                 "fkInstitutoOne"=>$this->put('campusone'),
                 "fkInstitutoTwo"=>$this->put('campustwo'),
                 "fkInstitutoThree"=>$this->put('campusthree'),
                 "anioMesIngreso"=>$this->put('mesanio')
               );
               $response = $this->DAO->updateData('Tb_Aspirante_Ingles_C_A_I',$data,array('fkAspirante'=>$id));
               }
          }
        }else{
          $response = array(
            "status"=>"error",
            "message"=> "check the id",
            "data"=>$Eixist,
            );
        }
        $this->response($response,200);
    }

    function inglesFile_put($id=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectEntity('Tb_DocumentosVerano',array('fkAspirante'=>$id),TRUE);
        if($Eixist){
          if(count($data) == 0 || count($data) > 2){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data),
                  "data"=>null,
                  "validations"=>array(
                    "status"=>"Required, The Status is required",
                  )
              );
          }else{
              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('status','Status','required');

             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'data received',
                      "data"=>$data,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{

                 if($this->put('status') == "Rechazado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>$this->put('status')
                   );
                   $statusResponse =$this->DAO->updateData('Tb_DocumentosVerano',$status,array('fkAspirante'=>$id));
                    if($statusResponse['status']=="success"){

                      $recomendation = array(
                        "descripcion"=>$this->put('desc'),
                        "fkAspirante"=>$id
                      );
                      $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendacionAspirante',$recomendation,null,true);
                      if($recomendationResponse['status']=="success"){
                          $response = array(
                             "status"=>"success",
                             "message"=>"update successfully",
                             "data"=>null,
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>  $recomendationResponse['message'],
                              "data"=>null,
                          );
                      }
                      if($this->db->trans_status()==FALSE){
                          $this->db->trans_rollback();
                      }else{
                          $this->db->trans_commit();
                      }
                    }else{
                      $response = array(
                          "status"=>"error",
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }

                 }else if($this->put('status') == "Aceptado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>"Aceptado"
                   );
                   $statusResponse = $this->DAO->saveOrUpdateItem('Tb_DocumentosVerano',$status,array('fkAspirante'=>$id),true);
                    if($statusResponse['status']=="success"){

                      $recomendationResponse = $this->DAO->deleteData('Tb_RecomendacionAspirante',array('fkAspirante'=>$id));

                      if($recomendationResponse['status']=="success"){
                          $response = array(
                             "status"=>"success",
                             "message"=>"update successfully",
                             "data"=>$statusResponse,
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>  $recomendationResponse['message'],
                              "data"=>null,
                          );
                      }
                      if($this->db->trans_status()==FALSE){
                          $this->db->trans_rollback();
                      }else{
                          $this->db->trans_commit();
                      }
                    }else{
                      $response = array(
                          "status"=>"error",
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }
                 }else {
                   $response = array(
                       "status"=>"error",
                       "message"=>  'error',
                       "data"=>null,
                   );
                 }
               // $data = array(
               //   "statusDocumento"=>$this->put('status'),
               // );
               // $response = $this->DAO->updateData('Tb_DocumentosVerano',$data,array('fkAspirante'=>$id));
               }
          }
        }else{
          $response = array(
            "status"=>"error",
            "message"=> "check the id",
            "data"=>$Eixist,
            );
        }
        $this->response($response,200);
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
