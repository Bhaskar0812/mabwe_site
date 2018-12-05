<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment_model extends CI_Model {

    //var $table , $column_order, $column_search , $order =  '';
    var $table = COMMENTS;
    var $column_order = array('comment','commentId','posts.title','comments.status'); //set column field database for datatable orderable
    var $column_search = array('commentId','comment','title'); //set column field database for datatable searchable
    var $order = array('comments.commentId' => 'DESC');  // default order
    var $where = '';
    
    public function __construct(){
        parent::__construct();
    }
    
    public function set_data($where=''){
        $this->where = $where;
    }

    function group_get($id,$where){ //get post by using join.
      $uid = $where['id'];
      $existThumb = base_url().UPLOAD_FOLDER.'/group/';
      $this->db->select('`user`.`userId`,`group`.`groupId`, `group`.`groupName`,`group`.`groupImage`,`group`.`category_id`, DATE_FORMAT(`group`.crd, "%d/%m/%Y %H:%m:%s") as Date, COUNT(DISTINCT `like`.`likeId`) as like_count, COUNT(DISTINCT `comment`.`commentId`) as comment_count,CONCAT("'.$existThumb.'",groupImage) as post_image,category.categoryName,tag_mapping.tag_id,category.categoryName,(SELECT `tagName` FROM `tags` WHERE `tagId` = `tag_id`) as name,COALESCE((SELECT group_likes.status FROM `group_likes` WHERE `group_id` = `group`.`groupId` AND `user_id` = '.$uid.'
      ),0) as user_like_status,tag.tagName'); 
      //$this->db->from('`posts` `post`');
      $this->db->join('`group_likes` `like`','`group`.`groupId` = `like`.`group_id` and like.status = 1','left');
      $this->db->join('`group_comments` `comment`','`group`.`groupId` = `comment`.`group_id`','left');
      $this->db->join('`categories` `category`','`group`.`category_id` = `category`.`categoryId`','left');
      $this->db->join('`group_tag_mapping` `tag_mapping`','`group`.`groupId` = `tag_mapping`.`group_id`','left');
      $this->db->join('`users` `user`','`group`.`user_id` = `user`.`userId`','left');
      $this->db->join('`tags` `tag`','`tag_mapping`.`tag_id` = `tag`.`tagId`','left');
      //$this->db->order_by('`group`.`groupId`','desc');
      //$this->db->group_by('`group`.`groupId`');
      $this->db->where(array('groupId'=>$id));
      if(!empty($search)){//searchng with tag will be done from here....
         $ser = explode(',',$search);
         foreach($ser as $values){
           $this->db->like('tagName',$values);
           $this->db->or_like('groupName',$values);
         }
      }
      /*if(!empty($filter)){//searchng with tag will be done from here....
         $this->db->like('category_id',$filter);
      }*/
      /*if($limit!='' && $start!=''){
        $this->db->limit($limit, $start);
      }*/
      $query = $this->db->get('`groups` `group`'); 
      //lq();
      if($query->num_rows()){
        $result = $query->row();

       /* foreach($result as $key => $values){
         $where = array('group_id'=>$values->groupId,'user_id'=>$id);
         $result[$key]->user_like_status =  $this->get_like_status($where);
         if($result[$key]->user_like_status == FALSE){
           $result[$key]->user_like_status = 0;
         }
        }*/
      //print_r($result);
        return $result;
      }
      return FALSE;
  }//end of function...

    function select_result(){  
    //function for select row from database..
        $this->db->select("*");
        $this->db->from('comments');
        $this->db->join('posts','comments.post_id = posts.postId','left');
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        if($query->num_rows()>0){
        $user = $query->result();
        return $user;
    }
        return FALSE;
    //die('fhg');
    }//END OF FUNCTION..
    //prepare post list query
    private function posts_get_query(){
        $sel_fields = array_filter($this->column_order);
        $this->db->select($sel_fields);
        $this->db->select('`posts`.`title`,`posts`.`postId`');

        $this->db->join('posts','posts.postId = comments.post_id');
        $this->db->from($this->table);
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

    function getImages($where){//select multiple images..
      $existThumb= base_url().UPLOAD_FOLDER.'/profile/';
      $query = $this->db->select('profileImage')->where('userId',$where)->get(USERS);
      if($query->num_rows()){
        $result = $query->row();
         if (!empty($result->profileImage) && filter_var($result->profileImage, FILTER_VALIDATE_URL) === false) {
          $result->profileImage = base_url().UPLOAD_FOLDER.'/profile/'.$result->profileImage;
        }else
        {
          $result->profileImage ='';
        }
        return $result;
      }else{
        return FALSE;
      }
    }//end of function..

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