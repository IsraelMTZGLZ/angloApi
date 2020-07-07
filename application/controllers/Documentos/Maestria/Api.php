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

    public function maestriaTranscripcion_post()
    {
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);
            if ($userExist) {

                $carpeta = 'Documentos/Maestria/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"Documentos/Maestria/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>"transcripcion",
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
                        "urlDocumento"=>'/Documentos/Maestria/'.$id.'/'.$this->upload->data('file_name'),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "fkAspirante"=>$id,
                        "tipo"=>"Transcripcion",
                        "statusDocumento"=>"Pendiente"
                    );

                    $response = $this->DAO->insertData('Tb_DocumentosMaestria',$data);
                    if($response['status']=="success"){
                        $response['message']= "Documento subido correctamente";
                        $this->cambiarEstatus($id);
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

    public function maestriaTranscripcionUpdated_post()
    {
        $id=$this->get('id');
        if ($id) {
            $Exist=$this->DAO->selectEntity('Tb_DocumentosMaestria',array('idDocumento'=>$id),true);
            if ($Exist) {

                $config =array(
                    "upload_path"=>"Documentos/Maestria/".$Exist->fkAspirante,
                    "allowed_types"=>"pdf",
                    "file_name"=>"transcripcion",
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
                        "urlDocumento"=>'/Documentos/Maestria/'.$Exist->fkAspirante.'/'.$this->upload->data('file_name'),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"Transcripcion",
                        "statusDocumento"=>"Rechazado"
                    );

                    $response=$this->DAO->updateData('Tb_DocumentosMaestria',$data,array('idDocumento'=>$id));
                    if($response['status']=="success"){
                        $response['message']= "Documento subido correctamente";
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

    function maestriaDesc_post(){
        $data = $this->post();
        $id = $this->get('id');
        if(count($data) == 0 || count($data) > 1){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(
                    "descDocument"=>"La descripcion es requerido"
                )
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('descDocument','Descripcion del Documento','required');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'check the validations',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{

               $data=array(
                   "descMaestriaDocumento"=>$this->post('descDocument')
               );
               $response = $this->DAO->updateData('Tb_DocumentosMaestria',$data,array('idDocumento'=>$id));


             }
        }

        $this->response($response,200);
    }

    public function maestriaTranscripcionTraduccion_post()
    {
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);
            if ($userExist) {

                $carpeta = 'Documentos/Maestria/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"Documentos/Maestria/".$id,
                    "allowed_types"=>"pdf|doc|docx",
                    "file_name"=>"transcripcionTraduccion",
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
                        "urlDocumento"=>'/Documentos/Maestria/'.$id.'/'.$this->upload->data('file_name'),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "fkAspirante"=>$id,
                        "tipo"=>"TranscripcionTraduccion",
                        "statusDocumento"=>"Pendiente"
                    );

                    $response = $this->DAO->insertData('Tb_DocumentosMaestria',$data);
                    if($response['status']=="success"){
                        $response['message']= "Documento subido correctamente";
                        $this->cambiarEstatus($id);
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

    public function maestriaTranscripcionTraduccionUpdated_post()
    {
        $id=$this->get('id');
        if ($id) {
            $exist=$this->DAO->selectEntity('Tb_DocumentosMaestria',array('idDocumento'=>$id),true);
            if ($exist) {

                $config =array(
                    "upload_path"=>"Documentos/Maestria/".$exist->fkAspirante,
                    "allowed_types"=>"pdf|doc|docx",
                    "file_name"=>"transcripcionTraduccion",
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
                        "urlDocumento"=>'/Documentos/Maestria/'.$exist->fkAspirante.'/'.$this->upload->data('file_name'),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"TranscripcionTraduccion",
                        "statusDocumento"=>"Rechazado"
                    );

                    $response = $this->DAO->updateData('Tb_DocumentosMaestria',$data,array('idDocumento'=>$id));
                    if($response['status']=="success"){
                        $response['message']= "Documento subido correctamente";
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

    public function maestriaCartaMotivo_post()
    {
        $id=$this->get('id');
        $institucion=$this->get('institucion');
        if ($id && $institucion) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);
            if ($userExist) {

                $carpeta = 'Documentos/Maestria/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $carpetaInstitucion = 'Documentos/Maestria/'.$id.'/'.$institucion;
                if (!file_exists($carpetaInstitucion)) {
                    mkdir($carpetaInstitucion, 0777, true);
                }

                $institucionExist=$this->DAO->selectEntity('Tb_Institucion',array('idInstitucion'=>$institucion),true);


                $config =array(
                    "upload_path"=>"Documentos/Maestria/".$id.'/'.$institucion,
                    "allowed_types"=>"pdf|doc|docx",
                    "file_name"=>"cartaMotivo_".$institucionExist->nombreInstitucion,
                    "overwrite"=>true,
                    "remove_spaces"=>true
                );
                
                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('CartaMotivo'))
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
                        "urlDocumento"=>'/Documentos/Maestria/'.$id.'/'.$institucion.'/'.$this->upload->data('file_name'),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "fkAspirante"=>$id,
                        "tipo"=>"CartaMotivo",
                        "statusDocumento"=>"Pendiente"
                    );

                    $response = $this->DAO->insertData('Tb_DocumentosMaestria',$data,true);
                    if($response['status']=="success"){

                        $dataNew=array(
                            "fkDM"=>$response['data'],
                            "fkInstitucion"=>$institucion
                        );

                        $this->DAO->insertData('Tb_DocumentosMaestriaCartaMotivos',$dataNew,true);

                        $response['message']= "Documento subido correctamente";
                        $this->cambiarEstatus($id);
                        $response['data'] = $institucionExist;
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

    public function maestriaCartaMotivoUpdated_post()
    {
        $id=$this->get('id');
        if ($id) {
            $exist=$this->DAO->selectEntity('Tb_DocumentosMaestria',array('idDocumento'=>$id),true);
            if ($exist) {


                $unionExist=$this->DAO->selectEntity('Tb_DocumentosMaestriaCartaMotivos',array('fkDM'=>$id),true);
                $institucionExist=$this->DAO->selectEntity('Tb_Institucion',array('idInstitucion'=>$unionExist->fkInstitucion),true);


                $config =array(
                    "upload_path"=>"Documentos/Maestria/".$exist->fkAspirante.'/'.$institucionExist->idInstitucion,
                    "allowed_types"=>"pdf|doc|docx",
                    "file_name"=>"cartaMotivo_".$institucionExist->nombreInstitucion,
                    "overwrite"=>true,
                    "remove_spaces"=>true
                );
                
                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('CartaMotivo'))
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
                        "urlDocumento"=>'/Documentos/Maestria/'.$exist->fkAspirante.'/'.$institucionExist->idInstitucion.'/'.$this->upload->data('file_name'),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"CartaMotivo",
                        "statusDocumento"=>"Rechazado"
                    );

                    $response = $this->DAO->updateData('Tb_DocumentosMaestria',$data,array('idDocumento'=>$id));
                    if($response['status']=="success"){
                        $response['message']= "Documento subido correctamente";
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

    public function maestriaCartaRecomendacion_post()
    {
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);
            if ($userExist) {

                $carpeta = 'Documentos/Maestria/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $data = $this->DAO->selectEntity('Tb_DocumentosMaestria',array('fkAspirante'=>$id,'tipo'=>'CartaRecomendacion'),true);
                if($data){
                    $name = "cartaRecomendacion2";
                }else{
                    $name = "cartaRecomendacion";
                }


                $config =array(
                    "upload_path"=>"Documentos/Maestria/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>$name,
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('CartaRecomendacion'))
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
                        "urlDocumento"=>'/Documentos/Maestria/'.$id.'/'.$this->upload->data('file_name'),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "fkAspirante"=>$id,
                        "tipo"=>"CartaRecomendacion",
                        "statusDocumento"=>"Pendiente"
                    );

                    $response = $this->DAO->insertData('Tb_DocumentosMaestria',$data);
                    if($response['status']=="success"){
                        $response['message']= "Documento subido correctamente";
                        $this->cambiarEstatus($id);
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

    function maestriaByAspirante_get(){
        $id=$this->get('id');
        $tipo=$this->get('tipo');
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
                $data = $this->DAO->selectEntity('Tb_DocumentosMaestria',array('fkAspirante'=>$id,'tipo'=>$tipo),false);
            }
            else{
                $data = $this->DAO->selectEntity('Tb_DocumentosMaestria',null,false);
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

    function maestriaByAspiranteOnly_get(){
        $id=$this->get('id');
        $tipo=$this->get('tipo');
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
                $data = $this->DAO->selectEntity('Tb_DocumentosMaestria',array('fkAspirante'=>$id),false);
            }
            else{
                $data = $this->DAO->selectEntity('Tb_DocumentosMaestria',null,false);
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

    function maestriaByAspiranteCartaMotivo_get(){
        $id=$this->get('id');
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
                $data = $this->DAO->selectEntity('Vw_DocumentoMaestriaCartaMotivo',array('fkAspirante'=>$id),false);
            }
            else{
                $data = $this->DAO->selectEntity('Vw_DocumentoMaestriaCartaMotivo',null,false);
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

    public function cambiarEstatus($id)
    {
        $item = $this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

        if($item->statusAspirante!='2'){
            $data=array(
                "statusAspirante"=>'2'
            );
            $this->DAO->updateData('Tb_Aspirantes',$data,array('idAspirante'=>$id));
        }
    }

    function maestriaByAspiranteTRansc_get(){
        $id=$this->get('id');
        $tipo=$this->get('tipo');
        $name=$this->get('name');
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
                $data = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id,'nameDocumento'=>'Transcripcion.pdf'),true);
            }
            else{
                $data = $this->DAO->selectEntity('Tb_Documentos',null,false);
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

    function carreraByAspiranteTransRaduccion_get(){
        $id=$this->get('id');
        $tipo=$this->get('tipo');
        $name=$this->get('name');
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
                $data = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id,'tipo'=>'Transcripcion'),false);
            }
            else{
                $data = $this->DAO->selectEntity('Tb_Documentos',null,false);
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

    function cartasMotivosUnis_get(){
        $id=$this->get('id');
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
            $item = $this->DAO->selectEntity('Tb_AspiranteUniversidades',array('fkAspirante'=>$id),true);
            $item2 = $this->DAO->selectEntity('Vw_AspiranteInstituciones',array('idAspiranteUniversidad'=>$item->idAspiranteUniversidad),false);
            $item3 = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id,'tipo'=>'CartaMotivo'),false);
            
            $cartasUniversidad = array();
            for ($j=0; $j < count($item3); $j++) { 
                $new = explode('Carta Motivo de la ',$item3[$j]->nameDocumento); 
                $test = explode('.',$new[1]);
                for ($i=0; $i < count($item2); $i++) { 
                    if($test[0]==$item2[$i]->nombreInstitucion){
                        array_push($cartasUniversidad,$test[0]);
                        break;
                    }
                }
            }

            $unis = array();
            for ($j=0; $j < count($item2); $j++) {  
                array_push($unis,$item2[$j]->nombreInstitucion);
            }

            $resultado = array_diff($unis, $cartasUniversidad);
            $res = array();
            foreach ($resultado as &$valor) {
                array_push($res,$valor);
            }
            
            $final = array();
            for ($i=0; $i < count($res); $i++) { 
                $item4 = $this->DAO->selectEntity('Vw_AspiranteInstituciones',array('idAspiranteUniversidad'=>$item->idAspiranteUniversidad,'nombreInstitucion'=>$res[$i]),true);
                array_push($final,$item4);
            }

            if ($res) {
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "Articulo Cargado correctamente",
                    "validations" =>null,
                    "data"=>$final
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

    function cartasRecomendacionUnisPrimera_get(){
        $id=$this->get('id');
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
            $item = $this->DAO->selectEntity('Tb_AspiranteUniversidades',array('fkAspirante'=>$id),true);
            $item2 = $this->DAO->selectEntity('Vw_AspiranteInstituciones',array('idAspiranteUniversidad'=>$item->idAspiranteUniversidad),false);
            $item3 = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id,'tipo'=>'CartaRecomendacion'),false);
            
            $cartasUniversidad = array();
            for ($j=0; $j < count($item3); $j++) { 
                $new = explode('Primera Carta Recomendacion de la ',$item3[$j]->nameDocumento); 
                $test = explode('.',$new[1]);
                for ($i=0; $i < count($item2); $i++) { 
                    if($test[0]==$item2[$i]->nombreInstitucion){
                        array_push($cartasUniversidad,$test[0]);
                        break;
                    }
                }
            }

            $unis = array();
            for ($j=0; $j < count($item2); $j++) {  
                array_push($unis,$item2[$j]->nombreInstitucion);
            }

            $resultado = array_diff($unis, $cartasUniversidad);
            $res = array();
            foreach ($resultado as &$valor) {
                array_push($res,$valor);
            }
            
            $final = array();
            for ($i=0; $i < count($res); $i++) { 
                $item4 = $this->DAO->selectEntity('Vw_AspiranteInstituciones',array('idAspiranteUniversidad'=>$item->idAspiranteUniversidad,'nombreInstitucion'=>$res[$i]),true);
                array_push($final,$item4);
            }

            if ($res) {
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "Articulo Cargado correctamente",
                    "validations" =>null,
                    "data"=>$final
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

    function cartasRecomendacionUnisSegunda_get(){
        $id=$this->get('id');
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
            $item = $this->DAO->selectEntity('Tb_AspiranteUniversidades',array('fkAspirante'=>$id),true);
            $item2 = $this->DAO->selectEntity('Vw_AspiranteInstituciones',array('idAspiranteUniversidad'=>$item->idAspiranteUniversidad),false);
            $item3 = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id,'tipo'=>'CartaRecomendacion'),false);
            
            $cartasUniversidad = array();
            for ($j=0; $j < count($item3); $j++) { 
                $new = explode('Segunda Carta Recomendacion de la ',$item3[$j]->nameDocumento);
                if(@$new[1]){
                    $test = explode('.',$new[1]);
                }else{
                    $test = explode('.',$new[0]);
                }
                
                for ($i=0; $i < count($item2); $i++) { 
                    if($test[0]==$item2[$i]->nombreInstitucion){
                        array_push($cartasUniversidad,$test[0]);
                        break;
                    }
                }
            }

            $unis = array();
            for ($j=0; $j < count($item2); $j++) {  
                array_push($unis,$item2[$j]->nombreInstitucion);
            }

            $resultado = array_diff($unis, $cartasUniversidad);
            $res = array();
            foreach ($resultado as &$valor) {
                array_push($res,$valor);
            }
            
            $final = array();
            for ($i=0; $i < count($res); $i++) { 
                $item4 = $this->DAO->selectEntity('Vw_AspiranteInstituciones',array('idAspiranteUniversidad'=>$item->idAspiranteUniversidad,'nombreInstitucion'=>$res[$i]),true);
                array_push($final,$item4);
            }

            if ($res) {
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "Articulo Cargado correctamente",
                    "validations" =>null,
                    "data"=>$final
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

    function transcripcionFinal_get(){
        $id=$this->get('id');
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
                $data = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id,'tipo'=>'TranscripcionFinal'),true);
            }
            else{
                $data = $this->DAO->selectEntity('Tb_Documentos',null,false);
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

    function titulo_get(){
        $id=$this->get('id');
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
                $data = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id,'tipo'=>'Titulo'),true);
            }
            else{
                $data = $this->DAO->selectEntity('Tb_Documentos',null,false);
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

    function ATAS_get(){
        $id=$this->get('id');
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
                $data = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id,'tipo'=>'ATAS'),true);
            }
            else{
                $data = $this->DAO->selectEntity('Tb_Documentos',null,false);
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
