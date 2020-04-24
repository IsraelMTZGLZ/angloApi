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
            $query = $this->db->query("select count(idUsuario) as total from Vw_Reporte where fecha between '".$totalAnterior."' and '".$totalNuevo."'")->row();
            

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

}
