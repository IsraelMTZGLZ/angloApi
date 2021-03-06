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
    function preparatoriaCampus_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_PrepaCampus',array('idCampus'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_PrepaCampus'),
            );
        }
        $this->response($response,200);
    }


    function preparatoriaCampus_post($id=null){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 11){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(
                      "nombreCampus"=>"Required, The name is required",
                      "ubicacionCampus"=>"Required, The ubication is required",
                      "urlUbicacionCampus"=>"Optional, the url is optional",
                      "tipoCampus"=>"Required, The type of campus ir required",
                      "alojamientoCampus"=>"Required, The type of campus ir required",
                      "materiasCampus"=>"Optional, The materias video is optional",
                      "urlVideoCampus"=>"Optional, The url video is optional",
                      "urlImagenCampus"=>"Optional, The url imagen is optional",
                      "urlImagenLogoCampus"=>"Optional, The url imagen is optional",
                      "statusCampus"=>"Required, The status is required",
                      "descripcionCampus"=>"Required, The description campus ir required",
                      "preparatoria"=>"Required, The preparatoria is required"
                )
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('nombreCampus','nombreCampus','required|min_length[1]|max_length[100]');
            $this->form_validation->set_rules('ubicacionCampus','ubicacionCampus','required|min_length[3]');
            $this->form_validation->set_rules('tipoCampus','tipoCampus','required|min_length[3]|max_length[50]');
            $this->form_validation->set_rules('alojamientoCampus','alojamientoCampus','required|min_length[3]|max_length[50]');
            $this->form_validation->set_rules('statusCampus','statusCampus','callback_check_status');
            $this->form_validation->set_rules('descripcionCampus','descripcionCampus','required');
            $this->form_validation->set_rules('preparatoria','preparatoria','callback_check_preparatoria');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'check the validations',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{

               $data=array(
                   "nombre_Campus"=>$this->post('nombreCampus'),
                   "ubicacion_Campus"=>$this->post('ubicacionCampus'),
                   "urlUbicacion_Campus"=>$this->post('urlUbicacionCampus'),
                   "tipo_Campus"=>$this->post('tipoCampus'),
                   "materias_Campus"=>$this->post('materiasCampus'),
                   "alojamiento_Campus"=>$this->post('alojamientoCampus'),
                   "urlVideo_Campus"=>$this->post('urlVideoCampus'),
                   "urlImagen_Campus"=>$this->post('urlImagenCampus'),
                   "urlImagenLogo_Campus"=>$this->post('urlImagenLogoCampus'),
                   "descripcion_Campus"=>$this->post('descripcionCampus'),
                   "status_Campus"=>$this->post('statusCampus'),
                   "fkPreparatoria"=>$id
               );
               $response = $this->DAO->insertData('Tb_Campus',$data);

             }
        }

        $this->response($response,200);
    }

    function preparatoriaCampus_put($id=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectEntity('Tb_Campus',array('idCampus'=>$id),TRUE);
        if($Eixist){
          if(count($data) == 0 || count($data) > 11){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                  "data"=>null,
                  "validations"=>array(
                    "nombreCampus"=>"Required, The name is required",
                    "ubicacionCampus"=>"Required, The ubication is required",
                    "urlUbicacionCampus"=>"Optional, the url is optional",
                    "tipoCampus"=>"Required, The type of campus ir required",
                    "alojamientoCampus"=>"Required, The type of campus ir required",
                    "urlVideoCampus"=>"Optional, The url video is optional",
                    "urlImagenCampus"=>"Optional, The url imagen is optional",
                    "urlImagenLogoCampus"=>"Optional, The url imagen is optional",
                    "statusCampus"=>"Required, The status is required",
                    "descripcionCampus"=>"Required, The description campus ir required",
                    "preparatoria"=>"Required, The preparatoria is required"
                  )
              );
          }else{
              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('nombreCampus','nombreCampus','required|min_length[1]|max_length[100]');
              $this->form_validation->set_rules('ubicacionCampus','ubicacionCampus','required|min_length[3]');
              $this->form_validation->set_rules('tipoCampus','tipoCampus','required|min_length[3]|max_length[50]');
              $this->form_validation->set_rules('alojamientoCampus','alojamientoCampus','required|min_length[3]|max_length[50]');
              $this->form_validation->set_rules('statusCampus','statusCampus','callback_check_status');
              $this->form_validation->set_rules('descripcionCampus','descripcionCampus','required');
              $this->form_validation->set_rules('preparatoria','preparatoria','callback_check_preparatoria');

             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'Too many data received',
                      "data"=>null,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{
               $data = array(
                 "nombre_Campus"=>$this->put('nombreCampus'),
                 "ubicacion_Campus"=>$this->put('ubicacionCampus'),
                 "urlUbicacion_Campus"=>$this->put('urlUbicacionCampus'),
                 "tipo_Campus"=>$this->put('tipoCampus'),
                 "alojamiento_Campus"=>$this->put('alojamientoCampus'),
                 "urlVideo_Campus"=>$this->put('urlVideoCampus'),
                 "urlImagen_Campus"=>$this->put('urlImagenCampus'),
                 "urlImagenLogo_Campus"=>$this->put('urlImagenLogoCampus'),
                 "descripcion_Campus"=>$this->put('descripcionCampus'),
                 "status_Campus"=>$this->put('statusCampus'),
                 "fkPreparatoria"=>$this->put('preparatoria')
               );
               $response = $this->DAO->updateData('Tb_Campus',$data,array('idCampus'=>$id));
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



    public function preparatoriaCampus_delete(){
          $id = $this->get('id');
      if ($id) {
        $IdExists = $this->DAO->selectEntity('Tb_Campus',array('idCampus'=>$id),TRUE);

        if($IdExists){
          $response = $this->DAO->deleteData('Tb_Campus',array('idCampus'=>$id));
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

    // This is a test to upload an image
    function files_post(){
      $data = $this->post();
      $config_upload["upload_path"] = "./files/";
      $config_upload['allowed_types'] = "jpg|gif|png|pdf";
      $config_upload['max_size'] = 2048;

      $this->load->library('upload',$config_upload);

      if($this->upload->do_upload('my_file')){
        $data = array(
          "ficheroNombre"=>$this->upload->data('file_name'),
          "ficheroExt"=>$this->upload->data()['file_ext'],
          "ficheroSize"=>$this->upload->data('file_size'),
          "ficheroUrl"=>base_url('files/'.$this->upload->data('file_name')),
          "ficherMime"=>$this->upload->data('file_type'),
           "name"=>$this->post('name')
        );
        $response = $this->DAO->insertData('Ficheros',$data);
        if($response=="success"){
          $response = array(
            "status"=>"success",
            "message"=>"Fichero fue subido correctamente",
            "data"=>$data
          );
        }

      }else{
        $response = array(
          "status"=>"error",
          "message"=>"Fichero no fue subido correctamente",
          "data"=>$data
        );
      }
      $this->response($response,200);
    }

    function preparatoriacampusfhoto_post(){
        $data = $this->post();

        $config_upload["upload_path"] = "./files/";
        $config_upload['allowed_types'] = "jpg|gif|png|pdf";
        $config_upload['max_size'] = 2048;

        $this->load->library('upload',$config_upload);
        if($this->upload->do_upload('my_file')){
        if(count($data) == 0 || count($data) > 15){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(

                  "nombreCampus"=>"Required, The name is required",
                  "ubicacionCampus"=>"Required, The ubication is required",
                  "urlUbicacionCampus"=>"Optional, the url is optional",
                  "tipoCampus"=>"Required, The type of campus ir required",
                  "alojamientoCampus"=>"Required, The type of campus ir required",
                  "urlVideoCampus"=>"Optional, The url video is optional",
                  "urlImagenCampus"=>"Optional, The url imagen is optional",
                  "urlImagenLogoCampus"=>"Optional, The url imagen is optional",
                  "statusCampus"=>"Required, The status is required",
                  "descripcionCampus"=>"Required, The description campus ir required",
                  "preparatoria"=>"Required, The preparatoria is required"
                )



            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('nombreCampus','nombreCampus','required|min_length[1]|max_length[100]');
            $this->form_validation->set_rules('ubicacionCampus','ubicacionCampus','required|min_length[3]');
            $this->form_validation->set_rules('tipoCampus','tipoCampus','required|min_length[3]|max_length[50]');
            $this->form_validation->set_rules('alojamientoCampus','alojamientoCampus','required|min_length[3]|max_length[50]');
            $this->form_validation->set_rules('statusCampus','statusCampus','callback_check_status');
            $this->form_validation->set_rules('descripcionCampus','descripcionCampus','required');
            $this->form_validation->set_rules('preparatoria','preparatoria','callback_check_preparatoria');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=> 'Too many data received',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{

                $this->load->library('bcrypt');
                $this->db->trans_begin();

                $ficheros = array(
                  "urlImagen"=>$this->upload->data('file_name'),
                  "extImagen"=>$this->upload->data()['file_ext'],
                  "urlImagen"=>base_url('files/'.$this->upload->data('file_name')),
                  "typeImagen"=>$this->upload->data('file_type')
                );

                $ficheroResponse = $this->DAO->saveOrUpdateItem('Tb_Imagenes',$ficheros,null,true);
                if($ficheroResponse['status']=="success"){

                          $config_upload["upload_path"] = "./files/";
                          $config_upload['allowed_types'] = "jpg|gif|png";
                          $config_upload['max_size'] = 2048;

                          $this->load->library('upload',$config_upload);
                          if($this->upload->do_upload('my_logo')){

                            $logotipo = array(
                              "urlLogotipo"=>$this->upload->data('file_name'),
                              "extLogotipo"=>$this->upload->data()['file_ext'],
                              "urlLogotipo"=>base_url('files/'.$this->upload->data('file_name')),
                              "typeLogotipo"=>$this->upload->data('file_type')
                            );

                            $logotipoResponse = $this->DAO->saveOrUpdateItem('Tb_Logotipos',$logotipo,null,true);
                              if($logotipoResponse['status']=="success"){
                                $Campus = array(
                                  "nombre_Campus"=>$this->post('nombreCampus'),
                                  "ubicacion_Campus"=>$this->post('ubicacionCampus'),
                                  "urlUbicacion_Campus"=>$this->post('urlUbicacionCampus'),
                                  "tipo_Campus"=>$this->post('tipoCampus'),
                                  "alojamiento_Campus"=>$this->post('alojamientoCampus'),
                                  "urlVideo_Campus"=>$this->post('urlVideoCampus'),
                                  "urlImagen_Campus"=>$this->post('urlImagenCampus'),
                                  "urlImagenLogo_Campus"=>$this->post('urlImagenLogoCampus'),
                                  "descripcion_Campus"=>$this->post('descripcionCampus'),
                                  "status_Campus"=>$this->post('statusCampus'),
                                  "fkPreparatoria"=>$this->post('preparatoria'),
                                  "photoCampus"=>$ficheroResponse['key'],
                                  "logotipoCampus"=>$logotipoResponse['key']
                                );
                                $campusResponse = $this->DAO->saveOrUpdateItem('Tb_Campus',$Campus,null,true);
                                if($campusResponse['status']=="success"){
                                    $response = array(
                                       "status"=>"success",
                                       "message"=>"Professor update successfully",
                                       "data"=>null,
                                   );

                                }else{
                                    $response = array(
                                        "status"=>"error",
                                        "message"=>  $campusResponse['message'],
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
                                  "message"=>$logotipoResponse['message'],
                                  "data"=>null,
                                  );

                              }

                          }else{
                            $response = array(
                              "status"=>"error",
                              "message"=> "Fichero no fue subido correctamente",
                              "data"=>null,
                              );
                          }

                }else{
                  $response = array(
                    "status"=>"error",
                    "message"=>  $personResponse['message'],
                    "data"=>null,
                    );
                }

             }
        }//here
      }else{
        $response = array(
          "status"=>"error",
          "message"=> "Fichero no fue subido correctamente",
          "data"=>null,
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

    function check_preparatoria($str){
      if ( strlen($str)>=1) {
        $Exists = $this->DAO->selectEntity('Tb_Preparatoria',array('idPreparatoria'=>$str),TRUE);
        if ($Exists) {
          return TRUE;
        } else {
        $this->form_validation->set_message('check_preparatoria','The {field} does not exist.');

          return FALSE;
        }

      } else {
        $this->form_validation->set_message('check_preparatoria','The {field} must be 10 characters in length');
          return FALSE;
      }
    }


}
