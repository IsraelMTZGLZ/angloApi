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

    public function sendEmailStatus0_post()
    {
        $id = $this->get('id');
        $existe = $this->DAO->selectEntity('Vw_Aspirante',array('usuario'=>$id),TRUE);
        if($existe){
            $headers = array(
                'Authorization: Bearer ',
                'Content-Type: application/json'
            );
    
            $data = array(
                "personalizations" => array(
                    array(
                        "to" => array(
                            array(
                                "email" => $existe->email,
                                "name" => $existe->names
                            )
                        ),
                        "dynamic_template_data"=> array (
                            
                            "user" => $existe->names
                            
                        )
    
                    )
                ),
                "from" => array(
                    "email" => "study@anglopageone.com",
                    "name"=>"Anglo Latino Education Partnership"
                ),
                "reply_to"=> array(
                    "email"=>"study@anglolatinoedu.com",
                    "name"=>"Anglo Latino Education Partnership"
                ),
                "template_id"=> "d-21a834291b584caaa9736b0c3fd2679f"
            );
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);

        }else{
            $response = array(
            "status"=>"error",
            "message"=> "Revisa el id",
            "data"=>null,
            );
        }
        $this->response($response,200);
    }

    public function sendEmailStatus1_post()
    {
        $id = $this->get('id');
        $existe = $this->DAO->selectEntity('Vw_Aspirante',array('aspirante'=>$id),TRUE);
        if($existe){
            $camposVacios = array();
            if($existe->programaDeInteres == null){
                array_push($camposVacios,'Programa de interes','Seleccion de escuelas de interes');
            }else{
                if($existe->programaDeInteres == "Universidad"){
                    $universidad = $this->DAO->selectEntity('Vw_AspiranteUniversidad',array('fkAspirante'=>$id),TRUE);
                    if(!$universidad){
                        array_push($camposVacios,'Tipo de estudio de interes','Facultad','Seleccion de universidades de interes');
                    }else{
                        if($universidad->anioMesIngreso == null){
                            array_push($camposVacios,'Seleccion de universidades de interes','Año de ingreso','Mes de ingreso');
                        }else{
                            array_push($camposVacios,'Falta subir algun tipo de documento requerido para iniciar tu proceso');
                        }
                    }
                }else if($existe->programaDeInteres == "Preparatoria"){
                    $preparatoria = $this->DAO->selectEntity('Vw_AspirantePreparatoria',array('fkAspirante'=>$id),TRUE);
                    if(!$preparatoria){
                        array_push($camposVacios,'Tipo de estudio de interes','Tipo de alojamiento','Seleccion de preparatorias de interes');
                    }else{
                        if($preparatoria->anioMesIngreso == null){
                            array_push($camposVacios,'Seleccion de preparatorias de interes','Año de ingreso','Mes de ingreso');

                        }else{
                            array_push($camposVacios,'Falta subir algun tipo de documento requerido para iniciar tu proceso');

                        }
                    }
                }else{
                    array_push($camposVacios,'Falta completar la informacion necesaria');

                }
            }
            if($camposVacios){
                $headers = array(
                    'Authorization: Bearer ',
                    'Content-Type: application/json'
                );
        
                $data = array(
                    "personalizations" => array(
                        array(
                            "to" => array(
                                array(
                                    "email" => $existe->email,
                                    "name" => $existe->names
                                )
                            ),
                            "dynamic_template_data"=> array (
                                
                                "user" => $existe->names,
                                "falta" => $camposVacios
                                
                            )
        
                        )
                    ),
                    "from" => array(
                        "email" => "study@anglopageone.com",
                        "name"=>"Anglo Latino Education Partnership"
                    ),
                    "reply_to"=> array(
                        "email"=>"study@anglolatinoedu.com",
                        "name"=>"Anglo Latino Education Partnership"
                    ),
                    "template_id"=> "d-35a19dfcb66f4705bec4ff2d07d30fe1"
                );
            
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($ch);
                curl_close($ch);
            }else{
                $response = array(
                    "status"=>"error",
                    "message"=> "Error inesperado",
                    "data"=>null,
                );
            }
        }else{
            $response = array(
                "status"=>"error",
                "message"=> "Revisa el id",
                "data"=>null,
            );
        }
        $this->response($response,200);
    }
}
