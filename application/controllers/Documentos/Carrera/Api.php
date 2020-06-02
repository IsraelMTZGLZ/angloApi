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

    function carreraByAspirante_get(){
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
                $data = $this->DAO->selectEntity('Tb_Documentos',array('fkAspirante'=>$id,'tipo'=>$tipo),false);
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
    
}
