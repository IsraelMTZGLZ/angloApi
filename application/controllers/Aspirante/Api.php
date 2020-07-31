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

    function aspirante_post(){
        if (count($this->post())==0 || count($this->post())>5) {
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>null,
                "validations"=>array(
                    "persona"=>"Requerido,Tiene que exisir",
                    "telefono"=>"Requerido,Tiene que ser menor a 20 caracteres",
                    "fecha"=>"Requerido,Tiene que ser una fecha valida",
                    "genero"=>"Opcional,Eso tiene que se 'Femenino' o Masculino'",
                    "ciudad"=>"Requerido",
                ),
                "data"=>null
            );
            count($this->post())>5  ? $response["message"]="Demasiados datos enviados" : $response["message"]="Datos no enviados";

        }else{
            $this->form_validation->set_data($this->post());
            $this->form_validation->set_rules('persona','Fk persona','callback_check_persona');
            $this->form_validation->set_rules('telefono','Telefono','required|min_length[1]|max_length[20]');
            $this->form_validation->set_rules('fecha','Fecha de nacimiento','required');
            $this->form_validation->set_rules('genero','Genero','callback_check_gender');
            $this->form_validation->set_rules('ciudad','Ciudad de origen','required');

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

                $data['Persona']=array(
                    "generoPersona"=>$this->post('genero')
                );

                $data['Aspirante']=array(
                    "fechaNacimientoAspirante"=>$this->post('fecha'),
                    "telefonoAspirante"=>$this->post('telefono'),
                    "ciudadAspirante"=>$this->post('ciudad'),
                    "fkPersona"=>$this->post('persona')
                );

                $response = $this->DAO->insertData('Tb_Aspirantes',$data['Aspirante']);
                $response = $this->DAO->updateData('Tb_Personas',$data['Persona'],array("idPersona"=>$this->post('persona')));

            }
        }
        $this->response($response,200);
    }

    function aspiranteEleccion_post(){
        if (count($this->post())==0 || count($this->post())>2) {
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>null,
                "validations"=>array(
                    "aspirante"=>"Requerido",
                    "institucion"=>"Requerido"
                ),
                "data"=>null
            );
            count($this->post())>2  ? $response["message"]="Demasiados datos enviados" : $response["message"]="Datos no enviados";

        }else{
            $this->form_validation->set_data($this->post());
            $this->form_validation->set_rules('aspirante','id Aspirante','callback_check_institucion');
            $this->form_validation->set_rules('institucion','institucion Universidad','required');

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

                $data=array(
                    "programaDeInteres"=>$this->post('institucion')
                );

                $response = $this->DAO->updateData('Tb_Aspirantes',$data,array("idAspirante"=>$this->post('aspirante')));

            }
        }
        $this->response($response,200);
    }

    public function aspirante_get()
    {
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
                $data = $this->DAO->selectEntity('Vw_Aspirante',array('usuario'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('Vw_Aspirante',null,false);
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

    public function aspiranteByStatus0_get()
    {
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
                $data = $this->DAO->selectEntity('Vw_Aspirante',array('usuario'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('Vw_Aspirante',array('statusAspirante'=>null),false);
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

    public function aspiranteByStatus1_get()
    {
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
                $data = $this->DAO->selectEntity('View_Aspirantes_Uni_Prepa',array('usuario'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('View_Aspirantes_Uni_Prepa',array('statusAspirante'=>'0'),false);
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

    public function aspiranteByStatus2_get()
    {
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
                $data = $this->DAO->selectEntity('View_Aspirantes_By_Status',array('aspirante'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('View_Aspirantes_By_Status',false);
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

    function check_persona($str){
        if (!$str) {
            $this->form_validation->set_message('check_persona','The {field} campo es requerido');
            return false;
        }

        $itemExist=$this->DAO->selectEntity('Tb_Personas',array('idPersona'=>$str),true);
        if (!$itemExist) {
            $this->form_validation->set_message('check_persona','The {field} campo no existe');
            return false;
        }else{
            return true;
        }

    }

    function check_institucion($str){
        if (!$str) {
            $this->form_validation->set_message('check_institucion','The {field} campo es requerido');
            return false;
        }

        $itemExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$str),true);
        if (!$itemExist) {
            $this->form_validation->set_message('check_institucion','The {field} campo no existe');
            return false;
        }else{
            return true;
        }

    }

    function check_gender($str){
        if (!$str) {
            return true;
        }

        switch ($str) {
            case 'Femenino':
                return true;
                break;
            case 'Masculino':
                return true;
                break;
            default:
                $this->form_validation->set_message('check_gender','The {field} campo tiene que ser la palabra "Femenino" or "Masculino"');
                return false;
                break;
        }

    }

    public function check_noRequerido($str){
        if (!$str) {
            return true;
        }else{
            $this->form_validation->set_message('check_noRequerido','El {field} campo no es requerido');
            return false;
        }

    }

    public function generarBecasNecesidades_get()
    {
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
            
            $existe = $this->DAO->selectEntity('Vw_NBA',array('fkAspirante'=>$id),true);
            
            if($existe){
                $data = $this->DAO->selectEntity('Vw_NBA',array('fkAspirante'=>$id),false);
            }else{
                $generar = $this->DAO->selectEntity('Tb_NecesidadesBecas',false);

                foreach ($generar as $key ) {
                    $data=array(
                        "fkAspirante"=>$id,
                        "fkNB"=>$key->idNB
                    );
                    $this->DAO->insertData('Tb_NBAspirante',$data);
                }

                $data = $this->DAO->selectEntity('Vw_NBA',array('fkAspirante'=>$id),false);

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

    function changeStatusNB_put(){
        $data = $this->put();
        $id = $this->get('id');
        $existe = $this->DAO->selectEntity('Tb_NBAspirante',array('idNBAAspirante'=>$id),TRUE);
        if($existe){
            if(count($data) == 0 || count($data) > 1){
                $response = array(
                    "status"=>"error",
                    "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                    "data"=>null,
                    "validations"=>array(
                        "status"=>"El status es requerido"
                    )
                );
            }else{
                $this->form_validation->set_data($data);
                $this->form_validation->set_rules('status','Estatus','required');
    
                 if($this->form_validation->run()==FALSE){
                    $response = array(
                        "status"=>"error",
                        "message"=>'check the validations',
                        "data"=>null,
                        "validations"=>$this->form_validation->error_array()
                    );
                }else{
    
                    $data=array(
                       "statusNB"=>$this->put('status')
                    );

                    $response = $this->DAO->updateData('Tb_NBAspirante',$data,array('idNBAAspirante'=>$id));
                    
                }
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

    function ATASPONER_post(){
        $id = $this->get('id');
        if (count($this->post())==0 || count($this->post())>2) {
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>null,
                "validations"=>array(
                    "status"=>"Requerido",
                ),
                "data"=>null
            );
            count($this->post())>2  ? $response["message"]="Demasiados datos enviados" : $response["message"]="Datos no enviados";

        }else{
            $this->form_validation->set_data($this->post());
            $this->form_validation->set_rules('status','La respuesta si o no','required');

            if ($this->form_validation->run()==false) {
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"La casilla de si/no es requerido",
                    "validations"=>$this->form_validation->error_array(),
                    "data"=>null
                );
            }
            else{

                $data=array(
                    "aplicaATAS"=>$this->post('status'),
                    "fkAspirante"=>$id
                );

                $response = $this->DAO->insertData('AplicaATASAspirante',$data);

            }
        }
        $this->response($response,200);
    }

    public function ATASSeleccion_get()
    {
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
                $data = $this->DAO->selectEntity('AplicaATASAspirante',array('fkAspirante'=>$id),true);
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

    public function aspiranteByStatus5_get()
    {
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
                $data = $this->DAO->selectEntity('View_Aspirantes_By_Status5',array('aspirante'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('View_Aspirantes_By_Status5',false);
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

    public function aspiranteByAspirante_get()
    {
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
                $data = $this->DAO->selectEntity('Vw_Aspirante',array('aspirante'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('Vw_Aspirante',null,false);
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

    public function aspiranteDocDeferal_get()
    {
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
                $data = $this->DAO->selectEntity('View_DocDefereal',array('fkAspirante'=>$id),false);
            }
            else{
                $data = $this->DAO->selectEntity('View_DocDefereal',false);
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

    function BECASPONER_post(){
        $id = $this->get('id');
        if (count($this->post())==0 || count($this->post())>2) {
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>null,
                "validations"=>array(
                    "status"=>"Requerido",
                ),
                "data"=>null
            );
            count($this->post())>2  ? $response["message"]="Demasiados datos enviados" : $response["message"]="Datos no enviados";

        }else{
            $this->form_validation->set_data($this->post());
            $this->form_validation->set_rules('status','La respuesta si o no','required');

            if ($this->form_validation->run()==false) {
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"La casilla de si/no es requerido",
                    "validations"=>$this->form_validation->error_array(),
                    "data"=>null
                );
            }
            else{

                $data=array(
                    "aplicaBecas"=>$this->post('status'),
                    "fkAspirante"=>$id
                );

                $response = $this->DAO->insertData('AplicaBecasAspirante',$data);

            }
        }
        $this->response($response,200);
    }

    public function BECASSeleccion_get()
    {
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
                $data = $this->DAO->selectEntity('AplicaBecasAspirante',array('fkAspirante'=>$id),true);
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

}
