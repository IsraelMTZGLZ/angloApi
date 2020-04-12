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

    //registro de configuracion de corroe
    public function registro_post()
    {
        if (count($this->post())==0 || count($this->post())>7) {
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>null,
                "validations"=>array(
                    "email"=>"Requerido,Tiene que ser un correo valido",
                    "password"=>"Requerido",
                    "protocolo"=>"Requerido",
                    "puerto"=>"Requerido",
                    "servidor"=>"Requerido",
                    "from"=>"Requerido",
                    "rPassword"=>"Requerido, igual al password"
                ),
                "data"=>null
            );
            count($this->post())>7  ? $response["message"]="Demasiados datos enviados" : $response["message"]="Datos no enviados";

        }else{
            $this->form_validation->set_data($this->post());
            $this->form_validation->set_rules('password','Password','required');
            $this->form_validation->set_rules('protocolo','Protocolo','required');
            $this->form_validation->set_rules('email','Correo','required|valid_email');
            $this->form_validation->set_rules('puerto','Puerto','required');
            $this->form_validation->set_rules('servidor','servidor','required');
            $this->form_validation->set_rules('from','Tipo de registro','required');
            $this->form_validation->set_rules('rPassword','Repetir Password','required|matches[password]');

            if ($this->form_validation->run()==false) {
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Revisa las validaciones",
                    "validations"=>$this->form_validation->error_array(),
                    "data"=>null
                );
            }
            else{
                $this->load->library('encryption');

                $pass=$this->encryption->encrypt($this->post('password'));
                $data=array(
                    "email_send"=>$this->post('email'),
                    "email_pass"=>$this->encryption->encrypt($this->post('password')),
                    "email_protocol"=>$this->post('protocolo'),
                    "email_port"=>$this->post('puerto'),
                    "email_host"=>$this->post('servidor'),
                    "from_email"=>$this->post('from')
                );

                $itemExist=$this->DAO->selectEntity('Tb_config',null,true);

                if(!$itemExist){
                    $response = $this->DAO->insertData('Tb_config',$data);
                }else{
                    $response = $this->DAO->updateData('Tb_config',$data,array('email_send'=>$itemExist->email_send));
                }
            }
        }

        $this->response($response,200);
    }

    public function email_get()
    {
        if (count($this->get())>0) {
            $response = array(
                "status" => "error",
                "status_code" => 409,
                "message" => "Too many data was sent",
                "validations" =>null,
                "data"=>null
            );
        }else{

            $data = $this->DAO->selectEntity('Tb_config',null,true);
            if ($data) {
                $response = array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "Artículo cargado correctamente",
                    "validations" =>null,
                    "data"=>$data
                );
            }else{
                $response = array(
                    "status" => "error",
                    "status_code" => 409,
                    "message" => "Datos no proveidos",
                    "validations" =>null,
                    "data"=>null
                );
            }
        }
        $this->response($response,200);
    }

    public function templateEmail($to,$name,$subject,$data=null,$vista)
    {
        $this->load->library('encryption');
        $this->load->library('email');

        $email_settings =  $this->DAO->selectEntity('Tb_config',null,true);
        if($email_settings){
            $config['protocol'] = $email_settings['email_protocol'];
            $config['smtp_host'] = "ssl://".$email_settings['email_host'];
            $config['smtp_user'] = $email_settings['email_send'];
            $config['smtp_pass'] = $this->encryption->decrypt($email_settings['email_pass']);
            $config['smtp_port'] = $email_settings['email_port'];
            $config['charset'] = "utf-8";
            $config['mailtype'] = "html";
            $this->email->initialize($config);

            $this->email->set_newline("\r\n");

            $this->email->from($email_settings['email_send'],$email_settings['from_email']);
            $this->email->to($to,$name);
            $this->email->subject($subject);
            $msg = $this->load->view($vista,$data,true);
            $this->email->message($msg);
            if($this->email->send()){

            }else{

            }
        }
    }

}
