<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_posts extends CI_Model {

    //var $table , $column_order, $column_search , $order =  '';
    var $table = USERS;
    var $column_order = array('userId','fullname','profileImage','users.status','posts.postId'); //set column field database for datatable orderable
    var $column_search = array('`categories`.`categoryName`','posts.title','fullname'); //set column field database for datatable searchable
    var $order = array('userId' => 'DESC');  // default order
    var $where = '';
    
    public function __construct(){
        parent::__construct();
    }
    
    public function set_data($where=''){
        $this->where = $where;
    }

    function select_result($table){  
    //function for select row from database..
        $this->db->select("*");
        $this->db->from($table);
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        if($query->num_rows()>0){
        $user = $query->result();
        return $user;
    }
        return FALSE;
    }//END OF FUNCTION..

    
    //prepare post list query
    private function posts_get_query($userId){
        $existThumb= base_url().UPLOAD_FOLDER.'/postImages/';
        $sel_fields = array_filter($this->column_order);
        $this->db->select($sel_fields);
        $this->db->select('`posts`.`title`,`posts`.`postId`,`posts`.`status`,`categories`.`categoryName`,CONCAT("'.$existThumb.'",attachmentName) as postImage');
        $this->db->join('posts','posts.user_id = users.userId','left');
        $this->db->join('post_attachments','posts.postId = post_attachments.post_id');
        $this->db->join('categories','posts.category_id = categories.categoryId');
        $this->db->group_by('`posts`.`postId`');
        $this->db->where('userId',$userId);
        $this->db->from($this->table);
        /*$this->prepare_query();*/
        $i = 0;

        foreach ($this->column_search as $emp) // loop column 
        {
            if(isset($_POST['search']['value']) && !empty($_POST['search']['value'])){
                $_POST['search']['value'] = $_POST['search']['value'];
            } else
                $_POST['search']['value'] = '';

            if($_POST['search']['value']) // if datatable send POST for search
            {
                if($i===0) // first loop
                {
                    $this->db->group_start();
                    $this->db->like(($emp), $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like(($emp), $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
            }
            if(!empty($this->where))
                $this->db->where($this->where); 

            $count_val = count($_POST['columns']);
            for($i=1;$i<=$count_val;$i++){ 

                if(!empty($_POST['columns'][$i]['search']['value'])){ 
                    $this->db->where(array($this->table_col[$i]=>$_POST['columns'][$i]['search']['value'])); 
                }else if(!empty($_POST['columns'][$i]['search']['value'])){ 
                    $this->db->where(array($this->table_col[$i]=>$_POST['columns'][$i]['search']['value'])); 
                } 
            }
            if(isset($_POST['order'])) // here order processing
            {
                $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            } 
            else if(isset($this->order))
            {
                $order = $this->order;
                $this->db->order_by(key($order), $order[key($order)]);
            }
    }

    function get_list($userId)
    {
        //echo $userId;die;

        $this->posts_get_query($userId);
        if(isset($_POST['length']) && $_POST['length'] < 1) {
            $_POST['length']= '10';
        } else
        $_POST['length']= $_POST['length'];
        
        if(isset($_POST['start']) && $_POST['start'] > 1) {
            $_POST['start']= $_POST['start'];
        }
        $this->db->limit($_POST['length'], $_POST['start']);
        //print_r($_POST);die;
        $query = $this->db->get(); 
        return $query->result();
    }

    function count_filtered($userId)
    {
        $this->posts_get_query($userId);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function counts_all($table)
    {
        $this->db->from($table);
        return $this->db->count_all_results();
    }


    function activeInactive($table,$data,$column){
        $where = array($column =>$data['id']);
        $this->db->select('*');  
        $this->db->where($where);
        $sql = $this->db->get($table)->row();
        if($sql->status == 0){
            $this->db->update($table,array('status'=> '1'),$where);
            return TRUE;
        }else{
            $this->db->update($table,array('status'=> '0'),$where);
            return FALSE;
        }
    }

    function deleteData($table,$data,$column){
        $where = array($column =>$data['id']);
        $this->db->where($where);
        $sql = $this->db->delete($table);
        if($sql){
            return TRUE;
        }else{
            return FALSE;
        }
    }

}