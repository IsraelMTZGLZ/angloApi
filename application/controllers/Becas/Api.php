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

    function instVI_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Vw_VeranoInglesInst',array('idInstitucion'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_VeranoInglesInst'),
            );
        }
        $this->response($response,200);
    }

    function infoBecas_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_BecasInst',array('idInstitucion'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_BecasInst'),
            );
        }
        $this->response($response,200);
    }

    function instVAcademico_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Vw_VeranoAcademicoInst',array('idInstitucion'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_VeranoAcademicoInst'),
            );
        }
        $this->response($response,200);
    }

    function becas_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEnt('Vw_BecasInst',array('idCampamento'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEnt('Vw_BecasInst'),
            );
        }
        $this->response($response,200);
    }

    function becas_post(){
        $data = $this->post();

        if(count($data) == 0){
            $response = array(
                "status"=>"error",
                "message"=>  'No data received',
                "data"=>null,
                "validations"=>array(

                    "Estados" => "Los estados son requeridos",
                    "Description" => "La descripcion es requerida",
                    "Instituciones" => "Instituciones requeridos",
                    "TipoEstudio" => "Estudios requeridos"
                )
            );
        }else{
            $this->form_validation->set_data($data);

            $this->form_validation->set_rules('Estados','Estados','required');
            $this->form_validation->set_rules('Description','Description','required');
            $this->form_validation->set_rules('Instituciones','Instituciones','required');
            $this->form_validation->set_rules('TipoEstudio','Tipo de estudio','required');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'check the validations',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{


           $len =$this->post('tamaniolist');

           $dataInstitution = $this->post('Instituciones');
           $arrayInst = explode(",", $dataInstitution);


           for ($i = 0; $i < $len; ++$i) {
             $datanew[] = array(
                  "fkInstitucion"=>(int)$arrayInst[$i],
                  "tipoInstitucion"=>$this->post('TipoEstudio'),
                  "fkDocBeca_Extra_Desc"=>1

             );

            }
            $responsen = $this->DAO->saveOrUpdateBatchItems('Tb_InstDocBeca_Extra_Desc',$datanew,null);
            if($responsen['status']=="success"){
              $response = array(
                  "status"=>"success",
                  "message"=>'check the validations',
                  "data"=>null,
                  "validations"=>$datanew
              );

            }else{
              $response = array(
                  "status"=>"error",
                  "message"=>'check the validations',
                  "data"=>(int)$arrayInst[0],
                  "validations"=>$responsen
              );

            }

             }
        }

        $this->response($response,200);
    }

    function becasConfig_post(){
        $data = $this->post();

        if(count($data) == 0){
            $response = array(
                "status"=>"error",
                "message"=>  'No data received',
                "data"=>null,
                "validations"=>array(

                    "Estados" => "Los estados son requeridos",
                    "Description" => "La descripcion es requerida",
                    "Instituciones" => "Instituciones requeridos",
                    "TipoEstudio" => "Estudios requeridos"
                )
            );
        }else{
            $this->form_validation->set_data($data);

            $this->form_validation->set_rules('Estados','Estados','required');
            $this->form_validation->set_rules('Description','Description','required');
            $this->form_validation->set_rules('Instituciones','Instituciones','required');
            $this->form_validation->set_rules('TipoEstudio','Tipo de estudio','required');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'check the validations',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{

               $dataCarpeta=array(
                   "nombre"=>"Archivo"
               );
               $responseCarpeta = $this->DAO->saveOrUpdateItem('Tb_CarpetaBecas',$dataCarpeta,null,true);
               if($responseCarpeta['status']=="success"){
                 $id = $responseCarpeta['key'];
                 $carpeta = 'files/Becas/'.$id;
                 if (!file_exists($carpeta)) {
                     mkdir($carpeta, 0777, true);
                 }

                 $config =array(
                     "upload_path"=>"files/Becas/".$id,
                     "allowed_types"=>"pdf",
                     "file_name"=>"Beca",
                     "overwrite"=>false
                 );

                 $this->load->library('upload',$config);
                 if (!$this->upload->do_upload('Beca'))
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
                     $dataBeca = array(
                         "nombreDocumentoBeca"=>$this->upload->data('file_name'),
                         "extDocumentoBeca"=>$this->upload->data()['file_ext'],
                         "urlDocumentoBeca"=>base_url('files/Becas/'.$id.'/'.$this->upload->data('file_name')),
                         "typeDocumentoBeca"=>$this->upload->data('file_type'),
                         "tipoBeca"=>"Beca",
                         "statusDocumentoBeca"=>"Activo"
                     );

                     // $response = $this->DAO->insertData('Tb_DocumentoBeca',$dataBeca);
                     $responseBeca = $this->DAO->saveOrUpdateItem('Tb_DocumentoBeca',$dataBeca,null,true);

                     if($responseBeca['status']=="success"){
                       $fkBeca = $responseBeca['key'];
                       $carpeta = 'files/Becas/'.$id;
                       if (!file_exists($carpeta)) {
                           mkdir($carpeta, 0777, true);
                       }

                       $config =array(
                           "upload_path"=>"files/Becas/".$id,
                           "allowed_types"=>"pdf",
                           "overwrite"=>false
                       );

                       $this->load->library('upload',$config);
                       if ( ! $this->upload->do_upload('BecaExtraInfo'))
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
                           $dataBecaExtra = array(

                               "nombreDocumentoBecaExtra"=>"BecaExtraInfo",
                               "extDocumentoBecaExtra"=>$this->upload->data()['file_ext'],
                               "urlDocumentoBecaExtra"=>base_url('files/Becas/'.$id.'/'.$this->upload->data('file_name')),
                               "typeDocumentoBecaExtra"=>$this->upload->data('file_type'),
                               "tipoBecaExtra"=>"ExtraBeca",
                               "statusDocumentoBecaExtra"=>"Activo"
                           );

                           // $response = $this->DAO->insertData('Tb_DocumentoBecaExtras',$data);
                           $responseBecaExtra = $this->DAO->saveOrUpdateItem('Tb_DocumentoBecaExtras',$dataBecaExtra,null,true);

                           if($responseBecaExtra['status']=="success"){
                             $fkBecaExtra = $responseBecaExtra['key'];
                             $dataDesc=array(
                                 "estados"=>$this->post('Estados'),
                                 "descBeca"=>$this->post('Description')
                             );
                             $responseDesc = $this->DAO->saveOrUpdateItem('Tb_DescBeca',$dataDesc,null,true);
                             if($responseDesc['status']=="success"){
                               $fkDesc = $responseDesc['key'];
                               $dataDescBeca=array(
                                   "fkDocumentoBeca"=>$fkBeca,
                                   "fkDocumentoBecaExtras"=>$fkBecaExtra,
                                   "fkDescBeca"=>$fkDesc
                               );
                               $responseDescBeca = $this->DAO->saveOrUpdateItem('Tb_DocBeca_Extra_Desc',$dataDescBeca,null,true);
                                if($responseDescBeca['status']=="success"){
                                  $fkDescBecaExtra = $responseDescBeca['key'];
                                  $len =$this->post('tamaniolist');
                                  $dataInstitution = $this->post('Instituciones');
                                  $arrayInst = explode(",", $dataInstitution);
                                  for ($i = 0; $i < $len; ++$i) {
                                       $datanew[] = array(
                                          "fkInstitucion"=>(int)$arrayInst[$i],
                                          "tipoInstitucion"=>$this->post('TipoEstudio'),
                                          "fkDocBeca_Extra_Desc"=>$fkDescBecaExtra,
                                          "fkCarpetaBeca"=>$id
                                        );

                                   }
                                  $responsen = $this->DAO->saveOrUpdateBatchItems('Tb_InstDocBeca_Extra_Desc',$datanew,null);
                                  if($responsen['status']=="success"){
                                    $response = array(
                                        "status"=>"success",
                                        "message"=>'check the validations',
                                        "data"=>null,
                                        "validations"=>$datanew
                                    );
                                   }else{
                                    $response = array(
                                        "status"=>"error",
                                        "message"=>'check the validations',
                                        "data"=>(int)$arrayInst[0],
                                        "validations"=>$responsen
                                        );
                                  }
                                }else{
                                  $response=array(
                                      "status"=>"error",
                                      "status_code"=>409,
                                      "message"=>"Beca desc extra Upload fails",
                                      "validations"=>null,
                                      "data"=>null
                                  );
                                }

                             }else{
                               $response=array(
                                   "status"=>"error",
                                   "status_code"=>409,
                                   "message"=>"Beca extras Upload fails",
                                   "validations"=>null,
                                   "data"=>null
                               );
                             }
                           }else{
                             $response=array(
                                 "status"=>"error",
                                 "status_code"=>409,
                                 "message"=>"Beca extras Upload fails",
                                 "validations"=>null,
                                 "data"=>null
                             );
                           }
                       }
                       // $this->cambiarEstatus($id,'2');
                       // $response = array(
                       //   "status"=>"success",
                       //   "message"=>"Fichero fue subido correctamente",
                       //   "data"=>$data
                       // );
                     }else{
                       $response=array(
                           "status"=>"error",
                           "status_code"=>409,
                           "message"=>"Beca Upload fails",
                           "validations"=>$responseBeca,
                           "data"=>null
                       );
                     }
               }


               }else{
                 $response=array(
                     "status"=>"error",
                     "status_code"=>409,
                     "message"=>"Beca Upload fails",
                     "validations"=>$responseBeca,
                     "data"=>null
                 );
               }

             }
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


    function campamento_put(){
        $data = $this->put();
        $id = $this->get('id');
        $existe = $this->DAO->selectEntity('Tb_Campamentos',array('idCampamento'=>$id),TRUE);
        if($existe){
            if(count($data) == 0 || count($data) > 2){
                $response = array(
                    "status"=>"error",
                    "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                    "data"=>null,
                    "validations"=>array(
                      "nombre"=>"El nombre es requerido",
                      "abreviacion" => "La abreviacion es requerida"
                    )
                );
            }else{
                $this->form_validation->set_data($data);
                $this->form_validation->set_rules('nombre','Nombre','required');
                $this->form_validation->set_rules('abreviacion','Abreviacion','required');

                 if($this->form_validation->run()==FALSE){
                    $response = array(
                        "status"=>"error",
                        "message"=>'check the validations',
                        "data"=>null,
                        "validations"=>$this->form_validation->error_array()
                    );
                }else{

                    $data=array(
                      "nombreCampamento"=>$this->put('nombre'),
                      "abreviacionCampamento"=>$this->put('abreviacion')
                    );

                   $response = $this->DAO->updateData('Tb_Campamentos',$data,array('idCampamento'=>$id));

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

    public function campamento_delete(){
        $id = $this->get('id');
      if ($id) {
        $IdExists = $this->DAO->selectEntity('Tb_Campamentos',array('idCampamento'=>$id),TRUE);

        if($IdExists){
          $response = $this->DAO->deleteData('Tb_Campamentos',array('idCampamento'=>$id));
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
