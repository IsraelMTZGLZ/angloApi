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

    public function insertData($entityName,$data,$returnData=false)
    {
        $query=$this->db->insert($entityName,$data);
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
              "message"=>"Item created Successfully", 
              "data"=>$returnData ? $query->inserted_id() : null
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
              "message"=>"Item update Successfully"
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
                "message"=>"Item deleted successful",
                "validations"=>null,
                "data"=>null
            );
        }
        return $reponseDB;

    }

}