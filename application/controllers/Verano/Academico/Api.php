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


    function fileVeranoFormAcademic_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Tb_formsolicitudVAcademicoAspirante',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Tb_formsolicitudVAcademicoAspirante'),
            );
        }
        $this->response($response,200);
    }
    function fileVeranoAcademico_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Tb_formsolicitudVeranoAcademico',array('fkInstitucion'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Tb_formsolicitudVeranoAcademico'),
            );
        }
        $this->response($response,200);
    }

    //new 05
    function aplicantNumber_get(){
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

    //new jul 2
    function pasaporteInfo_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Tb_DocumentosVeranoAcademico',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_DocumentosVeranoAcademico'),
            );
        }
        $this->response($response,200);
    }


        function fileFormAspirante_get(){
            $id = $this->get('id');
            if($id){
                 $response = array(
                    "status"=>"success",
                    "message"=> '',
                    "data"=>$this->DAO->selEntityMany('Tb_formsolicitudVAcademicoAspirante',array('fkAspirante'=>$id)),
                );
            }else{
                $response = array(
                    "status"=>"success",
                    "message"=> '',
                    "data"=>$this->DAO->selEntityMany('Tb_formsolicitudVAcademicoAspirante'),
                );
            }
            $this->response($response,200);
        }

        function traductionInfo_get(){
            $id = $this->get('id');
            if($id){
                 $response = array(
                    "status"=>"success",
                    "message"=> '',
                    "data"=>$this->DAO->select('Tb_TraduccionInglesAspirante',array('fkAspirante'=>$id)),
                );
            }else{
                $response = array(
                    "status"=>"success",
                    "message"=> '',
                    "data"=>$this->DAO->selectEntity('Tb_TraduccionInglesAspirante'),
                );
            }
            $this->response($response,200);
        }

        function recomendtionTraduction_get(){
            $id = $this->get('id');
            if($id){
                 $response = array(
                    "status"=>"success",
                    "message"=> '',
                    "data"=>$this->DAO->select('Tb_RecAcademicTraduccion',array('fkAspirante'=>$id)),
                );
            }else{
                $response = array(
                    "status"=>"success",
                    "message"=> '',
                    "data"=>$this->DAO->selectEntity('Tb_RecAcademicTraduccion'),
                );
            }
            $this->response($response,200);
        }
    //new 03
    function fileinfo_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Tb_DocumentosVeranoAcademico',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_DocumentosVeranoAcademico'),
            );
        }
        $this->response($response,200);
    }

    function recomendtionPassport_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Tb_PasaporteAspiranteAcademico',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_PasaporteAspiranteAcademico'),
            );
        }
        $this->response($response,200);
    }
    function transcriptionStatus_put($id=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectEntity('Tb_TranscripcionesAspirante',array('fkAspirante'=>$id),TRUE);
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
                   $statusResponse =$this->DAO->updateData('Tb_TranscripcionesAspirante',$status,array('fkAspirante'=>$id));
                    if($statusResponse['status']=="success"){

                      $recomendation = array(
                        "descripcion"=>$this->put('desc'),
                        "fkAspirante"=>$id
                      );
                      $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendacionTranscripcion',$recomendation,null,true);

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
                   $statusResponse = $this->DAO->saveOrUpdateItem('Tb_TranscripcionesAspirante',$status,array('fkAspirante'=>$id),true);
                    if($statusResponse['status']=="success"){

                      $recomendationResponse = $this->DAO->deleteData('Tb_RecomendacionTranscripcion',array('fkAspirante'=>$id));

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
    function traductionStatus_put($id=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectEntity('Tb_TraduccionInglesAspirante',array('fkAspirante'=>$id),TRUE);
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
                   $statusResponse =$this->DAO->updateData('Tb_TraduccionInglesAspirante',$status,array('fkAspirante'=>$id));
                    if($statusResponse['status']=="success"){

                      $recomendation = array(
                        "descripcion"=>$this->put('desc'),
                        "fkAspirante"=>$id
                      );
                      $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecAcademicTraduccion',$recomendation,null,true);

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
                   $statusResponse = $this->DAO->saveOrUpdateItem('Tb_TraduccionInglesAspirante',$status,array('fkAspirante'=>$id),true);
                    if($statusResponse['status']=="success"){

                      $recomendationResponse = $this->DAO->deleteData('Tb_RecAcademicTraduccion',array('fkAspirante'=>$id));

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

    function passportStatus_put($id=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectEntity('Tb_DocumentosVeranoAcademico',array('fkAspirante'=>$id),TRUE);
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
                   $statusResponse =$this->DAO->updateData('Tb_DocumentosVeranoAcademico',$status,array('fkAspirante'=>$id));
                    if($statusResponse['status']=="success"){

                      $recomendation = array(
                        "descripcion"=>$this->put('desc'),
                        "fkAspirante"=>$id
                      );
                      $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_PasaporteAspiranteAcademico',$recomendation,null,true);

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
                   $statusResponse = $this->DAO->saveOrUpdateItem('Tb_DocumentosVeranoAcademico',$status,array('fkAspirante'=>$id),true);
                    if($statusResponse['status']=="success"){

                      $recomendationResponse = $this->DAO->deleteData('Tb_PasaporteAspiranteAcademico',array('fkAspirante'=>$id));

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

    function academicoStatusFormFile_put(){
        $id = $this->get('id');
        $idDocForm = $this->get('idDocForm');

        $data = $this->put();
        $Eixist = $this->DAO->selectbyTwoEntity('Tb_formsolicitudVAcademicoAspirante',array('fkAspirante'=>$id),array('idDocumento'=>$idDocForm),TRUE);

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
                   $statusResponse =$this->DAO->updateByTwoData('Tb_formsolicitudVAcademicoAspirante',$status,array('fkAspirante'=>$id),array('idDocumento'=>$idDocForm));
                    if($statusResponse['status']=="success"){
                      $EixistRecomendation = $this->DAO->selectbyTwoEntity('Tb_RecomenAspiranteAcademicForm',array('fkAspirante'=>$id),array('fkDocumento'=>$idDocForm),TRUE);
                      if($EixistRecomendation){
                        $recomendationResponse = $this->DAO->deleteDataTwoClause('Tb_RecomenAspiranteAcademicForm',array('fkAspirante'=>$id),array('fkDocumento'=>$idDocForm));

                        if($recomendationResponse['status']=="success"){
                          $recomendation = array(
                            "descripcion"=>$this->put('desc'),
                            "fkAspirante"=>$id,
                            "fkDocumento"=>$idDocForm
                          );
                          $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomenAspiranteAcademicForm',$recomendation,null,true);

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
                          $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomenAspiranteAcademicForm',$recomendation,null,true);

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
                   $statusResponse = $this->DAO->updateByTwoData('Tb_formsolicitudVAcademicoAspirante',$status,array('fkAspirante'=>$id),array('idDocumento'=>$idDocForm));
                    if($statusResponse['status']=="success"){

                      $recomendationResponse = $this->DAO->deleteData('Tb_RecomenAspiranteAcademicForm',array('fkAspirante'=>$id));

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
                  $this->cambiarEstatus($this->post('fkAspirante'),'3');
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
                       "message"=>  $recomendationResponse['message'],
                       "data"=>null,
                   );
               }
             }
        }

        $this->response($response,200);
    }
    // function recomendtion_get(){
    //     $id = $this->get('id');
    //     $idDocForm = $this->get('idDocForm');
    //     if($id){
    //          $response = array(
    //             "status"=>"success",
    //             "message"=> '',
    //             "data"=>$this->DAO->selectbyTwoEntity('Tb_RecomenAspiranteAcademicForm',array('fkAspirante'=>$id),array('fkDocumento'=>$idDocForm),TRUE),
    //         );
    //     }else{
    //         $response = array(
    //             "status"=>"success",
    //             "message"=> '',
    //             "data"=>$this->DAO->selectEntity('Tb_RecomenAspiranteAcademicForm'),
    //         );
    //     }
    //     $this->response($response,200);
    // }

    function fileVeranoAca_get(){
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
    function docsAcepted_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Vw_DocumentosVA',array('idAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Vw_DocumentosVA'),
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
    function aspiranteVerAcademicoBYAspirante_get(){
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

    function statusUno_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Vw_VAcademicoStatusUno',array('idPersona'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_VAcademicoStatusUno'),
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
                "data"=>$this->DAO->select('Vw_VAcademicoStatusTres',array('idPersona'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_VAcademicoStatusTres'),
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
                "data"=>$this->DAO->select('Vw_VAcademicoStatusDos',array('idPersona'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_VAcademicoStatusDos'),
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
                "data"=>$this->DAO->select('Vw_VAcademicoStatusDosR',array('idAspirante'=>$id)),
            );
        }else{
            $response = array(
               "status"=>"success",
               "message"=> '',
               "data"=>$this->DAO->selectEntity('Vw_VAcademicoStatusDosR'),
            );
        }
        $this->response($response,200);
    }
    //now 01 Jul
    function recomendtionTranscription_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Tb_RecomendacionTranscripcion',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_RecomendacionTranscripcion'),
            );
        }
        $this->response($response,200);
    }
    function transcripcionesInfo_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Tb_TranscripcionesAspirante',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_TranscripcionesAspirante'),
            );
        }
        $this->response($response,200);
    }


    function veranoAcademico_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Tb_formsolicitudVeranoAcademico',array('fkInstitucion'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_formsolicitudVeranoAcademico'),
            );
        }
        $this->response($response,200);
    }

    function fileVeranoAcademicoo_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Tb_formsolicitudVeranoAcademico',array('fkInstitucion'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_formsolicitudVeranoAcademico'),
            );
        }
        $this->response($response,200);
    }

