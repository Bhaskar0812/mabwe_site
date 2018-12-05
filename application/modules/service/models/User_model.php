<?php 
class User_model extends CI_Model {

  //Generate token for user
  function _generate_token()
  {
    $this->load->helper('security');
    $salt = do_hash(time().mt_rand());
    $new_key = substr($salt, 0, 20);
    return $new_key;
  }

  function getsingle($table, $where = '', $fld = NULL, $order_by = '', $order = ''){

        if ($fld != NULL) {
            $this->db->select($fld);
        }
        $this->db->limit(1);
        if ($order_by != '') {
            $this->db->order_by($order_by, $order);
        }
        if ($where != '') {
            $this->db->where($where);
        }

        $q = $this->db->get($table);
        $num = $q->num_rows();
        if ($num > 0) {
            return $q->row();
        }
    }

  function insert($data,$table){
    $this->db->insert($table,$data);
   // echo $this->db->last_query();
    $last_id = $this->db->insert_id();
    if(!empty($last_id)){
      return $last_id;
    }
    return FALSE;
  }
  function insertData($table,$data){
    $this->db->insert($table,$data);
   // echo $this->db->last_query();
    $last_id = $this->db->insert_id();
    if(!empty($last_id)){
      return $last_id;
    }
    return FALSE;
  }

