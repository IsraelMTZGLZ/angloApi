<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
require APPPATH . 'libraries/sendgrid-php.php';

class Api extends REST_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('DAO');
        $this->load->library('email');

    }

    function instSelected_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Tb_Aspirante_Eleccion',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Tb_Aspirante_Eleccion'),
            );
        }
        $this->response($response,200);
    }
    public function correoStatusUno_post()
    {
        $id = $this->get('id');
        $existe = $this->DAO->selectEntity('Vw_Aspirante',array('aspirante'=>$id),TRUE);
        if($existe){
            $camposVacios = array();

            array_push($camposVacios,'Acompleta tu registro','Seleccion de interes');

            if(!$camposVacios){
                $headers = array(
                    'Authorization: Bearer',
                    'Content-Type: application/json'
                );

                $data = array(
                    "personalizations" => array(
                        array(
                            "to" => array(
                                array(
                                    "email" => '',
                                    "name" => $existe->names
                                )
                            ),
                            "dynamic_template_data"=> array (
                                "user" => 'Israel',
                                "falta" => $camposVacios
                            )

                        )
                    ),
                    "from" => array(
                        "email" => "israelmg250598@gmail.com",
                        "name"=>"Anglo Latino Education Partnership"
                    ),
                    "reply_to"=> array(
                        "email"=>"israelmg250598@gmail.com",
                        "name"=>"Anglo Latino Education Partnership"
                    ),
                    "template_id"=>"d-d68b0712c117436f9d46979f9b64e9d8"
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



$curl = curl_init();

curl_setopt_array($curl, array(
CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 30,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "POST",
CURLOPT_POSTFIELDS => "{\"personalizations\":[{\"to\":[{\"email\":\"israelmg250598@gmail.com\",\"name\":\"John Doe\"}],\"dynamic_template_data\":{\"verb\":\"Hola\",\"adjective\":\"Como estas  \",\"noun\":\"Hola\",\"currentDayofWeek\":\"Hola\"},\"subject\":\"Hello, World!\"}],\"from\":{\"email\":\"bl151297@gmail.com\",\"name\":\"John Doe\"},\"reply_to\":{\"email\":\"israelmg250598@gmail.com\",\"name\":\"John Doe\"},\"template_id\":\"d-d68b0712c117436f9d46979f9b64e9d8\"}",
CURLOPT_HTTPHEADER => array(
"authorization: Bearer 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",
"content-type: application/json"
),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
echo "cURL Error #:" . $err;
} else {
echo $response;
}


            }
        }else{
          //create mail object


        $response = array(
                "status"=>"error",
                "message"=> "Revisa el id",
                "data"=>$id,
            );
        }
        $this->response($response,200);
    }
    private function corsreoStatusUno_post() {

		$from = new \SendGrid\Email(null, "info@mydomain.com");
		$subject = "Weekly Customers Update";
		$to = new \SendGrid\Email(null, "recipient1@somedomain.com");

		$content = new \SendGrid\Content("text/plain", "Report attached.");
		// $content = new \SendGrid\Content("text/html", "<h1>Sending with SendGrid is Fun and easy to do anywhere, even with PHP</h1>");

		$mail = new \SendGrid\Mail($from, $subject, $to, $content);
		$to = new  \SendGrid\Email(null, "recipient2@somedomain.com");
		$mail->personalization[0]->addTo($to);

		$apiKey = 'xxxxx';
		$sg = new \SendGrid($apiKey);
		$response = $sg->client->mail()->send()->post($mail);
    $response = array(
        "status"=>"error",
        "message"=> "Revisa el id",
        "data"=>$id,
    );
		//echo $response->statusCode();
		//echo $response->body();
		//print_r($response->headers());
	}
    function numSelected_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Tb_NumAplicanteAcademico',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Tb_NumAplicanteAcademico'),
            );
        }
        $this->response($response,200);
    }

    function docsAcepted_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                  "data"=>$this->DAO->selEntityMany('Vw_Documentos',array('idAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Vw_Documentos'),
            );
        }
        $this->response($response,200);
    }
    function docsNumAplicante_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                  "data"=>$this->DAO->selEntityMany('Vw_DocumentosVIA',array('idAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Vw_DocumentosVIA'),
            );
        }
        $this->response($response,200);
    }

    function fileVeranoIngles_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Tb_formdesolicitudVeranoIngAspirante',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Tb_formdesolicitudVeranoIngAspirante'),
            );
        }
        $this->response($response,200);
    }

    function fileinfoVisa_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Tb_DocVerIngVisa',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_DocVerIngVisa'),
            );
        }
        $this->response($response,200);
    }

    function recomendationVisa_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Tb_RecomendacionAspirante',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_RecomendacionAspirante'),
            );
        }
        $this->response($response,200);
    }
    function aspiranteVerInglesBYAspirante_get(){
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
                $data = $this->DAO->selectEntity('Vw_InfoVeranoApirante',array('idAspirante'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('Vw_InfoVeranoApirante',null,false);
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
    function aspiranteInglesBYAspirante_get(){
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
                $data = $this->DAO->selectEntity('Vw_InfoEnglish',array('idAspirante'=>$id),true);
            }
            else{
                $data = $this->DAO->selectEntity('Vw_InfoEnglish',null,false);
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
    //new 09 JUl
    public function extraDocs_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {

                $carpeta = 'files/VeranoIngles/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"files/VeranoIngles/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>$this->post('nameDoc'),
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('Docs'))
                {
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Upload fails",
                    "validations"=>$this->upload->display_errors(),
                    "data"=>$this->post()
                );
                }
                else{
                  $this->db->trans_begin();
                    $data = array(

                        "nombreDocumento"=>$this->upload->data('file_name'),
                        "extDocumento"=>$this->upload->data()['file_ext'],
                        "urlDocumento"=>base_url('files/VeranoIngles/'.$id.'/'.$this->upload->data('file_name')),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"Docs",
                        "statusDocumento"=>"Revision"
                    );


                    $docResponse = $this->DAO->saveOrUpdateItem('Tb_DocumentosVeranoIngles',$data,null,true);
                    if($docResponse['status']=="success"){

                      $docExtra = array(
                          "fkAspirante"=>$this->post('aspirante'),
                          "fkDocumento"=>$docResponse['key']
                      );
                      $responseDocExtra = $this->DAO->saveOrUpdateItem('Tb_DocumentosAspirante',$docExtra,null,true);

                      if($responseDocExtra['status']=="success"){
                          $response = array(
                             "status"=>"success",
                             "message"=>"item update successfully",
                             "data"=>null,
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>$responseDocExtra['message'],
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
                        "message"=>"Fichero no fue subido correctamente",
                        "data"=>$data
                      );
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


            //a
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

    public function referencia_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {

                $carpeta = 'files/VeranoIngles/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"files/VeranoIngles/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>$this->post('nameDoc'),
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('Docs'))
                {
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Upload fails",
                    "validations"=>$this->upload->display_errors(),
                    "data"=>$this->post()
                );
                }
                else{
                  $this->db->trans_begin();
                    $data = array(

                        "nombreDocumento"=>$this->post('nameDoc'),
                        "extDocumento"=>$this->upload->data()['file_ext'],
                        "urlDocumento"=>base_url('files/VeranoIngles/'.$id.'/'.$this->upload->data('file_name')),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"referenciaDePago",
                        "statusDocumento"=>"Activo"
                    );


                    $docResponse = $this->DAO->saveOrUpdateItem('Tb_DocumentosVeranoIngles',$data,null,true);
                    if($docResponse['status']=="success"){

                      $docExtra = array(
                          "fkAspirante"=>$this->post('aspirante'),
                          "fkDocumento"=>$docResponse['key']
                      );
                      $responseDocExtra = $this->DAO->saveOrUpdateItem('Tb_DocumentosAspirante',$docExtra,null,true);

                      if($responseDocExtra['status']=="success"){
                          $response = array(
                             "status"=>"success",
                             "message"=>"item update successfully",
                             "data"=>null,
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>$responseDocExtra['message'],
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
                        "message"=>"Fichero no fue subido correctamente",
                        "data"=>$data
                      );
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


            //a
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
    public function ticketPago_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {

                $carpeta = 'files/VeranoIngles/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"files/VeranoIngles/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>"Ticket_De_Pago",
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('Docs'))
                {
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Upload fails",
                    "validations"=>$this->upload->display_errors(),
                    "data"=>$this->post()
                );
                }
                else{
                  $this->db->trans_begin();
                    $data = array(

                        "nombreDocumento"=>$this->upload->data('file_name'),
                        "extDocumento"=>$this->upload->data()['file_ext'],
                        "urlDocumento"=>base_url('files/VeranoIngles/'.$id.'/'.$this->upload->data('file_name')),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>$this->post('tipo'),
                        "statusDocumento"=>"Revision"
                    );


                    $docResponse = $this->DAO->saveOrUpdateItem('Tb_DocumentosVeranoIngles',$data,null,true);
                    if($docResponse['status']=="success"){

                      $docExtra = array(
                          "fkAspirante"=>$this->post('aspirante'),
                          "tipoDoc"=>$this->post('tipo'),
                          "fkDocumento"=>$docResponse['key']
                      );
                      $responseDocExtra = $this->DAO->saveOrUpdateItem('Tb_DocumentosAspirante',$docExtra,null,true);

                      if($responseDocExtra['status']=="success"){
                          $response = array(
                             "status"=>"success",
                             "message"=>"item update successfully",
                             "data"=>null,
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>$responseDocExtra['message'],
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
                        "message"=>"Fichero no fue subido correctamente",
                        "data"=>$data
                      );
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


            //a
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
    public function documentUpdate_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {
              $tipo  = (string)$this->post('tipo');
              // $Eixist = $this->DAO->selectbyTwoEntity('Vw_DocumentosVeranoIngles',array('idAspirante'=>$id),$tipo,TRUE);
              $Eixist = $this->DAO->selectbyTwoEntityTest('Vw_DocumentosVeranoIngles',array('idAspirante'=>$id),array('tipo'=>$tipo),TRUE);
              if($Eixist){
                $response = $this->DAO->deleteDataTwoClause('Tb_DocumentosAspirante',array('fkDocumento'=>$Eixist),array('tipoDoc'=>$tipo));
                if($response['status']=="success"){

                  $carpeta = 'files/VeranoIngles/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/VeranoIngles/".$id,
                      "allowed_types"=>"pdf",
                      "file_name"=>"Ticket_De_Pago",
                      "overwrite"=>true
                  );

                  $this->load->library('upload',$config);
                  if ( ! $this->upload->do_upload('Docs'))
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
                            "urlDocumento"=>base_url('files/VeranoIngles/'.$id.'/'.$this->upload->data('file_name')),
                            "typeDocumento"=>$this->upload->data('file_type'),
                            "tipo"=>$this->post('tipo'),
                            "statusDocumento"=>"Revision"
                        );


                      $docResponse = $this->DAO->saveOrUpdateItem('Tb_DocumentosVeranoIngles',$data,null,true);
                      if($docResponse['status']=="success"){

                        $docExtra = array(
                            "fkAspirante"=>$this->post('aspirante'),
                            "fkDocumento"=>$docResponse['key'],
                            "tipoDoc"=>$this->post('tipo')
                        );
                        $responseDocExtra = $this->DAO->saveOrUpdateItem('Tb_DocumentosAspirante',$docExtra,null,true);

                        if($responseDocExtra['status']=="success"){
                            $response = array(
                               "status"=>"success",
                               "message"=>"item update successfully",
                               "data"=>null,
                           );

                        }else{
                            $response = array(
                                "status"=>"error",
                                "message"=>$responseDocExtra['message'],
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
                          "message"=>"Fichero no fue subido correctamente",
                          "data"=>$data
                        );
                      }
                  }
                }else{
                  $response=array(
                      "status"=>"error",
                      "status_code"=>409,
                      "message"=>"The documento was not deleted correctly",
                      "data"=>$Eixist
                  );
                }
              }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Document does not existss",
                    "data"=>$Eixist
                );
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


            //a
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

    public function referenciaPago_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectbyTwoEntityReturnResult('Vw_DocumentosVeranoIngles',array('idAspirante'=>$id),array('tipo'=>'referenciaDePago'),TRUE),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_DocumentosVeranoIngles'),
            );
        }
        $this->response($response,200);
    }
    public function ticketPago_get(){
        $id = $this->get('id');
        $aux = $this->get('aux');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectbyThreeEntity('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('fkInstitucion'=>$aux),array('type'=>'Ticket_De_Pago'),TRUE),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_DocsApiranteVIA'),
            );
        }
        $this->response($response,200);
    }

    public function tipoOferta_get(){
        $id = $this->get('id');
        $aux = $this->get('aux');
        $tipo = $this->get('tipo');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=>$aux,
                "data"=>$this->DAO->selectbyThreeEntity('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('fkInstitucion'=>$aux),array('type'=>$tipo),TRUE),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_DocsApiranteVIA'),
            );
        }
        $this->response($response,200);
    }
    public function ticketStatus_put($id=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectbyTwoEntity('Vw_DocumentosVeranoIngles',array('idAspirante'=>$id),array('tipo'=>'Ticket_De_Pago'),TRUE);
        if($Eixist){
          if(count($data) == 0 || count($data) > 4){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data),
                  "data"=>null,
                  "validations"=>array(
                    "status"=>"Required, The Status is required",
                  )
              );
          }else{
              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('status','Status','required');

             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'data received',
                      "data"=>$data,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{

                 if($this->put('status') == "Rechazado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>$this->put('status')
                   );
                   // $statusResponse =$this->DAO->updateData('Tb_DocumentosVeranoIngles',$status,array('fkAspirante'=>$id));
                   $statusResponse = $this->DAO->saveOrUpdateItemByTwoEntity('Tb_DocumentosVeranoIngles',$status,array('idDocumento'=>$this->put('idDocument')),array('tipo'=>'Ticket_De_Pago'),true);
                    if($statusResponse['status']=="success"){
                      $EixistRec = $this->DAO->selectEnt('Tb_Recomendation',array('fkDocumento'=>$this->put('idDocument')),TRUE);
                      if($EixistRec){
                        $recomendation = array(
                          "description"=>$this->put('desc'),
                          "fkDocumento"=>$this->put('idDocument')
                        );
                        $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_Recomendation',$recomendation,array('fkDocumento'=>$this->put('idDocument')),true);

                        if($recomendationResponse['status']=="success"){
                            $response = array(
                               "status"=>"success",
                               "message"=>"update successfully",
                               "data"=>null,
                           );

                        }else{
                            $response = array(
                                "status"=>"errorr",
                                "message"=>  $recomendationResponse['message'],
                                "data"=>$statusResponse,
                            );
                        }
                        if($this->db->trans_status()==FALSE){
                            $this->db->trans_rollback();
                        }else{
                            $this->db->trans_commit();
                        }
                      }else{
                        $recomendation = array(
                          "description"=>$this->put('desc'),
                          "fkDocumento"=>$this->put('idDocument')
                        );
                        $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_Recomendation',$recomendation,null,true);

                        if($recomendationResponse['status']=="success"){
                            $response = array(
                               "status"=>"success",
                               "message"=>"update successfully",
                               "data"=>$EixistRec,
                           );

                        }else{
                            $response = array(
                                "status"=>"error",
                                "message"=>  $recomendationResponse['message'],
                                "data"=>$EixistRec,
                            );
                        }
                        if($this->db->trans_status()==FALSE){
                            $this->db->trans_rollback();
                        }else{
                            $this->db->trans_commit();
                        }
                      }

                    }else{
                      $response = array(
                          "status"=>"error",
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }

                 }else if($this->put('status') == "Aceptado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>$this->put('status')
                   );
                   $statusResponse = $this->DAO->saveOrUpdateItemByTwoEntity('Tb_DocumentosVeranoIngles',$status,array('idDocumento'=>$this->put('idDocument')),array('tipo'=>'Ticket_De_Pago'),true);
                    if($statusResponse['status']=="success"){
                      // $this->cambiarEstatus($id,'4U');
                      $recomendationResponse = $this->DAO->deleteData('Tb_Recomendation',array('fkDocumento'=>$this->put('idDocument')));
                         //  $response = array(
                         //     "status"=>"success",
                         //     "message"=>"update successfully",
                         //     "data"=>$statusResponse,
                         // );
                      if($recomendationResponse['status']=="success"){
                        $this->cambiarEstatus($id,'4C');
                          $response = array(
                             "status"=>"success",
                             "message"=>"update successfully",
                             "data"=>$statusResponse,
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>  $recomendationResponse['message'],
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
                          "message"=>  'error',
                          "data"=>$statusResponse,
                      );
                    }
                 }else {
                   $response = array(
                       "status"=>"error",
                       "message"=>  'error',
                       "data"=>null,
                   );
                 }
               // $data = array(
               //   "statusDocumento"=>$this->put('status'),
               // );
               // $response = $this->DAO->updateData('Tb_DocumentosVerano',$data,array('fkAspirante'=>$id));
               }
          }
        }else{
          $response = array(
            "status"=>"error",
            "message"=> "check the id",
            "data"=>$Eixist,
            );
        }
        $this->response($response,200);
    }
    //new Jul 07
    public function numAplication_get(){
        $id = $this->get('id');
        $idDocForm = $this->get('idDocForm');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectbyTwoEntity('Tb_NumAplicanteAcademico',array('fkAspirante'=>$id),array('fkInstitucion'=>$idDocForm),TRUE),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_NumAplicanteAcademico'),
            );
        }
        $this->response($response,200);
    }
    function numAplicante_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 4){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(
                    "numeroacademico"=>"El nombre es requerido",
                    "fkAspirante" => "Aspirante requerido",
                    "fkInstitucion" => "Instituciones requerido"
                )
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('numeroacademico','numeroacademico','required');
            $this->form_validation->set_rules('fkAspirante','Aspirante','required');
            $this->form_validation->set_rules('fkInstitucion','Instituciones','required');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'check the validations',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{

               $data=array(
                   "numeroAplicante"=>$this->post('numeroacademico'),
                   "fkAspirante"=>$this->post('fkAspirante'),
                   "fkInstitucion"=>$this->post('fkInstitucion')
               );

               $Response = $this->DAO->saveOrUpdateItem('Tb_NumAplicanteAcademico',$data,null,true);

               if($Response['status']=="success"){
                 $userData=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$this->post('fkAspirante')),true);
                 if($userData->statusAspirante == '2R'){
                   $this->cambiarEstatus($this->post('fkAspirante'),'3');
                 }
                  // $this->cambiarEstatus($this->post('fkAspirante'),'3');
                   $response = array(
                      "status"=>"success",
                      "message"=>"update successfullyy",
                      "data"=>$userData->statusAspirante
                  );
                  if($this->db->trans_status()==FALSE){
                      $this->db->trans_rollback();
                  }else{
                      $this->db->trans_commit();
                  }

               }else{
                   $response = array(
                       "status"=>"error",
                       "message"=>  $data,
                       "data"=>null,
                   );
               }
             }
        }

        $this->response($response,200);
    }
    //

    public function pasaporteUpdateTest_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {
              $Eixist = $this->DAO->selectEntity('Tb_DocumentosVerano',array('fkAspirante'=>$id),TRUE);
              if($Eixist){
                $response = $this->DAO->deleteData('Tb_DocumentosVerano',array('fkAspirante'=>$id));
                if($response['status']=="success"){

                  $carpeta = 'files/Verano/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/Verano/".$id,
                      "allowed_types"=>"pdf",
                      "file_name"=>"Pasaporte",
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
                          "urlDocumento"=>base_url('files/Verano/'.$id.'/'.$this->upload->data('file_name')),
                          "typeDocumento"=>$this->upload->data('file_type'),
                          "tipo"=>"Pasaporte",
                          "statusDocumento"=>"Revision",
                          "fkAspirante"=>$this->post('aspirante')
                      );

                      $response = $this->DAO->insertData('Tb_DocumentosVerano',$data);
                      if($response['status']=="success"){
                        $this->cambiarEstatus($id,'1');
                        $response = array(
                          "status"=>"success",
                          "message"=>"Fichero fue subido correctamente",
                          "data"=>$data
                        );
                      }
                  }
                }else{
                  $response=array(
                      "status"=>"error",
                      "status_code"=>409,
                      "message"=>"The documento was not deleted correctly",
                      "data"=>null
                  );
                }
              }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Document does not exists",
                    "data"=>null
                );
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


            //a
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
    function veranoIngles_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Tb_formdesolicitudVeranoIngAspirante',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_formdesolicitudVeranoIngAspirante'),
            );
        }
        $this->response($response,200);
    }
    function statusCero_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Vw_VeranoStatusCero',array('idPersona'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_VeranoStatusCero'),
            );
        }
        $this->response($response,200);
    }
    function statusUno_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Vw_VeranoStatusUno',array('idPersona'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_VeranoStatusUno'),
            );
        }
        $this->response($response,200);
    }

    function statusCinco_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Vw_VeranoStatus',array('idPersona'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectRow('Vw_VeranoStatus',array('statusAspirante'=>'5')),
            );
        }
        $this->response($response,200);
    }

    function instVI_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Vw_VeranoIngles',array('idInstitucion'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Vw_VeranoIngles'),
            );
        }
        $this->response($response,200);
    }

    function statusDos_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Vw_VeranoStatusDos',array('idPersona'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_VeranoStatusDos'),
            );
        }
        $this->response($response,200);
    }

    function statusDosR_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Vw_VeranoStatusDosR',array('idAspirante'=>$id)),
            );
        }else{
            $response = array(
               "status"=>"success",
               "message"=> '',
               "data"=>$this->DAO->selectEntity('Vw_VeranoStatusDosR'),
            );
        }
        $this->response($response,200);
    }
    function statusTres_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Vw_VeranoStatusTres',array('idPersona'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_VeranoStatusTres'),
            );
        }
        $this->response($response,200);
    }

    function statusCuatroC_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Vw_VeranoStatusCuatroC',array('idPersona'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_VeranoStatusCuatroC'),
            );
        }
        $this->response($response,200);
    }

    function statusCuatroU_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Vw_VeranoStatusCuatroU',array('idPersona'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_VeranoStatusCuatroU'),
            );
        }
        $this->response($response,200);
    }

    function docsByPerson_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selEntityMany('Vw_DocumentosVeranoIngles',array('idAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Vw_DocumentosVeranoIngles'),
            );
        }
        $this->response($response,200);
    }
    //new Today
    function fileinfoCarta_get(){
        $id = $this->get('id');
        $idAux = $this->get('aux');
        $type = $this->get('type');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=>strval($type),
                "data"=>$this->DAO->selectbyThreeEntity('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('fkInstitucion'=>$idAux),array('type'=>$type)),
                //$this->DAO->selectbyThreeEntity('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('fkInstitucion'=>$idAux),array('type'=>"OfertaCondicional")),

            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_DocsApiranteVIA'),
            );
        }
        $this->response($response,200);
    }


    function fileinfoFormatoSolicitud_get(){
        $id = $this->get('id');
        $idAux = $this->get('aux');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectbyThreeEntity('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('fkInstitucion'=>$idAux),array('type'=>'formatoSolicitud')),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_DocsApiranteVIA'),
            );
        }
        $this->response($response,200);
    }

    public function documentos_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {

                $carpeta = 'files/VeranoIngles/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"files/VeranoIngles/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>$this->post('name'),
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload($this->post('name')))
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
                        "urlDocumento"=>base_url('files/VeranoIngles/'.$id.'/'.$this->upload->data('file_name')),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "type"=>$this->post('tipo'),
                        "typeUser"=>$this->post('tipoUsuario'),
                        "statusDocumento"=>$this->post('status'),
                        "fkAspirante"=>$this->post('aspirante'),
                        "fkInstitucion"=>$this->post('institucion')
                    );

                    $response = $this->DAO->insertData('Tb_DocsApiranteVIA',$data);
                    if($response['status']=="success"){
                      $this->cambiarEstatus($id,$this->post('statusAspirante'));
                      $response = array(
                        "status"=>"success",
                        "message"=>"Fichero fue subido correctamente",
                        "data"=>'3'
                      );
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


            //a
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
    public function cartaCondicionalIncondicional_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {

                $carpeta = 'files/VeranoIngles/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"files/VeranoIngles/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>$this->post('name'),
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload($this->post('name')))
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
                        "urlDocumento"=>base_url('files/VeranoIngles/'.$id.'/'.$this->upload->data('file_name')),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "type"=>$this->post('tipo'),
                        "typeUser"=>$this->post('tipoUsuario'),
                        "statusDocumento"=>$this->post('status'),
                        "fkAspirante"=>$this->post('aspirante'),
                        "fkInstitucion"=>$this->post('institucion')
                    );

                    $response = $this->DAO->insertData('Tb_DocsApiranteVIA',$data);
                    if($response['status']=="success"){
                      $userData=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$this->post('aspirante')),true);
                      if($userData->statusAspirante == '3' and  $this->post('tipoUsuario') == 'Agente'){
                        $this->cambiarEstatus($this->post('aspirante'),$this->post('statusAspirante'));
                      }
                      $response = array(
                        "status"=>"success",
                        "message"=>"Fichero fue subido correctamente",
                        "data"=>'3'
                      );
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


            //a
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
    public function cartaUpdate_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {
              $Eixist = $this->DAO->selectbyTwoEntity('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('fkInstitucion'=>$this->post('institucion')),TRUE);
              if($Eixist){
                $EixistRecomendation = $this->DAO->selectbyTwoEntity('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('fkInstitucion'=>$this->post('institucion')),TRUE);
                if($EixistRecomendation){
                  $response = $this->DAO->deleteDataTwoClause('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->post('idDocument')));
                }
                $response = $this->DAO->deleteDataTwoClause('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('fkInstitucion'=>$this->post('institucion')));
                if($response['status']=="success"){

                  $carpeta = 'files/VeranoIngles/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/VeranoIngles/".$id,
                      "allowed_types"=>"pdf",
                      "file_name"=>$this->post('name'),
                      "overwrite"=>true
                  );

                  $this->load->library('upload',$config);
                  if ( ! $this->upload->do_upload($this->post('name')))
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
                          "urlDocumento"=>base_url('files/VeranoIngles/'.$id.'/'.$this->upload->data('file_name')),
                          "typeDocumento"=>$this->upload->data('file_type'),
                          "type"=>$this->post('tipo'),
                          "typeUser"=>$this->post('tipoUsuario'),
                          "statusDocumento"=>$this->post('status'),
                          "fkAspirante"=>$this->post('aspirante'),
                          "fkInstitucion"=>$this->post('institucion')
                      );

                      $response = $this->DAO->insertData('Tb_DocsApiranteVIA',$data);
                      if($response['status']=="success"){
                        // $this->cambiarEstatus($id,'2');
                        $response = array(
                          "status"=>"success",
                          "message"=>"Fichero fue subido correctamente",
                          "data"=>$data
                        );
                      }
                  }
                }else{
                  $response=array(
                      "status"=>"error",
                      "status_code"=>409,
                      "message"=>"The documento was not deleted correctly",
                      "data"=>null
                  );
                }
              }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Document does not exists",
                    "data"=>null
                );
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


            //a
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

    public function documetsUpdate_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {
              $Eixist = $this->DAO->selectbyThreeEntity('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('fkInstitucion'=>$this->post('institucion')),array('type'=>$this->post('tipo')),TRUE);
              if($Eixist){
                $EixistRecomendation = $this->DAO->selectbyTwoEntity('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->post('idDocument')),TRUE);
                if($EixistRecomendation){
                  $response = $this->DAO->deleteDataTwoClause('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->post('idDocument')));
                }
                $response = $this->DAO->deleteDataThreeClause('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('fkInstitucion'=>$this->post('institucion')),array('type'=>$this->post('tipo')));
                if($response['status']=="success"){

                  $carpeta = 'files/VeranoIngles/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/VeranoIngles/".$id,
                      "allowed_types"=>"pdf",
                      "file_name"=>$this->post('name'),
                      "overwrite"=>true
                  );

                  $this->load->library('upload',$config);
                  if ( ! $this->upload->do_upload($this->post('name')))
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
                          "urlDocumento"=>base_url('files/VeranoIngles/'.$id.'/'.$this->upload->data('file_name')),
                          "typeDocumento"=>$this->upload->data('file_type'),
                          "type"=>$this->post('tipo'),
                          "typeUser"=>$this->post('tipoUsuario'),
                          "statusDocumento"=>$this->post('status'),
                          "fkAspirante"=>$this->post('aspirante'),
                          "fkInstitucion"=>$this->post('institucion')
                      );

                      $response = $this->DAO->insertData('Tb_DocsApiranteVIA',$data);
                      if($response['status']=="success"){
                        // $this->cambiarEstatus($id,'2');
                        $response = array(
                          "status"=>"success",
                          "message"=>"Fichero fue subido correctamente",
                          "data"=>$data
                        );
                      }
                  }
                }else{
                  $response=array(
                      "status"=>"error",
                      "status_code"=>409,
                      "message"=>"The documento was not deleted correctly",
                      "data"=>null
                  );
                }
              }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Document does not exists",
                    "data"=>null
                );
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


            //a
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
    public function ticketUpdate_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {
              $Eixist = $this->DAO->selectbyThreeEntity('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('fkInstitucion'=>$this->post('institucion')),array('type'=>$this->post('tipo')),TRUE);
              if($Eixist){
                $EixistRecomendation = $this->DAO->selectbyTwoEntity('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->post('idDocument')),TRUE);
                if($EixistRecomendation){
                  $response = $this->DAO->deleteDataTwoClause('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->post('idDocument')));
                }
                $response = $this->DAO->deleteDataThreeClause('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('fkInstitucion'=>$this->post('institucion')),array('type'=>$this->post('tipo')));
                if($response['status']=="success"){

                  $carpeta = 'files/VeranoIngles/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/VeranoIngles/".$id,
                      "allowed_types"=>"pdf",
                      "file_name"=>$this->post('name'),
                      "overwrite"=>true
                  );

                  $this->load->library('upload',$config);
                  if ( ! $this->upload->do_upload($this->post('name')))
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
                          "urlDocumento"=>base_url('files/VeranoIngles/'.$id.'/'.$this->upload->data('file_name')),
                          "typeDocumento"=>$this->upload->data('file_type'),
                          "type"=>$this->post('tipo'),
                          "typeUser"=>$this->post('tipoUsuario'),
                          "statusDocumento"=>$this->post('status'),
                          "fkAspirante"=>$this->post('aspirante'),
                          "fkInstitucion"=>$this->post('institucion')
                      );

                      $response = $this->DAO->insertData('Tb_DocsApiranteVIA',$data);
                      if($response['status']=="success"){
                        // $this->cambiarEstatus($id,'2');
                        $response = array(
                          "status"=>"success",
                          "message"=>"Fichero fue subido correctamente",
                          "data"=>$data
                        );
                      }
                  }
                }else{
                  $response=array(
                      "status"=>"error",
                      "status_code"=>409,
                      "message"=>"The documento was not deleted correctly",
                      "data"=>null
                  );
                }
              }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Document does not exists",
                    "data"=>null
                );
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


            //a
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
    // public function cartaCondInconUpdate_post(){
    //     $id=$this->get('id');
    //     $idAux=$this->get('idAux');
    //     if ($id) {
    //         $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);
    //
    //         if ($userExist) {
    //           $Eixist = $this->DAO->selectbyTwoEntity('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('type'=>$idAux),TRUE);
    //           if($Eixist){
    //             $response = $this->DAO->deleteDataTwoClause('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('type'=>$idAux));
    //             if($response['status']=="success"){
    //
    //               $carpeta = 'files/VeranoIngles/'.$id;
    //               if (!file_exists($carpeta)) {
    //                   mkdir($carpeta, 0777, true);
    //               }
    //
    //               $config =array(
    //                   "upload_path"=>"files/VeranoIngles/".$id,
    //                   "allowed_types"=>"pdf",
    //                   "file_name"=>"Carta",
    //                   "overwrite"=>true
    //               );
    //
    //               $this->load->library('upload',$config);
    //               if ( ! $this->upload->do_upload('Carta'))
    //               {
    //               $response=array(
    //                   "status"=>"error",
    //                   "status_code"=>409,
    //                   "message"=>"Upload fails",
    //                   "validations"=>$this->upload->display_errors(),
    //                   "data"=>$this->post()
    //               );
    //               }
    //               else
    //               {
    //                   $data = array(
    //
    //                       "nombreDocumento"=>$this->upload->data('file_name'),
    //                       "extDocumento"=>$this->upload->data()['file_ext'],
    //                       "urlDocumento"=>base_url('files/Verano/'.$id.'/'.$this->upload->data('file_name')),
    //                       "typeDocumento"=>$this->upload->data('file_type'),
    //                       "type"=>$this->post('tipo'),
    //                       "typeUser"=>$this->post('tipoUsuario'),
    //                       "statusDocumento"=>"Revision",
    //                       "fkAspirante"=>$this->post('aspirante')
    //                   );
    //
    //                   $response = $this->DAO->insertData('Tb_DocsApiranteVIA',$data);
    //                   if($response['status']=="success"){
    //                     $this->cambiarEstatus($id,'2');
    //                     $response = array(
    //                       "status"=>"success",
    //                       "message"=>"Fichero fue subido correctamente",
    //                       "data"=>$data
    //                     );
    //                   }
    //               }
    //             }else{
    //               $response=array(
    //                   "status"=>"error",
    //                   "status_code"=>409,
    //                   "message"=>"The documento was not deleted correctly",
    //                   "data"=>null
    //               );
    //             }
    //           }else{
    //             $response=array(
    //                 "status"=>"error",
    //                 "status_code"=>409,
    //                 "message"=>"Document does not exists",
    //                 "data"=>null
    //             );
    //           }
    //         }else{
    //             $response=array(
    //                 "status"=>"error",
    //                 "status_code"=>409,
    //                 "message"=>"id does not exist",
    //                 "validations"=>array(
    //                     "id"=>"required (get)"
    //                 ),
    //                 "data"=>null
    //             );
    //         }
    //
    //
    //         //a
    //     }else{
    //         $response=array(
    //             "status"=>"error",
    //             "status_code"=>409,
    //             "message"=>"id was not sent",
    //             "validations"=>array(
    //                 "id"=>"required (get)"
    //             ),
    //             "data"=>null
    //         );
    //     }
    //     $this->response($response,200);
    // }
    function statusAspirante_post(){
        $data = $this->post();

        if(count($data) == 0 || count($data) > 3){
            $response = array(
                "status"=>"error",
                "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                "data"=>null,
                "validations"=>array(
                    "status"=>"El nombre es requerido",
                    "aspirante" => "La abreviacion es requerida"
                )
            );
        }else{
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('status','Estatus','required');
            $this->form_validation->set_rules('aspirante','Aspirante','required');


             if($this->form_validation->run()==FALSE){
                $response = array(
                    "status"=>"error",
                    "message"=>'check the validations',
                    "data"=>null,
                    "validations"=>$this->form_validation->error_array()
                );
             }else{

               $data=array(
                   "nombreEdad"=>$this->post('nombre'),
                   "abreviacionEdad"=>$this->post('abreviacion'),
                   "edadEdad"=>$this->post('edad')
               );
               $statusResponse =$this->DAO->updateData('Tb_DocVerIngVisa',$status,array('fkAspirante'=>$id));
                if($statusResponse['status']=="success"){
                }

             }
        }

        $this->response($response,200);
    }
    function estatusAspirante_put(){
        $data = $this->put();
        $id = $this->get('id');
        $existe = $this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),TRUE);
        if($existe){
            if(count($data) == 0 || count($data) > 3){
                $response = array(
                    "status"=>"error",
                    "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                    "data"=>null,
                    "validations"=>array(
                      "status"=>"El nombre es requerido"
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
                      "statusAspirante"=>$this->put('status')
                    );

                   $response = $this->DAO->updateData('Tb_Aspirantes',$data,array('idAspirante'=>$id));

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

    public function documentosStatus_put($id=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectbyTwoEntity('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('idDocumento'=>$this->put('idDocumento')),TRUE);
        if($Eixist){
          if(count($data) == 0 || count($data) > 8){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data),
                  "data"=>null,
                  "validations"=>array(
                    "status"=>"Required, The Status is required",
                  )
              );
          }else{
              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('status','Status','required');

             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'data received',
                      "data"=>$data,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{

                 if($this->put('status') == "Rechazado"){
                   $this->db->trans_begin();
                   $statuss = array(
                     "statusDocumento"=>$this->put('status')
                   );
                   $statusResponse =$this->DAO->updateByTwoData('Tb_DocsApiranteVIA',$statuss,array('fkAspirante'=>$id),array('idDocumento'=>$this->put('idDocumento')));

                    if($statusResponse['status']=="success"){

                        $Eixist = $this->DAO->selectbyTwoEntity('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocumento')),TRUE);
                        if($Eixist){
                          $recomendationResponseDelete = $this->DAO->deleteDataTwoClause('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocumento')));
                          if($recomendationResponseDelete['status']=="success"){
                            $recomendation = array(
                              "descripcion"=>$this->put('desc'),
                              "fkAspirante"=>$id,
                              "fkDocumento"=>$this->put('idDocumento')
                            );
                            $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendationDocsApiranteVIA',$recomendation,null,true);

                            if($recomendationResponse['status']=="success"){
                                $response = array(
                                   "status"=>"success",
                                   "message"=>"update successfullyu",
                                   "data"=>$statusResponse,
                               );

                            }else{
                                $response = array(
                                    "status"=>"error",
                                    "message"=>  $recomendationResponse['message'],
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
                                "message"=>  'error',
                                "data"=>$recomendationResponseDelete,
                            );
                          }
                          if($this->db->trans_status()==FALSE){
                              $this->db->trans_rollback();
                          }else{
                              $this->db->trans_commit();
                          }
                        }else{
                          $recomendation = array(
                            "descripcion"=>$this->put('desc'),
                            "fkAspirante"=>$id,
                            "fkDocumento"=>$this->put('idDocumento')
                          );
                          $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendationDocsApiranteVIA',$recomendation,null,true);

                          if($recomendationResponse['status']=="success"){
                              $response = array(
                                 "status"=>"success",
                                 "message"=>"update successfullyy",
                                 "data"=>$statusResponse,
                             );

                          }else{
                              $response = array(
                                  "status"=>"error",
                                  "message"=>  $recomendationResponse['message'],
                                  "data"=>null,
                              );
                          }
                          if($this->db->trans_status()==FALSE){
                              $this->db->trans_rollback();
                          }else{
                              $this->db->trans_commit();
                          }

                        }

                    }else{
                      $response = array(
                          "status"=>"error",
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }
                        // if($this->db->trans_status()==FALSE){
                        //     $this->db->trans_rollback();
                        // }else{
                        //     $this->db->trans_commit();
                        // }

                 }else if($this->put('status') == "Aceptado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>$this->put('status'),
                     "type"=>$this->put('tipo')
                   );
                   $statusResponse = $this->DAO->saveOrUpdateItemByTwoEntity('Tb_DocsApiranteVIA',$status,array('fkAspirante'=>$id),array('idDocumento'=>$this->put('idDocumento')),true);
                    if($statusResponse['status']=="success"){

                      $EixistRecomendation = $this->DAO->selectbyTwoEntity('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocumento')),TRUE);
                      if($EixistRecomendation){
                        $recomendationResponse = $this->DAO->deleteDataTwoClause('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocumento')));
                      }else{
                          $recomendationResponse['status']="success";
                      }
                      if($recomendationResponse['status']=="success"){
                        // $this->cambiarEstatus($id,$this->put('statusAspirante'));
                          $response = array(
                             "status"=>"success",
                             "message"=>"update successfully",
                             "data"=>$statusResponse,
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>  $recomendationResponse['message'],
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
                          "status"=>"errorr",
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }
                    if($this->db->trans_status()==FALSE){
                        $this->db->trans_rollback();
                    }else{
                        $this->db->trans_commit();
                    }
                 }else {
                   $response = array(
                       "status"=>"error",
                       "message"=>  'error',
                       "data"=>null,
                   );
                 }
                 if($this->db->trans_status()==FALSE){
                     $this->db->trans_rollback();
                 }else{
                     $this->db->trans_commit();
                 }
               // $data = array(
               //   "statusDocumento"=>$this->put('status'),
               // );
               // $response = $this->DAO->updateData('Tb_DocumentosVerano',$data,array('fkAspirante'=>$id));

               }
          }
        }else{
          $response = array(
            "status"=>"error",
            "message"=> "check the id",
            "data"=>$Eixist,
            );
        }
        $this->response($response,200);
    }
    // public function documentsStatus_put($id=null){
    //     $data = $this->put();
    //     $Eixist = $this->DAO->selectbyTwoEntity('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('fkInstitucion'=>$this->put('institucion')),TRUE);
    //     if($Eixist){
    //       if(count($data) == 0 || count($data) > 8){
    //           $response = array(
    //               "status"=>"error",
    //               "message"=> count($data),
    //               "data"=>null,
    //               "validations"=>array(
    //                 "status"=>"Required, The Status is required",
    //               )
    //           );
    //       }else{
    //           $this->form_validation->set_data($data);
    //           $this->form_validation->set_rules('status','Status','required');
    //
    //          if($this->form_validation->run()==FALSE){
    //               $response = array(
    //                   "status"=>"error",
    //                   "message"=> 'data received',
    //                   "data"=>$data,
    //                   "validations"=>$this->form_validation->error_array()
    //               );
    //            }else{
    //
    //              if($this->put('status') == "Rechazado"){
    //                $this->db->trans_begin();
    //                $statuss = array(
    //                  "statusDocumento"=>$this->put('status')
    //                );
    //                $statusResponse =$this->DAO->updateByTwoData('Tb_DocsApiranteVIA',$statuss,array('fkAspirante'=>$id),array('idDocumento'=>$this->put('idDocumento')));
    //
    //                 if($statusResponse['status']=="success"){
    //
    //                     $Eixist = $this->DAO->selectbyTwoEntity('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocumento')),TRUE);
    //                     if($Eixist){
    //                       $recomendationResponseDelete = $this->DAO->deleteDataTwoClause('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocumento')));
    //                       if($recomendationResponseDelete['status']=="success"){
    //                         $recomendation = array(
    //                           "descripcion"=>$this->put('desc'),
    //                           "fkAspirante"=>$id,
    //                           "fkDocumento"=>$this->put('idDocumento')
    //                         );
    //                         $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendationDocsApiranteVIA',$recomendation,null,true);
    //
    //                         if($recomendationResponse['status']=="success"){
    //                             $response = array(
    //                                "status"=>"success",
    //                                "message"=>"update successfullyu",
    //                                "data"=>$statusResponse,
    //                            );
    //
    //                         }else{
    //                             $response = array(
    //                                 "status"=>"error",
    //                                 "message"=>  $recomendationResponse['message'],
    //                                 "data"=>null,
    //                             );
    //                         }
    //                         if($this->db->trans_status()==FALSE){
    //                             $this->db->trans_rollback();
    //                         }else{
    //                             $this->db->trans_commit();
    //                         }
    //                       }else{
    //                         $response = array(
    //                             "status"=>"error",
    //                             "message"=>  'error',
    //                             "data"=>$recomendationResponseDelete,
    //                         );
    //                       }
    //                       if($this->db->trans_status()==FALSE){
    //                           $this->db->trans_rollback();
    //                       }else{
    //                           $this->db->trans_commit();
    //                       }
    //                     }else{
    //                       $recomendation = array(
    //                         "descripcion"=>$this->put('desc'),
    //                         "fkAspirante"=>$id,
    //                         "fkDocumento"=>$this->put('idDocumento')
    //                       );
    //                       $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendationDocsApiranteVIA',$recomendation,null,true);
    //
    //                       if($recomendationResponse['status']=="success"){
    //                           $response = array(
    //                              "status"=>"success",
    //                              "message"=>"update successfullyy",
    //                              "data"=>$statusResponse,
    //                          );
    //
    //                       }else{
    //                           $response = array(
    //                               "status"=>"error",
    //                               "message"=>  $recomendationResponse['message'],
    //                               "data"=>null,
    //                           );
    //                       }
    //                       if($this->db->trans_status()==FALSE){
    //                           $this->db->trans_rollback();
    //                       }else{
    //                           $this->db->trans_commit();
    //                       }
    //
    //                     }
    //
    //                 }else{
    //                   $response = array(
    //                       "status"=>"error",
    //                       "message"=>  'error',
    //                       "data"=>null,
    //                   );
    //                 }
    //                     // if($this->db->trans_status()==FALSE){
    //                     //     $this->db->trans_rollback();
    //                     // }else{
    //                     //     $this->db->trans_commit();
    //                     // }
    //
    //              }else if($this->put('status') == "Aceptado"){
    //                $this->db->trans_begin();
    //                $status = array(
    //                  "statusDocumento"=>$this->put('status'),
    //                  "type"=>$this->put('tipo')
    //                );
    //                $statusResponse = $this->DAO->saveOrUpdateItemByTwoEntity('Tb_DocsApiranteVIA',$status,array('fkAspirante'=>$id),array('idDocumento'=>$this->put('idDocumento')),true);
    //                 if($statusResponse['status']=="success"){
    //
    //                   $EixistRecomendation = $this->DAO->selectbyTwoEntity('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocumento')),TRUE);
    //                   if($EixistRecomendation){
    //                     $recomendationResponse = $this->DAO->deleteDataTwoClause('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocumento')));
    //                   }else{
    //                       $recomendationResponse['status']="success";
    //                   }
    //                   if($recomendationResponse['status']=="success"){
    //                     // $this->cambiarEstatus($id,$this->put('statusAspirante'));
    //                       $response = array(
    //                          "status"=>"success",
    //                          "message"=>"update successfully",
    //                          "data"=>$statusResponse,
    //                      );
    //
    //                   }else{
    //                       $response = array(
    //                           "status"=>"error",
    //                           "message"=>  $recomendationResponse['message'],
    //                           "data"=>null,
    //                       );
    //                   }
    //                   if($this->db->trans_status()==FALSE){
    //                       $this->db->trans_rollback();
    //                   }else{
    //                       $this->db->trans_commit();
    //                   }
    //                 }else{
    //                   $response = array(
    //                       "status"=>"errorr",
    //                       "message"=>  'error',
    //                       "data"=>null,
    //                   );
    //                 }
    //                 if($this->db->trans_status()==FALSE){
    //                     $this->db->trans_rollback();
    //                 }else{
    //                     $this->db->trans_commit();
    //                 }
    //              }else {
    //                $response = array(
    //                    "status"=>"error",
    //                    "message"=>  'error',
    //                    "data"=>null,
    //                );
    //              }
    //              if($this->db->trans_status()==FALSE){
    //                  $this->db->trans_rollback();
    //              }else{
    //                  $this->db->trans_commit();
    //              }
    //            // $data = array(
    //            //   "statusDocumento"=>$this->put('status'),
    //            // );
    //            // $response = $this->DAO->updateData('Tb_DocumentosVerano',$data,array('fkAspirante'=>$id));
    //
    //            }
    //       }
    //     }else{
    //       $response = array(
    //         "status"=>"error",
    //         "message"=> "check the id",
    //         "data"=>$Eixist,
    //         );
    //     }
    //     $this->response($response,200);
    // }
    public function veranoVisaStatus_put($id=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectbyTwoEntity('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('fkInstitucion'=>$this->put('institucion')),TRUE);
        if($Eixist){
          if(count($data) == 0 || count($data) > 8){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data),
                  "data"=>null,
                  "validations"=>array(
                    "status"=>"Required, The Status is required",
                  )
              );
          }else{
              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('status','Status','required');

             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'data received',
                      "data"=>$data,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{

                 if($this->put('status') == "Rechazado"){
                   $this->db->trans_begin();
                   $statuss = array(
                     "statusDocumento"=>$this->put('status')
                   );
                   $statusResponse =$this->DAO->updateByTwoData('Tb_DocsApiranteVIA',$statuss,array('fkAspirante'=>$id),array('idDocumento'=>$this->put('idDocumento')));

                    if($statusResponse['status']=="success"){

                        $Eixist = $this->DAO->selectbyTwoEntity('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocumento')),TRUE);
                        if($Eixist){
                          $recomendationResponseDelete = $this->DAO->deleteDataTwoClause('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocumento')));
                          if($recomendationResponseDelete['status']=="success"){
                            $recomendation = array(
                              "descripcion"=>$this->put('desc'),
                              "fkAspirante"=>$id,
                              "fkDocumento"=>$this->put('idDocumento')
                            );
                            $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendationDocsApiranteVIA',$recomendation,null,true);

                            if($recomendationResponse['status']=="success"){
                                $response = array(
                                   "status"=>"success",
                                   "message"=>"update successfullyu",
                                   "data"=>$statusResponse,
                               );

                            }else{
                                $response = array(
                                    "status"=>"error",
                                    "message"=>  $recomendationResponse['message'],
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
                                "message"=>  'error',
                                "data"=>$recomendationResponseDelete,
                            );
                          }
                          if($this->db->trans_status()==FALSE){
                              $this->db->trans_rollback();
                          }else{
                              $this->db->trans_commit();
                          }
                        }else{
                          $recomendation = array(
                            "descripcion"=>$this->put('desc'),
                            "fkAspirante"=>$id,
                            "fkDocumento"=>$this->put('idDocumento')
                          );
                          $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendationDocsApiranteVIA',$recomendation,null,true);

                          if($recomendationResponse['status']=="success"){
                              $response = array(
                                 "status"=>"success",
                                 "message"=>"update successfullyy",
                                 "data"=>$statusResponse,
                             );

                          }else{
                              $response = array(
                                  "status"=>"error",
                                  "message"=>  $recomendationResponse['message'],
                                  "data"=>null,
                              );
                          }
                          if($this->db->trans_status()==FALSE){
                              $this->db->trans_rollback();
                          }else{
                              $this->db->trans_commit();
                          }

                        }

                    }else{
                      $response = array(
                          "status"=>"error",
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }
                        // if($this->db->trans_status()==FALSE){
                        //     $this->db->trans_rollback();
                        // }else{
                        //     $this->db->trans_commit();
                        // }

                 }else if($this->put('status') == "Aceptado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>$this->put('status'),
                     "type"=>$this->put('tipo')
                   );
                   $statusResponse = $this->DAO->saveOrUpdateItemByTwoEntity('Tb_DocsApiranteVIA',$status,array('fkAspirante'=>$id),array('idDocumento'=>$this->put('idDocumento')),true);
                    if($statusResponse['status']=="success"){

                      $EixistRecomendation = $this->DAO->selectbyTwoEntity('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocumento')),TRUE);
                      if($EixistRecomendation){
                        $recomendationResponse = $this->DAO->deleteDataTwoClause('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocumento')));
                      }else{
                          $recomendationResponse['status']="success";
                      }
                      if($recomendationResponse['status']=="success"){
                        // $this->cambiarEstatus($id,$this->put('statusAspirante'));
                          $response = array(
                             "status"=>"success",
                             "message"=>"update successfully",
                             "data"=>$statusResponse,
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>  $recomendationResponse['message'],
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
                          "status"=>"errorr",
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }
                    if($this->db->trans_status()==FALSE){
                        $this->db->trans_rollback();
                    }else{
                        $this->db->trans_commit();
                    }
                 }else {
                   $response = array(
                       "status"=>"error",
                       "message"=>  'error',
                       "data"=>null,
                   );
                 }
                 if($this->db->trans_status()==FALSE){
                     $this->db->trans_rollback();
                 }else{
                     $this->db->trans_commit();
                 }
               // $data = array(
               //   "statusDocumento"=>$this->put('status'),
               // );
               // $response = $this->DAO->updateData('Tb_DocumentosVerano',$data,array('fkAspirante'=>$id));

               }
          }
        }else{
          $response = array(
            "status"=>"error",
            "message"=> "check the id",
            "data"=>$Eixist,
            );
        }
        $this->response($response,200);
    }

    public function veranoCartaStatusAspirante_put($id=null){
        $data = $this->put();
        if($this->put('oferta') == 'OfertaCondicional'){
          $Eixist = $this->DAO->selectbyThreeEntity('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('fkInstitucion'=>$this->put('institucion')),array('type'=>'OfertaCondicional'),TRUE);
        }else if($this->put('oferta') == 'OfertaIncondicional'){
          $Eixist = $this->DAO->selectbyThreeEntity('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('fkInstitucion'=>$this->put('institucion')),array('type'=>'OfertaIncondicional'),TRUE);
        }else{
            $Eixist = False;
        }

        if($Eixist){
          if(count($data) == 0 || count($data) > 8){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data),
                  "data"=>null,
                  "validations"=>array(
                    "status"=>"Required, The Status is required",
                  )
              );
          }else{
              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('status','Status','required');

             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'data received',
                      "data"=>$data,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{

                 if($this->put('status') == "Rechazado"){
                   $this->db->trans_begin();
                   $statuss = array(
                     "statusDocumento"=>$this->put('status')
                   );

                   $statusResponse = $this->DAO->saveOrUpdateItemByThreeEntity('Tb_DocsApiranteVIA',$status,array('fkAspirante'=>$id),array('fkInstitucion'=>$this->put('institucion')),array('type'=>$this->put('oferta')),true);
                    if($statusResponse['status']=="success"){
                      // $response = array(
                      //     "status"=>"success",
                      //     "message"=> "mal",
                      //     "data"=>$statusResponse
                      // );
                        $Eixist = $this->DAO->selectbyTwoEntity('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocument')),TRUE);
                        if($Eixist){
                          $recomendationResponseDelete = $this->DAO->deleteDataTwoClause('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocument')));
                          if($recomendationResponseDelete['status']=="success"){
                            $recomendation = array(
                              "descripcion"=>$this->put('desc'),
                              "fkAspirante"=>$id,
                              "fkDocumento"=>$this->put('idDocument')
                            );
                            $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendationDocsApiranteVIA',$recomendation,null,true);

                            if($recomendationResponse['status']=="success"){
                                $response = array(
                                   "status"=>"success",
                                   "message"=>"update successfullyu",
                                   "data"=>$statusResponse,
                               );

                            }else{
                                $response = array(
                                    "status"=>"error",
                                    "message"=>  $recomendationResponse['message'],
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
                                "message"=>  'error',
                                "data"=>$recomendationResponseDelete,
                            );
                          }
                          if($this->db->trans_status()==FALSE){
                              $this->db->trans_rollback();
                          }else{
                              $this->db->trans_commit();
                          }
                        }else{
                          $recomendation = array(
                            "descripcion"=>$this->put('desc'),
                            "fkAspirante"=>$id,
                            "fkDocumento"=>$this->put('idDocument')
                          );
                          $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendationDocsApiranteVIA',$recomendation,null,true);

                          if($recomendationResponse['status']=="success"){
                              $response = array(
                                 "status"=>"success",
                                 "message"=>"update successfullyy",
                                 "data"=>$statusResponse,
                             );

                          }else{
                              $response = array(
                                  "status"=>"error",
                                  "message"=>  $recomendationResponse['message'],
                                  "data"=>null,
                              );
                          }
                          if($this->db->trans_status()==FALSE){
                              $this->db->trans_rollback();
                          }else{
                              $this->db->trans_commit();
                          }

                        }

                    }else{
                      $response = array(
                          "status"=>"error",
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }
                        // if($this->db->trans_status()==FALSE){
                        //     $this->db->trans_rollback();
                        // }else{
                        //     $this->db->trans_commit();
                        // }

                 }else if($this->put('status') == "Aceptado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>$this->put('status'),
                     "type"=>$this->put('oferta')
                   );
                   $statusResponse = $this->DAO->saveOrUpdateItemByThreeEntity('Tb_DocsApiranteVIA',$status,array('fkAspirante'=>$id),array('fkInstitucion'=>$this->put('institucion')),array('type'=>$this->put('oferta')),true);
                    if($statusResponse['status']=="success"){

                      $EixistRecomendation = $this->DAO->selectbyTwoEntity('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocument')),TRUE);
                      if($EixistRecomendation){
                        $recomendationResponse = $this->DAO->deleteDataTwoClause('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocument')));
                      }else{
                          $recomendationResponse['status']="success";
                      }
                      if($recomendationResponse['status']=="success"){
                        // $this->cambiarEstatus($id,$this->put('statusAspirante'));
                        $userData=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);
                        if($userData->statusAspirante == '3'){
                          $this->cambiarEstatus($id,$this->post('statusAspirante'));
                        }
                          $response = array(
                             "status"=>"success",
                             "message"=>"update successfully",
                             "data"=>$statusResponse,
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>  $recomendationResponse['message'],
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
                          "status"=>"errorr",
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }
                    if($this->db->trans_status()==FALSE){
                        $this->db->trans_rollback();
                    }else{
                        $this->db->trans_commit();
                    }
                 }else {
                   $response = array(
                       "status"=>"error",
                       "message"=>  'error',
                       "data"=>null,
                   );
                 }
                 if($this->db->trans_status()==FALSE){
                     $this->db->trans_rollback();
                 }else{
                     $this->db->trans_commit();
                 }
               // $data = array(
               //   "statusDocumento"=>$this->put('status'),
               // );
               // $response = $this->DAO->updateData('Tb_DocumentosVerano',$data,array('fkAspirante'=>$id));

               }
          }
        }else{
          $response = array(
            "status"=>"error",
            "message"=> "check the id",
            "data"=>$this->put('oferta'),
            );
        }
        $this->response($response,200);
    }
    public function documentosStatusCarta_put($id=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectbyTwoEntity('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('idDocumento'=>$this->put('idDocument')),TRUE);
        if($Eixist){
          if(count($data) == 0 || count($data) > 8){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data),
                  "data"=>null,
                  "validations"=>array(
                    "status"=>"Required, The Status is required",
                  )
              );
          }else{
              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('status','Status','required');

             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'data received',
                      "data"=>$data,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{

                 if($this->put('status') == "Rechazado"){
                   $this->db->trans_begin();
                   $statuss = array(
                     "statusDocumento"=>$this->put('status')
                   );
                   $statusResponse =$this->DAO->updateByTwoData('Tb_DocsApiranteVIA',$statuss,array('fkAspirante'=>$id),array('idDocumento'=>$this->put('idDocumento')));

                    if($statusResponse['status']=="success"){

                        $Eixist = $this->DAO->selectbyTwoEntity('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocumento')),TRUE);
                        if($Eixist){
                          $recomendationResponseDelete = $this->DAO->deleteDataTwoClause('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocumento')));
                          if($recomendationResponseDelete['status']=="success"){
                            $recomendation = array(
                              "descripcion"=>$this->put('desc'),
                              "fkAspirante"=>$id,
                              "fkDocumento"=>$this->put('idDocumento')
                            );
                            $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendationDocsApiranteVIA',$recomendation,null,true);

                            if($recomendationResponse['status']=="success"){
                                $response = array(
                                   "status"=>"success",
                                   "message"=>"update successfullyu",
                                   "data"=>$statusResponse,
                               );

                            }else{
                                $response = array(
                                    "status"=>"error",
                                    "message"=>  $recomendationResponse['message'],
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
                                "message"=>  'error',
                                "data"=>$recomendationResponseDelete,
                            );
                          }
                          if($this->db->trans_status()==FALSE){
                              $this->db->trans_rollback();
                          }else{
                              $this->db->trans_commit();
                          }
                        }else{
                          $recomendation = array(
                            "descripcion"=>$this->put('desc'),
                            "fkAspirante"=>$id,
                            "fkDocumento"=>$this->put('idDocumento')
                          );
                          $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendationDocsApiranteVIA',$recomendation,null,true);

                          if($recomendationResponse['status']=="success"){
                              $response = array(
                                 "status"=>"success",
                                 "message"=>"update successfullyy",
                                 "data"=>$statusResponse,
                             );

                          }else{
                              $response = array(
                                  "status"=>"error",
                                  "message"=>  $recomendationResponse['message'],
                                  "data"=>null,
                              );
                          }
                          if($this->db->trans_status()==FALSE){
                              $this->db->trans_rollback();
                          }else{
                              $this->db->trans_commit();
                          }

                        }

                    }else{
                      $response = array(
                          "status"=>"error",
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }
                        // if($this->db->trans_status()==FALSE){
                        //     $this->db->trans_rollback();
                        // }else{
                        //     $this->db->trans_commit();
                        // }

                 }else if($this->put('status') == "Aceptado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>$this->put('status'),
                     "type"=>$this->put('oferta')
                   );
                   $statusResponse =$this->DAO->updateByTwoData('Tb_DocsApiranteVIA',$status,array('fkAspirante'=>$id),array('idDocumento'=>$this->put('idDocument')));


                    if($statusResponse['status']=="success"){

                      $EixistRecomendation = $this->DAO->selectbyTwoEntity('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocument')),TRUE);
                      if($EixistRecomendation){
                        $recomendationResponse = $this->DAO->deleteDataTwoClause('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocument')));
                      }else{
                          $recomendationResponse['status']="success";
                      }
                      if($recomendationResponse['status']=="success"){
                        // $this->cambiarEstatus($id,$this->put('statusAspirante'));
                        $userData=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);
                        if($userData->statusAspirante == '3'){
                          $this->cambiarEstatus($id,$this->put('statusAspirante'));
                        }
                          $response = array(
                             "status"=>"success",
                             "message"=>"update successfully",
                             "data"=>$this->put('statusAspirante'),
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>  $recomendationResponse['message'],
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
                          "status"=>"errorr",
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }
                    if($this->db->trans_status()==FALSE){
                        $this->db->trans_rollback();
                    }else{
                        $this->db->trans_commit();
                    }
                 }else {
                   $response = array(
                       "status"=>"error",
                       "message"=>  'error',
                       "data"=>null,
                   );
                 }
                 if($this->db->trans_status()==FALSE){
                     $this->db->trans_rollback();
                 }else{
                     $this->db->trans_commit();
                 }
               // $data = array(
               //   "statusDocumento"=>$this->put('status'),
               // );
               // $response = $this->DAO->updateData('Tb_DocumentosVerano',$data,array('fkAspirante'=>$id));

               }
          }
        }else{
          $response = array(
            "status"=>"error",
            "message"=> "check the id",
            "data"=>$Eixist,
            );
        }
        $this->response($response,200);
    }
    public function veranoCartaStatus_put($id=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectbyTwoEntity('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('fkInstitucion'=>$this->put('institucion')),TRUE);
        if($Eixist){
          if(count($data) == 0 || count($data) > 8){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data),
                  "data"=>null,
                  "validations"=>array(
                    "status"=>"Required, The Status is required",
                  )
              );
          }else{
              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('status','Status','required');

             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'data received',
                      "data"=>$data,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{

                 if($this->put('status') == "Rechazado"){
                   $this->db->trans_begin();
                   $statuss = array(
                     "statusDocumento"=>$this->put('status')
                   );
                   $statusResponse =$this->DAO->updateByTwoData('Tb_DocsApiranteVIA',$statuss,array('fkAspirante'=>$id),array('fkInstitucion'=>$this->put('institucion')));

                    if($statusResponse['status']=="success"){
                      // $response = array(
                      //     "status"=>"success",
                      //     "message"=> "mal",
                      //     "data"=>$statusResponse
                      // );
                        $Eixist = $this->DAO->selectbyTwoEntity('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocument')),TRUE);
                        if($Eixist){
                          $recomendationResponseDelete = $this->DAO->deleteDataTwoClause('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocument')));
                          if($recomendationResponseDelete['status']=="success"){
                            $recomendation = array(
                              "descripcion"=>$this->put('desc'),
                              "fkAspirante"=>$id,
                              "fkDocumento"=>$this->put('idDocument')
                            );
                            $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendationDocsApiranteVIA',$recomendation,null,true);

                            if($recomendationResponse['status']=="success"){
                                $response = array(
                                   "status"=>"success",
                                   "message"=>"update successfullyu",
                                   "data"=>$statusResponse,
                               );

                            }else{
                                $response = array(
                                    "status"=>"error",
                                    "message"=>  $recomendationResponse['message'],
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
                                "message"=>  'error',
                                "data"=>$recomendationResponseDelete,
                            );
                          }
                          if($this->db->trans_status()==FALSE){
                              $this->db->trans_rollback();
                          }else{
                              $this->db->trans_commit();
                          }
                        }else{
                          $recomendation = array(
                            "descripcion"=>$this->put('desc'),
                            "fkAspirante"=>$id,
                            "fkDocumento"=>$this->put('idDocument')
                          );
                          $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendationDocsApiranteVIA',$recomendation,null,true);

                          if($recomendationResponse['status']=="success"){
                              $response = array(
                                 "status"=>"success",
                                 "message"=>"update successfullyy",
                                 "data"=>$statusResponse,
                             );

                          }else{
                              $response = array(
                                  "status"=>"error",
                                  "message"=>  $recomendationResponse['message'],
                                  "data"=>null,
                              );
                          }
                          if($this->db->trans_status()==FALSE){
                              $this->db->trans_rollback();
                          }else{
                              $this->db->trans_commit();
                          }

                        }

                    }else{
                      $response = array(
                          "status"=>"error",
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }
                        // if($this->db->trans_status()==FALSE){
                        //     $this->db->trans_rollback();
                        // }else{
                        //     $this->db->trans_commit();
                        // }

                 }else if($this->put('status') == "Aceptado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>$this->put('status'),
                     "type"=>$this->put('oferta')
                   );
                   $statusResponse = $this->DAO->saveOrUpdateItemByTwoEntity('Tb_DocsApiranteVIA',$status,array('fkAspirante'=>$id),array('fkInstitucion'=>$this->put('institucion')),true);
                    if($statusResponse['status']=="success"){

                      $EixistRecomendation = $this->DAO->selectbyTwoEntity('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocument')),TRUE);
                      if($EixistRecomendation){
                        $recomendationResponse = $this->DAO->deleteDataTwoClause('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocument')));
                      }else{
                          $recomendationResponse['status']="success";
                      }
                      if($recomendationResponse['status']=="success"){
                        // $this->cambiarEstatus($id,$this->put('statusAspirante'));
                          $response = array(
                             "status"=>"success",
                             "message"=>"update successfully",
                             "data"=>$statusResponse,
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>  $recomendationResponse['message'],
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
                          "status"=>"errorr",
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }
                    if($this->db->trans_status()==FALSE){
                        $this->db->trans_rollback();
                    }else{
                        $this->db->trans_commit();
                    }
                 }else {
                   $response = array(
                       "status"=>"error",
                       "message"=>  'error',
                       "data"=>null,
                   );
                 }
                 if($this->db->trans_status()==FALSE){
                     $this->db->trans_rollback();
                 }else{
                     $this->db->trans_commit();
                 }
               // $data = array(
               //   "statusDocumento"=>$this->put('status'),
               // );
               // $response = $this->DAO->updateData('Tb_DocumentosVerano',$data,array('fkAspirante'=>$id));

               }
          }
        }else{
          $response = array(
            "status"=>"error",
            "message"=> "check the id",
            "data"=>$Eixist,
            );
        }
        $this->response($response,200);
    }
    public function veranoTicketStatus_put($id=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectbyTwoEntity('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('fkInstitucion'=>$this->put('institucion')),TRUE);
        if($Eixist){
          if(count($data) == 0 || count($data) > 8){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data),
                  "data"=>null,
                  "validations"=>array(
                    "status"=>"Required, The Status is required",
                  )
              );
          }else{
              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('status','Status','required');

             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'data received',
                      "data"=>$data,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{

                 if($this->put('status') == "Rechazado"){
                   $this->db->trans_begin();
                   $statuss = array(
                     "statusDocumento"=>$this->put('status')
                   );
                   $statusResponse =$this->DAO->updateByTwoData('Tb_DocsApiranteVIA',$statuss,array('fkAspirante'=>$id),array('fkInstitucion'=>$this->put('institucion')));

                    if($statusResponse['status']=="success"){
                      // $response = array(
                      //     "status"=>"success",
                      //     "message"=> "mal",
                      //     "data"=>$statusResponse
                      // );
                        $Eixist = $this->DAO->selectbyTwoEntity('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocument')),TRUE);
                        if($Eixist){
                          $recomendationResponseDelete = $this->DAO->deleteDataTwoClause('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocument')));
                          if($recomendationResponseDelete['status']=="success"){
                            $recomendation = array(
                              "descripcion"=>$this->put('desc'),
                              "fkAspirante"=>$id,
                              "fkDocumento"=>$this->put('idDocument')
                            );
                            $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendationDocsApiranteVIA',$recomendation,null,true);

                            if($recomendationResponse['status']=="success"){
                                $response = array(
                                   "status"=>"success",
                                   "message"=>"update successfullyu",
                                   "data"=>$statusResponse,
                               );

                            }else{
                                $response = array(
                                    "status"=>"error",
                                    "message"=>  $recomendationResponse['message'],
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
                                "message"=>  'error',
                                "data"=>$recomendationResponseDelete,
                            );
                          }
                          if($this->db->trans_status()==FALSE){
                              $this->db->trans_rollback();
                          }else{
                              $this->db->trans_commit();
                          }
                        }else{
                          $recomendation = array(
                            "descripcion"=>$this->put('desc'),
                            "fkAspirante"=>$id,
                            "fkDocumento"=>$this->put('idDocument')
                          );
                          $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendationDocsApiranteVIA',$recomendation,null,true);

                          if($recomendationResponse['status']=="success"){
                              $response = array(
                                 "status"=>"success",
                                 "message"=>"update successfullyy",
                                 "data"=>$statusResponse,
                             );

                          }else{
                              $response = array(
                                  "status"=>"error",
                                  "message"=>  $recomendationResponse['message'],
                                  "data"=>null,
                              );
                          }
                          if($this->db->trans_status()==FALSE){
                              $this->db->trans_rollback();
                          }else{
                              $this->db->trans_commit();
                          }

                        }

                    }else{
                      $response = array(
                          "status"=>"error",
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }
                        // if($this->db->trans_status()==FALSE){
                        //     $this->db->trans_rollback();
                        // }else{
                        //     $this->db->trans_commit();
                        // }

                 }else if($this->put('status') == "Aceptado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>$this->put('status'),
                     "type"=>$this->put('tipo')
                   );
                   $statusResponse = $this->DAO->saveOrUpdateItemByTwoEntity('Tb_DocsApiranteVIA',$status,array('fkAspirante'=>$id),array('idDocumento'=>$this->put('idDocumento')),true);
                    if($statusResponse['status']=="success"){

                      $EixistRecomendation = $this->DAO->selectbyTwoEntity('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocumento')),TRUE);
                      if($EixistRecomendation){
                        $recomendationResponse = $this->DAO->deleteDataTwoClause('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$this->put('idDocumento')));
                      }else{
                          $recomendationResponse['status']="success";
                      }
                      if($recomendationResponse['status']=="success"){
                        // $this->cambiarEstatus($id,$this->put('statusAspirante'));
                          $response = array(
                             "status"=>"success",
                             "message"=>"update successfully",
                             "data"=>$statusResponse,
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>  $recomendationResponse['message'],
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
                          "status"=>"errorr",
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }
                    if($this->db->trans_status()==FALSE){
                        $this->db->trans_rollback();
                    }else{
                        $this->db->trans_commit();
                    }
                 }else {
                   $response = array(
                       "status"=>"error",
                       "message"=>  'error',
                       "data"=>null,
                   );
                 }
                 if($this->db->trans_status()==FALSE){
                     $this->db->trans_rollback();
                 }else{
                     $this->db->trans_commit();
                 }
               // $data = array(
               //   "statusDocumento"=>$this->put('status'),
               // );
               // $response = $this->DAO->updateData('Tb_DocumentosVerano',$data,array('fkAspirante'=>$id));

               }
          }
        }else{
          $response = array(
            "status"=>"error",
            "message"=> "check the id",
            "data"=>$Eixist,
            );
        }
        $this->response($response,200);
    }
    function statusDocuments_put($id=null,$idAux=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectbyTwoEntity('Tb_DocsApiranteVIA',array('fkAspirante'=>$id),array('type'=>$idAux),TRUE);
        if($Eixist){
          if(count($data) == 0 || count($data) > 2){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data),
                  "data"=>null,
                  "validations"=>array(
                    "status"=>"Required, The Status is required",
                  )
              );
          }else{
              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('status','Status','required');

             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'data received',
                      "data"=>$data,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{

                 if($this->put('status') == "Rechazado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>$this->put('status')
                   );
                   $statusResponse =$this->DAO->updateByTwoData('Tb_DocsApiranteVIA',$status,array('fkAspirante'=>$id),array('type'=>$id));
                    if($statusResponse['status']=="success"){

                      $recomendation = array(
                        "descripcion"=>$this->put('desc'),
                        "fkAspirante"=>$id,
                        "type"=>$idAux
                      );
                      $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendationDocsApiranteVIA',$recomendation,null,true);

                      if($recomendationResponse['status']=="success"){
                          $response = array(
                             "status"=>"success",
                             "message"=>"update successfully",
                             "data"=>null,
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>  $recomendationResponse['message'],
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
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }

                 }else if($this->put('status') == "Aceptado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>"Aceptado"
                   );
                   $statusResponse = $this->DAO->saveOrUpdateItem('Tb_DocsApiranteVIA',$status,array('fkAspirante'=>$id),true);
                    if($statusResponse['status']=="success"){

                      $recomendationResponse = $this->DAO->deleteDataTwoClause('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('type'=>$idAux));

                      if($recomendationResponse['status']=="success"){
                          $response = array(
                             "status"=>"success",
                             "message"=>"update successfully",
                             "data"=>$statusResponse,
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>  $recomendationResponse['message'],
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
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }
                 }else {
                   $response = array(
                       "status"=>"error",
                       "message"=>  'error',
                       "data"=>null,
                   );
                 }

               }
          }
        }else{
          $response = array(
            "status"=>"error",
            "message"=> "check the id",
            "data"=>$Eixist,
            );
        }
        $this->response($response,200);
    }
    //New Jul 8
    public function pago_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {

                $carpeta = 'files/VeranoIngles/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"files/VeranoIngles/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>"Pago",
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('Pago'))
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
                        "urlDocumento"=>base_url('files/VeranoIngles/'.$id.'/'.$this->upload->data('file_name')),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"Pago",
                        "statusDocumento"=>"Revision",
                        "fkAspirante"=>$this->post('aspirante')
                    );

                    $response = $this->DAO->insertData('Tb_DocumentosVerano',$data);
                    if($response['status']=="success"){
                      // $this->cambiarEstatus($id,'2');
                      $response = array(
                        "status"=>"success",
                        "message"=>"Fichero fue subido correctamente",
                        "data"=>$data
                      );
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


            //a
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
    public function pagoUpdate_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {
              $Eixist = $this->DAO->selectbyTwoEntity('Tb_DocumentosVerano',array('fkAspirante'=>$id),array('tipo'=>'Pago'),TRUE);
              if($Eixist){
                $response = $this->DAO->deleteDataTwoClause('Tb_DocumentosVerano',array('fkAspirante'=>$id),array('tipo'=>'Pago'));
                if($response['status']=="success"){

                  $carpeta = 'files/VeranoIngles/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/VeranoIngles/".$id,
                      "allowed_types"=>"pdf",
                      "file_name"=>"Pago",
                      "overwrite"=>true
                  );

                  $this->load->library('upload',$config);
                  if ( ! $this->upload->do_upload('Pago'))
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
                          "urlDocumento"=>base_url('files/VeranoIngles/'.$id.'/'.$this->upload->data('file_name')),
                          "typeDocumento"=>$this->upload->data('file_type'),
                          "tipo"=>"Pago",
                          "statusDocumento"=>"Revision",
                          "fkAspirante"=>$this->post('aspirante')
                      );

                      $response = $this->DAO->insertData('Tb_DocumentosVerano',$data);
                      if($response['status']=="success"){
                      //  $this->cambiarEstatus($id,'2');
                        $response = array(
                          "status"=>"success",
                          "message"=>"Fichero fue subido correctamente",
                          "data"=>$data
                        );
                      }
                  }
                }else{
                  $response=array(
                      "status"=>"error",
                      "status_code"=>409,
                      "message"=>"The documento was not deleted correctly",
                      "data"=>null
                  );
                }
              }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Document does not exists",
                    "data"=>null
                );
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


            //a
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
    public function pagoFile_put($id=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectbyTwoEntity('Tb_DocumentosVerano',array('fkAspirante'=>$id),array('tipo'=>'Pago'),TRUE);
        if($Eixist){
          if(count($data) == 0 || count($data) > 2){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data),
                  "data"=>null,
                  "validations"=>array(
                    "status"=>"Required, The Status is required",
                  )
              );
          }else{
              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('status','Status','required');

             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'data received',
                      "data"=>$data,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{

                 if($this->put('status') == "Rechazado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>$this->put('status')
                   );
                   $statusResponse =$this->DAO->updateByTwoData('Tb_DocumentosVerano',$status,array('fkAspirante'=>$id),array('tipo'=>'Pago'));
                    if($statusResponse['status']=="success"){

                      $recomendation = array(
                        "descripcion"=>$this->put('desc'),
                        "fkAspirante"=>$id,
                        "tipo"=>'Pago'
                      );
                      $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendacionAspirante',$recomendation,null,true);

                      if($recomendationResponse['status']=="success"){
                          $response = array(
                             "status"=>"success",
                             "message"=>"update successfully",
                             "data"=>null,
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>  $recomendationResponse['message'],
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
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }

                 }else if($this->put('status') == "Aceptado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>"Aceptado"
                   );
                   $statusResponse = $this->DAO->saveOrUpdateItemByTwoEntity('Tb_DocumentosVerano',$status,array('fkAspirante'=>$id),array('tipo'=>'Pago'),true);
                    if($statusResponse['status']=="success"){
                      $this->cambiarEstatus($id,'4U');
                      $recomendationResponse = $this->DAO->deleteDataTwoClause('Tb_RecomendacionAspirante',array('fkAspirante'=>$id),array('tipo'=>'Pago'));

                      if($recomendationResponse['status']=="success"){
                          $response = array(
                             "status"=>"success",
                             "message"=>"update successfully",
                             "data"=>$statusResponse,
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>  $recomendationResponse['message'],
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
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }
                 }else {
                   $response = array(
                       "status"=>"error",
                       "message"=>  'error',
                       "data"=>null,
                   );
                 }
               // $data = array(
               //   "statusDocumento"=>$this->put('status'),
               // );
               // $response = $this->DAO->updateData('Tb_DocumentosVerano',$data,array('fkAspirante'=>$id));
               }
          }
        }else{
          $response = array(
            "status"=>"error",
            "message"=> "check the id",
            "data"=>$Eixist,
            );
        }
        $this->response($response,200);
    }

    //new today

    public function visaIngles_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {

                $carpeta = 'files/VeranoIngles/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"files/VeranoIngles/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>"Visa",
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('Visa'))
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
                        "urlDocumento"=>base_url('files/VeranoIngles/'.$id.'/'.$this->upload->data('file_name')),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"Visa",
                        "statusDocumento"=>"Revision",
                        "fkAspirante"=>$this->post('aspirante')
                    );

                    $response = $this->DAO->insertData('Tb_DocumentosVerano',$data);
                    if($response['status']=="success"){
                      $response = array(
                        "status"=>"success",
                        "message"=>"Fichero fue subido correctamente",
                        "data"=>$data
                      );
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


            //a
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
    public function visaInglesUpdate_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {
              $Eixist = $this->DAO->selectbyTwoEntity('Tb_DocumentosVerano',array('fkAspirante'=>$id),array('tipo'=>'Visa'),TRUE);
              if($Eixist){
                $response = $this->DAO->deleteDataTwoClause('Tb_DocumentosVerano',array('fkAspirante'=>$id),array('tipo'=>'Visa'));
                if($response['status']=="success"){

                  $carpeta = 'files/VeranoIngles/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/VeranoIngles/".$id,
                      "allowed_types"=>"pdf",
                      "file_name"=>"Visa",
                      "overwrite"=>true
                  );

                  $this->load->library('upload',$config);
                  if ( ! $this->upload->do_upload('Visa'))
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
                          "urlDocumento"=>base_url('files/VeranoIngles/'.$id.'/'.$this->upload->data('file_name')),
                          "typeDocumento"=>$this->upload->data('file_type'),
                          "tipo"=>"Visa",
                          "statusDocumento"=>"Revision",
                          "fkAspirante"=>$this->post('aspirante')
                      );

                      $response = $this->DAO->insertData('Tb_DocumentosVerano',$data);
                      if($response['status']=="success"){
                      //  $this->cambiarEstatus($id,'2');
                        $response = array(
                          "status"=>"success",
                          "message"=>"Fichero fue subido correctamente",
                          "data"=>$data
                        );
                      }
                  }
                }else{
                  $response=array(
                      "status"=>"error",
                      "status_code"=>409,
                      "message"=>"The documento was not deleted correctly",
                      "data"=>null
                  );
                }
              }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Document does not exists",
                    "data"=>null
                );
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


            //a
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
    public function visaStatusIngles_put($id=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectbyTwoEntity('Tb_DocumentosVerano',array('fkAspirante'=>$id),array('tipo'=>'Visa'),TRUE);
        if($Eixist){
          if(count($data) == 0 || count($data) > 2){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data),
                  "data"=>null,
                  "validations"=>array(
                    "status"=>"Required, The Status is required",
                  )
              );
          }else{
              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('status','Status','required');

             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'data received',
                      "data"=>$data,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{

                 if($this->put('status') == "Rechazado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>$this->put('status')
                   );
                   $statusResponse =$this->DAO->updateByTwoData('Tb_DocumentosVerano',$status,array('fkAspirante'=>$id),array('tipo'=>'Visa'));
                    if($statusResponse['status']=="success"){

                      $recomendation = array(
                        "descripcion"=>$this->put('desc'),
                        "fkAspirante"=>$id,
                        "tipo"=>'Visa'
                      );
                      $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendacionAspirante',$recomendation,null,true);

                      if($recomendationResponse['status']=="success"){
                          $response = array(
                             "status"=>"success",
                             "message"=>"update successfully",
                             "data"=>null,
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>  $recomendationResponse['message'],
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
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }

                 }else if($this->put('status') == "Aceptado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>"Aceptado"
                   );
                   $statusResponse = $this->DAO->saveOrUpdateItemByTwoEntity('Tb_DocumentosVerano',$status,array('fkAspirante'=>$id),array('tipo'=>'Visa'),true);
                    if($statusResponse['status']=="success"){
                      $this->cambiarEstatus($id,'5');
                      $recomendationResponse = $this->DAO->deleteDataTwoClause('Tb_RecomendacionAspirante',array('fkAspirante'=>$id),array('tipo'=>'Visa'));

                      if($recomendationResponse['status']=="success"){
                          $response = array(
                             "status"=>"success",
                             "message"=>"update successfully",
                             "data"=>$statusResponse,
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>  $recomendationResponse['message'],
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
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }
                 }else {
                   $response = array(
                       "status"=>"error",
                       "message"=>  'error',
                       "data"=>null,
                   );
                 }
               // $data = array(
               //   "statusDocumento"=>$this->put('status'),
               // );
               // $response = $this->DAO->updateData('Tb_DocumentosVerano',$data,array('fkAspirante'=>$id));
               }
          }
        }else{
          $response = array(
            "status"=>"error",
            "message"=> "check the id",
            "data"=>$Eixist,
            );
        }
        $this->response($response,200);
    }


    public function pasaporte_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {

                $carpeta = 'files/Verano/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"files/Verano/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>"Pasaporte",
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
                        "urlDocumento"=>base_url('files/Verano/'.$id.'/'.$this->upload->data('file_name')),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"Pasaporte",
                        "statusDocumento"=>"Revision",
                        "fkAspirante"=>$this->post('aspirante')
                    );

                    $response = $this->DAO->insertData('Tb_DocumentosVerano',$data);
                    if($response['status']=="success"){
                      $this->cambiarEstatus($id,'2');
                      $response = array(
                        "status"=>"success",
                        "message"=>"Fichero fue subido correctamente",
                        "data"=>$data
                      );
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


            //a
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

    public function pasaporteUpdate_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {
              $Eixist = $this->DAO->selectEntity('Tb_DocumentosVerano',array('fkAspirante'=>$id),TRUE);
              if($Eixist){
                $response = $this->DAO->deleteData('Tb_DocumentosVerano',array('fkAspirante'=>$id));
                if($response['status']=="success"){

                  $carpeta = 'files/Verano/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/Verano/".$id,
                      "allowed_types"=>"pdf",
                      "file_name"=>"Pasaporte",
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
                          "urlDocumento"=>base_url('files/Verano/'.$id.'/'.$this->upload->data('file_name')),
                          "typeDocumento"=>$this->upload->data('file_type'),
                          "tipo"=>"Pasaporte",
                          "statusDocumento"=>"Revision",
                          "fkAspirante"=>$this->post('aspirante')
                      );

                      $response = $this->DAO->insertData('Tb_DocumentosVerano',$data);
                      if($response['status']=="success"){
                        $this->cambiarEstatus($id,'2');
                        $response = array(
                          "status"=>"success",
                          "message"=>"Fichero fue subido correctamente",
                          "data"=>$data
                        );
                      }
                  }
                }else{
                  $response=array(
                      "status"=>"error",
                      "status_code"=>409,
                      "message"=>"The documento was not deleted correctly",
                      "data"=>null
                  );
                }
              }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Document does not exists",
                    "data"=>null
                );
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


            //a
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


    public function visa_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {

                $carpeta = 'files/VeranoIngles/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"files/VeranoIngles/".$id,
                    "allowed_types"=>"pdf",
                    "file_name"=>"Visa",
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('Visa'))
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
                        "urlDocumento"=>base_url('files/VeranoIngles/'.$id.'/'.$this->upload->data('file_name')),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"Visa",
                        "statusDocumento"=>"Revision",
                        "fkAspirante"=>$this->post('aspirante')
                    );

                    $response = $this->DAO->insertData('Tb_DocVerIngVisa',$data);
                    if($response['status']=="success"){
                       // $this->cambiarEstatus($id,'2');
                      $response = array(
                        "status"=>"success",
                        "message"=>"Fichero fue subido correctamente",
                        "data"=>$data
                      );
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


            //a
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

    function recomendtionVisa_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Tb_RecomenVisaAspirante',array('fkAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_RecomenVisaAspirante'),
            );
        }
        $this->response($response,200);
    }

    // function recomendtionTicket_get(){
    //     $id = $this->get('id');
    //     if($id){
    //          $response = array(
    //             "status"=>"success",
    //             "message"=> '',
    //             "data"=>$this->DAO->select('Tb_Recomendation',array('fkDocumento'=>$id)),
    //         );
    //     }else{
    //         $response = array(
    //             "status"=>"success",
    //             "message"=> '',
    //             "data"=>$this->DAO->selectEntity('Tb_Recomendation'),
    //         );
    //     }
    //     $this->response($response,200);
    // }

    function recomendtionCarta_get(){
        $id = $this->get('id');
        $aux = $this->get('aux');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectbyTwoEntity('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$aux)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_RecomendationDocsApiranteVIA'),
            );
        }
        $this->response($response,200);
    }

    function recomendtionDocuments_get(){
        $id = $this->get('id');
        $aux = $this->get('aux');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectbyTwoEntity('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$aux)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_RecomendationDocsApiranteVIA'),
            );
        }
        $this->response($response,200);
    }



    function recomendationDocs_get(){
        $id = $this->get('id');
        $aux = $this->get('aux');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectbyTwoEntity('Tb_RecomendacionAspirante',array('fkAspirante'=>$id),array('tipo'=>$aux)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_RecomendacionAspirante'),
            );
        }
        $this->response($response,200);
    }

    function documentosVerano_get(){
        $id = $this->get('id');
        $aux = $this->get('aux');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectbyTwoEntity('Tb_DocumentosVerano',array('fkAspirante'=>$id),array('tipo'=>$aux)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_DocumentosVerano'),
            );
        }
        $this->response($response,200);
    }



    function recomendtionTicket_get(){
        $id = $this->get('id');
        $aux = $this->get('aux');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectbyTwoEntity('Tb_RecomendationDocsApiranteVIA',array('fkAspirante'=>$id),array('fkDocumento'=>$aux)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_RecomendationDocsApiranteVIA'),
            );
        }
        $this->response($response,200);
    }

    function visaFile_put($id=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectEntity('Tb_DocVerIngVisa',array('fkAspirante'=>$id),TRUE);
        if($Eixist){
          if(count($data) == 0 || count($data) > 2){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data),
                  "data"=>null,
                  "validations"=>array(
                    "status"=>"Required, The Status is required",
                  )
              );
          }else{
              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('status','Status','required');

             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'data received',
                      "data"=>$data,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{

                 if($this->put('status') == "Rechazado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>$this->put('status')
                   );
                   $statusResponse =$this->DAO->updateData('Tb_DocVerIngVisa',$status,array('fkAspirante'=>$id));
                    if($statusResponse['status']=="success"){

                      $recomendation = array(
                        "descripcion"=>$this->put('desc'),
                        "fkAspirante"=>$id
                      );
                      $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomenVisaAspirante',$recomendation,null,true);

                      if($recomendationResponse['status']=="success"){
                          $response = array(
                             "status"=>"success",
                             "message"=>"update successfully",
                             "data"=>null,
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>  $recomendationResponse['message'],
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
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }

                 }else if($this->put('status') == "Aceptado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>"Aceptado"
                   );
                   $statusResponse = $this->DAO->saveOrUpdateItem('Tb_DocVerIngVisa',$status,array('fkAspirante'=>$id),true);
                    if($statusResponse['status']=="success"){

                      $recomendationResponse = $this->DAO->deleteData('Tb_RecomenVisaAspirante',array('fkAspirante'=>$id));

                      if($recomendationResponse['status']=="success"){
                        $this->cambiarEstatus($id,'5');
                          $response = array(
                             "status"=>"success",
                             "message"=>"update successfully",
                             "data"=>$statusResponse,
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>  $recomendationResponse['message'],
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
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }
                 }else {
                   $response = array(
                       "status"=>"error",
                       "message"=>  'error',
                       "data"=>null,
                   );
                 }
               // $data = array(
               //   "statusDocumento"=>$this->put('status'),
               // );
               // $response = $this->DAO->updateData('Tb_DocumentosVerano',$data,array('fkAspirante'=>$id));
               }
          }
        }else{
          $response = array(
            "status"=>"error",
            "message"=> "check the id",
            "data"=>$Eixist,
            );
        }
        $this->response($response,200);
    }

    public function visaUpdate_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {
              $Eixist = $this->DAO->selectEntity('Tb_DocVerIngVisa',array('fkAspirante'=>$id),TRUE);
              if($Eixist){
                $response = $this->DAO->deleteData('Tb_DocVerIngVisa',array('fkAspirante'=>$id));
                if($response['status']=="success"){

                  $carpeta = 'files/VeranoIngles/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/VeranoIngles/".$id,
                      "allowed_types"=>"pdf",
                      "file_name"=>"Visa",
                      "overwrite"=>true
                  );

                  $this->load->library('upload',$config);
                  if ( ! $this->upload->do_upload('Visa'))
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
                          "urlDocumento"=>base_url('files/VeranoIngles/Visa/'.$id.'/'.$this->upload->data('file_name')),
                          "typeDocumento"=>$this->upload->data('file_type'),
                          "tipo"=>"Visa",
                          "statusDocumento"=>"Revision",
                          "fkAspirante"=>$this->post('aspirante')
                      );

                      $response = $this->DAO->insertData('Tb_DocVerIngVisa',$data);
                      if($response['status']=="success"){
                        // $this->cambiarEstatus($id,'2');
                        $response = array(
                          "status"=>"success",
                          "message"=>"Fichero fue subido correctamente",
                          "data"=>$data
                        );
                      }
                  }
                }else{
                  $response=array(
                      "status"=>"error",
                      "status_code"=>409,
                      "message"=>"The documento was not deleted correctly",
                      "data"=>null
                  );
                }
              }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Document does not exists",
                    "data"=>null
                );
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


            //a
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


    public function formatoSolicitud_post(){
        $id=$this->get('id');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {

                $carpeta = 'files/VeranoIngles/FormDeSolicitud/'.$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $config =array(
                    "upload_path"=>"files/VeranoIngles/FormDeSolicitud/".$id,
                    "allowed_types"=>"pdf",
                    "overwrite"=>true
                );

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('formatoDeSolicitud'))
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
                        "urlDocumento"=>base_url('files/VeranoIngles/FormDeSolicitud/'.$id.'/'.$this->upload->data('file_name')),
                        "typeDocumento"=>$this->upload->data('file_type'),
                        "tipo"=>"formato de solicitud",
                        "statusDocumento"=>"Revision",
                        "fkAspirante"=>$id
                    );

                    $response = $this->DAO->insertData('Tb_formdesolicitudVeranoIngAspirante',$data);
                    if($response['status']=="success"){
                         $this->cambiarEstatus($id,'2');
                      $response=array(
                          "status"=>"success",
                          "status_code"=>409,
                          "message"=>"Success the document was upload correctly",
                          "data"=>null
                      );
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


            //a
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

    //new TODAY
    function infoAspirante_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->select('Tb_Aspirantes',array('idAspirante'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_Aspirantes'),
            );
        }
        $this->response($response,200);
    }
    //

    public function formatoSolicitudUpdate_post(){
        $id=$this->get('id');
        $idDocForm = $this->get('idDocForm');
        if ($id) {
            $userExist=$this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

            if ($userExist) {
              $Eixist = $this->DAO->selectbyTwoEntity('Tb_formdesolicitudVeranoIngAspirante',array('fkAspirante'=>$id),array('idDocumento'=>$idDocForm),TRUE);
              if($Eixist){
                // $response = $this->DAO->deleteDataTwoClause('Tb_formdesolicitudVeranoIngAspirante',array('fkAspirante'=>$id),array('idDocumento'=>$idDocForm));


                  $carpeta = 'files/VeranoIngles/FormDeSolicitud/'.$id;
                  if (!file_exists($carpeta)) {
                      mkdir($carpeta, 0777, true);
                  }

                  $config =array(
                      "upload_path"=>"files/VeranoIngles/FormDeSolicitud/".$id,
                      "allowed_types"=>"pdf",
                      "overwrite"=>true
                  );

                  $this->load->library('upload',$config);
                  if ( ! $this->upload->do_upload('formatoDeSolicitud'))
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
                          "urlDocumento"=>base_url('files/VeranoIngles/FormDeSolicitud/'.$id.'/'.$this->upload->data('file_name')),
                          "typeDocumento"=>$this->upload->data('file_type'),
                          "tipo"=>"formato de solicitud ",
                          "statusDocumento"=>"Revision",
                          "fkAspirante"=>$id
                      );

                      $response = $this->DAO->updateByTwoData('Tb_formdesolicitudVeranoIngAspirante',$data,array('fkAspirante'=>$id),array('idDocumento'=>$idDocForm));
                      if($response['status']=="success"){
                        $response=array(
                            "status"=>"success",
                            "status_code"=>409,
                            "message"=>"Success the document was upload correctlyy",
                            "data"=>$response
                        );
                      }
                  }

              }else{
                $response=array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"Document does not exists",
                    "data"=>null
                );
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




    function inglesFormFile_put(){
        $id = $this->get('id');
        $idDocForm = $this->get('idDocForm');

        $data = $this->put();
        $Eixist = $this->DAO->selectbyTwoEntity('Tb_formdesolicitudVeranoIngAspirante',array('fkAspirante'=>$id),array('idDocumento'=>$idDocForm),TRUE);

        if($Eixist){
          if(count($data) == 0 || count($data) > 2){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data),
                  "data"=>null,
                  "validations"=>array(
                    "status"=>"Required, The Status is required",
                  )
              );
          }else{
              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('status','Status','required');

             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'data received',
                      "data"=>$data,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{

                 if($this->put('status') == "Rechazado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>$this->put('status')
                   );
                   $statusResponse =$this->DAO->updateByTwoData('Tb_formdesolicitudVeranoIngAspirante',$status,array('fkAspirante'=>$id),array('idDocumento'=>$idDocForm));
                    if($statusResponse['status']=="success"){
                      $EixistRecomendation = $this->DAO->selectbyTwoEntity('Tb_RecomendacionAspiranteForm',array('fkAspirante'=>$id),array('fkDocumento'=>$idDocForm),TRUE);
                      if($EixistRecomendation){
                        $recomendationResponse = $this->DAO->deleteDataTwoClause('Tb_RecomendacionAspiranteForm',array('fkAspirante'=>$id),array('fkDocumento'=>$idDocForm));

                        if($recomendationResponse['status']=="success"){
                          $recomendation = array(
                            "descripcion"=>$this->put('desc'),
                            "fkAspirante"=>$id,
                            "fkDocumento"=>$idDocForm
                          );
                          $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendacionAspiranteForm',$recomendation,null,true);

                          if($recomendationResponse['status']=="success"){
                              $response = array(
                                 "status"=>"success",
                                 "message"=>"update successfullyy",
                                 "data"=>$recomendationResponse,
                             );
                             if($this->db->trans_status()==FALSE){
                                 $this->db->trans_rollback();
                             }else{
                                 $this->db->trans_commit();
                             }

                          }else{
                              $response = array(
                                  "status"=>"error",
                                  "message"=>  $recomendationResponse['message'],
                                  "data"=>null,
                              );
                          }
                        }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>  $recomendationResponse['message'],
                              "data"=>null,
                          );
                        }
                        }else{
                          $recomendation = array(
                            "descripcion"=>$this->put('desc'),
                            "fkAspirante"=>$id,
                            "fkDocumento"=>$idDocForm
                          );
                          $recomendationResponse = $this->DAO->saveOrUpdateItem('Tb_RecomendacionAspiranteForm',$recomendation,null,true);

                          if($recomendationResponse['status']=="success"){
                              $response = array(
                                 "status"=>"success",
                                 "message"=>"update successfullyy",
                                 "data"=>$recomendationResponse,
                             );
                             if($this->db->trans_status()==FALSE){
                                 $this->db->trans_rollback();
                             }else{
                                 $this->db->trans_commit();
                             }

                          }else{
                              $response = array(
                                  "status"=>"error",
                                  "message"=>  $recomendationResponse['message'],
                                  "data"=>null,
                              );
                          }
                        }
                      }else{
                        $response = array(
                            "status"=>"error",
                            "message"=>  'error',
                            "data"=>null,
                        );
                      }
                     //  $response = array(
                     //     "status"=>"success",
                     //     "message"=>"update successfully",
                     //     "data"=>null,
                     // );




                 }else if($this->put('status') == "Aceptado"){
                   $this->db->trans_begin();
                   $status = array(
                     "statusDocumento"=>"Aceptado"
                   );
                   $statusResponse = $this->DAO->updateByTwoData('Tb_formdesolicitudVeranoIngAspirante',$status,array('fkAspirante'=>$id),array('idDocumento'=>$idDocForm));
                    if($statusResponse['status']=="success"){

                      $recomendationResponse = $this->DAO->deleteData('Tb_RecomendacionAspirante',array('fkAspirante'=>$id));

                      if($recomendationResponse['status']=="success"){
                          $response = array(
                             "status"=>"success",
                             "message"=>"update successfully thanks",
                             "data"=>$statusResponse,
                         );

                      }else{
                          $response = array(
                              "status"=>"error",
                              "message"=>  $recomendationResponse['message'],
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
                          "message"=>  'error',
                          "data"=>null,
                      );
                    }
                 }else {
                   $response = array(
                       "status"=>"error",
                       "message"=>  'error',
                       "data"=>null,
                   );
                 }
               // $data = array(
               //   "statusDocumento"=>$this->put('status'),
               // );
               // $response = $this->DAO->updateData('Tb_formdesolicitudVeranoIngAspirante',$data,array('fkAspirante'=>$id));
               }
          }
        }else{
          $response = array(
            "status"=>"error",
            "message"=> "check the id",
            "data"=>$id,
            );
        }
        $this->response($response,200);
    }
    function statusAspirante_put($id=null){
        $data = $this->put();
        $Eixist = $this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),TRUE);
        if($Eixist){
          if(count($data) == 0 || count($data) > 4){
              $response = array(
                  "status"=>"error",
                  "message"=> count($data) == 0 ? 'No data received' : 'Too many data received',
                  "data"=>null,
                  "validations"=>array(
                    "status"=>"The name is required"
                  )
              );
          }else{
              $this->form_validation->set_data($data);
              $this->form_validation->set_rules('status','Status','required');

             if($this->form_validation->run()==FALSE){
                  $response = array(
                      "status"=>"error",
                      "message"=> 'Too many data received',
                      "data"=>null,
                      "validations"=>$this->form_validation->error_array()
                  );
               }else{
               $data = array(
                 "statusAspirante"=>$this->put('status')
               );
               $responseaspirante = $this->DAO->updateData('Tb_Aspirantes',$data,array('idAspirante'=>$id));
               if($responseaspirante['status']=="success"){
                 $response = array(
                    "status"=>"success",
                    "message"=>"update successfully thanks",
                    "data"=>$responseaspirante,
                );
               }else{
                 $response = array(
                     "status"=>"error",
                     "message"=>  $responseaspirante['message'],
                     "data"=>$this->put('status'),
                 );
               }
               }
          }
        }else{
          $response = array(
            "status"=>"error",
            "message"=> "check the id id",
            "data"=>null,
            );
        }
        $this->response($response,200);
    }

    function recomendtion_get(){
        $id = $this->get('id');
        $idDocForm = $this->get('idDocForm');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectbyTwoEntity('Tb_RecomendacionAspiranteForm',array('fkAspirante'=>$id),array('fkDocumento'=>$idDocForm),TRUE),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->selectEntity('Tb_RecomendacionAspiranteForm'),
            );
        }
        $this->response($response,200);
    }


    // public function sendEmailStatus1_post()
    // {
    //     $id = $this->get('id');
    //     $existe = $this->DAO->selectEntity('Vw_Aspirante',array('aspirante'=>$id),TRUE);
    //     if($existe){
    //         $camposVacios = array();
    //         if($existe->programaDeInteres == null){
    //             array_push($camposVacios,'Programa de interes','Seleccion de escuelas de interes');
    //         }else{
    //             if($existe->programaDeInteres == "Universidad"){
    //                 $universidad = $this->DAO->selectEntity('Vw_AspiranteUniversidad',array('fkAspirante'=>$id),TRUE);
    //                 if(!$universidad){
    //                     array_push($camposVacios,'Tipo de estudio de interes','Facultad','Seleccion de universidades de interes');
    //                 }else{
    //                     if($universidad->anioMesIngreso == null){
    //                         array_push($camposVacios,'Seleccion de universidades de interes','Año de ingreso','Mes de ingreso');
    //                     }else{
    //                         array_push($camposVacios,'Falta subir algun tipo de documento requerido para iniciar tu proceso');
    //                     }
    //                 }
    //             }else if($existe->programaDeInteres == "Preparatoria"){
    //                 $preparatoria = $this->DAO->selectEntity('Vw_AspirantePreparatoria',array('fkAspirante'=>$id),TRUE);
    //                 if(!$preparatoria){
    //                     array_push($camposVacios,'Tipo de estudio de interes','Tipo de alojamiento','Seleccion de preparatorias de interes');
    //                 }else{
    //                     if($preparatoria->anioMesIngreso == null){
    //                         array_push($camposVacios,'Seleccion de preparatorias de interes','Año de ingreso','Mes de ingreso');
    //
    //                     }else{
    //                         array_push($camposVacios,'Falta subir algun tipo de documento requerido para iniciar tu proceso');
    //
    //                     }
    //                 }
    //             }else{
    //                 array_push($camposVacios,'Falta completar la informacion necesaria');
    //
    //             }
    //         }
    //         if($camposVacios){
    //             $headers = array(
    //                 'Authorization: Bearer ',
    //                 'Content-Type: application/json'
    //             );
    //
    //             $data = array(
    //                 "personalizations" => array(
    //                     array(
    //                         "to" => array(
    //                             array(
    //                                 "email" => $existe->email,
    //                                 "name" => $existe->names
    //                             )
    //                         ),
    //                         "dynamic_template_data"=> array (
    //
    //                             "user" => $existe->names,
    //                             "falta" => $camposVacios
    //
    //                         )
    //
    //                     )
    //                 ),
    //                 "from" => array(
    //                     "email" => "study@anglopageone.com",
    //                     "name"=>"Anglo Latino Education Partnership"
    //                 ),
    //                 "reply_to"=> array(
    //                     "email"=>"study@anglolatinoedu.com",
    //                     "name"=>"Anglo Latino Education Partnership"
    //                 ),
    //                 "template_id"=> "d-35a19dfcb66f4705bec4ff2d07d30fe1"
    //             );
    //
    //             $ch = curl_init();
    //             curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
    //             curl_setopt($ch, CURLOPT_POST, 1);
    //             curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    //             curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //             curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    //             curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //             $response = curl_exec($ch);
    //             curl_close($ch);
    //         }else{
    //             $response = array(
    //                 "status"=>"error",
    //                 "message"=> "Error inesperado",
    //                 "data"=>null,
    //             );
    //         }
    //     }else{
    //         $response = array(
    //             "status"=>"error",
    //             "message"=> "Revisa el id",
    //             "data"=>null,
    //         );
    //     }
    //     $this->response($response,200);
    // }
    public function sendEmailStatus1_post()
    {
        $id = $this->get('id');
        $existe = $this->DAO->selectEntity('Vw_Aspirante',array('aspirante'=>$id),TRUE);
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
                "message"=> "Revisa el id",
                "data"=>null,
            );
        }
        $this->response($response,200);
    }





    public function cambiarEstatus($id,$Status)
    {
        $item = $this->DAO->selectEntity('Tb_Aspirantes',array('idAspirante'=>$id),true);

        if($item->statusAspirante!='3' or $item->statusAspirante!='2'){
            $data=array(
                "statusAspirante"=>$Status
            );
            $this->DAO->updateData('Tb_Aspirantes',$data,array('idAspirante'=>$id));
        }
    }


    /**exta validations**/



}
