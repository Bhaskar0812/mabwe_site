<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group_model extends CI_Model {

    //var $table , $column_order, $column_search , $order =  '';
    var $table = GROUPS;
    var $column_order = array('groupName','groupId','groupImage','categoryName','groups.status'); //set column field database for datatable orderable
    var $column_search = array('groupId','groupName'); //set column field database for datatable searchable
    var $order = array('groupId' => 'DESC');  // default order
    var $where = '';
    
    public function __construct(){
        parent::__construct();
    }
    
    public function set_data($where=''){
        $this->where = $where;
    }

    function group_get($id,$where){ //get post by using join.
      $uid = $where['id'];
      //pr($uid);
      $existThumbs= base_url().UPLOAD_FOLDER.'/profile/';
      $default   = base_url().DEFAULT_IMAGE;
      $existThumb = base_url().UPLOAD_FOLDER.'/group/large/';
      $this->db->select('`user`.`userId`,`user`.`fullname`,(case 
        when( user.profileImage = "" OR 
        user.profileImage IS NULL) 
        THEN "'.$default.'"
        ELSE
        concat("'.$existThumbs.'",
        user.profileImage) 
        END 
     ) as profile_image,`group`.`groupId`, `group`.`groupName`,`group`.`groupImage`,`group`.`category_id`, DATE_FORMAT(`group`.crd, "%d/%m/%Y %H:%m:%s") as Date, COUNT(DISTINCT `like`.`likeId`) as like_count, COUNT(DISTINCT `comment`.`commentId`) as comment_count,CONCAT("'.$existThumb.'",groupImage) as group_image,category.categoryName,tag_mapping.tag_id,category.categoryName,(SELECT `tagName` FROM `tags` WHERE `tagId` = `tag_id`) as name,COALESCE((SELECT group_likes.status FROM `group_likes` WHERE `group_id` = `group`.`groupId` AND `user_id` = '.$uid.'
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
      $query = $this->db->get('`groups` `group`'); //lq();
      //lq();
      if($query->num_rows()){
        $result = $query->row();
        $comm= $this->getComments($id);
            if(empty($comm)){
               $query->row()->comments = array();
            }else{
              $query->row()->comments = $comm;
            }
            $result = $query->result();
        return $result;
      }
      return FALSE;
  }//end of function...

  function getComments($id){//select multiple images..
        $existThumb= base_url().UPLOAD_FOLDER.'/profile/';
        $default   = base_url().DEFAULT_IMAGE;
      $query = $this->db->select('group_comment.comment,group_comment.user_id,user.fullname,concat("'.$existThumb.'",user.profileImage) as profile_image')
      ->join('`users` `user`','user.userId = group_comment.user_id','left')
      ->where('group_id',$id)
      ->get('`group_comments` `group_comment`');
      if($query->num_rows()){
        $result = $query->result();
        return $result;
      }else{
        return FALSE;
      }
    }//end of function..

    function getGroupMembers($id){//select multiple images..
        $existThumb= base_url().UPLOAD_FOLDER.'/profile/';
        $default   = base_url().DEFAULT_IMAGE;
      $query = $this->db->select('group_comment.comment,group_comment.user_id,user.fullname,concat("'.$existThumb.'",user.profileImage) as profile_image')
      ->join('`users` `user`','user.userId = group_comment.user_id','left')
      ->where('group_id',$id)
      ->group_by('user.userId')
      ->get('`group_comments` `group_comment`');
      if($query->num_rows()){
        $result = $query->result();
        return $result;
      }else{
        return FALSE;
      }
    }//end of function..

    function getGroupLikesCount($id){//select multiple images..
        $existThumb= base_url().UPLOAD_FOLDER.'/profile/';
        $default   = base_url().DEFAULT_IMAGE;
      $query = $this->db->select('group_like.user_id,user.fullname,concat("'.$existThumb.'",user.profileImage) as profile_image')
      ->join('`users` `user`','user.userId = group_like.user_id','left')
      ->where('group_id',$id)
      ->group_by('user.userId')
      ->get('`group_likes` `group_like`');
      if($query->num_rows()){
        $result = $query->result();
        return $result;
      }else{
        return FALSE;
      }
    }//end of function..

    function getGroupLikes($id,$limit, $offset=0){//select multiple images..
        $existThumb= base_url().UPLOAD_FOLDER.'/profile/';
        $default   = base_url().DEFAULT_IMAGE;
        $this->db->limit($limit, $offset);
      $query = $this->db->select('group_like.user_id,user.fullname,user.userId,(case 
        when( user.profileImage = "" OR 
        user.profileImage IS NULL) 
        THEN "'.$default.'"
        ELSE
        concat("'.$existThumb.'",
        user.profileImage) 
        END 
     ) as profile_image')
      ->join('`users` `user`','user.userId = group_like.user_id','left')
      ->where('group_id',$id)
      ->group_by('user.userId')
      ->get('`group_likes` `group_like`');
      if($query->num_rows()){
        $result = $query->result();
        return $result;
      }else{
        return FALSE;
      }
    }//end of function..

    function getMembersList($id,$limit, $offset=0){//select multiple images..
        $existThumb= base_url().UPLOAD_FOLDER.'/profile/';
        $default   = base_url().DEFAULT_IMAGE;
        $this->db->limit($limit, $offset);
      $query = $this->db->select('group_comment.comment,group_comment.user_id,user.fullname,user.userId,(case 
        when( user.profileImage = "" OR 
        user.profileImage IS NULL) 
        THEN "'.$default.'"
        ELSE
        concat("'.$existThumb.'",
        user.profileImage) 
        END 
     ) as profile_image')
      ->join('`users` `user`','user.userId = group_comment.user_id','left')
      ->where('group_id',$id)
      ->group_by('user.userId')
      ->get('`group_comments` `group_comment`');
      if($query->num_rows()){
        $result = $query->result();
        return $result;
      }else{
        return FALSE;
      }
    }//end of function..

    function getCommentsLists($where, $limit, $offset=0){//select multiple images..
        $existThumb= base_url().UPLOAD_FOLDER.'/profile/';
        $default = base_url().DEFAULT_IMAGE;
        $this->db->limit($limit, $offset);
      $query = $this->db->select('group_comment.comment,group_comment.user_id,user.fullname,user.userId,(case 
        when( user.profileImage = "" OR 
        user.profileImage IS NULL) 
        THEN "'.$default.'"
        ELSE
        concat("'.$existThumb.'",
        user.profileImage) 
        END 
     ) as profile_image')
      ->join('`users` `user`','user.userId = group_comment.user_id','left')
      ->where('group_id',$where)
      ->get('`group_comments` `group_comment`');
      if($query->num_rows()){
        $result = $query->result();
        return $result;
      }else{
        return FALSE;
      }
    }//end of function..


    function select_result(){  
    //function for select row from database..
        $this->db->select("*");
        $this->db->from('groups');
        $this->db->join('categories','groups.category_id = categories.categoryId','left');
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
        $this->db->select('`categories`.`categoryName`,`categories`.`categoryId`');

        $this->db->join('categories','categories.categoryId = groups.category_id');
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

    function get_members($data,$table){//get member list function
    $existThumb = base_url().UPLOAD_FOLDER.'/profile/';
    $default   = base_url().PROFILE_DETAIL_DEFAULT;
    $groupId = $data['group_id'];
    //$this->db->distinct('users.fullname');
    $this->db->select('`group_member`.`user_id`,`group_member`.`group_id`,
      `user`.`fullname`,`user`.`country`,(case 
        when( user.profileImage = "" OR 
        user.profileImage IS NULL) 
        THEN "'.$default.'"
        ELSE
        concat("'.$existThumb.'",
        user.profileImage) 
        END 
     ) as profile_image
    ,`group`.`groupName`,
    IF(`group`.`user_id`='.$data['user_id'].',1,0) as isAdmin
    ');
    $this->db->join('`groups` `group`', '`group_member`.`user_id` = `group`.`user_id`','left');
    $this->db->join('`users` `user`', '`group_member`.`user_id` = `user`.`userId`','left');
    $this->db->order_by('`isAdmin`','desc');
    //$this->db->order_by('`isAdmin`','asec');
    /*$this->db->join('`group_members` `group_mem`', '`user`.`userId` = `group_mem`.`user_id`','left');*/
    $this->db->where('`group_member`.`group_id`',$groupId);
    $this->db->group_by('user.fullname');
    if(!empty($search)){//searchng with tag will be done from here....
         $this->db->like('user.fullname',$search);
      }
    //$this->db->group_by('group_member.group_id');
    $query = $this->db->get('`group_members` `group_member`');
    if($query->num_rows()){
      $result = $query->result();

      return $result;
    }
    return FALSE;
  }//end of function 

}