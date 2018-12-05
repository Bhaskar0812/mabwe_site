<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends CI_Model {

     function job_add_post($data,$data1,$table){
        $this->db->insert($table,$data);
        $last_id = $this->db->insert_id();
        if(!empty($last_id) AND !empty($data1)){
            $data1['user_id']   =  $data['user_id'];
            $data1['post_id']   =  $last_id;
            $insert =   $this->db->insert(POST_PERMISSION,$data1);
        if($insert){
            return $last_id;
        }else{
            return FALSE;
        }
        }
            return FALSE;
      }

      function check_tags($data,$post,$table){
        $i=0;
        $countTags = count($data);
        for ($i=0; $i < $countTags; $i++) {
          $tags['tagName'] = $data[$i];
          $query = $this->db->select('tagId,tagName')
          ->where($tags['tagName'])
          ->get($table);
        if($query->num_rows()){
            $result = $query->result_array();
            $dataInsert = array();
            foreach($result as $key => $value){
              $dataInsert[$key]['tag_id']  = $value['tagId'];
              $dataInsert[$key]['post_id']  = $post;
            } 
            $insert = $this->db->insert_batch(TAGS_MAPPING,$dataInsert); 
        }else{
            $countTag = count($tags);
            $getid = $this->db->insert_batch(TAGS,$tags);
            $last_id = $this->db->insert_id();
            $getData = $this->db->select('tagId,tagName')
            ->where('tagId',$last_id)->limit($countTag)->get(TAGS);
            if($getData->num_rows()){
              $results = $getData->result_array();
              foreach($results as $key => $value){
              $dataInsert[$key]['tag_id']  = $value['tagId'];
              $dataInsert[$key]['post_id']  = $post;
            }
            $insert = $this->db->insert_batch(TAGS_MAPPING,$dataInsert);  
            }
            else
            {
              return FALSE;
            }
        }
        }
        return FALSE;
  }//end of function..

  function postDetail_get($table,$where,$offset,$id,$limit,$start){//this function is used for get group detail
    if($offset == 1){
      $result = $this->select_post($where,$id);
    }else{
       $result = $this->postComments($where,$limit, $start);

    }
    return $result;
  }//end of function 

  function postComments($where,$limit='', $start=''){//group comment function..
    $existThumb = base_url().UPLOAD_FOLDER.'/profile/';
    $default   = base_url().PROFILE_DETAIL_DEFAULT;
    $this->db->select('comment.comment,`comment`.crd as date,NOW() as date_of_comment,
     user.fullname,user.country,user.profileImage as profile_image
    ,user.userId');
    $this->db->join('`users` `user`',
      '`comment`.`user_id` = `user`.`userId`',
      'left');
    $this->db->where('post_id',$where);
    $this->db->order_by('comment.commentId','desc');
    $this->db->order_by('`comment`.crd','desc');
    if($limit!='' && $start!=''){
        $this->db->limit($limit, $start);
    }
    $query = $this->db->get('`comments` `comment`');
    if($query->num_rows()){
      $result = $query->result();
      return $result;
    }
  } //end of function  


  function postCommentsFeedDetails($table,$where,$start, $limit){//group comment function..
    $existThumb = base_url().UPLOAD_FOLDER.'/profile/';
    $default   = base_url().PROFILE_DETAIL_DEFAULT;
    $this->db->select('comment.comment,`comment`.crd as date,NOW() as date_of_comment,
     user.fullname,user.country,user.profileImage as profile_image
    ,user.userId');
    $this->db->join('`users` `user`',
      '`comment`.`user_id` = `user`.`userId`',
      'left');
    $this->db->where($where);
    $this->db->where('user.status',1);
    $this->db->order_by('comment.commentId','desc');
    $this->db->order_by('`comment`.crd','desc');
    $this->db->limit($limit, $start);
    $query = $this->db->get('`comments` `comment`');
    if($query->num_rows()){
      $result = $query->result();
      return $result;
    }
  } //end of function 

  function postCommentsFeedDetailsCount($table,$where){//group comment function..
    $this->db->select('comment.commentId');
    $this->db->join('`users` `user`',
      '`comment`.`user_id` = `user`.`userId`',
      'left');
    $this->db->where($where);
     $this->db->where('user.status',1);
    $query = $this->db->get('`comments` `comment`');
    if($query->num_rows()){
      return $query->num_rows();
    }
  } //end of function 


   function select_post($where,$id){
        //select jobs data..with multiple images..
       $existThumb= base_url().UPLOAD_FOLDER.'/profile/';
       $existThumbs= base_url().UPLOAD_FOLDER.'/postImages/';
       $existMedium= base_url().UPLOAD_FOLDER.'/postImages/medium/';
       $existVideoThumbs= base_url().UPLOAD_FOLDER.'/video_thumb/thumb';
        $default   = base_url().PROFILE_DETAIL_DEFAULT;
        $this->db->select(POSTS.'.postId,(case 
            when( posts.video = "" OR posts.video IS NULL) 
            THEN ""
            ELSE
            concat("'.$existThumbs.'",posts.video) 
            END ) as post_video,(case 
            when( posts.video_thumb = "" OR posts.video_thumb IS NULL) 
            THEN ""
            ELSE
            concat("'.$existThumbs.'",posts.video_thumb) 
            END ) as video_thumb,title,description,latitude,longitude,posts.country,state,address,posts.crd as post_created_date,posts.upd');
        $this->db->select(CATEGORIES.'.categoryName');
        $this->db->select(USERS.'.fullname,(case 
            when( users.profileImage = "" OR users.profileImage IS NULL) 
            THEN "'.$default.'"
            ELSE
            users.profileImage 
            END ) as profile_image,userId');
        $this->db->select(LIKES.'.likeId,COUNT(DISTINCT `likes`.`likeId`) as like_count, COALESCE((SELECT likes.status FROM `likes` WHERE `post_id` = `posts`.`postId` AND `user_id` = '.$id.'
        ),0) as user_like_status
       ');
        $this->db->select(COMMENTS.'.commentId,COUNT(DISTINCT `comments`.`commentId`) as comment_count
       ');
        $this->db->select(POST_PERMISSION.'.authorisedToWork,willingToRelocate,whilingToship,post_permissions.email,contact');
        //$this->db->select('CONCAT("'.$existThumb.'",postImage) as post_image');
        $this->db->join(CATEGORIES,'categories.categoryId = posts.category_id');
        $this->db->join(POST_PERMISSION,'post_permissions.post_id = posts.postId');
        $this->db->join(USERS,'users.userId = posts.user_id');
        $this->db->join(LIKES,'`posts`.`postId` = `likes`.`post_id` and likes.status = 1','left');
        $this->db->join(COMMENTS,'`posts`.`postId` = `comments`.`post_id`','left');
        $this->db->where('postId',$where);
        $this->db->group_by('postId');
        //$this->db->group_by('`comments`.`user_id`');
        $query = $this->db->get(POSTS);
        //echo $this->db->last_query();die;
        if($query->num_rows()){
          $query->row()->postimage = $this->getImages($where);
          $dat= $this->getTags($where);
          $datComment= $this->postComments($where);
            if(empty($dat)){
               $query->row()->tags = array();
            }else{
              $query->row()->tags = $dat;
            }
            if(empty($datComment)){
             $query->row()->comments = array();
            }else{
              $query->row()->comments = $datComment;
            }
          //$query->row()->tags = $this->getTags($where);
        $user = $query->row();
        if (!empty($user->profileImage) && filter_var($user->profileImage, FILTER_VALIDATE_URL) === false) {
        $user->profileImage = base_url().UPLOAD_FOLDER.'/profile/'.$user->profileImage;
         }
        return $user;
        }return FALSE;
    }//END OF FUNCTION..

    function getImages($where){//select multiple images.

      $default   = base_url().PROFILE_DETAIL_DEFAULT;
      $existThumb= base_url().UPLOAD_FOLDER.'/postImages/medium/';
      $query = $this->db->select('(case 
            when( post_attachments.attachmentName = "" OR post_attachments.attachmentName IS NULL) 
            THEN ""
            ELSE
            concat("'.$existThumb.'",post_attachments.attachmentName) 
            END ) as post_attachment')->where('post_id',$where)->get(POST_ATTACHMENTS);
      //if($query->num_rows()){
        if($query->num_rows()>0){
         $result = $query->result();
        }else{
          $result= "";
        }
        return $result;
    }//end of function..

   function getTags($where){//select multiple images..
      //$existThumb= base_url().UPLOAD_FOLDER.'/postImages/';
      $this->db->select(TAGS_MAPPING.'.tag_id');
      $this->db->select(TAGS.'.tagName');
      $this->db->join(TAGS,'tags_mapping.tag_id = tags.tagId');
       $this->db->where('post_id',$where);
      $query =  $this->db->get(TAGS_MAPPING);
      if($query->num_rows()){
        $result = $query->result();
        return $result;
      }else{
        return FALSE;
      }
    }//end of function.. 

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
      $this->db->select('comment,comments.crd as comment_crd,`usr`.`fullname`,`usr`.`profileImage`,`usr`.userId as comment_user_id');
      $this->db->join(USERS. ' usr','`usr`.`userId` = `comments`.`user_id`','left');
      $this->db->where($where);
      $this->db->order_by('commentId','DESC');
      $query = $this->db->get(COMMENTS);
     
      if($query->num_rows()){
        $result=  $query->row();
        return $result;
      }
     
    }//end of function
}