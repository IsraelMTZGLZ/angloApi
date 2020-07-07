<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DAO extends CI_Model{
	function __construct(){
        parent::__construct();
    }

     public function selectEntity($entityName,$whereClause = null,$uniqueResult =false)
    {
        if ($whereClause) {
            $this->db->where($whereClause);
        }
        $result = $this->db->get($entityName);
        if ($this->db->error()['message']!='') {
            return null;
        }
        else{
            if ($uniqueResult) {
                return $result->row();
            }
            else{
                return $result->result();
            }
        }
    }

		public function select($entityName,$whereClause = null,$uniqueResult =false)
	 {

					 $this->db->where($whereClause);

			 $result = $this->db->get($entityName);
			 if ($this->db->error()['message']!='') {
					 return null;
			 }
			 else{
							 return $result->row();
			 }
	 }

	 public function selectbyTwoEntity($entityName,$whereClause = null,$whereClauseTwo = null,$uniqueResult =false){
 		 if ($whereClause) {
 				 $this->db->where($whereClause);
 				 $this->db->where($whereClauseTwo);
 		 }
 		 $result = $this->db->get($entityName);
 		 if ($this->db->error()['message']!='') {
 				 return null;
 		 }
 		 else{
 				 if ($uniqueResult) {
 						 return $result->row();
 				 }
 				 else{
 						 return $result->result();
 				 }
 		 }
  }
	 function selectEntityVerano($entity, $whereClauseOne = null,$whereClauseTwo = null,$whereClauseThree = null){
		 if($whereClauseOne and $whereClauseTwo and $whereClauseThree){
		 $this->db->where($whereClauseOne);
		 $this->db->where($whereClauseTwo);
		 $this->db->where($whereClauseThree);
		 	$query = $this->db->get($entity);
		 }else{
			 $query = $this->db->get($entity);
		 }
		 return  $query->result();
	 }

	 function selectEntityIngles($entity,$whereClauseTwo = null,$whereClauseThree = null){
		 if( $whereClauseTwo and $whereClauseThree){
		 $this->db->where($whereClauseTwo);
		 $this->db->where($whereClauseThree);
			$query = $this->db->get($entity);
		 }else{
			 $query = $this->db->get($entity);
		 }
		 return  $query->result();
	 }


    public function insertData($entityName,$data,$returnData=false)
    {
        $this->db->insert($entityName,$data);
        $id = ($returnData) ? $this->db->insert_id() : null;
        if ($this->db->error()['message']!='') {
            $message = "";
            switch ($this->db->error()['code']) {
                case '1062':
                    $message="Item already inserted, please insert another one";
                    break;

                default:
                    $message = $this->db->error()['message'];
                    break;
            }
            $response = array(
              "status"=>"error",
              "status_code"=>201,
              "message"=>$message
            );

        }
        else{
            $response = array(
              "status"=>"success",
              "status_code"=>201,
              "message"=>"Artículo creado con éxito",
              "data"=>$id
            );
        }

        return $response;
    }

		public function updateByTwoData($entityName,$data,$whereClause,$whereClauseTwo)
		{
				$this->db->where($whereClause);
				$this->db->where($whereClauseTwo);
				$this->db->update($entityName,$data);
				if ($this->db->error()['message']!='') {
						$message = "";
						switch ($this->db->error()['code']) {
								case '1062':
										$message="Item already inserted, please insert another one";
										break;
								case '1054':
										$message="One column does not exist en databese";
										break;
								default:
										$message = $this->db->error()['message'];
										break;
						}
						$response = array(
							"status"=>"error",
							"status_code"=>201,
							"message"=>$message
						);
				}
				else{
						$response = array(
							"status"=>"success",
							"status_code"=>201,
							"message"=>"Artículo actualizado correctamente"
						);
				}

				return $response;
		}


    public function updateData($entityName,$data,$whereClause)
    {
        $this->db->where($whereClause);
        $this->db->update($entityName,$data);
        if ($this->db->error()['message']!='') {
            $message = "";
            switch ($this->db->error()['code']) {
                case '1062':
                    $message="Item already inserted, please insert another one";
                    break;
                case '1054':
                    $message="One column does not exist en databese";
                    break;
                default:
                    $message = $this->db->error()['message'];
                    break;
            }
            $response = array(
              "status"=>"error",
              "status_code"=>201,
              "message"=>$message
            );
        }
        else{
            $response = array(
              "status"=>"success",
              "status_code"=>201,
              "message"=>"Artículo actualizado correctamente"
            );
        }

        return $response;
    }

    function deleteData($entityName,$whereClause){
        $this->db->where($whereClause);
        $this->db->delete($entityName);
         if($this->db->error()['message']!=""){
            $reponseDB = array(
                "status"=>"error",
                "status_code"=>409,
                "message"=>"Db error: ".$this->db->error()['message'],
                "validations"=>null,
                "data"=>null
            );
        }else{
            $reponseDB = array(
                "status"=>"success",
                "status_code"=>200,
                "message"=>"Artículo eliminado correctamente",
                "validations"=>null,
                "data"=>null
            );
        }
        return $reponseDB;

    }

		function selectEnt($entity, $whereClause = NULL){
			if($whereClause){
				$this->db->where($whereClause);

			}
			$query = $this->db->get($entity);


			return $whereClause ? $query->row() : $query->result();
		}



		function saveOrUpdateItem($entityName,$data,$whereClause = NULL,$generateKey =  FALSE){

		    if($whereClause){
		        $this->db->where($whereClause);
		        $this->db->update($entityName,$data);
		    }else{
		        $this->db->insert($entityName,$data);
		    }
		    if($this->db->error()['message']!=''){
		        $responseDB = array(
		            "status"=>"error",
		            "status_code"=>409,
		            "message"=>$this->db->error()['message']
		        );
		    }else{
		        $responseDB = array(
		            "status"=>"success",
		            "status_code"=>$whereClause ? 200 : 201,
		            "message"=>"Item created Successfully",
		            "key"=>$generateKey ? $this->db->insert_id() : null
		        );
		    }
		    return $responseDB;

		}


		function saveOrUpdateBatchItems($entityName,$data,$whereClause = NULL){
		    if($whereClause){

		    }else{
		        $this->db->insert_batch($entityName,$data);
		    }
		    if($this->db->error()['message']!=''){
		        $responseDB = array(
		            "status"=>"error",
		            "status_code"=>409,
		            "message"=>$this->db->error()['message']
		        );
		    }else{
		        $responseDB = array(
		            "status"=>"success",
		            "status_code"=>201,
		            "message"=>"Item created Successfully"
		        );
		    }
		    return $responseDB;

		}

		function selEntityMany($entity, $whereClause = NULL){
			if($whereClause){
			$this->db->where($whereClause);
				 $query = $this->db->get($entity);
			}else{
				$query = $this->db->get($entity);
			}
			return  $query->result();
		}


		//new
		function deleteDataTwoClause($entityName,$whereClause,$whereClauseTwo){
				$this->db->where($whereClause);
				$this->db->where($whereClauseTwo);
				$this->db->delete($entityName);
				 if($this->db->error()['message']!=""){
						$reponseDB = array(
								"status"=>"error",
								"status_code"=>409,
								"message"=>"Db error: ".$this->db->error()['message'],
								"validations"=>null,
								"data"=>null
						);
				}else{
						$reponseDB = array(
								"status"=>"success",
								"status_code"=>200,
								"message"=>"Artículo eliminado correctamente",
								"validations"=>null,
								"data"=>null
						);
				}
				return $reponseDB;

		}



}
