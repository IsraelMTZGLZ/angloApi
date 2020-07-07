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

    function instSelected_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Tb_Aspirante_Eleccion',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Tb_Aspirante_Eleccion'),
            );
        }
        $this->response($response,200);
    }

    function numSelected_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Tb_NumAplicanteAcademico',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Tb_NumAplicanteAcademico'),
            );
        }
        $this->response($response,200);
    }

    function docsAcepted_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('vw_DocumentosVI',array('idAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('vw_DocumentosVI'),
            );
        }
        $this->response($response,200);
    }

    function fileVeranoIngles_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Tb_formdesolicitudVeranoIngAspirante',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Tb_formdesolicitudVeranoIngAspirante'),
            );
        }
        $this->response($response,200);
    }

    function fileinfoVisa_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Tb_DocVerIngVisa',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_DocVerIngVisa'),
            );
        }
        $this->response($response,200);
    }

    function recomendationVisa_get(){
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
    function aspiranteVerInglesBYAspirante_get(){
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
                $data = $this->DAO->selectEntity('Vw_InfoVeranoApirante',array('idAspirante'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('Vw_InfoVeranoApirante',null,false);
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

    //new Jul 07
    public function numAplication_get(){
        $id = $this->get('id');
        $idDocForm = $this->get('idDocForm');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectbyTwoEntity('Tb_NumAplicanteAcademico',array('fkAspirante'=>$id),array('fkInstitucion'=>$idDocForm),TRUE),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_NumAplicanteAcademico'),
            );
        }
        $this->response($response,200);
    }
    function numAplicante_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 4){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(
                    "numeroacademico"=>"El nombre es requerido",
                    "fkAspirante" => "Aspirante requerido",
                    "fkInstitucion" => "Instituciones requerido"
                )
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('numeroacademico','numeroacademico','required');
            $this->form_validation->set_rules('fkAspirante','Aspirante','required');
            $this->form_validation->set_rules('fkInstitucion','Instituciones','required');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'check the validations',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{

               $data=array(
                   "numeroAplicante"=>$this->post('numeroacademico'),
                   "fkAspirante"=>$this->post('fkAspirante'),
                   "fkInstitucion"=>$this->post('fkInstitucion')
               );

               $Response = $this->DAO->saveOrUpdateItem('Tb_NumAplicanteAcademico',$data,null,true);

               if($Response['status']=="success"){
                  // $this->cambiarEstatus($this->post('fkAspirante'),'3');
                   $response = array(
                      "status"=>"success",
                      "message"=>"update successfullyy",
                      "data"=>$Response,
                  );
                  if($this->db->trans_status()==FALSE){
                      $this->db->trans_rollback();
                  }else{
                      $this->db->trans_commit();
                  }

               }else{
                   $response = array(
                       "status"=>"error",
                       "message"=>  $data,
                       "data"=>null,
                   );
               }
             }
        }

        $this->response($response,200);
    }
    //

    public function pasaporteUpdateTest_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {
              $Eixist = $this->DAO->selectEntity('Tb_DocumentosVerano',array('fkAspirante'=>$id),TRUE);
              if($Eixist){
                $response = $this->DAO->deleteData('Tb_DocumentosVerano',array('fkAspirante'=>$id));
                if($response['status']=="success"){

                  $carpeta = 'files/Verano/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/Verano/".$id,
                      "allowed_types"=>"pdf",
                      "file_name"=>"Pasaporte",
                      "overwrite"=>true
                  );

                  $this->load->library('upload',$config);
                  if ( ! $this->upload->do_upload('Pasaporte'))
                  {
                  $response=array(
                      "status"=>"error",
                      "status_code"=>409,
                      "message"=>"Upload fails",
                      "validations"=>$this->upload->display_errors(),
                      "data"=>$this->post()
                  );
                  }
                  else
                  {
                      $data = array(

                          "nombreDocumento"=>$this->upload->data('file_name'),
                          "extDocumento"=>$this->upload->data()['file_ext'],
                          "urlDocumento"=>base_url('files/Verano/'.$id.'/'.$this->upload->data('file_name')),
                          "typeDocumento"=>$this->upload->data('file_type'),
                          "tipo"=>"Pasaporte",
                          "statusDocumento"=>"Revision",
                          "fkAspirante"=>$this->post('aspirante')
                      );

                      $response = $this->DAO->insertData('Tb_DocumentosVerano',$data);
                      if($response['status']=="success"){
                        $this->cambiarEstatus($id,'1');
                        $response = array(
                          "status"=>"success",
                          "message"=>"Fichero fue subido correctamente",
                          "data"=>$data
                        );
                      }
                  }
                }else{
                  $response=array(
                      "status"=>"error",
                      "status_code"=>409,
                      "message"=>"The documento was not deleted correctly",
                      "data"=>null
                  );
                }
              }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Document does not exists",
                    "data"=>null
                );
              }
            }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"id does not exist",
                    "validations"=>array(
                        "id"=>"required (get)"
                    ),
                    "data"=>null
                );
            }


            //a
        }else{
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>"id was not sent",
                "validations"=>array(
                    "id"=>"required (get)"
                ),
                "data"=>null
            );
        }
        $this->response($response,200);
    }
    function veranoIngles_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Tb_formdesolicitudVeranoIngAspirante',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_formdesolicitudVeranoIngAspirante'),
            );
        }
        $this->response($response,200);
    }
    function statusCero_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Vw_VeranoStatusCero',array('idPersona'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_VeranoStatusCero'),
            );
        }
        $this->response($response,200);
    }
    function statusUno_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Vw_VeranoStatusUno',array('idPersona'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_VeranoStatusUno'),
            );
        }
        $this->response($response,200);
    }
    function statusDos_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Vw_VeranoStatusDos',array('idPersona'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_VeranoStatusDos'),
            );
        }
        $this->response($response,200);
    }

    function statusDosR_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Vw_VeranoStatusDosR',array('idAspirante'=>$id)),
            );
        }else{
            $response = array(
               "status"=>"success",
               "message"=> '',
               "data"=>$this->DAO->selectEntity('Vw_VeranoStatusDosR'),
            );
        }
        $this->response($response,200);
    }
    function statusTres_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Vw_VeranoStatusTres',array('idPersona'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_VeranoStatusTres'),
            );
        }
        $this->response($response,200);
    }


    public function pasaporte_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {

                $carpeta = 'files/Verano/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"files/Verano/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>"Pasaporte",
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('Pasaporte'))
                {
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Upload fails",
                    "validations"=>$this->upload->display_errors(),
                    "data"=>$this->post()
                );
                }
                else
                {
                    $data = array(

                        "nombreDocumento"=>$this->upload->data('file_name'),
                        "extDocumento"=>$this->upload->data()['file_ext'],
                        "urlDocumento"=>base_url('files/Verano/'.$id.'/'.$this->upload->data('file_name')),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"Pasaporte",
                        "statusDocumento"=>"Revision",
                        "fkAspirante"=>$this->post('aspirante')
                    );

                    $response = $this->DAO->insertData('Tb_DocumentosVerano',$data);
                    if($response['status']=="success"){
                      $this->cambiarEstatus($id,'2');
                      $response = array(
                        "status"=>"success",
                        "message"=>"Fichero fue subido correctamente",
                        "data"=>$data
                      );
                    }
                }


            }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"id does not exist",
                    "validations"=>array(
                        "id"=>"required (get)"
                    ),
                    "data"=>null
                );
            }


            //a
        }else{
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>"id was not sent",
                "validations"=>array(
                    "id"=>"required (get)"
                ),
                "data"=>null
            );
        }
        $this->response($response,200);
    }

    public function pasaporteUpdate_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {
              $Eixist = $this->DAO->selectEntity('Tb_DocumentosVerano',array('fkAspirante'=>$id),TRUE);
              if($Eixist){
                $response = $this->DAO->deleteData('Tb_DocumentosVerano',array('fkAspirante'=>$id));
                if($response['status']=="success"){

                  $carpeta = 'files/Verano/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/Verano/".$id,
                      "allowed_types"=>"pdf",
                      "file_name"=>"Pasaporte",
                      "overwrite"=>true
                  );

                  $this->load->library('upload',$config);
                  if ( ! $this->upload->do_upload('Pasaporte'))
                  {
                  $response=array(
                      "status"=>"error",
                      "status_code"=>409,
                      "message"=>"Upload fails",
                      "validations"=>$this->upload->display_errors(),
                      "data"=>$this->post()
                  );
                  }
                  else
                  {
                      $data = array(

                          "nombreDocumento"=>$this->upload->data('file_name'),
                          "extDocumento"=>$this->upload->data()['file_ext'],
                          "urlDocumento"=>base_url('files/Verano/'.$id.'/'.$this->upload->data('file_name')),
                          "typeDocumento"=>$this->upload->data('file_type'),
                          "tipo"=>"Pasaporte",
                          "statusDocumento"=>"Revision",
                          "fkAspirante"=>$this->post('aspirante')
                      );

                      $response = $this->DAO->insertData('Tb_DocumentosVerano',$data);
                      if($response['status']=="success"){
                        $this->cambiarEstatus($id,'2');
                        $response = array(
                          "status"=>"success",
                          "message"=>"Fichero fue subido correctamente",
                          "data"=>$data
                        );
                      }
                  }
                }else{
                  $response=array(
                      "status"=>"error",
                      "status_code"=>409,
                      "message"=>"The documento was not deleted correctly",
                      "data"=>null
                  );
                }
              }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Document does not exists",
                    "data"=>null
                );
              }
            }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"id does not exist",
                    "validations"=>array(
                        "id"=>"required (get)"
                    ),
                    "data"=>null
                );
            }


            //a
        }else{
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>"id was not sent",
                "validations"=>array(
                    "id"=>"required (get)"
                ),
                "data"=>null
            );
        }
        $this->response($response,200);
    }


    public function visa_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {

                $carpeta = 'files/VeranoIngles/Visa/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"files/VeranoIngles/Visa/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>"Visa",
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('Visa'))
                {
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Upload fails",
                    "validations"=>$this->upload->display_errors(),
                    "data"=>$this->post()
                );
                }
                else
                {
                    $data = array(

                        "nombreDocumento"=>$this->upload->data('file_name'),
                        "extDocumento"=>$this->upload->data()['file_ext'],
                        "urlDocumento"=>base_url('files/VeranoIngles/Visa/'.$id.'/'.$this->upload->data('file_name')),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"Visa",
                        "statusDocumento"=>"Revision",
                        "fkAspirante"=>$this->post('aspirante')
                    );

                    $response = $this->DAO->insertData('Tb_DocVerIngVisa',$data);
                    if($response['status']=="success"){
                      // $this->cambiarEstatus($id,'2');
                      $response = array(
                        "status"=>"success",
                        "message"=>"Fichero fue subido correctamente",
                        "data"=>$data
                      );
                    }
                }


            }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"id does not exist",
                    "validations"=>array(
                        "id"=>"required (get)"
                    ),
                    "data"=>null
                );
            }


            //a
        }else{
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>"id was not sent",
                "validations"=>array(
                    "id"=>"required (get)"
                ),
                "data"=>null
            );
        }
        $this->response($response,200);
    }

    function recomendtionVisa_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Tb_RecomenVisaAspirante',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_RecomenVisaAspirante'),
            );
        }
        $this->response($response,200);
    }

    function visaFile_put($id=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectEntity('Tb_DocVerIngVisa',array('fkAspirante'=>$id),TRUE);
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
                   $statusResponse =$this->DAO->updateData('Tb_DocVerIngVisa',$status,array('fkAspirante'=>$id));
                    if($statusResponse['status']=="success"){

                      $recomendation = array(
                        "descripcion"=>$this->put('desc'),
                        "fkAspirante"=>$id
                      );
                      $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomenVisaAspirante',$recomendation,null,true);

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
                   $statusResponse = $this->DAO->saveOrUpdateItem('Tb_DocVerIngVisa',$status,array('fkAspirante'=>$id),true);
                    if($statusResponse['status']=="success"){

                      $recomendationResponse = $this->DAO->deleteData('Tb_RecomenVisaAspirante',array('fkAspirante'=>$id));

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

    public function visaUpdate_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {
              $Eixist = $this->DAO->selectEntity('Tb_DocVerIngVisa',array('fkAspirante'=>$id),TRUE);
              if($Eixist){
                $response = $this->DAO->deleteData('Tb_DocVerIngVisa',array('fkAspirante'=>$id));
                if($response['status']=="success"){

                  $carpeta = 'files/VeranoIngles/Visa/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/VeranoIngles/Visa/".$id,
                      "allowed_types"=>"pdf",
                      "file_name"=>"Visa",
                      "overwrite"=>true
                  );

                  $this->load->library('upload',$config);
                  if ( ! $this->upload->do_upload('Visa'))
                  {
                  $response=array(
                      "status"=>"error",
                      "status_code"=>409,
                      "message"=>"Upload fails",
                      "validations"=>$this->upload->display_errors(),
                      "data"=>$this->post()
                  );
                  }
                  else
                  {
                      $data = array(

                          "nombreDocumento"=>$this->upload->data('file_name'),
                          "extDocumento"=>$this->upload->data()['file_ext'],
                          "urlDocumento"=>base_url('files/VeranoIngles/Visa/'.$id.'/'.$this->upload->data('file_name')),
                          "typeDocumento"=>$this->upload->data('file_type'),
                          "tipo"=>"Visa",
                          "statusDocumento"=>"Revision",
                          "fkAspirante"=>$this->post('aspirante')
                      );

                      $response = $this->DAO->insertData('Tb_DocVerIngVisa',$data);
                      if($response['status']=="success"){
                        // $this->cambiarEstatus($id,'2');
                        $response = array(
                          "status"=>"success",
                          "message"=>"Fichero fue subido correctamente",
                          "data"=>$data
                        );
                      }
                  }
                }else{
                  $response=array(
                      "status"=>"error",
                      "status_code"=>409,
                      "message"=>"The documento was not deleted correctly",
                      "data"=>null
                  );
                }
              }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Document does not exists",
                    "data"=>null
                );
              }
            }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"id does not exist",
                    "validations"=>array(
                        "id"=>"required (get)"
                    ),
                    "data"=>null
                );
            }


            //a
        }else{
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>"id was not sent",
                "validations"=>array(
                    "id"=>"required (get)"
                ),
                "data"=>null
            );
        }
        $this->response($response,200);
    }


    public function formatoSolicitud_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {

                $carpeta = 'files/VeranoIngles/FormDeSolicitud/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"files/VeranoIngles/FormDeSolicitud/".$id,
                    "allowed_types"=>"pdf",
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('formatoDeSolicitud'))
                {
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Upload fails",
                    "validations"=>$this->upload->display_errors(),
                    "data"=>$this->post()
                );
                }
                else
                {
                    $data = array(

                        "nombreDocumento"=>$this->upload->data('file_name'),
                        "extDocumento"=>$this->upload->data()['file_ext'],
                        "urlDocumento"=>base_url('files/VeranoIngles/FormDeSolicitud/'.$id.'/'.$this->upload->data('file_name')),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"formato de solicitud",
                        "statusDocumento"=>"Activo",
                        "fkAspirante"=>$id
                    );

                    $response = $this->DAO->insertData('Tb_formdesolicitudVeranoIngAspirante',$data);
                    if($response['status']=="success"){
                        $this->cambiarEstatus($id,'2R');
                      $response=array(
                          "status"=>"success",
                          "status_code"=>409,
                          "message"=>"Success the document was upload correctly",
                          "data"=>null
                      );
                    }
                }


            }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"id does not exist",
                    "validations"=>array(
                        "id"=>"required (get)"
                    ),
                    "data"=>null
                );
            }


            //a
        }else{
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>"id was not sent",
                "validations"=>array(
                    "id"=>"required (get)"
                ),
                "data"=>null
            );
        }
        $this->response($response,200);
    }

    //new TODAY
    function infoAspirante_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Tb_Aspirantes',array('idAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_Aspirantes'),
            );
        }
        $this->response($response,200);
    }
    //

    public function formatoSolicitudUpdate_post(){
        $id=$this->get('id');
        $idDocForm = $this->get('idDocForm');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {
              $Eixist = $this->DAO->selectbyTwoEntity('Tb_formdesolicitudVeranoIngAspirante',array('fkAspirante'=>$id),array('idDocumento'=>$idDocForm),TRUE);
              if($Eixist){
                // $response = $this->DAO->deleteDataTwoClause('Tb_formdesolicitudVeranoIngAspirante',array('fkAspirante'=>$id),array('idDocumento'=>$idDocForm));


                  $carpeta = 'files/VeranoIngles/FormDeSolicitud/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/VeranoIngles/FormDeSolicitud/".$id,
                      "allowed_types"=>"pdf",
                      "overwrite"=>true
                  );

                  $this->load->library('upload',$config);
                  if ( ! $this->upload->do_upload('formatoDeSolicitud'))
                  {
                  $response=array(
                      "status"=>"error",
                      "status_code"=>409,
                      "message"=>"Upload fails",
                      "validations"=>$this->upload->display_errors(),
                      "data"=>$this->post()
                  );
                  }
                  else
                  {
                      $data = array(

                          "nombreDocumento"=>$this->upload->data('file_name'),
                          "extDocumento"=>$this->upload->data()['file_ext'],
                          "urlDocumento"=>base_url('files/VeranoIngles/FormDeSolicitud/'.$id.'/'.$this->upload->data('file_name')),
                          "typeDocumento"=>$this->upload->data('file_type'),
                          "tipo"=>"formato de solicitud ",
                          "statusDocumento"=>"Activo",
                          "fkAspirante"=>$id
                      );

                      $response = $this->DAO->updateByTwoData('Tb_formdesolicitudVeranoIngAspirante',$data,array('fkAspirante'=>$id),array('idDocumento'=>$idDocForm));
                      if($response['status']=="success"){
                        $response=array(
                            "status"=>"success",
                            "status_code"=>409,
                            "message"=>"Success the document was upload correctlyy",
                            "data"=>$response
                        );
                      }
                  }

              }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Document does not exists",
                    "data"=>null
                );
              }
            }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"id does not exist",
                    "validations"=>array(
                        "id"=>"required (get)"
                    ),
                    "data"=>null
                );
            }
        }else{
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>"id was not sent",
                "validations"=>array(
                    "id"=>"required (get)"
                ),
                "data"=>null
            );
        }
        $this->response($response,200);
    }




    function inglesFormFile_put(){
        $id = $this->get('id');
        $idDocForm = $this->get('idDocForm');

        $data = $this->put();
        $Eixist = $this->DAO->selectbyTwoEntity('Tb_formdesolicitudVeranoIngAspirante',array('fkAspirante'=>$id),array('idDocumento'=>$idDocForm),TRUE);

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
                   $statusResponse =$this->DAO->updateByTwoData('Tb_formdesolicitudVeranoIngAspirante',$status,array('fkAspirante'=>$id),array('idDocumento'=>$idDocForm));
                    if($statusResponse['status']=="success"){
                      $EixistRecomendation = $this->DAO->selectbyTwoEntity('Tb_RecomendacionAspiranteForm',array('fkAspirante'=>$id),array('fkDocumento'=>$idDocForm),TRUE);
                      if($EixistRecomendation){
                        $recomendationResponse = $this->DAO->deleteDataTwoClause('Tb_RecomendacionAspiranteForm',array('fkAspirante'=>$id),array('fkDocumento'=>$idDocForm));

                        if($recomendationResponse['status']=="success"){
                          $recomendation = array(
                            "descripcion"=>$this->put('desc'),
                            "fkAspirante"=>$id,
                            "fkDocumento"=>$idDocForm
                          );
                          $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendacionAspiranteForm',$recomendation,null,true);

                          if($recomendationResponse['status']=="success"){
                              $response = array(
                                 "status"=>"success",
                                 "message"=>"update successfullyy",
                                 "data"=>$recomendationResponse,
                             );
                             if($this->db->trans_status()==FALSE){
                                 $this->db->trans_rollback();
                             }else{
                                 $this->db->trans_commit();
                             }

                          }else{
                              $response = array(
                                  "status"=>"error",
                                  "message"=>  $recomendationResponse['message'],
                                  "data"=>null,
                              );
                          }
                        }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>  $recomendationResponse['message'],
                              "data"=>null,
                          );
                        }
                        }else{
                          $recomendation = array(
                            "descripcion"=>$this->put('desc'),
                            "fkAspirante"=>$id,
                            "fkDocumento"=>$idDocForm
                          );
                          $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendacionAspiranteForm',$recomendation,null,true);

                          if($recomendationResponse['status']=="success"){
                              $response = array(
                                 "status"=>"success",
                                 "message"=>"update successfullyy",
                                 "data"=>$recomendationResponse,
                             );
                             if($this->db->trans_status()==FALSE){
                                 $this->db->trans_rollback();
                             }else{
                                 $this->db->trans_commit();
                             }

                          }else{
                              $response = array(
                                  "status"=>"error",
                                  "message"=>  $recomendationResponse['message'],
                                  "data"=>null,
                              );
                          }
                        }
                      }else{
                        $response = array(
                            "status"=>"error",
                            "message"=>  'error',
                            "data"=>null,
                        );
                      }
                     //  $response = array(
                     //     "status"=>"success",
                     //     "message"=>"update successfully",
                     //     "data"=>null,
                     // );




                 }else if($this->put('status') == "Aceptado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>"Aceptado"
                   );
                   $statusResponse = $this->DAO->updateByTwoData('Tb_formdesolicitudVeranoIngAspirante',$status,array('fkAspirante'=>$id),array('idDocumento'=>$idDocForm));
                    if($statusResponse['status']=="success"){

                      $recomendationResponse = $this->DAO->deleteData('Tb_RecomendacionAspirante',array('fkAspirante'=>$id));

                      if($recomendationResponse['status']=="success"){
                          $response = array(
                             "status"=>"success",
                             "message"=>"update successfully thanks",
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
               // $response = $this->DAO->updateData('Tb_formdesolicitudVeranoIngAspirante',$data,array('fkAspirante'=>$id));
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
    function statusAspirante_put($id=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),TRUE);
        if($Eixist){
          if(count($data) == 0 || count($data) > 4){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                  "data"=>null,
                  "validations"=>array(
                    "status"=>"The name is required"
                  )
              );
          }else{
              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('status','Status','required');

             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'Too many data received',
                      "data"=>null,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{
               $data = array(
                 "statusAspirante"=>$this->put('status'),
                 "idAspirante"=>$id
               );
               $response = $this->DAO->updateData('Tb_Aspirantes',$data,array('idAspirante'=>$id));
               }
          }
        }else{
          $response = array(
            "status"=>"error",
            "message"=> "check the id id",
            "data"=>null,
            );
        }
        $this->response($response,200);
    }

    function recomendtion_get(){
        $id = $this->get('id');
        $idDocForm = $this->get('idDocForm');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectbyTwoEntity('Tb_RecomendacionAspiranteForm',array('fkAspirante'=>$id),array('fkDocumento'=>$idDocForm),TRUE),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_RecomendacionAspiranteForm'),
            );
        }
        $this->response($response,200);
    }






    public function cambiarEstatus($id,$Status)
    {
        $item = $this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

        if($item->statusAspirante!='2'){
            $data=array(
                "statusAspirante"=>$Status
            );
            $this->DAO->updateData('Tb_Aspirantes',$data,array('idAspirante'=>$id));
        }
    }


    /**exta validations**/



}