  function getUserDetailsComment($where,$data='',$table){
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


  function insertLike($data,$table){ 
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
  }

  function getlikeData($where,$post_id){
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
  }

  function postDetail_get($table,$where,$offset,$id,$limit,$start){//this function is used for get group detail
    if($offset == 1){
      $result = $this->select_post($where,$id);
    }else{
       $result = $this->postComments($where,$limit, $start);

    }
    return $result;
  }//end of function 

  function postComments($where,$limit, $start){//group comment function..
    $existThumb = base_url().UPLOAD_FOLDER.'/profile/';
    $default   = base_url().PROFILE_DETAIL_DEFAULT;
    $this->db->select('comment.comment,`comment`.crd as Date,NOW() as currentDate,
     user.fullname,user.country,
    (case 
        when( user.profileImage = "" OR 
        user.profileImage IS NULL) 
        THEN "'.$default.'"
        ELSE
        concat("'.$existThumb.'",
        user.profileImage) 
        END 
     ) as profile_image
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

  function getAll($table){
    $existThumb = base_url().UPLOAD_FOLDER.'/categories/thumb/';
    $default   = base_url().DEFAULT_USER;
    $query = $this->db
    ->select('categoryName,categoryId,(case 
        when( categories.categoryImage = "" OR categories.categoryImage IS NULL) 
        THEN ""
        ELSE
        concat("'.$existThumb.'",categories.categoryImage) 
       END ) as categoryImage')
    ->where('status',1)
    ->get('categories');
    if($query->num_rows()){
      $result = $query->result();
      return $result;
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
      }else
      {
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

  function job_add_post($data,$data1,$table){
    $this->db->insert($table,$data);
    $last_id = $this->db->insert_id();
    if(!empty($last_id)){
      $data1['user_id'] =  $data['user_id'];
      $data1['post_id']  =  $last_id;
      $insert =   $this->db->insert(POST_PERMISSION,$data1);
      if($insert){
        return $last_id;
      }else{
        return FALSE;
      }
    }
    return FALSE;
  }

  function insert_batch($data,$table){
    $result = $this->db->insert_batch($table,$data);
    $last_id = $this->db->insert_id();
    if(!empty($last_id)){
      return array('type'=>'IS');
    }
    return FALSE;
  }

  function registration($data,$table) {// fun for registration.
  $userDetail = array();
  $res = $this->db->select('email')->where(array('email'=>$data['email']))->get($table);
  //check data exist or not
  if($res->num_rows() == 0 ) {
  	if(!empty($data['socialId']) && !empty($data['socialType'])) {// this will check social type
  		$check = $this->db->select('userId')
      ->where(array('socialId'=>$data['socialId'],
        'socialType'=>$data['socialType']))
      ->get($table);
  		//check data exist using social id
  		if($check->num_rows() == 1) {
  			$id=$check->row();
  			//update divice token and type
  			$this->db->where(array('id'=>$id->id));
  			$this->db->update($table,array('authToken'=>$data['authToken'],'deviceToken'=>$data['deviceToken'],'deviceType'=>$data['deviceType']));
  			$userDetail['data'] = $this->userInfo(array('userId'=>$id->userId),$table);					
  			$userDetail['regType'] = 'SL';
  			return $userDetail;
  		} else{
  			if(empty($data['phone'])){
  				return "FT";
  			}
  			//insert data into user table in social registration
  			$this->db->insert($table,$data);//data will be inserted from here.
  			$userId = $this->db->insert_id();
  			//get user detail
  			$userDetail['data'] = $this->userInfo(array('userId'=>$userId),$table);
  			$userDetail['regType'] = 'SR';
  			return $userDetail;			
  		}
  		}
  		//$pwd = $this->random_password(8);
  		$pwd = $data['password'];
  		//hash password using hash algo
  		$data['password']  = password_hash($pwd, PASSWORD_DEFAULT);
  		$this->db->insert($table,$data);
  		$userId = $this->db->insert_id();
  		//check data inserted yes or not
  		if(empty($userId)){
  			return "SGW";
  		}
  		//get user detail from table
  		$userDetail['data'] = $this->userInfo(array('userId'=>$userId),$table);            
            //send mail								
  		$userDetail['regType'] = 'NR';
  		return $userDetail;
  	} else {
  	//check social id or type
  	if(!empty($data['socialId']) && !empty($data['socialType'])) {
  		//get user info using socialid
  		$check = $this->db->select('userId')
      ->where(array('socialId'=>$data['socialId'],'socialType'=>$data['socialType']))
      ->get($table);
  		if($check->num_rows() == 1){
  			$id=$check->row();
  			$this->db->where(array('userId'=>$id->userId));
  			$this->db->update($table,array('authToken'=>$data['authToken'],'deviceToken'=>$data['deviceToken'],'deviceType'=>$data['deviceType']));
  			$userDetail['data'] = $this->userInfo(array('userId'=>$id->userId),$table);
  			$userDetail['regType'] = 'SL';
  			return $userDetail;
  		} else{
  			$userDetail['regType'] = 'AE';
  		}
  	} else{
  		$userDetail['regType'] = 'AE';
  	}
  	$userDetail['regType'] = 'AE';
  }
  return $userDetail;
  }//end of function.

  function userInfo($id,$table){
  $res = $this->db->select('userId,fullName,email,authToken,deviceToken,deviceType,profileImage,COALESCE(profession,"") as profession ,country,status AS user_status')->where($id)->get($table);
  if($res->num_rows()){
    $result = $res->row();
    if (!empty($result->profileImage) && filter_var($result->profileImage, FILTER_VALIDATE_URL) === false) {
      $result->profileImage = base_url().UPLOAD_FOLDER.'/profile/'.$result->profileImage;
    }else{
                  $profileImage = base_url().PROFILE_DETAIL_DEFAULT;
                }
    return $result;
  } else{
    return false;
  }
  }

 

  function getNotification($id,$table){
    $existThumb=base_url().UPLOAD_FOLDER.'/profile/';
  $this->db->select('users.fullName,CONCAT("'.$existThumb.'",profileImage) as profileImage,notifications.id,notifications.notification_by,notifications.notification_for,notifications.is_read,notifications.status,notifications.notification_type,notifications.created_on,notifications.notification_message as notification_category,notifications.crd,NOW() as CurrentTime');
  $this->db->from('users');
  $this->db->join('notifications', 'notifications.notification_by = users.userId');
  $this->db->where($id);
  $this->db->order_by('notifications.crd','DESC');
  $res = $this->db->get();
 // lq($res);
  if($res->num_rows()){
    $result = $res->result();

   
    return array('type'=>"SE",'data'=>$result);
  } else{
    return false;
  }
  }

  function isLogin($data,$authToken,$where,$table){// function for login
  $res = $this->db->select('*')->where($where)->get($table);
  if($res->num_rows() > 0){
  	$result = $res->row();
  	$password = $data['password'];	
  	if(password_verify($password,$result->password)){
  		if($result->status == 1){//if user is active
  				$update_data = array();
  				$update_data['deviceToken'] = $data['deviceToken'];
  				$update_data['deviceType'] = $data['deviceType'];
          $update_data['authToken'] = $authToken;
  				$update_data['forgetPass'] = '';
  			if(!empty($update_data['deviceToken'])){
  				$this->db->update($table,array('deviceToken' => '','forgetPass'   =>''),array('deviceToken'=>$update_data['deviceToken']));
  				$this->db->update($table,$update_data,array('userId'=>$result->userId));
  				$userDetail = $this->userInfo(array('userId'=>$result->userId),$table);
  				return array('type'=>'LS','userDetail'=>$userDetail); //login successfull
  			} else{
  				$this->db->update($table,array('authToken'=>$data['authToken'],'forgetPass'=>''),array('userId'=>$result->userId));
  				$userDetail = $this->userInfo(array('userId'=>$result->userId),$table);
  				return array('type'=>'LS','userDetail'=>$userDetail); //login successfull
  			}
  		} else {
  			return array('type'=>'NA','userDetail'=>array()); // not active
  		}
  	} else {
  		return array('type'=>'WP','userDetail'=>array()); //wrong password
  	}
  } 
  return FALSE;
  }//end of function.

  function forgetPassword($table,$data){ 
  //function for forget password 
      $get = array();
      $this->load->library('Smtp_email');
      //smtp email send library..
      $email = $data['email'];
      $query = $this->db->select('*')->where('email',$email)->get($table);
      if($query->num_rows()==1){
          $getData 		= 	$query->row();
          $get['uId'] 	= 	$getData->uId;
          $temp_pass 		= 	md5(uniqid());
          $userUpdate		= 	$this->db
          ->set(array('forgetPass'=>$temp_pass))
          ->where(array('uId'=>$get['uId']))
          ->update($table);
          if($userUpdate){
              $query = $this->db->select('*')
              ->where('uId',$get['uId'])
              ->get($table);
              if($query->num_rows()==1){
                $dataUser = $query->row();
                $dataSend['name']       = $dataUser->fullName;
                $dataSend['message']    = 'Reset Link for You Email:'.''.$data['email'].' '.'is'.'';
                $dataSend['email']      = $data['email'];
                $dataSend['link']       = base_url()."admin/setPassword/".$dataUser->forgetPass."/".md5($get['uId']);//used md5 for encrypt user id becase it will give always same encode for same digit.
                 $dataSend['browser']   = $_SERVER['HTTP_USER_AGENT'];
                 // this will give browser name and os detail.
                $message = $this->load->view('email/reset_password',$dataSend,TRUE);
                //email template load from this.
                $subject = "Reset password";
                $this->smtp_email->send_mail($data['email'],$subject,$message);
                //email sent to user email id..
                return array('type'=>'ES','userDetail'=>$dataUser);//Email Send.
              } else{
                  return FALSE; //Not Sent
              }
          } 
      }//Email Found on database
      else{
          return array('type'=>'IE','userDetail'=>array()); //Invalid Email
      }
  }//END OF FUNCTION..

 function updateProfile($table,$where,$updateData){//function for update profile.
      if(isset($where)){  
          $query = $this->db->update($table, $updateData, array('userId'=>$where));
          if($query){
            $userDetail   =   $this->userInfo(array('userId'=>$where),$table);//fetch user data from userInfo function.
        return array('type'=>'US','userDetail'=>$userDetail);//update successfully.
          } else{
            return array('type'=>'NU','userDetail'=>array()); //not update.
          }
        } else{
          return FALSE; //we cannt process your request.
        }
    }//End Of FUNCTION

  function update($table,$where,$updateData){//function for update profile.
  	if(isset($where)){	
    		$query = $this->db->update($table, $updateData, $where);
    		//echo $this->db->last_query();die;
    		if($query){
    			$userDetail 	= 	$this->getData($where,$table);//fetch user data from userInfo function.
  		return array('type'=>'US','userDetail'=>$userDetail);//update successfully.
    		} else{
    			return array('type'=>'NU','userDetail'=>array()); //not update.
    		}
    	} else{
    		return FALSE; //we cannt process your request.
    	}
  }//End Of FUNCTION

   function customGet($select,$where,$table){
        //select single data..with given criteria..
        $this->db->select($select);
        $this->db->where($where);
        $query = $this->db->get($table);
        //echo $this->db->last_query();die;
        if($query->num_rows() == 1){
        $user = $query->row();
        return $user;
        }return FALSE;
    }//END OF FUNCTION..

    function getData($table){
        //select single data..with given criteria..
        $this->db->select('*');
        $query = $this->db->get($table);
        //echo $this->db->last_query();die;
        if($query->num_rows()){
        $result = $query->result();
        return $result;
        }return FALSE;
    }//END OF FUNCTION..


    function select_post($where,$id){
        //select jobs data..with multiple images..
       $existThumb= base_url().UPLOAD_FOLDER.'/profile/';
       $existThumbs= base_url().UPLOAD_FOLDER.'/postImages/';
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
            END ) as video_thumb,title,description,latitude,longitude,posts.country,state,address,posts.crd,posts.upd');
        $this->db->select(CATEGORIES.'.categoryName');
        $this->db->select(USERS.'.fullname,(case 
            when( users.profileImage = "" OR users.profileImage IS NULL) 
            THEN "'.$default.'"
            ELSE
            concat("'.$existThumb.'",users.profileImage) 
            END ) as profile_image');
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
            if(empty($dat)){
               $query->row()->tags = array();
            }else{
              $query->row()->tags = $dat;
            }
          //$query->row()->tags = $this->getTags($where);
        $user = $query->result();
        if (!empty($user->profileImage) && filter_var($user->profileImage, FILTER_VALIDATE_URL) === false) {
        $user->profileImage = base_url().UPLOAD_FOLDER.'/profile/'.$user->profileImage;
         }
        return $user;
        }return FALSE;
    }//END OF FUNCTION..

    function getImages($where){//select multiple images.

      $default   = base_url().PROFILE_DETAIL_DEFAULT;
      $existThumb= base_url().UPLOAD_FOLDER.'/postImages/';
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
     // }else{
        //return FALSE;
      //}
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


    function tagSearching($where){//select multiple images..
      //$existThumb= base_url().UPLOAD_FOLDER.'/postImages/';
      $this->db->select('tagName,tagId');
      if(!empty($where)){
       $this->db->like('tagName',$where);
      }
      $query =  $this->db->get(TAGS);
      if($query->num_rows()){
        $result = $query->result();
        return $result;
      }else{
        return FALSE;
      }
    }//end of function..
    function post_get($table,$limit='',$start='',$search='',$filter='',$id){ //get post by using join.
      //pr($start);
      $existThumb= base_url().UPLOAD_FOLDER.'/postImages/';
      $default   = base_url().PROFILE_DETAIL_DEFAULT;
      $this->db->select('`post`.`postId`, 
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
            END ) as post_attachment,
         tag_mapping.tag_id,category.categoryName,
         (SELECT `tagName` FROM `tags` WHERE `tagId`=`tag_id`) as name,tag.tagName
         ,COALESCE((SELECT likes.status FROM `likes` WHERE `post_id` = `post`.`postId` AND `user_id` = '.$id.'
        ),0) as user_like_status
       '); 
      $this->db->join('`post_attachments` `post_attachment`'
        ,'`post`.`postId` = `post_attachment`.`post_id`','left');
      $this->db->join('`likes` `like`'
        ,'`post`.`postId` = `like`.`post_id` and like.status = 1','left');
      $this->db->join('`comments` `comment`'
        ,'`post`.`postId` = `comment`.`post_id`','left');
      $this->db->join('`categories` `category`'
        ,'`post`.`category_id` = `category`.`categoryId`','left');
      //
      $this->db->join('`tags_mapping` `tag_mapping`'
        ,'`post`.`postId` = `tag_mapping`.`post_id`','left');
      $this->db->join('`tags` `tag`'
        ,'`tag_mapping`.`tag_id` = `tag`.`tagId`','left');
      //$this->db->order_by('like_count','desc');
      $this->db->order_by('postId','desc');
      $this->db->group_by('`post`.`postId`');
      if(!empty($search)){
      $this->db->group_start();
      if(!empty($search)){//searchng with tag will be done from here....
         $ser = explode(',',$search);
         foreach($ser as $values){
          $this->db->like('tagName',$values);
          $this->db->or_like('title',$values);
         }
      }
      $this->db->group_end();
    }
    if(!empty($filter)){
      $this->db->group_start();
      if(!empty($filter)){//searchng with tag will be done from here....
         $ser = explode(',',$filter);
         foreach($ser as $values){
          //$this->db->like('category_id',$values);
          $this->db->or_like('category_id',$values);
         }
      }
      $this->db->group_end();
    }
      if($limit!='' && $start!=''){
        $this->db->limit($limit, $start);
      }
      $query = $this->db->get('`posts` `post`'); //lq();
      if($query->num_rows()){
        $result = $query->result();
       // pr($result);
        return $result;
      }
      return FALSE;
    }//end of function...
    function getCount($where,$table){
      $this->db->select('count(post_id) as count');
        $this->db->where('post_id',$where);
        $this->db->where('status',1);
        $this->db->group_by('post_id');
        $this->db->order_by('count','desc');
        $query = $this->db->get($table);
        if($query->num_rows()){
        $result = $query->row();
        if($result->count == 0){
          $result->count = 0;
        }
        return $result;
        }return FALSE;
    }


  }//end of class..

