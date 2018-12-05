<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Common_model extends CI_Model {
  public function __construct(){
      parent::__construct();
      
  }
  
  public function updateFields($table,$whereCondition,$updateData){
    
      $this->db->update($table, $updateData, $whereCondition);
      $row = $this->db->affected_rows() ;
      return $row;
  }    

   public function update_data($table,$whereCondition,$updateData){
    
      $this->db->update($table, $updateData, $whereCondition);
      $row = $this->db->affected_rows() ;
      return $row;
  }


  public function insert_data($table,$data){
   // print_r($data);
      $re = array();
      $this->db->insert($table,$data);
      $last_id = $this->db->insert_id();
      //echo $this->db->last_query();
      //$re= $this->getData(array('tagId'=>$last_id));
      //print_r($result);
      return $last_id;
          
  }  

  public function insert_batch($table,$data){
      $this->db->insert_batch($table, $data);
      //$last_id = $this->db->insert_id();
      //echo $this->db->last_query();die;
      //$result = $this->getData(array('tagId'=>$last_id));
      return $result;
          
  }  
    
    
  public function delete_data($table,$whereCondition){
      $this->db->delete($table, $whereCondition); 
      $affected_rows  = $this->db->affected_rows();
      return $affected_rows;
      
  } 


  function getData($id){
    $res = $this->db->select('*')->where($id)->get(TAGS);
    if($res->num_rows()){
      $result = $res->row();
      return $result;
    } else {
      return false;
    }
  }

  function customGet($select,$where,$table){
        //select single data..with given criteria..
        $this->db->select($select);
        $this->db->where($where);
        $query = $this->db->get($table);
        //echo $this->db->last_query();die;
        if($query->num_rows()){
        return $query->row();
       
        }
    }//END OF FUNCTION..

    function countRow($select,$where,$table){
        //select single data..with given criteria..
        $this->db->select($select);
        $this->db->where($where);
        $query = $this->db->get($table);
        //echo $this->db->last_query();die;
        if($query->num_rows()){
        return $query->num_rows();
       
        }
    }//END OF FUNCTION..

  public function get_records_by_id($table,$single,$where,$select,$order_by_field,$order_by_value ){
        if(!empty($select)){
            $this->db->select($select);
        }
        
        if(!empty($where)){
            $this->db->where($where);
        }
        
        if(!empty($order_by_field) && !empty($order_by_value)){
            $this->db->order_by($order_by_field, $order_by_value);
        }
        
            $query = $this->db->get($table);
            $result = $query->result_array();
            if(!empty($result)){
            if($single){
                $result = $result[0];
            }else{
                $result = $result;
            }  
        } else{
           $result = 0; 
        }
        return $result; 
        
    }     
}//end of clas