public function formatoSolicitudInstitucion_post(){
    $id=$this->get('id');
    if ($id) {
        $userExist=$this->DAO->selectEntity('Tb_Institucion',array('idInstitucion'=>$id),true);

        if ($userExist) {

            $carpeta = 'files/DocumentosVerano/'.$id;
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }

            $config =array(
                "upload_path"=>"files/DocumentosVerano/".$id,
                "allowed_types"=>"pdf",
                "file_name"=>"formatoDeSolicitud".$this->post('nameInst'),
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
                    "urlDocumento"=>base_url('files/DocumentosVerano/'.$id.'/'.$this->upload->data('file_name')),
                    "typeDocumento"=>$this->upload->data('file_type'),
                    "tipo"=>"formato de solicitud",
                    "statusDocumento"=>"Revision",
                    "fkInstitucion"=>$id
                );

                $response = $this->DAO->insertData('Tb_formsolicitudVeranoAcademico',$data);
                if($response['status']=="success"){
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
    public function formatoSolicitud_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {

                $carpeta = 'files/VeranoAcademico/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"files/VeranoAcademico/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>"formatoDeSolicitud".$this->post('nameInst'),
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
                        "urlDocumento"=>base_url('files/VeranoAcademico/'.$id.'/'.$this->upload->data('file_name')),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"formato de solicitud",
                        "statusDocumento"=>"Revision",
                        "fkAspirante"=>$id
                    );

                    $response = $this->DAO->insertData('Tb_formsolicitudVAcademicoAspirante',$data);
                    if($response['status']=="success"){
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

    public function formatoSolicitudUpdate_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Institucion',array('idInstitucion'=>$id),true);

            if ($userExist) {
              $Eixist = $this->DAO->selectEntity('Tb_formsolicitudVAcademicoAspirante',array('fkInstitucion'=>$id),TRUE);
              if($Eixist){
                $response = $this->DAO->deleteData('Tb_formsolicitudVAcademicoAspirante',array('fkInstitucion'=>$id));
                if($response['status']=="success"){

                  $carpeta = 'files/VeranoAcademico/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/VeranoAcademico/".$id,
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
                          "urlDocumento"=>base_url('files/VeranoAcademico/'.$id.'/'.$this->upload->data('file_name')),
                          "typeDocumento"=>$this->upload->data('file_type'),
                          "tipo"=>"formato de solicitud ",
                          "statusDocumento"=>"Activo",
                          "fkInstitucion"=>$id
                      );

                      $response = $this->DAO->insertData('Tb_formsolicitudVAcademicoAspirante',$data);
                      if($response['status']=="success"){
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
    // public function formatoSolicitudUpdate_post(){
    //     $id=$this->get('id');
    //     if ($id) {
    //         $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);
    //
    //         if ($userExist) {
    //           $Eixist = $this->DAO->selectEntity('Tb_formsolicitudVAcademicoAspirante',array('fkAspirante'=>$id),TRUE);
    //           if($Eixist){
    //             $response = $this->DAO->deleteData('Tb_formsolicitudVAcademicoAspirante',array('fkAspirante'=>$id));
    //             if($response['status']=="success"){
    //
    //               $carpeta = 'files/VeranoAcademico/'.$id;
    //               if (!file_exists($carpeta)) {
    //                   mkdir($carpeta, 0777, true);
    //               }
    //
    //               $config =array(
    //                   "upload_path"=>"files/VeranoAcademico/".$id,
    //                   "allowed_types"=>"pdf",
    //                   "file_name"=>"formatoDeSolicitud".$this->post('nameInst'),
    //                   "overwrite"=>true
    //               );
    //
    //               $this->load->library('upload',$config);
    //               if ( ! $this->upload->do_upload('formatoDeSolicitud'))
    //               {
    //               $response=array(
    //                   "status"=>"error",
    //                   "status_code"=>409,
    //                   "message"=>"Upload fails",
    //                   "validations"=>$this->upload->display_errors(),
    //                   "data"=>$this->post()
    //               );
    //               }
    //               else
    //               {
    //                   $data = array(
    //
    //                       "nombreDocumento"=>$this->upload->data('file_name'),
    //                       "extDocumento"=>$this->upload->data()['file_ext'],
    //                       "urlDocumento"=>base_url('files/VeranoAcademico/'.$id.'/'.$this->upload->data('file_name')),
    //                       "typeDocumento"=>$this->upload->data('file_type'),
    //                       "tipo"=>"formato de solicitud ",
    //                       "statusDocumento"=>"Revision",
    //                       "fkInstitucion"=>$id
    //                   );
    //
    //                   $response = $this->DAO->insertData('Tb_formsolicitudVAcademicoAspirante',$data);
    //                   if($response['status']=="success"){
    //                     $response=array(
    //                         "status"=>"success",
    //                         "status_code"=>409,
    //                         "message"=>"Success the document was upload correctly",
    //                         "data"=>null
    //                     );
    //                   }
    //               }
    //             }else{
    //               $response=array(
    //                   "status"=>"error",
    //                   "status_code"=>409,
    //                   "message"=>"The documento was not deleted correctly",
    //                   "data"=>null
    //               );
    //             }
    //           }else{
    //             $response=array(
    //                 "status"=>"error",
    //                 "status_code"=>409,
    //                 "message"=>"Document does not exists",
    //                 "data"=>null
    //             );
    //           }
    //         }else{
    //             $response=array(
    //                 "status"=>"error",
    //                 "status_code"=>409,
    //                 "message"=>"id does not exist",
    //                 "validations"=>array(
    //                     "id"=>"required (get)"
    //                 ),
    //                 "data"=>null
    //             );
    //         }
    //
    //
    //         //a
    //     }else{
    //         $response=array(
    //             "status"=>"error",
    //             "status_code"=>409,
    //             "message"=>"id was not sent",
    //             "validations"=>array(
    //                 "id"=>"required (get)"
    //             ),
    //             "data"=>null
    //         );
    //     }
    //     $this->response($response,200);
    // }
    public function academicoFormFile_put(){
        $id = $this->get('id');
        $idDocForm = $this->get('idDocForm');

        $data = $this->put();
        $Eixist = $this->DAO->selectbyTwoEntity('Tb_formsolicitudVAcademicoAspirante',array('fkAspirante'=>$id),array('idDocumento'=>$idDocForm),TRUE);

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
                   $statusResponse =$this->DAO->updateByTwoData('Tb_formsolicitudVAcademicoAspirante',$status,array('fkAspirante'=>$id),array('idDocumento'=>$idDocForm));
                    if($statusResponse['status']=="success"){
                      $EixistRecomendation = $this->DAO->selectbyTwoEntity('Tb_RecomenAspiranteAcademicForm',array('fkAspirante'=>$id),array('fkDocumento'=>$idDocForm),TRUE);
                      if($EixistRecomendation){
                        $recomendationResponse = $this->DAO->deleteDataTwoClause('Tb_RecomenAspiranteAcademicForm',array('fkAspirante'=>$id),array('fkDocumento'=>$idDocForm));

                        if($recomendationResponse['status']=="success"){
                          $recomendation = array(
                            "descripcion"=>$this->put('desc'),
                            "fkAspirante"=>$id,
                            "fkDocumento"=>$idDocForm
                          );
                          $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomenAspiranteAcademicForm',$recomendation,null,true);

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
                          $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomenAspiranteAcademicForm',$recomendation,null,true);

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
                 }else if($this->put('status') == "Aceptado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>"Aceptado"
                   );
                   $statusResponse = $this->DAO->updateByTwoData('Tb_formsolicitudVAcademicoAspirante',$status,array('fkAspirante'=>$id),array('idDocumento'=>$idDocForm));
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
    public function statusAspirante_put($id=null){
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
    public function recomendtion_get(){
        $id = $this->get('id');
        $idDocForm = $this->get('idDocForm');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectbyTwoEntity('Tb_RecomenAspiranteAcademicForm',array('fkAspirante'=>$id),array('fkDocumento'=>$idDocForm),TRUE),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_RecomenAspiranteAcademicForm'),
            );
        }
        $this->response($response,200);
    }
    //new 06 Jul 2020 this methods is to create and update the document "Examen de Ingles"
    public function examenDeIngles_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {

                $carpeta = 'files/VeranoAcademico/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"files/VeranoAcademico/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>"ExamenDeIngles",
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('ExamenDeIngles'))
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
                        "urlDocumento"=>base_url('files/VeranoAcademico/'.$id.'/'.$this->upload->data('file_name')),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"ExamenDeIngles",
                        "statusDocumento"=>"Revision",
                        "fkAspirante"=>$this->post('aspirante')
                    );

                    $response = $this->DAO->insertData('Tb_ExamenInglesAspirante',$data);
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

    public function examenDeInglesUpdate_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {
              $Eixist = $this->DAO->selectEntity('Tb_ExamenInglesAspirante',array('fkAspirante'=>$id),TRUE);
              if($Eixist){
                $response = $this->DAO->deleteData('Tb_ExamenInglesAspirante',array('fkAspirante'=>$id));
                if($response['status']=="success"){

                  $carpeta = 'files/VeranoAcademico/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/VeranoAcademico/".$id,
                      "allowed_types"=>"pdf",
                      "file_name"=>"ExamenDeIngles",
                      "overwrite"=>true
                  );

                  $this->load->library('upload',$config);
                  if ( ! $this->upload->do_upload('ExamenDeIngles'))
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
                          "urlDocumento"=>base_url('files/VeranoAcademico/'.$id.'/'.$this->upload->data('file_name')),
                          "typeDocumento"=>$this->upload->data('file_type'),
                          "tipo"=>"ExamenDeIngles",
                          "statusDocumento"=>"Revision",
                          "fkAspirante"=>$this->post('aspirante')
                      );

                      $response = $this->DAO->insertData('Tb_ExamenInglesAspirante',$data);
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
    //new 06 Jul 2020 this methods is to create and update the document "Examen de Ingles"
    public function cartaCoU_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {

                $carpeta = 'files/VeranoAcademico/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"files/VeranoAcademico/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>"ExamenDeIngles",
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('ExamenDeIngles'))
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
                        "urlDocumento"=>base_url('files/VeranoAcademico/'.$id.'/'.$this->upload->data('file_name')),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"ExamenDeIngles",
                        "statusDocumento"=>"Revision",
                        "fkAspirante"=>$this->post('aspirante')
                    );

                    $response = $this->DAO->insertData('Tb_ExamenInglesAspirante',$data);
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

    public function cartaCoUpdate_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {
              $Eixist = $this->DAO->selectEntity('Tb_ExamenInglesAspirante',array('fkAspirante'=>$id),TRUE);
              if($Eixist){
                $response = $this->DAO->deleteData('Tb_ExamenInglesAspirante',array('fkAspirante'=>$id));
                if($response['status']=="success"){

                  $carpeta = 'files/VeranoAcademico/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/VeranoAcademico/".$id,
                      "allowed_types"=>"pdf",
                      "file_name"=>"ExamenDeIngles",
                      "overwrite"=>true
                  );

                  $this->load->library('upload',$config);
                  if ( ! $this->upload->do_upload('ExamenDeIngles'))
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
                          "urlDocumento"=>base_url('files/VeranoAcademico/'.$id.'/'.$this->upload->data('file_name')),
                          "typeDocumento"=>$this->upload->data('file_type'),
                          "tipo"=>"ExamenDeIngles",
                          "statusDocumento"=>"Revision",
                          "fkAspirante"=>$this->post('aspirante')
                      );

                      $response = $this->DAO->insertData('Tb_ExamenInglesAspirante',$data);
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
    //new 30
    public function pasaporte_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {

                $carpeta = 'files/VeranoAcademico/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"files/VeranoAcademico/".$id,
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
                        "urlDocumento"=>base_url('files/VeranoAcademico/'.$id.'/'.$this->upload->data('file_name')),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"Pasaporte",
                        "statusDocumento"=>"Revision",
                        "fkAspirante"=>$this->post('aspirante')
                    );

                    $response = $this->DAO->insertData('Tb_DocumentosVeranoAcademico',$data);
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
              $Eixist = $this->DAO->selectEntity('Tb_DocumentosVeranoAcademico',array('fkAspirante'=>$id),TRUE);
              if($Eixist){
                $response = $this->DAO->deleteData('Tb_DocumentosVeranoAcademico',array('fkAspirante'=>$id));
                if($response['status']=="success"){

                  $carpeta = 'files/VeranoAcademico/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/VeranoAcademico/".$id,
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
                          "urlDocumento"=>base_url('files/VeranoAcademico/'.$id.'/'.$this->upload->data('file_name')),
                          "typeDocumento"=>$this->upload->data('file_type'),
                          "tipo"=>"Pasaporte",
                          "statusDocumento"=>"Revision",
                          "fkAspirante"=>$this->post('aspirante')
                      );

                      $response = $this->DAO->insertData('Tb_DocumentosVeranoAcademico',$data);
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
    //Visa
    public function visa_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {

                $carpeta = 'files/VeranoAcademico/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"files/VeranoAcademico/".$id,
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
                        "urlDocumento"=>base_url('files/VeranoAcademico/'.$id.'/'.$this->upload->data('file_name')),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"Visa",
                        "statusDocumento"=>"Revision",
                        "fkAspirante"=>$this->post('aspirante')
                    );

                    $response = $this->DAO->insertData('Tb_DocVerAcademicVisa',$data);
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
    public function visaUpdate_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {
              $Eixist = $this->DAO->selectEntity('Tb_DocVerAcademicVisa',array('fkAspirante'=>$id),TRUE);
              if($Eixist){
                $response = $this->DAO->deleteData('Tb_DocVerAcademicVisa',array('fkAspirante'=>$id));
                if($response['status']=="success"){

                  $carpeta = 'files/VeranoAcademico/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/VeranoAcademico/".$id,
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
                          "urlDocumento"=>base_url('files/VeranoAcademico/'.$id.'/'.$this->upload->data('file_name')),
                          "typeDocumento"=>$this->upload->data('file_type'),
                          "tipo"=>"Visa",
                          "statusDocumento"=>"Revision",
                          "fkAspirante"=>$this->post('aspirante')
                      );

                      $response = $this->DAO->insertData('Tb_DocVerAcademicVisa',$data);
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
    public function recomendtionVisa_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Tb_RecAcademicVisaAspirante',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_RecAcademicVisaAspirante'),
            );
        }
        $this->response($response,200);
    }
    public function visaFile_put($id=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectEntity('Tb_DocVerAcademicVisa',array('fkAspirante'=>$id),TRUE);
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
                   $statusResponse =$this->DAO->updateData('Tb_DocVerAcademicVisa',$status,array('fkAspirante'=>$id));
                    if($statusResponse['status']=="success"){

                      $recomendation = array(
                        "descripcion"=>$this->put('desc'),
                        "fkAspirante"=>$id
                      );
                      $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecAcademicVisaAspirante',$recomendation,null,true);

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
                   $statusResponse = $this->DAO->saveOrUpdateItem('Tb_DocVerAcademicVisa',$status,array('fkAspirante'=>$id),true);
                    if($statusResponse['status']=="success"){

                      $recomendationResponse = $this->DAO->deleteData('Tb_RecAcademicVisaAspirante',array('fkAspirante'=>$id));

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
    public function infoAspirante_get(){
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


    //transcripciones
    public function transcripcion_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {

                $carpeta = 'files/VeranoAcademico/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"files/VeranoAcademico/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>"Transcripcion",
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('Transcripcion'))
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
                        "urlDocumento"=>base_url('files/VeranoAcademico/'.$id.'/'.$this->upload->data('file_name')),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"Transcripcion",
                        "statusDocumento"=>"Revision",
                        "fkAspirante"=>$id
                    );

                    $response = $this->DAO->insertData('Tb_TranscripcionesAspirante',$data);
                    if($response['status']=="success"){
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

    public function transcripcionUpdate_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {
              $Eixist = $this->DAO->selectEntity('Tb_TranscripcionesAspirante',array('fkAspirante'=>$id),TRUE);
              if($Eixist){
                $response = $this->DAO->deleteData('Tb_TranscripcionesAspirante',array('fkAspirante'=>$id));
                if($response['status']=="success"){

                  $carpeta = 'files/VeranoAcademico/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/VeranoAcademico/".$id,
                      "allowed_types"=>"pdf",
                      "file_name"=>"Transcripcion",
                      "overwrite"=>true
                  );

                  $this->load->library('upload',$config);
                  if ( ! $this->upload->do_upload('Transcripcion'))
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
                          "urlDocumento"=>base_url('files/VeranoAcademico/'.$id.'/'.$this->upload->data('file_name')),
                          "typeDocumento"=>$this->upload->data('file_type'),
                          "tipo"=>"Transcripcion",
                          "statusDocumento"=>"Revision",
                          "fkAspirante"=>$id
                      );

                      $response = $this->DAO->insertData('Tb_TranscripcionesAspirante',$data);
                      if($response['status']=="success"){
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

    //traducciones
    public function traduccion_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {

                $carpeta = 'files/VeranoAcademico/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"files/VeranoAcademico/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>"Traduccion",
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('Traduccion'))
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
                        "urlDocumento"=>base_url('files/VeranoAcademico/'.$id.'/'.$this->upload->data('file_name')),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"Traduccion",
                        "statusDocumento"=>"Revision",
                        "fkAspirante"=>$id
                    );

                    $response = $this->DAO->insertData('Tb_TraduccionInglesAspirante',$data);
                    if($response['status']=="success"){
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

    public function traduccionUpdate_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Institucion',array('idInstitucion'=>$id),true);

            if ($userExist) {
              $Eixist = $this->DAO->selectEntity('Tb_TraduccionInglesAspirante',array('fkAspirante'=>$id),TRUE);
              if($Eixist){
                $response = $this->DAO->deleteData('Tb_TraduccionInglesAspirante',array('fkAspirante'=>$id));
                if($response['status']=="success"){

                  $carpeta = 'files/VeranoAcademico/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/VeranoAcademico/".$id,
                      "allowed_types"=>"pdf",
                      "file_name"=>"Traduccion",
                      "overwrite"=>true
                  );

                  $this->load->library('upload',$config);
                  if ( ! $this->upload->do_upload('Traduccion'))
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
                          "urlDocumento"=>base_url('files/VeranoAcademico/'.$id.'/'.$this->upload->data('file_name')),
                          "typeDocumento"=>$this->upload->data('file_type'),
                          "tipo"=>"Traduccion",
                          "statusDocumento"=>"Revision",
                          "fkAspirante"=>$id
                      );

                      $response = $this->DAO->insertData('Tb_TraduccionInglesAspirante',$data);
                      if($response['status']=="success"){
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

    //


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
