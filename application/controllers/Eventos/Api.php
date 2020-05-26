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

    function eventos_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 5){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No se recibio datos' : 'Demasiados datos recibidos',
                "data"=>null,
                "validations"=>array(
                    "titulo"=>"El titulo es requerido",
                    "fecha" => "La fecha es requerida",
                    "horario" => "El horario es requerida",
                    "descE" => "La descripcion es opcional",
                    "urlE" => "La url del evento es requerida"
                )
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('titulo','Titulo','required');
            $this->form_validation->set_rules('fecha','Fecha','required');
            $this->form_validation->set_rules('horario','Horario','required');
            $this->form_validation->set_rules('urlE','Link de acceso','required');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'check the validations',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{

                $data=array(
                   "tituloEvento"=>$this->post('titulo'),
                   "fechaEvento"=>$this->post('fecha'),
                   "horarioEvento"=>$this->post('horario'),
                   "descEvento"=>$this->post('descE'),
                   "urlEvento"=>$this->post('urlE')
                );
               $response = $this->DAO->insertData('Tb_Eventos',$data);

             }
        }

        $this->response($response,200);
    }

    //cabir la foto del usuario en base al id del usuario
    public function photoEvento_post()
    {
        $id=$this->get('id');
        
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Eventos',array('idEvento'=>$id),true);
            if ($userExist) {

                $config =array(
                    "upload_path"=>"Eventos",
                    "allowed_types"=>"png|jpg|jpeg",
                    "file_name"=>$id,
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('photoEvento'))
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
                        "extEventoI"=>$this->upload->data()['file_ext'],
                        "urlEventoI"=>base_url('Eventos/').$id.$this->upload->data()['file_ext'],
                        "typeEventoI"=>$this->upload->data('file_type')
                    );

                    if($userExist->fkEventoI){
                        $response = $this->DAO->updateData('Tb_EventosImages',$data,array('idEventoI'=>$userExist->fkEventoI));
                    }else{
                        $response = $this->DAO->insertData('Tb_EventosImages',$data,true);
                        $dataEvento = array(
                            "fkEventoI"=>$response['data']
                        );
                        $this->DAO->updateData('Tb_Eventos',$dataEvento,array('idEvento'=>$id));
                    }

                    
                    if($response['status']=="success"){
                        $response['message']= "Imagen subida correctamente";
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

    function eventos_get(){
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
                $data = $this->DAO->selectEntity('Vw_Eventos',array('idEvento'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('Vw_Eventos',null,false);
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

    function eventos_put(){
        $data = $this->put();
        $id = $this->get('id');
        $existe = $this->DAO->selectEntity('Tb_Eventos',array('idEvento'=>$id),TRUE);
        if($existe){
            if(count($data) == 0 || count($data) > 5){
                $response = array(
                    "status"=>"error",
                    "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                    "data"=>null,
                    "validations"=>array(
                        "titulo"=>"El titulo es requerido",
                        "fecha" => "La fecha es requerida",
                        "horario" => "El horario es requerida",
                        "descE" => "La descripcion es opcional",
                        "urlE" => "La url del evento es requerida"
                    )
                );
            }else{
                $this->form_validation->set_data($data);
                $this->form_validation->set_rules('titulo','Titulo','required');
                $this->form_validation->set_rules('fecha','Fecha','required');
                $this->form_validation->set_rules('horario','Horario','required');
                $this->form_validation->set_rules('urlE','Link de acceso','required');
                
                if($this->form_validation->run()==FALSE){
                    $response = array(
                        "status"=>"error",
                        "message"=>'check the validations',
                        "data"=>null,
                        "validations"=>$this->form_validation->error_array()
                    );
                }else{
    
                    $data=array(
                        "tituloEvento"=>$this->put('titulo'),
                        "fechaEvento"=>$this->put('fecha'),
                        "horarioEvento"=>$this->put('horario'),
                        "descEvento"=>$this->put('descE'),
                        "urlEvento"=>$this->put('urlE')
                    );

                   $response = $this->DAO->updateData('Tb_Eventos',$data,array('idEvento'=>$id));
    
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

    public function eventos_delete(){
        $id = $this->get('id');
        if ($id) {
            $exists = $this->DAO->selectEntity('Tb_Eventos',array('idEvento'=>$id),TRUE);

            if($exists){
                if($exists->fkEventoI){
                    $imagen = $this->DAO->selectEntity('Tb_EventosImages',array('idEventoI'=>$exists->fkEventoI),TRUE);
                    if(file_exists('Eventos/'.$id.$imagen->extEventoI)){
                        unlink('Eventos/'.$id.$imagen->extEventoI);
                        $response = $this->DAO->deleteData('Tb_EventosImages',array('idEventoI'=>$imagen->idEventoI));
                    }else{
                        $response = array(
                            "status"=>"error",
                            "status_code"=>409,
                            "message"=>"La imagen no pudo ser eliminada",
                            "validations"=>null,
                            "data"=>null
                        );
                    }
                }else{
                    $response = $this->DAO->deleteData('Tb_EventosImages',array('idEventoI'=>$id));
                }
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
