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

    public function registradosDiario_get()
    {
        if (count($this->get())>1) {
            $response = array(
                "status" => "error",
                "status_code" => 409,
                "message" => "Too many data was sent",
                "validations" =>array(
                        "id"=>"send Id (get) to get specific item or empty to get all items "
                ),
                "data"=>null
            );
        }else{
            $fecha = date('Y-m-d');
            $query = $this->db->query("select count(idUsuario) as total, DATE_FORMAT(creationDateUsuario, '%Y-%m-%d') as fecha from Tb_Usuarios where DATE_FORMAT(creationDateUsuario, '%Y-%m-%d')='".$fecha."' group by creationDateUsuario")->row();
           //$query = null;

            if ($query) {
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "Item loaded succesful",
                    "validations" =>null,
                    "data"=>$query
                );
            }else{
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "No data provided",
                    "validations" =>null,
                    "data"=>array('total'=>'0','fecha'=>$fecha)
                );
            }
            
            
        }
        $this->response($response,200);
    }

    public function registradosMes_get()
    {
        if (count($this->get())>1) {
            $response = array(
                "status" => "error",
                "status_code" => 409,
                "message" => "Too many data was sent",
                "validations" =>array(
                        "id"=>"send Id (get) to get specific item or empty to get all items "
                ),
                "data"=>null
            );
        }else{
            $fecha = date('Y-m-d');
            $m = date('m');
            $y = date('Y');
            $new = cal_days_in_month(CAL_GREGORIAN,$m,$y);
            $anterior = $m-01;
            $totalAnterior=$y.'-0'.$anterior.'-01';
            $totalNuevo= $y.'-'.$m.'-'.$new;
            $query = $this->db->query("select count(idUsuario) as total from Vw_Test where fecha between '".$totalAnterior."' and '".$totalNuevo."'")->row();
            

            if ($query) {
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "Item loaded succesful",
                    "validations" =>null,
                    "data"=>$query
                );
                $response['data']->fechaAnterior=$totalAnterior;
                $response['data']->fechaFinal=$totalNuevo;
            }else{
                $response = array(
                    "status" => "error",
                    "status_code" => 409,
                    "message" => "No data provided",
                    "validations" =>null,
                    "data"=>null
                );
            }
            
            
        }
        $this->response($response,200);
    }

    public function statusPorcentaje_get()
    {
        $id = $this->get('id');
        $existe = $this->DAO->selectEntity('Vw_Aspirante',array('aspirante'=>$id),TRUE); 
        if($existe){
            $porcentaje = 0;
            switch($existe->statusAspirante){
                case '0':
                    $porcentaje = 17; 
                    break;
                case '1':
                    $porcentaje = 33; 
                    break;
                case '2':
                    $porcentaje = 50; 
                    break;
                case '2R':
                    $porcentaje = 60; 
                    break;
                case '3':
                    $porcentaje = 67; 
                    break;
                case '4C':
                    $porcentaje = 75; 
                    break;
                case '4U':
                    $porcentaje = 83; 
                    break;
                case 'D':
                    $porcentaje = 70; 
                    break;
                default:
                    $porcentaje = 100; 
                    break;
            }

            $response = array(
                "status"=>"success",
                "message"=> "Porcentaje obtenido con exito",
                "data"=>$porcentaje,
            );
        }else{
            $response = array(
                "status"=>"error",
                "message"=> "Revisa el id",
                "data"=>null,
                );
        } 
        $this->response($response,200);
    }

    public function primerRegistro_get($id)
    {
        if (count($this->get())>1) {
            $response = array(
                "status" => "error",
                "status_code" => 409,
                "message" => "Too many data was sent",
                "validations" =>array(
                        "id"=>"send Id (get) to get specific item or empty to get all items "
                ),
                "data"=>null
            );
        }else{
            $query = $this->db->query("select * from Tb_Usuarios where idUsuario='".$id."'")->row();
           //$query = null;

            if ($query) {
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "Item loaded succesful",
                    "validations" =>null,
                    "data"=>$query
                );
            }else{
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "No data provided",
                    "validations" =>null,
                    "data"=>null
                );
            }
            
            
        }
        $this->response($response,200);
    }

    public function primerRegistroAspirante_get($id)
    {
        if (count($this->get())>1) {
            $response = array(
                "status" => "error",
                "status_code" => 409,
                "message" => "Too many data was sent",
                "validations" =>array(
                        "id"=>"send Id (get) to get specific item or empty to get all items "
                ),
                "data"=>null
            );
        }else{
            $query = $this->db->query("select * from Tb_Aspirantes where idAspirante='".$id."'")->row();
           //$query = null;

            if ($query) {
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "Item loaded succesful",
                    "validations" =>null,
                    "data"=>$query
                );
            }else{
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "No data provided",
                    "validations" =>null,
                    "data"=>null
                );
            }
            
            
        }
        $this->response($response,200);
    }

    public function primerDocumentos_get($id)
    {
        if (count($this->get())>1) {
            $response = array(
                "status" => "error",
                "status_code" => 409,
                "message" => "Too many data was sent",
                "validations" =>array(
                        "id"=>"send Id (get) to get specific item or empty to get all items "
                ),
                "data"=>null
            );
        }else{
            $query = $this->db->query("select * from Tb_Documentos WHERE creationDate  = ( select min(creationDate) from tb_documentos) and fkAspirante ='".$id."'")->row();
           //$query = null;

            if ($query) {
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "Item loaded succesful",
                    "validations" =>null,
                    "data"=>$query
                );
            }else{
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "No data provided",
                    "validations" =>null,
                    "data"=>null
                );
            }
            
            
        }
        $this->response($response,200);
    }

    public function primerNumero3_get($id)
    {
        if (count($this->get())>1) {
            $response = array(
                "status" => "error",
                "status_code" => 409,
                "message" => "Too many data was sent",
                "validations" =>array(
                        "id"=>"send Id (get) to get specific item or empty to get all items "
                ),
                "data"=>null
            );
        }else{
            $query = $this->db->query("select * from Tb_InstitucionAspiranteUniversidades WHERE CIAU  = ( select min(CIAU) from Tb_InstitucionAspiranteUniversidades) and fkAspiranteUniversidad ='".$id."'")->row();
           //$query = null;

            if ($query) {
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "Item loaded succesful",
                    "validations" =>null,
                    "data"=>$query
                );
            }else{
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "No data provided",
                    "validations" =>null,
                    "data"=>null
                );
            }
            
            
        }
        $this->response($response,200);
    }

    public function primeraCarta4_get($id)
    {
        if (count($this->get())>1) {
            $response = array(
                "status" => "error",
                "status_code" => 409,
                "message" => "Too many data was sent",
                "validations" =>array(
                        "id"=>"send Id (get) to get specific item or empty to get all items "
                ),
                "data"=>null
            );
        }else{
            $query = $this->db->query("select * from Tb_DocumentosOfertaCU WHERE creationDate  = ( select min(creationDate) from Tb_DocumentosOfertaCU) and fkAspirante ='".$id."'")->row();
           //$query = null;

            if ($query) {
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "Item loaded succesful",
                    "validations" =>null,
                    "data"=>$query
                );
            }else{
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "No data provided",
                    "validations" =>null,
                    "data"=>null
                );
            }
            
            
        }
        $this->response($response,200);
    }

    public function primeraVisa_get($id)
    {
        if (count($this->get())>1) {
            $response = array(
                "status" => "error",
                "status_code" => 409,
                "message" => "Too many data was sent",
                "validations" =>array(
                        "id"=>"send Id (get) to get specific item or empty to get all items "
                ),
                "data"=>null
            );
        }else{
            $query = $this->db->query("select * from Tb_DocVisa WHERE tipoDocumento='Visa' and fkAspirante ='".$id."'")->row();
           //$query = null;

            if ($query) {
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "Item loaded succesful",
                    "validations" =>null,
                    "data"=>$query
                );
            }else{
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "No data provided",
                    "validations" =>null,
                    "data"=>null
                );
            }
            
            
        }
        $this->response($response,200);
    }

}
