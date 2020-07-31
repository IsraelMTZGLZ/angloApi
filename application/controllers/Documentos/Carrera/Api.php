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

    public function carreraBoleta_post()
    {
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);
            if ($userExist) {

                $carpeta = 'Documentos/Carrera/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"Documentos/Carrera/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>"boleta",
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('Boleta'))
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
                        "urlDocumento"=>'/Documentos/Carrera/'.$id.'/'.$this->upload->data('file_name'),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "fkAspirante"=>$id,
                        "tipo"=>"Boleta",
                        "statusDocumento"=>"Pendiente"
                    );

                    $response = $this->DAO->insertData('Tb_Documentos',$data);
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

    public function carreraBoletaUpdated_post()
    {
        $id=$this->get('id');
        if ($id) {
            $exist=$this->DAO->selectEntity('Tb_Documentos',array('idDocumento'=>$id),true);
            if ($exist) {


                $config =array(
                    "upload_path"=>"Documentos/Carrera/".$exist->fkAspirante,
                    "allowed_types"=>"pdf",
                    "file_name"=>"boleta",
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('Boleta'))
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
                        "urlDocumento"=>'/Documentos/Carrera/'.$exist->fkAspirante.'/'.$this->upload->data('file_name'),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"Boleta",
                        "statusDocumento"=>"Rechazado"
                    );

                    $response = $this->DAO->updateData('Tb_Documentos',$data,array('idDocumento'=>$id));
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

    function carreraDesc_post(){
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
                   "descDocumento"=>$this->post('descDocument')
               );
               $response = $this->DAO->updateData('Tb_Documentos',$data,array('idDocumento'=>$id));


             }
        }

        $this->response($response,200);
    }
    

    public function carreraBoletaDOC_post()
    {
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);
            if ($userExist) {

                $carpeta = 'Documentos/Carrera/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"Documentos/Carrera/".$id,
                    "allowed_types"=>"docx|doc|pdf|",
                    "file_name"=>"boletaTraduccion",
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('BoletaDOC'))
                {
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"La subida fallo",
                    "validations"=>$this->upload->display_errors(),
                    "data"=>$this->post()
                ); 
                }
                else
                {
                    $data = array(
                        "nombreDocumento"=>$this->upload->data('file_name'),
                        "extDocumento"=>$this->upload->data()['file_ext'],
                        "urlDocumento"=>'/Documentos/Carrera/'.$id.'/'.$this->upload->data('file_name'),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "fkAspirante"=>$id,
                        "tipo"=>"BoletaTraduccion",
                        "statusDocumento"=>"Pendiente"
                    );

                    $response = $this->DAO->insertData('Tb_Documentos',$data);
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

    public function carreraBoletaDOCUpdated_post()
    {
        $id=$this->get('id');
        if ($id) {
            $exist=$this->DAO->selectEntity('Tb_Documentos',array('idDocumento'=>$id),true);
            if ($exist) {

                $config =array(
                    "upload_path"=>"Documentos/Carrera/".$exist->fkAspirante,
                    "allowed_types"=>"docx|doc|pdf|",
                    "file_name"=>"boletaTraduccion",
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('BoletaDOC'))
                {
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"La subida fallo",
                    "validations"=>$this->upload->display_errors(),
                    "data"=>$this->post()
                ); 
                }
                else
                {
                    $data = array(
                        "nombreDocumento"=>$this->upload->data('file_name'),
                        "extDocumento"=>$this->upload->data()['file_ext'],
                        "urlDocumento"=>'/Documentos/Carrera/'.$exist->fkAspirante.'/'.$this->upload->data('file_name'),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"BoletaTraduccion",
                        "statusDocumento"=>"Rechazado"
                    );

                    $response = $this->DAO->updateData('Tb_Documentos',$data,array('idDocumento'=>$id));

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

    public function carreraCarta_post()
    {
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);
            if ($userExist) {

                $carpeta = 'Documentos/Carrera/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }
                $config =array(
                    "upload_path"=>"Documentos/Carrera/".$id,
                    "allowed_types"=>"pdf|docx|doc",
                    "file_name"=>"cartaMotivo",
                    "overwrite"=>true
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
                        "urlDocumento"=>'/Documentos/Carrera/'.$id.'/'.$this->upload->data('file_name'),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "fkAspirante"=>$id,
                        "tipo"=>"CartaMotivo",
                        "statusDocumento"=>"Pendiente"
                    );

                    $response = $this->DAO->insertData('Tb_Documentos',$data);
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

    public function carreraCartaUpdated_post()
    {
        $id=$this->get('id');
        if ($id) {
            $exist=$this->DAO->selectEntity('Tb_Documentos',array('idDocumento'=>$id),true);
            if ($exist) {

                $config =array(
                    "upload_path"=>"Documentos/Carrera/".$exist->fkAspirante,
                    "allowed_types"=>"pdf|docx|doc",
                    "file_name"=>"cartaMotivo",
                    "overwrite"=>true
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
                        "urlDocumento"=>'/Documentos/Carrera/'.$exist->fkAspirante.'/'.$this->upload->data('file_name'),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"CartaMotivo",
                        "statusDocumento"=>"Rechazado"
                    );

                    $response = $this->DAO->updateData('Tb_Documentos',$data,array('idDocumento'=>$id));
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

    public function carreraPasaporte_post()
    {
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);
            if ($userExist) {

                $carpeta = 'Documentos/Carrera/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"Documentos/Carrera/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>"pasaporte",
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
                        "urlDocumento"=>'/Documentos/Carrera/'.$id.'/'.$this->upload->data('file_name'),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "fkAspirante"=>$id,
                        "tipo"=>"Pasaporte",
                        "statusDocumento"=>"Pendiente"
                    );

                    $response = $this->DAO->insertData('Tb_Documentos',$data);
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

    public function carreraPasaporteUpdated_post()
    {
        $id=$this->get('id');
        if ($id) {
            $exist=$this->DAO->selectEntity('Tb_Documentos',array('idDocumento'=>$id),true);
            if ($exist) {

                $config =array(
                    "upload_path"=>"Documentos/Carrera/".$exist->fkAspirante,
                    "allowed_types"=>"pdf",
                    "file_name"=>"pasaporte",
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
                        "urlDocumento"=>'/Documentos/Carrera/'.$exist->fkAspirante.'/'.$this->upload->data('file_name'),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"Pasaporte",
                        "statusDocumento"=>"Rechazado"
                    );

                    $response = $this->DAO->updateData('Tb_Documentos',$data,array('idDocumento'=>$id));
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

    function carreraByAspiranteBoleta_get(){
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
                $data = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id,'nameDocumento'=>'Boleta.pdf'),true);
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

    function carreraByAspiranteBoletaTRaduccion_get(){
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
                $data = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id,'tipo'=>'Boleta'),false);
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

    function carreraByAspiranteCartaMotivo_get(){
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
                $data = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id,'tipo'=>'CartaMotivo'),true);
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

    function carreraByAspirantePasaporte_get(){
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
                $data = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id,'tipo'=>'Pasaporte'),true);
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

    function carreraByAspiranteCartaRecomendacion_get(){
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
                $data = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id,'tipo'=>'CartaRecomendacion'),false);
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

    function carreraByAspiranteOnly_get(){
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
                $data = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id),false);
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

    function uploadDropbox_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 20){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No se recibio datos' : 'Demasiados datos recibidos',
                "data"=>null,
                "validations"=>null
            );
        }else{

            $response = $this->DAO->insertData('Tb_Documentos',$data);
            $this->cambiarEstatus($this->post('fkAspirante'));
             
        }

        $this->response($response,200);
    }

    function uploadDropboxSinCarpeta_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 20){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No se recibio datos' : 'Demasiados datos recibidos',
                "data"=>null,
                "validations"=>null
            );
        }else{
            $capertaExist=$this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$this->post('fkAspirante')),true);

            $data= array(
                "idDocumento"=>$this->post('idDocumento'),
                "nameDocumento"=>$this->post('nameDocumento'),
                "sizeDocumento"=>$this->post('sizeDocumento'),
                "pathDisplayDocumento"=>$this->post('pathDisplayDocumento'),
                "pathLowerDocumento"=>$this->post('pathLowerDocumento'),
                "contentHashDocumento"=>$this->post('contentHashDocumento'),
                "clientModifiedDocumento"=>$this->post('clientModifiedDocumento'),
                "nameCarpeta"=>$capertaExist->nameCarpeta,
                "idCarpeta"=>$capertaExist->idDocumento,
                "pathDisplayCarpeta"=>$capertaExist->pathDisplayCarpeta,
                "pathLowerCarpeta"=>$capertaExist->pathLowerCarpeta,
                "fkAspirante"=>$this->post('fkAspirante'),
                "tipoDocumento"=>$this->post('tipoDocumento'),
                "tipo"=>$this->post('tipo'),
            );

            $response = $this->DAO->insertData('Tb_Documentos',$data);
            $this->cambiarEstatus($this->post('fkAspirante'));
             
        }

        $this->response($response,200);
    }

    public function cambiarEstatus($id)
    {
        $item = $this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

        if($item->statusAspirante!='2' && $item->statusAspirante!='4U' && $item->statusAspirante!='4C'  && $item->statusAspirante!='3'  && $item->statusAspirante!='2R' && $item->statusAspirante!='5'){
            $data=array(
                "statusAspirante"=>'2'
            );
            $this->DAO->updateData('Tb_Aspirantes',$data,array('idAspirante'=>$id));
        }
    }

    function test_get(){
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
                $new = explode('Carta Recomendacion de la ',$item3[$j]->nameDocumento); 
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

    function carreraCartaAutorizacion_get(){
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
            $item3 = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id,'tipo'=>'CartaAutorizacion'),false);
            
            $cartasUniversidad = array();
            for ($j=0; $j < count($item3); $j++) { 
                $new = explode('Carta Autorizacion de la ',$item3[$j]->nameDocumento); 
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

    function cartaRecomen_get(){
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
                $data = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id,'tipo'=>'CartaRecomendacion'),true);
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

    function cartaAuto_get(){
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
                $data = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id,'tipo'=>'CartaAutorizacion'),true);
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

    function documentosDescAdd_post(){
        $id = $this->get('id');
        $data = $this->post();

        if(count($data) == 0 || count($data) > 1){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(
                    "descDocumento"=>"La descripcion es requerido",
                )
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('descDocumento','Descripcion Documento','required');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'check the validations',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{

                $data=array(
                   "descDocumento"=>$this->post('descDocumento'),
                   "statusDocumento"=>'Rechazado'
                );

                $response = $this->DAO->updateData('Tb_Documentos',$data,array('idReal'=>$id));

             }
        }

        $this->response($response,200);
    }

    function documentosChangeActive_post(){
        $id = $this->get('id');
        $data = $this->post();

        if(count($data) > 1){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(
                    "data"=>"Data no es requerido",
                )
            );
        }else{
            
            $data=array(
                "statusDocumento"=>'Aceptado',
                "BoletaTipo"=>$this->post('BoletaTipo')
            );

            $response = $this->DAO->updateData('Tb_Documentos',$data,array('idReal'=>$id));

             
        }

        $this->response($response,200);
    }

    function resubirDocDropbox_put(){
        $data = $this->put();
        $id = $this->get('id');

        if(count($data) == 0 || count($data) > 20){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No se recibio datos' : 'Demasiados datos recibidos',
                "data"=>null,
                "validations"=>null
            );
        }else{

            $data= array(
                "idDocumento"=>$this->put('idDocumento'),
                "nameDocumento"=>$this->put('nameDocumento'),
                "sizeDocumento"=>$this->put('sizeDocumento'),
                "pathDisplayDocumento"=>$this->put('pathDisplayDocumento'),
                "pathLowerDocumento"=>$this->put('pathLowerDocumento'),
                "contentHashDocumento"=>$this->put('contentHashDocumento'),
                "clientModifiedDocumento"=>$this->put('clientModifiedDocumento'),
                "statusDocumento"=>'Pendiente'
            );

            $response = $this->DAO->updateData('Tb_Documentos',$data,array('idReal'=>$id));
             
        }

        $this->response($response,200);
    }

    public function documentosAgente_post()
    {
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);
            if ($userExist) {

                $carpeta = 'Documentos/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"Documentos/".$id,
                    "allowed_types"=>"*"
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('archivo'))
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
                        "nameDocumento"=>$this->upload->data('file_name'),
                        "extDA"=>$this->upload->data()['file_ext'],
                        "urlDA"=>'/Documentos/'.$id.'/'.$this->upload->data('file_name'),
                        "typeDA"=>$this->upload->data('file_type'),
                        "fkAspirante"=>$id
                    );

                    $response = $this->DAO->insertData('Tb_DocumentosAgente',$data);
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

    function documentosAgentesByAspirante_get(){
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
                $data = $this->DAO->selectEntity('Tb_DocumentosAgente',array('fkAspirante'=>$id),false);
            }
            else{
                $data = null;
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

    function cambiarStatusN_post(){
        $id=$this->get('id');
        $existe = $this->DAO->selectEntity('Vw_Aspirante',array('aspirante'=>$id),TRUE); 
        $data = $this->post();

        if(count($data) == 0 || count($data) > 1){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No se recibio datos' : 'Demasiados datos recibidos',
                "data"=>null,
                "validations"=>array(
                    "status"=>"El status es requerido"
                )
            );
        }else{
            if($existe->statusAspirante=='4U'){
                $response = array(
                    "status"=>"success",
                    "message"=> 'Gracias por la espera',
                    "data"=>null
                );
            }else {
                $this->form_validation->set_data($data);
                $this->form_validation->set_rules('status','Status','required');


                if($this->form_validation->run()==FALSE){
                    $response = array(
                        "status"=>"error",
                        "message"=>'El status tiene que ser seleccionado',
                        "data"=>null,
                        "validations"=>$this->form_validation->error_array()
                    );
                }else{

                    $data=array(
                        "statusAspirante"=>$this->post('status')
                    );
        
                    $response = $this->DAO->updateData('Tb_Aspirantes',$data,array('idAspirante'=>$id));
        

                }
            }
            
        }

        $this->response($response,200);
    }
    
    function examenIngles_get(){
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
                $data = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id,'tipo'=>'examenIngles'),true);
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

    function boletaFinal_get(){
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
                $data = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id,'tipo'=>'calificacionFinal'),true);
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

    function visa_get(){
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
                $data = $this->DAO->selectEntity('Tb_DocVisa',array('fkAspirante'=>$id,'tipoDocumento'=>'Visa'),true);
            }
            else{
                $data = $this->DAO->selectEntity('Tb_DocVisa',null,false);
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

    function uploadDropboxSinCarpeta2R_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 20){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No se recibio datos' : 'Demasiados datos recibidos',
                "data"=>null,
                "validations"=>null
            );
        }else{
            $capertaExist=$this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$this->post('fkAspirante')),true);

            $data= array(
                "idDocumento"=>$this->post('idDocumento'),
                "nameDocumento"=>$this->post('nameDocumento'),
                "sizeDocumento"=>$this->post('sizeDocumento'),
                "pathDisplayDocumento"=>$this->post('pathDisplayDocumento'),
                "pathLowerDocumento"=>$this->post('pathLowerDocumento'),
                "contentHashDocumento"=>$this->post('contentHashDocumento'),
                "clientModifiedDocumento"=>$this->post('clientModifiedDocumento'),
                "nameCarpeta"=>$capertaExist->nameCarpeta,
                "idCarpeta"=>$capertaExist->idDocumento,
                "pathDisplayCarpeta"=>$capertaExist->pathDisplayCarpeta,
                "pathLowerCarpeta"=>$capertaExist->pathLowerCarpeta,
                "fkAspirante"=>$this->post('fkAspirante'),
                "tipoDocumento"=>$this->post('tipoDocumento'),
                "tipo"=>$this->post('tipo'),
            );

            $response = $this->DAO->insertData('Tb_Documentos',$data);
             
        }

        $this->response($response,200);
    }

    function dropboxVisa_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 20){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No se recibio datos' : 'Demasiados datos recibidos',
                "data"=>null,
                "validations"=>null
            );
        }else{
            $capertaExist=$this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$this->post('fkAspirante')),true);

            $data= array(
                "idDocumento"=>$this->post('idDocumento'),
                "nameDocumento"=>$this->post('nameDocumento'),
                "sizeDocumento"=>$this->post('sizeDocumento'),
                "pathDisplayDocumento"=>$this->post('pathDisplayDocumento'),
                "pathLowerDocumento"=>$this->post('pathLowerDocumento'),
                "contentHashDocumento"=>$this->post('contentHashDocumento'),
                "clientModifiedDocumento"=>$this->post('clientModifiedDocumento'),
                "nameCarpeta"=>$capertaExist->nameCarpeta,
                "fkAspirante"=>$this->post('fkAspirante')
            );

            $response = $this->DAO->insertData('Tb_DocumentosVisa',$data);
             
        }

        $this->response($response,200);
    }

    function aplicacionVisa_get(){
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
                $data = $this->DAO->selectEntity('Tb_DocumentosVisa',array('fkAspirante'=>$id),false);
            }
            else{
                $data = $this->DAO->selectEntity('Tb_DocumentosVisa',null,false);
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

    //borrar los documentos para la aplicacion de la beca
    public function docAplicacion_delete(){
        $id = $this->get('id');
      if ($id) {
        $IdExists = $this->DAO->selectEntity('Tb_DocumentosVisa',array('idReal'=>$id),TRUE);

        if($IdExists){
          $response = $this->DAO->deleteData('Tb_DocumentosVisa',array('idReal'=>$id));
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

    function documentosChangeActiveAplicacion_post(){
        $id = $this->get('id');
        $data = $this->post();

        if(count($data) > 0){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(
                    "data"=>"Data no es requerido",
                )
            );
        }else{
            
            $data=array(
                "statusDocumento"=>'Aceptado'
            );

            $response = $this->DAO->updateData('Tb_DocumentosVisa',$data,array('idReal'=>$id));

             
        }

        $this->response($response,200);
    }

    function dropboxVisaFinal_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 20){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No se recibio datos' : 'Demasiados datos recibidos',
                "data"=>null,
                "validations"=>null
            );
        }else{
            $capertaExist=$this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$this->post('fkAspirante')),true);

            $data= array(
                "idDocumento"=>$this->post('idDocumento'),
                "nameDocumento"=>$this->post('nameDocumento'),
                "sizeDocumento"=>$this->post('sizeDocumento'),
                "pathDisplayDocumento"=>$this->post('pathDisplayDocumento'),
                "pathLowerDocumento"=>$this->post('pathLowerDocumento'),
                "contentHashDocumento"=>$this->post('contentHashDocumento'),
                "clientModifiedDocumento"=>$this->post('clientModifiedDocumento'),
                "nameCarpeta"=>$capertaExist->nameCarpeta,
                "fkAspirante"=>$this->post('fkAspirante'),
                "tipoDocumento"=>$this->post('tipoDocumento')
            );

            $response = $this->DAO->insertData('Tb_DocVisa',$data);
             
        }

        $this->response($response,200);
    }

    function visaAll_get(){
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
                $data = $this->DAO->selectEntity('Tb_DocVisa',array('fkAspirante'=>$id),false);
            }
            else{
                $data = $this->DAO->selectEntity('Tb_DocVisa',null,false);
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

    function documentosChangeActiveVisa_post(){
        $id = $this->get('id');
        $data = $this->post();

        if(count($data) > 1){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(
                    "data"=>"Data no es requerido",
                )
            );
        }else{
            $Exist=$this->DAO->selectEntity('Tb_DocVisa',array('idReal'=>$id),true);

            $data=array(
                "statusDocumento"=>'Aceptado',
            );

            
            

            $response = $this->DAO->updateData('Tb_DocVisa',$data,array('idReal'=>$id));

            if ($Exist->tipoDocumento=='Visa') {
                $dataAspirante=array(
                    "statusAspirante"=>'5',
                );
    
                $this->DAO->updateData('Tb_Aspirantes',$dataAspirante,array('idAspirante'=>$Exist->fkAspirante));
            }

            
             
        }

        $this->response($response,200);
    }

    function documentosDescAddVisa_post(){
        $id = $this->get('id');
        $data = $this->post();

        if(count($data) == 0 || count($data) > 1){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(
                    "descDocumento"=>"La descripcion es requerido",
                )
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('descDocumento','Descripcion Documento','required');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'check the validations',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{

                $data=array(
                   "descDocumento"=>$this->post('descDocumento'),
                   "statusDocumento"=>'Rechazado'
                );

                $response = $this->DAO->updateData('Tb_DocVisa',$data,array('idReal'=>$id));

             }
        }

        $this->response($response,200);
    }

    function resubirDocDropboxVisa_put(){
        $data = $this->put();
        $id = $this->get('id');

        if(count($data) == 0 || count($data) > 20){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No se recibio datos' : 'Demasiados datos recibidos',
                "data"=>null,
                "validations"=>null
            );
        }else{

            $data= array(
                "idDocumento"=>$this->put('idDocumento'),
                "nameDocumento"=>$this->put('nameDocumento'),
                "sizeDocumento"=>$this->put('sizeDocumento'),
                "pathDisplayDocumento"=>$this->put('pathDisplayDocumento'),
                "pathLowerDocumento"=>$this->put('pathLowerDocumento'),
                "contentHashDocumento"=>$this->put('contentHashDocumento'),
                "clientModifiedDocumento"=>$this->put('clientModifiedDocumento'),
                "statusDocumento"=>'Pendiente'
            );

            $response = $this->DAO->updateData('Tb_DocVisa',$data,array('idReal'=>$id));
             
        }

        $this->response($response,200);
    }

    function dropboxATASFinal_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 20){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No se recibio datos' : 'Demasiados datos recibidos',
                "data"=>null,
                "validations"=>null
            );
        }else{
            $capertaExist=$this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$this->post('fkAspirante')),true);

            $data= array(
                "idDocumento"=>$this->post('idDocumento'),
                "nameDocumento"=>$this->post('nameDocumento'),
                "sizeDocumento"=>$this->post('sizeDocumento'),
                "pathDisplayDocumento"=>$this->post('pathDisplayDocumento'),
                "pathLowerDocumento"=>$this->post('pathLowerDocumento'),
                "contentHashDocumento"=>$this->post('contentHashDocumento'),
                "clientModifiedDocumento"=>$this->post('clientModifiedDocumento'),
                "nameCarpeta"=>$capertaExist->nameCarpeta,
                "fkAspirante"=>$this->post('fkAspirante'),
                "tipoDocumento"=>$this->post('tipoDocumento')
            );

            $response = $this->DAO->insertData('Tb_DocVisa',$data);
             
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
                $data = $this->DAO->selectEntity('Tb_DocVisa',array('fkAspirante'=>$id,'tipoDocumento'=>'ATAS'),true);
            }
            else{
                $data = $this->DAO->selectEntity('Tb_DocVisa',null,false);
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

    function deferralDropbox_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 20){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No se recibio datos' : 'Demasiados datos recibidos',
                "data"=>null,
                "validations"=>null
            );
        }else{
            $capertaExist=$this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$this->post('fkAspirante')),true);

            $data= array(
                "idDocumento"=>$this->post('idDocumento'),
                "nameDocumento"=>$this->post('nameDocumento'),
                "sizeDocumento"=>$this->post('sizeDocumento'),
                "pathDisplayDocumento"=>$this->post('pathDisplayDocumento'),
                "pathLowerDocumento"=>$this->post('pathLowerDocumento'),
                "contentHashDocumento"=>$this->post('contentHashDocumento'),
                "clientModifiedDocumento"=>$this->post('clientModifiedDocumento'),
                "nameCarpeta"=>$capertaExist->nameCarpeta,
                "pathDisplayCarpeta"=>$capertaExist->pathDisplayCarpeta,
                "pathLowerCarpeta"=>$capertaExist->pathLowerCarpeta,
                "fkAspirante"=>$this->post('fkAspirante'),
                "fkInstitucion"=>$this->post('fkInstitucion'),
                "keyDocumentoEliminar"=>$this->post('keyDocumentoEliminar')
            );

            $response = $this->DAO->insertData('Tb_DocDeferral',$data);

            $dataAspirante=array(
                "statusAspirante"=>'D',
            );

            $this->DAO->updateData('Tb_Aspirantes',$dataAspirante,array('idAspirante'=>$this->post('fkAspirante')));
             
        }

        $this->response($response,200);
    }

    public function defeatDocs_delete(){
        $id = $this->get('id');
      if ($id) {
        $IdExists = $this->DAO->selectEntity('Tb_DocumentosOfertaCU',array('idReal'=>$id),TRUE);

        if($IdExists){
          $response = $this->DAO->deleteData('Tb_DocumentosOfertaCU',array('idReal'=>$id));
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

    function deferralDropboxStatus_post(){
        
        $id = $this->get('id');

        $data= array(
            "statusDocumento"=>'Aceptado',
        );

        $response = $this->DAO->updateData('Tb_DocDeferral',$data,array('idReal'=>$id));
             
        $this->response($response,200);
    }

}
