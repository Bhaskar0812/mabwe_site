<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group_model extends CI_Model {

  function addGroup($data,$table){//add group function..
    $check['groupName'] = $data['groupName'];
    $check['user_id']   = $data['user_id'];
    $getGroup = $this->db->select('groupName')->where($check)->get($table);
    if($getGroup->num_rows() == 0){
      $this->db->insert($table,$data);
      $query = $this->db->insert_id();
      if($query){
        $groupJoin['user_id'] = $data['user_id'];
        $groupJoin['group_id'] = $query;
        //$groupJoin['isAdmin'] = 1;
         $tableName = GROUP_MEMBERS;
        $sql = $this->db->select('user_id','group_id')->where($groupJoin)->get($tableName);
       
        if($sql->num_rows() == 0){
        $res =  $this->db->insert($tableName,$groupJoin);
        if($res){
          $limit ='';$start='';$search='';$filter='';$id =$_SESSION[USER_SESS_KEY]['userId'];
           $groupData = $this->group_get($table,$limit,$start,$search,$filter,$id,array('group.groupId'=>$query)); 
          return array('type'=>'SC','data'=>$groupData,'id'=>$query);
        }
        }else{
          return array('type'=>'AM','id'=>$query);
        }
        return FALSE;
      }
    }
     return array('type'=>'GF');
  }//end of function....

  function getDetail($table,$where,$column){//select data by giving column name and where.
    $res = $this->db->select($column)->join(CATEGORIES .' as cat','cat.categoryId = groups.category_id','left')->where($where)->get($table);
      if($res->num_rows()){
        $result = $res->row();
        if (!empty($result->groupImage) && filter_var($result->groupImage, FILTER_VALIDATE_URL) === false) {
          $result->groupImage = $result->groupImage;
        }
        if(!empty($result->isAdmin) AND $result->isAdmin == 1){
          $result->userType = "ADMIN";
        }else{
          $result->userType = "USER";
        }
        return $result;
      } else{
        return false;
      }
  }//end of function.

  function check_tags($data,$group,$table){//this function is used for cheack tags.
    $i=0;
    $countTags = count($data);
    for ($i=0; $i < $countTags; $i++) {
      $tags['tagName'] = $data[$i];
      $query = $this->db->select('tagId,tagName')->where($tags['tagName'])->get($table);
      if($query->num_rows()){
        $result = $query->result_array();
        //print_r($result);
        $dataInsert = array();
        foreach($result as $key => $value){
          $dataInsert[$key]['tag_id']  = $value['tagId'];
          $dataInsert[$key]['group_id']  = $group;
        } 
      $insert = $this->db->insert_batch(GROUP_TAGS_MAPPING,$dataInsert); 
      }else{
        $countTag = count($tags);
        $getid = $this->db->insert_batch(TAGS,$tags);
        $last_id = $this->db->insert_id();
        $getData = $this->db->select('tagId,tagName')->where('tagId',$last_id)->limit($countTag)->get(TAGS);
        if($getData->num_rows()){
          $results = $getData->result_array();
          foreach($results as $key => $value){
          $dataInsert[$key]['tag_id']  = $value['tagId'];
          $dataInsert[$key]['group_id']  = $group;
        }
        $insert = $this->db->insert_batch(GROUP_TAGS_MAPPING,$dataInsert);  
        }else{
          return FALSE;
        }
    }
    }
    return FALSE;
  }//end of function..

  function group_get($table,$limit='',$start='',$search='',$filter='',$id='',$where=''){ //get post by using join.
      $this->db->select('`user`.`userId`,`group`.`groupId`, `group`.`groupName`,`group`.`category_id`, DATE_FORMAT(`group`.crd, "%d/%m/%Y %H:%m:%s") as Date, COUNT(DISTINCT `like`.`likeId`) as like_count, COUNT(DISTINCT `comment`.`commentId`) as comment_count,groupImage,category.categoryName,tag_mapping.tag_id,category.categoryName,(SELECT `tagName` FROM `tags` WHERE `tagId` = `tag_id`) as name,COALESCE((SELECT group_likes.status FROM `group_likes` WHERE `group_id` = `group`.`groupId` AND `user_id` = '.$id.'
      ),0) as user_like_status,tag.tagName'); 
      //$this->db->from('`posts` `post`');
      $this->db->join('`group_likes` `like`','`group`.`groupId` = `like`.`group_id` and like.status = 1','left');
      $this->db->join('`group_comments` `comment`','`group`.`groupId` = `comment`.`group_id`','left');
      $this->db->join('`categories` `category`','`group`.`category_id` = `category`.`categoryId`','left');
      $this->db->join('`group_tag_mapping` `tag_mapping`','`group`.`groupId` = `tag_mapping`.`group_id`','left');
      $this->db->join('`users` `user`','`group`.`user_id` = `user`.`userId`','left');
      $this->db->join('`tags` `tag`','`tag_mapping`.`tag_id` = `tag`.`tagId`','left');
      $this->db->order_by('`group`.`groupId`','desc');
      $this->db->group_by('`group`.`groupId`');
      if(!empty($search)){//searchng with tag will be done from here....
         $ser = explode(',',$search);
         foreach($ser as $values){
           $this->db->like('tagName',$values);
           $this->db->or_like('groupName',$values);
         }
      }
      if(!empty($filter)){//searchng with tag will be done from here....
         $this->db->like('category_id',$filter);
      }
      if($limit!='' && $start!=''){
        $this->db->limit($limit, $start);
      }

      if(!empty($where)){
        $this->db->where($where);
      }
      $query = $this->db->get('`groups` `group`'); 
      //lq();
      if($query->num_rows()){
        $result = array('count'=>$query->num_rows(),'data'=>$query->result());
        return $result;
      }
      return FALSE;
  }//end of function...

  function insertLike($data,$table){ //insert like on group..
    $user_id = $data['user_id'];
    $group_id = $data['group_id'];
    $query = $this->db->select('likeId,group_id,status')->where(array('group_id'=>$group_id,'user_id'=>$user_id))->get(GROUP_LIKES);
    if($query->num_rows()){
      $result = $query->row();
      $like_id = $result->likeId;
      $status = $result->status;
      if($status == 1){
        $this->db->update($table, array('status'=>0), array('likeId'=>$like_id));
       $res =  $this->getlikeData(array('likeId'=>$like_id),$data);
        return array('type'=>'UNLIKE','data'=>$res);

      }else{
        $this->db->update($table, array('status'=>1), array('likeId'=>$like_id));
         $res =  $this->getlikeData(array('likeId'=>$like_id),$data);
         return array('type'=>'LIKE','data'=>$res);
      }
    }else{

    $query = $this->db->select('*')->where(array('user_id'=>$user_id,'group_id'=>$group_id))->get(GROUP_MEMBERS);
    if($query->num_rows() == 0){
      $joinGroup['user_id'] = $user_id;
      $joinGroup['group_id'] = $group_id;
      $this->db->insert(GROUP_MEMBERS,$joinGroup);
    }
    $this->db->insert($table,$data);
    $last_id = $this->db->insert_id();
      $res =  $this->getlikeData(array('likeId'=>$last_id),$data);
      return array('type'=>'LIKE','data'=>$res);
   }
   return FALSE;
  }//end of function

  function getlikeData($where,$group_id){
    $query = $this->db->select('`group_likes`.`group_id`,`group_likes`.`user_id`,`group_likes`.`status`,(SELECT COUNT(`likeId`) from `group_likes` WHERE group_id ='.$group_id['group_id'].' AND `status`= 1) as like_count')->where($where)->get('group_likes');
    if($query->num_rows()){
      $result = $query->row();
     
      return $result;
    }
    return FALSE;
  }

  function getUserDetails($where,$data='',$table){
        $query = $this->db->select($data)->where($where)->get($table);
        if($query->num_rows()){
           $row = $query->row();
            $uid = $this->db
            ->select('userId,deviceToken,fullname,profileImage,categoryName')
            ->from('groups')
            ->join('users','groups.user_id = users.userId')
            ->join('categories','groups.category_id = categories.categoryId')
            ->where($where)
            ->get();
            if($uid->num_rows()){
                $user_id = $uid->row();
                return $user_id;
            }return FALSE;
        }
        return FALSE;
    }

    public function getCountLikes($table,$where,$start='',$limit=''){//get count of likes which users is ACTIVE
       $this->db->select('`group_likes`.`likeId`,`group_likes`.`user_id`,`group_likes`.`group_id`,`usr`.`fullName`,`usr`.`userId`,`usr`.`profileImage`,`usr`.`country`');
      $this->db->join(USERS.' as usr','`usr`.`userId` = `group_likes`.`user_id`','left');
      $this->db->where($where);
       $this->db->where('usr.status',1);
      $this->db->order_by('`group_likes`.`upd`','desc');
     $query = $this->db->get($table);
        if($query->num_rows()){
            return array('count'=>$query->num_rows(),'result'=>$query->result());
        }
        return 0;
    }//end of function 

    function likedUser($table,$where,$start,$limit){
      $this->db->select('`group_likes`.`likeId`,`group_likes`.`user_id`,`group_likes`.`group_id`,`usr`.`fullName`,`usr`.`userId`,`usr`.`profileImage`,`usr`.`country`');
      $this->db->join(USERS.' as usr','`usr`.`userId` = `group_likes`.`user_id`','left');
      $this->db->where($where);
       $this->db->where('usr.status',1);
      $this->db->order_by('`group_likes`.`upd`','desc');
      $this->db->limit($limit, $start);
      $query = $this->db->get($table);
      if($query->num_rows()){
        return $query->result();
      }
      return FALSE;
  }

  function groupDetail_get($table,$where,$id){//this function is used for get group detail
   
      $result = $this->groupInfo($where,$id);

    return $result;
  }//end of function 

  function groupComments($where){//group comment function..
    $existThumb = base_url().UPLOAD_FOLDER.'/profile/';
    $default   = base_url().DEFAULT_USER;
    $this->db->select('group_comment.comment,`group_comment`.crd as date,,user.fullname,NOW()as currentDate,user.country,
    (case 
        when( user.profileImage = "" OR user.profileImage IS NULL) 
        THEN "'.$default.'"
        ELSE
        concat("'.$existThumb.'",user.profileImage) 
       END ) as profile_image
    ,user.userId');
    $this->db->join('`users` `user`','`group_comment`.`user_id` = `user`.`userId`','left');
    $this->db->where('group_id',$where);
    $this->db->order_by('commentId','desc');
    $this->db->order_by('`group_comment`.crd','desc');
    
    $query = $this->db->get('`group_comments` `group_comment`');
    if($query->num_rows()){
      $result = $query->result();
      return $result;
    }
  } //end of function 


  function groupInfo($where,$id){//get group info...
    $existThumb = base_url().UPLOAD_FOLDER.'/group/';
    $this->db->select('`usr`.*,`group`.`groupId`, `group`.`groupName`,`group`.`category_id`, DATE_FORMAT(`group`.crd, "%d/%m/%Y %H:%m:%s") as Date, COUNT(DISTINCT `like`.`likeId`) as like_count,COUNT(DISTINCT `comment`.`user_id`) as comment_count,groupImage as post_image,COUNT(DISTINCT `group_member`.`user_id`) as members_count,category.categoryName,COALESCE((SELECT group_likes.status FROM `group_likes` WHERE `group_id` = `group`.`groupId` AND `user_id` = '.$id.'
      ),0) as user_like_status,(case 
        when( `group`.`user_id` = '.$id.') 
        THEN 1
        ELSE
        0
       END ) as isAdmin'); 
    //$this->db->from('`posts` `post`');
    $this->db->join('`group_likes` `like`','`group`.`groupId` = `like`.`group_id` and like.status = 1','left');
    $this->db->join('`group_comments` `comment`','`group`.`groupId` = `comment`.`group_id`','left');
    $this->db->join('`categories` `category`','`group`.`category_id` = `category`.`categoryId`','left'); 
    $this->db->join('`group_members` `group_member`','`group`.`groupId` = `group_member`.`group_id`','left');
    $this->db->join('`users` `usr`','`usr`.`userId` = `group`.`user_id`','left');
    $this->db->where('groupId',$where);
    //
    $this->db->order_by('like_count','desc');
    $this->db->group_by('`group`.`groupId`');
    //$this->db->group_by('`comment`.`user_id`');
    $query = $this->db->get('`groups` `group`'); 

   //echo  $this->db->last_query(); die;
    if($query->num_rows()){
      $dat= $this->getGroupTags($where);

            if(empty($dat)){
               $query->row()->tags = array();
            }else{
              $query->row()->tags = $dat;
            }
    $result = $query->row();
    return $result;
    }
  }//end of function....


   function getGroupTags($where){//select multiple images..
      //$existThumb= base_url().UPLOAD_FOLDER.'/postImages/';
      $this->db->select(TAGS_MAPPING.'.tag_id');
      $this->db->select(TAGS.'.tagName');
      $this->db->join(TAGS,'group_tag_mapping.tag_id = tags.tagId','left');
      $this->db->join(TAGS_MAPPING,'tags_mapping.tag_id = tags.tagId','left');
       $this->db->where('group_id',$where);
       $this->db->group_by(TAGS.'.tagName');
      $query =  $this->db->get(GROUP_TAGS_MAPPING);
      //lq();
      if($query->num_rows()){
        $result = $query->result();
        return $result;
      }else{
        return FALSE;
      }
    }//end of function..


  function GroupCommentsDetails($table,$where,$start='',$limit=''){//get group comment function
    $this->db->select('group_comment.comment,group_comment`.crd as date,user.userId,user.fullname,user.country,profileImage as profile_image');
    $this->db->join('`users` `user`','`group_comment`.`user_id` = `user`.`userId`','left');
    $this->db->order_by('group_comment.commentId','desc');
    $this->db->order_by('`group_comment`.crd','desc');
    $this->db->where($where);
    $this->db->limit($limit, $start);
    $query = $this->db->get('`group_comments` `group_comment`');
    if($query->num_rows()){
      $result = $query->result();
      return $result;
    }
  } 

  function GroupCommentsCounts($table,$where){//get group comment function
    $this->db->select('group_comment.comment');
    $this->db->order_by('group_comment.commentId','desc');
    $this->db->order_by('`group_comment`.crd','desc');
    $this->db->where($where);
   
    $query = $this->db->get('`group_comments` `group_comment`');
    if($query->num_rows()){
      $result = $query->num_rows();
      return $result;
    }
  } 

  function getUserDetailsComment($where,$data='',$table){//get user Details and Comment get data with post id
        $query = $this->db->select($data)->where($where)->get($table);
        if($query->num_rows()){
           $row = $query->row();
            $uid = $this->db
            ->select('userId,deviceToken,fullname,profileImage,categoryName')
            ->from('posts')
            ->join('users','posts.user_id = users.userId')
            ->join('categories','posts.category_id = categories.categoryId')
            ->where($where)
            ->get();
            if($uid->num_rows()){
                $user_id = $uid->row();
                return $user_id;
            }return FALSE;
        }
        return FALSE;
    }//end of function

     function getCommentsWithUser($table,$where){//get Comment with user data to show
      $this->db->select('group_comments.comment,group_comments.crd as comment_crd,`usr`.`fullname`,`usr`.`profileImage`,`usr`.userId as comment_user_id');
      $this->db->join(USERS. ' usr','`usr`.`userId` = `group_comments`.`user_id`','left');
      $this->db->where($where);
      $this->db->order_by('commentId','DESC');
      $query = $this->db->get($table);
     
      if($query->num_rows()){
        $result=  $query->row();
        return $result;
      }
     
    }//end of function

    function groupMembers($data,$table,$search='',$start='',$limit=''){//get member list function
      $default   = base_url().PROFILE_DETAIL_DEFAULT;
      $groupId = $data['group_id'];
      //$this->db->distinct('users.fullname');
      $this->db->select('`group_member`.`user_id`,`group_member`.`group_id`,
        `user`.`fullname`,`user`.`country`,
          user.profileImage as profile_image
      ,`group`.`groupName`,`group`.`user_id` as createdBy,
      IF(`group`.`user_id`=`group_member`.`user_id`,1,0) as isAdmin
      ');
      $this->db->join('`groups` `group`', '`group`.`groupId` = `group_member`.`group_id`','left');
      $this->db->join('`users` `user`', '`user`.`userId` = `group_member`.`user_id`','left');
      $this->db->order_by('`isAdmin`','desc');
      if(!empty($start) OR !empty($limit)){
       $this->db->limit($limit, $start);
      }
     
      $this->db->where('`group_member`.`group_id`',$groupId);
      $this->db->group_by('user.userId');
      if(!empty($search)){//searchng with tag will be done from here....
           $this->db->like('user.fullname',$search);
        }
      //$this->db->group_by('group_member.group_id');
      $query = $this->db->get('`group_members` `group_member`');
      if($query->num_rows()){
        $result = $query->result();

        return array('type'=>"SE",'data'=>$result,'count'=>$query->num_rows());
      }
      return FALSE;
  }//end of function 


  function groupComment($data,$table){//get comment function..
    $user_id = $data['user_id'];
    $group_id = $data['group_id'];
    $query = $this->db->select('*')->where(array('user_id'=>$user_id,'group_id'=>$group_id))->get(GROUP_MEMBERS);
    if($query->num_rows() == 0){
      $joinGroup['user_id'] = $user_id;
      $joinGroup['group_id'] = $group_id;
      $this->db->insert(GROUP_MEMBERS,$joinGroup);
    }
    $this->db->insert($table,$data);
   // echo $this->db->last_query();
    $last_id = $this->db->insert_id();
    if(!empty($last_id)){
      $result = $last_id;
      return $result;
    }
    return FALSE;
  }//end of function.

  function getGroupId($where){  
    //function for select row from database..
       $query = $this->db
        ->select('GROUP_CONCAT(tagId)as tagId')
            ->from('group_tag_mapping')
            ->join('tags','tags.tagId = group_tag_mapping.tag_id')
            ->where($where)
            ->get();
        //echo $str = $this->db->last_query(); die;
        if($query->num_rows() > 0){
        $user = $query->row();
        return $user;
    }
        return FALSE;
    }//END OF FUNCTION.

    function getTagsId($tagName){

    $this->db->select('GROUP_CONCAT(tagId) as tagId');
    $this->db->where_in('tagName', $tagName);
    $query = $this->db->get(TAGS);
    //lq();
    if ($query->num_rows()>0) {
      return $query->row();
    }
    return FALSE;
  }

  function getAllTags(){  
    //function for select row from database..
        $this->db->select('GROUP_CONCAT(tagName)as tagName');
        //$this->db->where($where);
        $query = $this->db->get('tags');
        //echo $str = $this->db->last_query(); die;
        if($query->num_rows()>0){
        $user = $query->row();
        return $user;
    }
        return FALSE;
    }//END OF FUNCTION.

    

}//end of class