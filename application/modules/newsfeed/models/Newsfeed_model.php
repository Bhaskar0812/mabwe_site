<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newsfeed_model extends CI_Model {

    function getPost($table,$search='',$filter='',$id,$start='',$limit=''){ //get post by using join.
      $existThumb= base_url().UPLOAD_FOLDER.'/postImages/';
      $default   = base_url().PROFILE_DETAIL_DEFAULT;
      $this->db->select('users.*,`post`.*,`post`.`crd` as created_post ,
        `post`.`title`,(case 
            when( post.video = "" OR post.video IS NULL) 
            THEN ""
            ELSE
            concat("'.$existThumb.'",post.video) 
            END ) as video,(case 
            when( post.video_thumb = "" OR post.video_thumb IS NULL) 
            THEN ""
            ELSE
            concat("'.$existThumb.'",post.video_thumb) 
            END ) as video_thumb, 
        `post`.`description`,
        `post`.`category_id`,
         DATE_FORMAT(`post`.crd, "%d/%m/%Y %H:%m:%s") as Crd, 
         DATE_FORMAT(`post`.upd, "%d/%m/%Y %H:%m:%s") as Upd, 
         `post`.`country`,NOW() as CurrentTime,
         `post`.`address`,
         `post`.`state`, 
         COUNT(DISTINCT `like`.`likeId`) as like_count, 
         COUNT(DISTINCT `comment`.`commentId`) as comment_count,
         (case 
            when( post_attachment.attachmentName = "" OR post_attachment.attachmentName IS NULL) 
            THEN ""
            ELSE
            concat("'.$existThumb.'",post_attachment.attachmentName) 
            END ) as post_attachment,(case 
            when( post_attachment.attachmentName = "" OR post_attachment.attachmentName IS NULL) 
            THEN ""
            ELSE
           post_attachment.attachmentType 
            END ) as atachment_type,
         tag_mapping.tag_id,category.categoryName,
         (SELECT `tagName` FROM `tags` WHERE `tagId`=`tag_id`) as name,tag.tagName
         ,COALESCE((SELECT likes.status FROM `likes` WHERE `post_id` = `post`.`postId` AND `user_id` = '.$id.'
        ),0) as user_like_status
       '); 
      $this->db->join('`post_attachments` `post_attachment`','`post`.`postId` = `post_attachment`.`post_id`','left');
      $this->db->join('`likes` `like`','`post`.`postId` = `like`.`post_id` and like.status = 1','left');
      $this->db->join('`comments` `comment`','`post`.`postId` = `comment`.`post_id`','left');
      $this->db->join('`categories` `category`','`post`.`category_id` = `category`.`categoryId`','left');
      //
      $this->db->join('`tags_mapping` `tag_mapping`','`post`.`postId` = `tag_mapping`.`post_id`','left');
      $this->db->join('`tags` `tag`','`tag_mapping`.`tag_id` = `tag`.`tagId`','left');
      $this->db->join('`users`','`post`.`user_id` = `users`.`userId`','left');
      //$this->db->order_by('like_count','desc');
      $this->db->order_by('postId','desc');
      $this->db->group_by('`post`.`postId`');
      if(!empty($limit) OR  $start){
        $this->db->limit($limit, $start);
      }
      if(!empty($search)){
      $this->db->group_start();
      if(!empty($search)){//searchng with tag will be done from here....
         $set = explode(' ',$search);
         foreach($set as $values){
          $this->db->like('tagName',$values);
          $this->db->or_like('title',$values);
         }
      }
      $this->db->group_end();
    }
    if(!empty($filter)){
      $this->db->group_start();
         foreach($filter as $values){
          //$this->db->like('category_id',$values);
          $this->db->or_like('category_id',$values);
         }
      $this->db->group_end();
    }
    $this->db->where('users.status',1);

    $query = $this->db->get('`posts` `post`'); //lq();
      if($query->num_rows()){

        /*$result['tags'] = $this->getTags();*/
        $result = $query->result();
        foreach($result as $key=>$value){
          $result[$key]->images = $this->getPostImages($value->postId);
          $result[$key]->tags_name = $this->getTags($value->postId);
          $result[$key]->comment = $this->getComments($value->postId);
          $result[$key]->comment_count = $this->getCommentsCount($value->postId);

          $result[$key]->like_count = $this->getLikesCount($value->postId);
         

        }
      
        return $result;
      }
      return FALSE;
    }//end of function...
    function getPostImages($where){
      $get = array('post_id'=>$where,'attachmentType'=>'image');
      $query = $this->db->select('attachmentName','attachmentType')->where($get)->get(POST_ATTACHMENTS);
      if($query->num_rows()){
        $result=  $query->result();
        return $result;
      }
    }

    function getComments($where){
      $get = array('post_id'=>$where);
      $this->db->select('comment,comments.crd as comment_crd,`usr`.`fullname`,`usr`.`profileImage`,`usr`.userId as comment_user_id');
      $this->db->join(USERS. ' usr','`usr`.`userId` = `comments`.`user_id`','left');
      $this->db->where($get);
      $this->db->order_by('commentId','DESC');
      $this->db->limit(1,0);
      $query = $this->db->get(COMMENTS);
     
      if($query->num_rows()){
        $result=  $query->result();
        return $result;
      }
    } 

    function getCommentsCount($where){//get comment count from here
      $get = array('post_id'=>$where);
      $query = $this->db->select('commentId')->where($get)->get(COMMENTS);
      if($query->num_rows()){
        $result=  $query->num_rows();
        return $result;
      }else{
       return 0; 
      }
    }//end of function


    function getLikesCount($where){//get likes count what ever liked by users
      $get = array('post_id'=>$where,'likes.status'=>1);
       $this->db->select('`likes`.`likeId`,`usr`.`userId`,`usr`.`profileImage`,`usr`.`country`');
      $this->db->join(USERS.' as usr','`usr`.`userId` = `likes`.`user_id`','left');
      $this->db->where($get);
       $this->db->where('usr.status',1);
      $query = $this->db->get(LIKES);
      if($query->num_rows()){
        $result=  $query->num_rows();
        return $result;
      }else{
       return 0; 
      }
    }//end of function
    

    function getTags($where){//get tags from database for posts
      $get = array('post_id'=>$where);
      $this->db->select('`tags`.`tagName`,`tags_mapping`.`tag_id`');
      $this->db->join(TAGS_MAPPING,'`tags`.`tagId` = `tags_mapping`.`tag_id`');
      $this->db->where($get);
      $query = $this->db->get(TAGS);
      if($query->num_rows()){
        $result=  $query->result();
        return $result;
      }
    }//end of function

    function getCount($table,$where){//get Count function for pagination
      $this->db->select('*');
      $this->db->where($where);
      $query = $this->db->get($table);
      if($query->num_rows()){
        return $query->num_rows();
      }
      return 0;
    }//end of function

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

    function insertLike($data,$table){ //like and unlike will handle from here
      $user_id = $data['user_id'];
      $post_id = $data['post_id'];
      $query = $this->db->select('likeId,post_id,status')
      ->where(array('post_id'=>$post_id,'user_id'=>$user_id))
      ->get(LIKES);
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
      $this->db->insert($table,$data);
     // echo $this->db->last_query();
      $last_id = $this->db->insert_id();
      if(!empty($last_id)){
        $res =  $this->getlikeData(array('likeId'=>$last_id),$data);
        return array('type'=>'LIKE','data'=>$res);
      }
      return FALSE;
      }
    }//end of function

  function getlikeData($where,$post_id){//get Like data using this function
    //$post_id = $where['post_id']; 
    $query = $this->db->select('`likes`.`post_id`,
      `likes`.`user_id`,
      `likes`.`status`,
      (
        SELECT COUNT(`likeId`) from `likes` 
        WHERE post_id ='.$post_id['post_id'].' 
        AND `status`= 1
      ) as like_count')
    ->where($where)
    ->get('likes');
    if($query->num_rows()){
      $result = $query->row();
      return $result;
    }
    return FALSE;
  }//end of function

  function likedUser($table,$where,$start,$limit){
      $this->db->select('`likes`.`likeId`,`likes`.`user_id`,`likes`.`post_id`,`usr`.`fullName`,`usr`.`userId`,`usr`.`profileImage`,`usr`.`country`');
      $this->db->join(USERS.' as usr','`usr`.`userId` = `likes`.`user_id`','left');
      $this->db->where($where);
       $this->db->where('usr.status',1);
      $this->db->order_by('`likes`.`upd`','desc');
      $this->db->limit($limit, $start);
      $query = $this->db->get($table);
      if($query->num_rows()){
        return $query->result();
      }
      return FALSE;
  }

  public function getCountLikes($table,$where){
       $this->db->select('`likes`.`likeId`,`likes`.`user_id`,`likes`.`post_id`,`usr`.`fullName`,`usr`.`userId`,`usr`.`profileImage`,`usr`.`country`');
      $this->db->join(USERS.' as usr','`usr`.`userId` = `likes`.`user_id`','left');
      $this->db->where($where);
       $this->db->where('usr.status',1);
      $this->db->order_by('`likes`.`upd`','desc');
     $query = $this->db->get($table);
        if($query->num_rows()){
            return $query->num_rows();
        }
        return 0;
    }

}//End of Class