<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UserDAO extends CI_Model{
	function __construct(){
        parent::__construct();
    }

    public function registrar($data)
    {
        $this->db->trans_begin();

        if($data['image']['urlImagen']){
            $this->db->insert('Tb_Imagenes',$data['image']);
            $idFoto=$this->db->insert_id();
            $data['Person']['photoPersona']=$idFoto;
        }

        $this->db->insert('Tb_Personas',$data['Person']);
        $idPersona = $this->db->insert_id();

        $data['Usuario']['fkPersona']=$idPersona;
        $this->db->insert('Tb_Usuarios',$data['Usuario']);

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            $response=array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>$this->db->error()['message'],
                "data"=>$data
            );
          }
          else{
              $this->db->trans_commit();
              $response=array(
                "status"=>"success",
                "status_code"=>201,
                "message"=>"Usuario Creado Exitosamente",
                "data"=>null,
                "password"=>null
              );
          }

          return $response;
    }

		public function registrarAgente($data)
		{
				$this->db->trans_begin();

				if($data['image']['urlImagen']){
						$this->db->insert('Tb_Imagenes',$data['image']);
						$idFoto=$this->db->insert_id();
						$data['Person']['photoPersona']=$idFoto;
				}

				$this->db->insert('Tb_Personas',$data['Person']);
				$idPersona = $this->db->insert_id();

				$data['Usuario']['fkPersona']=$idPersona;
				$this->db->insert('Tb_Usuarios',$data['Usuario']);

				$data['Agente']['fkPersona']=$idPersona;
				$this->db->insert('Tb_Agentes',$data['Agente']);

				if ($this->db->trans_status() == FALSE) {
						$this->db->trans_rollback();
						$response=array(
								"status"=>"error",
								"status_code"=>409,
								"message"=>$this->db->error()['message'],
								"data"=>$data
						);
					}
					else{
							$this->db->trans_commit();
							$response=array(
								"status"=>"success",
								"status_code"=>201,
								"message"=>"Usuario Creado Exitosamente",
								"data"=>null,
								"password"=>null
							);
					}

					return $response;
		}

		public function editarAdmin($data,$id)
    {
        $this->db->trans_begin();

        if($data['image']['urlImagen']){
					 $this->db->insert('Tb_Imagenes',$data['image']);
            $idFoto=$this->db->insert_id();
            $data['Person']['photoPersona']=$idFoto;
        }
				$this->db->where('idPersona', $id);
        $this->db->update('Tb_Personas',$data['Person']);
        $idPersona = $this->db->insert_id();
				if ($this->db->trans_status() == FALSE) {

						$response=array(
								"status"=>"error person",
								"status_code"=>409,
								"message"=>$this->db->error()['message'],
								"data"=>$data
						);
							$this->db->trans_rollback();
					}
					else{

						$this->db->where('fkPersona', $id);
		        $this->db->update('Tb_Usuarios',$data['Usuario']);
						if($this->db->trans_status() == FALSE){
							$response=array(
									"status"=>"error user",
									"status_code"=>409,
									"message"=>$this->db->error()['message'],
									"data"=>$data
							);
								$this->db->trans_rollback();

						}else{
							$this->db->trans_commit();
							$response=array(
								"status"=>"success",
								"status_code"=>201,
								"message"=>"Usuario Creado Exitosamente",
								"data"=>null,
								"password"=>null
							);
						}

					}




          return $response;
    }

}
