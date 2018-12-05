<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends CI_Model {

    //var $table , $column_order, $column_search , $order =  '';
    var $table = POSTS;
    var $column_order = array('postId','post_image','category.categoryName','`post`.`title`','`post`.`description`','like_count','comment_count','`user`.`fullname`','`post`.`status`'); //set column field database for datatable orderable
    var $column_search = array('`post`.`postId`','`category`.`categoryName`','post.title','post.description','post.status'); //set column field database for datatable searchable
    var $order = array('post.postId' => 'DESC');  // default order
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

    function getPostLikesCount($id){//select multiple images..
        $existThumb= base_url().UPLOAD_FOLDER.'/profile/';
        $default   = base_url().DEFAULT_IMAGE;
      $query = $this->db->select('like.likeId,like.user_id,user.fullname,concat("'.$existThumb.'",user.profileImage) as profile_image')
      ->join('`users` `user`','user.userId = like.user_id','left')
      ->where('post_id',$id)
      ->group_by('user.userId')
      ->get('`likes` `like`');
      if($query->num_rows()){
        $result = $query->result();
        return $result;
      }else{
        return FALSE;
      }
    }//end of function..

    function getPostLikes($id,$limit, $offset=0){//select multiple images..
        $existThumb= base_url().UPLOAD_FOLDER.'/profile/';
        $default   = base_url().DEFAULT_IMAGE;
        $this->db->limit($limit, $offset);
      $query = $this->db->select('like.user_id,user.fullname,user.userId,(case 
        when( user.profileImage = "" OR 
        user.profileImage IS NULL) 
        THEN "'.$default.'"
        ELSE
        concat("'.$existThumb.'",
        user.profileImage) 
        END 
     ) as profile_image')
      ->join('`users` `user`','user.userId = like.user_id','left')
      ->where('post_id',$id)
      ->group_by('user.userId')
      ->get('`likes` `like`');
      if($query->num_rows()){
        $result = $query->result();
        return $result;
      }else{
        return FALSE;
      }
    }//end of function..

    function prepare_query(){
       $existThumb= base_url().UPLOAD_FOLDER.'/postImages/';
        $sel_fields = array_filter($this->column_order);
        $this->db->select('`post`.`postId`, `post`.`title`,`post`.`status`, `post`.`description`,`post`.`category_id`, COUNT(DISTINCT `like`.`likeId`) as like_count, COUNT(DISTINCT `comment`.`commentId`) as comment_count,CONCAT("'.$existThumb.'",attachmentName) as post_image,tag_mapping.tag_id,category.categoryName,(SELECT `tagName` FROM `tags` WHERE `tagId`=`tag_id`) as name,tag.tagName,`user`.`fullname`,`user`.`userId`'); 
      //$this->db->from('`posts` `post`');
         $this->db->from('`posts` `post`');
        $this->db->join('`post_attachments` `post_attachment`','`post`.`postId` = `post_attachment`.`post_id`','left');
        $this->db->join('`likes` `like`','`post`.`postId` = `like`.`post_id` and like.status = 1','left');
        $this->db->join('`comments` `comment`','`post`.`postId` = `comment`.`post_id`','left');
        $this->db->join('`categories` `category`','`post`.`category_id` = `category`.`categoryId`','left');
      //
        $this->db->join('`tags_mapping` `tag_mapping`','`post`.`postId` = `tag_mapping`.`post_id`','left');
        $this->db->join('`tags` `tag`','`tag_mapping`.`tag_id` = `tag`.`tagId`','left');
        $this->db->join('`users` `user`','`post`.`user_id` = `user`.`userId`','left');
        $this->db->group_by('`post`.`postId`');
        
    }
    
    //prepare post list query
    private function posts_get_query(){
        
        $this->prepare_query();
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

    function get_list()
    {
        $this->posts_get_query();
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

    function count_filtered()
    {
        $this->posts_get_query();
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
    }//END OF FUNCTION

    function getCommentsLists($where, $limit, $offset=0){//select multiple images..
        $existThumb= base_url().UPLOAD_FOLDER.'/profile/';
        $default = base_url().DEFAULT_IMAGE;
        $this->db->limit($limit, $offset);
      $query = $this->db->select('comment.comment,comment.user_id,user.fullname,user.userId,(case 
        when( user.profileImage = "" OR 
        user.profileImage IS NULL) 
        THEN "'.$default.'"
        ELSE
        concat("'.$existThumb.'",
        user.profileImage) 
        END 
     ) as profile_image')
      ->join('`users` `user`','user.userId = comment.user_id','left')
      ->where('post_id',$where)
      ->get('`comments` `comment`');
      if($query->num_rows()){
        $result = $query->result();
        return $result;
      }else{
        return FALSE;
      }
    }//end of function..

}//END OF CLASS