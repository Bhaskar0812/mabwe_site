<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CommonService {

  function __construct(){
    parent::__construct();
     $this->load->model('user_model');
     $this->load->model('image_model');
     $this->load->model('group_model');
  }
    function updateUserProfile_post(){//update user profile function
    	$this->check_service_auth();
    	$this->form_validation->set_rules('fullName', 'Full name', 'trim|required|callback__alpha_spaces_check');
      $this->form_validation->set_rules('country', 'Country', 'trim|required');
      $this->form_validation->set_rules('profession', 'Profession', 'trim|required');
    	if ($this->form_validation->run() == FALSE){
    		$responseArray = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
    		$response = $this->generate_response($responseArray);
    		$this->response($response);
          }//foreach end..
          else{
          	$response = array();
          	if(!empty($_FILES['profileImage']['name'])){ 
          		$this->load->model('image_model');   
          		$upload                      =   $_FILES['profileImage']['name'];
          		$imageName                   =   'profileImage';
          		$folder                      =   "profile";
              $response['image']           =   $this->image_model->updateMedia($imageName,$folder);//IMAGE UPLOAD.
          }if(!empty($response['image']) && is_array($response['image'])):
          $responseArray = array('status'=>FAIL,'message'=> $response['image']['error']);
          $response = $this->generate_response($responseArray);
          $this->response($response);
          endif;
          $dataUpdate = array();
          $image                      =   isset($response['image'])?($response['image']):"";
          $table                      =   USERS;
          $dataUpdate['fullname']     =   $this->input->post('fullName');
          $dataUpdate['country']      =   $this->input->post('country');
          $dataUpdate['profession']   =   $this->input->post('profession');

          if(!empty($image)){
          	$dataUpdate['profileImage'] =   $image;
          }
          $res = $this->user_model->updateProfile($table,$this->authData->userId,$dataUpdate);
          if(is_string($res['type']) && $res['type'] == 'US'){
          	$response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(108),'data'=>$res['userDetail']);
          } elseif(is_string($res) && $res == 'NU'){
          	$response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(138));
          } else{
          	$response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(147));  
          } 
          $this->response($response);
      }
    }//end of fun

    function numeric_wcomma ($str){ //this function will check the numeric value or not.
    	if(preg_match('/^[0-9,]+$/', $str)){
    		return TRUE;
    	}
    	else{
    		$this->form_validation->set_message('numeric_wcomma','The Telephone field must contain only numbers.');
    		return FALSE;
    	}
    }

    function addCategories_post(){ //this is not in use it is only used to insert insert category into database. 
      if(!empty($_FILES['photo']['name'])){ 
          $this->load->model('image_model');   
          $upload                      =   $_FILES['photo']['name'];
          $imageName                   =   'photo';
          $folder                      =   "categories";
          $dataImage['profile']        =   $this->image_model->updateMedia($imageName,$folder);//IMAGE UPLOAD.
          }if(!empty($dataImage['profile']) && is_array($dataImage['profile'])):
          $responseArray = array('status'=>FAIL,'message'=> $dataImage['profile']['error']);
          $response = $this->generate_response($responseArray);
          $this->response($response);
        endif;
        $userData['categoryName']  = $this->post('category');
        $userData['categoryImage'] = $dataImage['profile'];
        $isRegister = $this->user_model->insert($userData,CATEGORIES);
    }//end of function

    function _alpha_spaces_check($string){
      if(alpha_spaces($string)){
        return true;
      } else{
        $this->form_validation->set_message('_alpha_spaces_check','Only alphabets and spaces are allowed in {field} field');
        return FALSE;
      }
    }//end of fun.

    function user_get(){//select data of loggedin user.
    // i inserted table name from here that will make it common for all tables.
      $this->check_service_auth();
      $table = USERS;
      $where = array('userId'=>$this->authData->userId,'status'=>1);
      $res = $this->user_model->userInfo($where,$table);
      if(!empty($res)){
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(157),'data'=>$res);
      }else{
        $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(158));
      }
      $this->response($response);
    }//end of functions


    function tags_post(){//select data of loggedin user.
    // i inserted table name from here that will make it common for all tables.
      $this->check_service_auth();
      $table = TAGS;
      //$where = array('userId'=>$this->authData->userId,'status'=>1);\
      if(!empty($this->post('search')))
      {
        $searching = $this->post('search');
      }
      else
      {
        $searching ='';
      }
      $res = $this->user_model->tagSearching($searching);
      if(!empty($res))
      {
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(159),'count'=>count($res),'data'=>$res);
      }
      else
      {
        $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(158));
      }
      $this->response($response);
    }//end of functions

    function categories_get(){//select data of loggedin user.
    // i inserted table name from here that will make it common for all tables.
      $this->check_service_auth();
      $table = CATEGORIES;
      $res = $this->user_model->getAll($table);
      if(!empty($res)){
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(161),'count'=>count($res),'data'=>$res);
      }else{
        $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(158));
      }
      $this->response($response);
    }//end of functions
    
    // add Job post by user
    function addPost_post(){
    
        log_event(json_encode($_POST));
        $this->check_service_auth();

        $this->form_validation->set_rules('title', 'Job title', 'trim|required');
        //$this->form_validation->set_rules('tags', 'Tags', 'trim|required');
        $this->form_validation->set_rules('discription', 'Description', 'trim|required');
        
        
        if ($this->form_validation->run() == FALSE){
            //form validations failed
            log_event(json_encode($_POST));
            $responseArray = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
            $this->response($responseArray);
        }
        
        $insertJob = array();
        $insertJob['title']                =   $this->input->post('title');
        $insertJob['description']          =   $this->input->post('discription');
        $insertJob['category_id']          =   $this->input->post('category');
        $insertJob['latitude']             =   $this->input->post('lat');
        $insertJob['longitude']            =   $this->input->post('long');
        $insertJob['longitude']            =   $this->input->post('long');
        $insertJob['country']              =   $this->input->post('country');
        $insertJob['city']                 =   $this->input->post('city');
        $insertJob['state']                =   $this->input->post('state');
        $insertJob['address']              =   $this->input->post('address');
        $insertJobs['willingToRelocate']   =   $this->input->post('relocate');
        $insertJobs['authorisedToWork']    =   $this->input->post('authorised');
        $insertJobs['whilingToship']       =   $this->input->post('whilingToship');
        $insertJobs['email']               =   $this->input->post('email');
        $insertJobs['contact']             =   $this->input->post('contact');
        $insertJob['user_id']             =   $this->authData->userId;

        //log_event(json_encode($insertJob));
            
        //check video and video thumb
        if(!empty($_FILES['video']) && !empty($_FILES['video']['name']) && !empty($_FILES['video_thumb']['name'])){ 
            
            // pr($_FILES['images']['name'][0]);
            //process video upload
            $this->load->model('upload_media_model');   
            $upload                      =   $_FILES['video']['name'];
            $imageName                   =   'postImages';
            $folder                      =   "postImages";
            $_FILES[$imageName]['name']       = $_FILES['video']['name'];
            $_FILES[$imageName]['type']       = $_FILES['video']['type'];
            $_FILES[$imageName]['tmp_name']   = $_FILES['video']['tmp_name'];
            $_FILES[$imageName]['error']      = $_FILES['video']['error'];
            $_FILES[$imageName]['size']       = $_FILES['video']['size'];
            $response['images'] =   $this->upload_media_model->upload_video($imageName,$folder);//IMAGE UPLOAD.
            
            if(!empty($response['images']) && is_array($response['images'])){
                $responseArray = array('status'=>FAIL,'message'=> $response['images']['error']);
                $this->response($responseArray);
            }
                  
            $image = isset($response['images'])? ($response['images']):"";
            $table = USERS;
            if(!empty($image)){
                $insertJob['video'] =   $image;
            }

            //process video thumb upload
            $responses = array();
            $this->load->model('image_model');   
            $uploads                      =   'video_thumb';
            $imageNames                   =   'postImages';
            $folders                      =   "postImages";
            $responses['image']           =   $this->image_model->updateMedia('video_thumb',$folders);//IMAGE UPLOAD.
            
            if(!empty($responses['image']['error'])):
              $responseArray = array('status'=>FAIL,'message'=> $responses['image']['error']);
              $this->response($responseArray);
            endif;
              
            //$insertJob = array();
            $images                       =   isset($responses['image'])?($responses['image']):"";
            $table                        =   USERS;
            if(!empty($image)){
              $insertJob['video_thumb']   =   $images;
            }
        }
        
        //create post
        $res = $this->user_model->job_add_post($insertJob, $insertJobs, POSTS);
        if(empty($res)){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(152));
            $this->response($response);
        }
        
        if(!empty($this->input->post('tags'))){
            
          $tags = explode(',', $this->input->post('tags'));
          $insertTag = array();
          
          foreach($tags as $key =>$values){
            $insertTag[$key]['tagName'] =   $values;
            $post = $res;
          }
          
          //check tag exist or not if not exist insert it into databse and mapp it.
          $this->user_model->check_tags($insertTag,$post,TAGS);
        }
        
        
          
        if(empty($_FILES['video']) && !empty($_FILES['images'])){
            
            $response = array();
            $files = $_FILES['images'];
            $filesCount = count($_FILES['images']['name']);
            $this->load->model('image_model');   
            $upload           =   'images';
            $imageName        =   'images';
            $folder           =   "postImages";
            
            for($i = 0; $i < $filesCount; $i++){
                
                $_FILES[$imageName]['name']       = $files['name'][$i];
                $_FILES[$imageName]['type']       = $files['type'][$i];
                $_FILES[$imageName]['tmp_name']   = $files['tmp_name'][$i];
                $_FILES[$imageName]['error']      = $files['error'][$i];
                $_FILES[$imageName]['size']       = $files['size'][$i];
                
                //upload each image and insert data in attachemnt table
                $response[] = $this->image_model->updateMedia($imageName, $folder);
                
                if (!empty($response) && is_array($response[$i])){
                    //upload failed for some reason, so unset that key
                  $response = array('status'=>FAIL,'message'=> $response[$i]['error']);
                  unset($response[$i]);
                  $this->response($response);

                }
                //pr($response);
            }
            

            if(!empty($response)){
                
                $dataImage = array(); $k = 0;
                foreach($response as $key => $value){
                    
                    if(isset($value)){ 
                        $dataImage[$k]['attachmentName'] = $value;
                        $dataImage[$k]['post_id']   = $res;
                        $k++;
                    }
                }
              
                if(!empty($dataImage)){
                    $resp = $this->user_model->insert_batch($dataImage,POST_ATTACHMENTS);
                }
            }
        }
        
        //get last created post details
        $id = $this->authData->userId; 
        $post_detail = $this->user_model->select_post($res, $id);
        
        if($post_detail){
          $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(151),'data'=>$post_detail);
        } else{
          $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(147));
        } 
        $this->response($response);
        
    }//end of functions


  function likes_post(){// add like or dislike on post user
      $this->check_service_auth();
      $this->form_validation->set_rules('post_id', 'Post_id','trim|required');
      if ($this->form_validation->run() == FALSE){
        $responseArray = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
        $response = $this->generate_response($responseArray);
        $this->response($response);
      }//validation if end here...
        else{
          $insertLike = array();
          $insertLike['post_id']             =   $this->input->post('post_id');
          $insertLike['user_id']             =   $this->authData->userId;
          $res = $this->user_model->insertLike($insertLike,LIKES);
          //pr($res);
            if($res['type'] == 'LIKE'){
              $user_info = $this->user_model->getUserDetailsComment(array('postId'=>$insertLike['post_id']),array('user_id'),POSTS);
              $userId = $this->authData->userId;
            //echo $userId;
            //pr($user_info->userId);
            if(!empty($user_info) AND  $userId != $user_info->userId){
              //echo "hello";
                $registrationIds = $user_info->deviceToken; 
                $title = $user_info->categoryName; 
                $body_send = $this->authData->fullname.' '."liked on your post."; 
                $notif_type = "mabwe_Like"; 
                $notif_id = $insertLike['post_id']; 
                if (!empty($user_info->profileImage) && filter_var($user_info->profileImage, FILTER_VALIDATE_URL) === false) {
                  $profileImage = base_url().UPLOAD_FOLDER.'/profile/'.$user_info->profileImage;
                }else{
                  $profileImage = base_url().PROFILE_DETAIL_DEFAULT;
                }
                $is_notify = $this->send_push_notification(array($registrationIds),$title,$body_send,$notif_type,$userId,$profileImage,$notif_id);
                //pr($is_notify);
                $data = array('notification_by'=>$this->authData->userId,'notification_for'=>$user_info->userId,'notification_message'=>json_encode($is_notify),'notification_type'=>$notif_type);
                $this->notification_model->save_notification(NOTIFICATIONS,$data);
            }
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(166),'data'=>$res['data']);
            }elseif($res['type'] == 'UNLIKE'){
              //echo "hello";
              $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(167),'data'=>$res['data']); 
            }else{
              $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(147)); 
            }

          //}
          $this->response($response);
        }
  }//end of functions

  function comment_post(){
    $this->check_service_auth();
      $this->form_validation->set_rules('post_id', 'Post_id', 'trim|required');
      $this->form_validation->set_rules('comment', 'Comment', 'trim|required');
      if ($this->form_validation->run() == FALSE){
        $responseArray = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
        $response = $this->generate_response($responseArray);
        $this->response($response);
      }//validation if end here...
        else{
          $insertComment= array();
          $insertComment['post_id']  =   $this->input->post('post_id');
          $insertComment['comment']  =   $this->input->post('comment');
          $insertComment['user_id']  =   $this->authData->userId;

          $res = $this->user_model->insert($insertComment,COMMENTS);
          
          if($res){
          $user_info = $this->user_model->getUserDetailsComment(array('postId'=>$insertComment['post_id']),array('user_id','postId'),POSTS);
          $userId = $this->authData->userId;
          if(!empty($user_info) AND  $userId != $user_info->userId){
          $registrationIds = $user_info->deviceToken; 
          $title = $user_info->categoryName;
          $notify_id = $insertComment['post_id']; 
          $body_send = $this->authData->fullname.' '."commented on your post."; 
          $notif_type = "mabwe_comment"; 
          if (!empty($user_info->profileImage) && filter_var($user_info->profileImage, FILTER_VALIDATE_URL) === false) {
                  $profileImage = base_url().UPLOAD_FOLDER.'/profile/'.$user_info->profileImage;
                }else{
                  $profileImage = base_url().PROFILE_DETAIL_DEFAULT;
                }
          $is_notify = $this->send_push_notification(array($registrationIds),$title,$body_send,$notif_type,$userId,$profileImage,$notify_id);
          //pr($is_notify);
         // pr($is_notify);
          $data = array('notification_by'=>$this->authData->userId,'notification_for'=>$user_info->userId,'notification_message'=>json_encode($is_notify),'notification_type'=>$notif_type);
          $this->notification_model->save_notification(NOTIFICATIONS,$data);
          }
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(173));
          }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(147)); 
          }
          $this->response($response);
        }
  }

  

  function post_post(){//this function is used for add group..
   
      $this->check_service_auth();
        $limit = !empty($this->post('limit')) ? $this->post('limit') : '';
        $start = !empty($this->post('start')) ? $this->post('start') : 0;
        $search = !empty($this->post('search'))?$this->post('search'): '';
        $filter = !empty($this->post('filter'))?$this->post('filter'): '';
        //$category = explode(" ",$filter);
        //echo $filter;die;
        $id = $this->authData->userId;
      //$search = $this->post('search');
      $table = USERS;
      $res = $this->user_model->post_get($table,$limit,$start,$search,$filter,$id);
      //print_r($res);
      if(!empty($res)){
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(165),'count'=>count($res),'data'=>$res);
      }else{
        $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(158));
      }
      $this->response($response);
    }//end of functions

  function postDetail_post(){//get post detail by calling this function. passed only post id.
      $this->check_service_auth();
      $where =  $this->post('post_id');
      $id =  $this->authData->userId;
      $table = USERS;
      $res = $this->user_model->select_post($where,$id);
      //print_r($res);
      if(!empty($res)){
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(165),'count'=>count($res),'data'=>$res);
      }else{
        $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(527));
      }
      $this->response($response);
  }//end of functions

    function updateGroups_post(){// Make Group by user
        $insertDataP = array();
        $insertDataT = array();
        
        $this->form_validation->set_rules('group_id', 'Group Id', 'required');
        $insertData = array();
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }else{
            $response = array();
           // print_r($_FILES['groupImage']['name']);die;
            if(!empty($_FILES['groupImage']['name'])){ 
              $this->load->model('image_model');   
              $upl                          =   $_FILES['groupImage']['name'];
              $imageName                    =   'groupImage';
              $folder                       =   'group';
              $response['image']            =   $this->image_model->updateMedia('groupImage',$folder);//IMAGE UPLOAD..
            }if(!empty($response['image']) && is_array($response['image'])):
            $responseArray = array('status'=>FAIL,'message'=> $response['image']['error']);
            $response = $this->generate_response($responseArray);
            $this->response($response);
            endif;
            $insertData = array();
            $image                      =   isset($response['image'])?($response['image']):"";
            $insertData['groupName']    =   $this->post('groupName');
            if(!empty($image)){
              $insertData['groupImage'] =   $image;
            }
            $groupId = $this->post('group_id');
            $where  = array('groupId'=>$groupId);
            $response = $this->group_model->updateFields(GROUPS,$where,$insertData);//UPDATE GROUP NAME AND GROUP IMAGE
            $groupId = $this->post('group_id');
            $tagName = $this->post('tagName');
            //pr($tagName);
            $tagName = explode(',', $tagName);
            $dbTags = $this->group_model->getAllTags();
            $dbTagsExp = explode(',', $dbTags->tagName);
            $commonTags = array_intersect($tagName, $dbTagsExp);//common data
            $insertDataP = array_diff($tagName,$commonTags);//different data
           //pr($insertData);
            if(!empty($insertDataP)){ 
                      $insertDataP = array_values($insertDataP);
                      for($i=0; $i < count($insertDataP); $i++){
                          $insertTag['tagName'] = $insertDataP[$i];
                          $response = $this->common_model->insert_data(TAGS,$insertTag);
                          //print_r($response);
                      }
                    
            } 
            $tagId = $this->group_model->getTagsId($tagName);
            $tagsId = explode(',', $tagId->tagId);
            $dbMapdata = $this->group_model->getGroupId(array('group_id'=>$groupId));
            $dbMapdatas = explode(',', $dbMapdata->tagId);
            if(!empty($dbMapdata)){
                $commonTags = array_intersect($tagsId, $dbMapdatas);//common data
                $removeData = array_diff($dbMapdatas, $commonTags);//different data
                $insertDataT = array_diff($tagsId, $commonTags);//different data
               // print_r($insertDataT);die();

            }
            //pr($insertDataT[0]);
            if(!empty($removeData)){
                $where = array('group_id'=>$groupId);
                $this->db->where_in('tag_id',$removeData);
                $this->db->delete('group_tag_mapping',$where);
            }
            if(!empty($insertDataT)){
                $insertDataT = array_values($insertDataT);
                for($i=0; $i < count($insertDataT); $i++){
                  $insertT = array();
                    $insertT['tag_id'] = $insertDataT[$i];
                    $insertT['group_id'] = $this->post('group_id');
                    //$insertsData['group_id'] = $insertData[$i];
                    $resp1 = $this->common_model->insert_data('group_tag_mapping',$insertT);
                    //print_r($resp1);
                    if($resp1){
                    $ans = $this->common_model->getData(array('tagId'=>$insertT['tag_id'])); 
                    }
                  // print_r($ans);
                }
            }

            
 
            //print_r($response);
            if($response){
              $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(523),$response['data']);
            
            }else{
              $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(524)); 
            }
            $this->response($response);
                     
      }
  }

  function updateGroups1_post(){// Make Group by user
        $this->form_validation->set_rules('group_id', 'Group Id', 'required');
        $insertData = array();
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }else{
            $response = array();
           // print_r($_FILES['groupImage']['name']);die;
            if(!empty($_FILES['groupImage']['name'])){ 
              $this->load->model('image_model');   
              $upl                          =   $_FILES['groupImage']['name'];
              $imageName                    =   'groupImage';
              $folder                       =   'group';
              $response['image']            =   $this->image_model->updateMedia('groupImage',$folder);//IMAGE UPLOAD..
            }if(!empty($response['image']) && is_array($response['image'])):
            $responseArray = array('status'=>FAIL,'message'=> $response['image']['error']);
            $response = $this->generate_response($responseArray);
            $this->response($response);
            endif;
            $insertData = array();
            $image                      =   isset($response['image'])?($response['image']):"";
            $insertData['groupName']    =   $this->post('groupName');
            if(!empty($image)){
              $insertData['groupImage'] =   $image;
            }
            $groupId = $this->post('group_id');
            $where  = array('groupId'=>$groupId);
            $response = $this->group_model->updateFields(GROUPS,$where,$insertData);//UPDATE GROUP NAME AND GROUP IMAGE
            $groupId = $this->post('group_id');
            $tagName = $this->post('tagName');
            //pr($tagName);
            $tagName = explode(',', $tagName);
            $dbTags = $this->group_model->getAllTags();
            $dbTagsExp = explode(',', $dbTags->tagName);
            $commonTags = array_intersect($tagName, $dbTagsExp);//common data
            $insertDataP = array_diff($tagName,$commonTags);//different data
           //pr($insertData);
            if(!empty($insertDataP)){ 
                      $insertDataP = array_values($insertDataP);
                      for($i=0; $i < count($insertDataP); $i++){
                          $insertTag['tagName'] = $insertDataP[$i];
                          $response = $this->common_model->insert_data(TAGS,$insertTag);
                      }
                    
            } 
            $tagId = $this->group_model->getTagsId($tagName);
            $tagsId = explode(',', $tagId->tagId);
            $dbMapdata = $this->group_model->getGroupId(array('group_id'=>$groupId));
            $dbMapdatas = explode(',', $dbMapdata->tagId);
            if(!empty($dbMapdata)){
                $commonTags = array_intersect($tagsId, $dbMapdatas);//common data
                $removeData = array_diff($dbMapdatas, $commonTags);//different data
                $insertDataT = array_diff($tagsId, $commonTags);//different data
            }
            //pr($insertDataT[0]);
            if(!empty($removeData)){
                $where = array('group_id'=>$groupId);
                $this->db->where_in('tag_id',$removeData);
                $this->db->delete('group_tag_mapping',$where);
            }
            if(!empty($$insertDataT[0])){
                //$insertDataT = array_values($insertDataT);
                for($i=0; $i < count($insertDataT); $i++){
                    $insertDataT['tag_id'] = $insertDataT[$i];
                    $insertDataT['group_id'] = $this->post('group_id');
                    //$insertsData['group_id'] = $insertData[$i];
                    $response = $this->common_model->insert_data('group_tag_mapping',$insertDataT); 
                }
            }

            if($response){
              $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(523),'data'=>$response['data']);
            
            }else{
              $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(524)); 
            }
            $this->response($response);
                     
      }
  }//end of functions
    
  function group_post(){// Make Group by user
      $this->check_service_auth();
      $this->form_validation->set_rules('group', 'Group Name', 'trim|required');
      $this->form_validation->set_rules('category_id', 'Category Name', 'trim|required');
      //$this->form_validation->set_rules('tags', 'Tag Name', 'trim|required');
      if ($this->form_validation->run() == FALSE){
        $responseArray = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
        $response = $this->generate_response($responseArray);
        $this->response($response);
      }//validation if end here...
        else{
          $response = array();
            if(!empty($_FILES['groupImage']['name'])){ 
              $this->load->model('image_model');   
              $upload                      =   $_FILES['groupImage']['name'];
              $imageName                   =   'groupImage';
              $folder                      =   "group";
              $response['image']           =   $this->image_model->updateMedia($imageName,$folder);//IMAGE UPLOAD.
          }if(!empty($response['image']) && is_array($response['image'])):
          $responseArray = array('status'=>FAIL,'message'=> $response['image']['error']);
          $response = $this->generate_response($responseArray);
          $this->response($response);
          endif;
          $insertData = array();
          $image                      =   isset($response['image'])?($response['image']):"";
          $table                      =   USERS;
          $insertData['groupName']    =   $this->post('group');
          $insertData['category_id']  =   $this->post('category_id');
          $insertData['user_id']      =   $this->authData->userId;
          if(!empty($image)){
            $insertData['groupImage'] =   $image;
          }

          $res = $this->group_model->addGroup($insertData,GROUPS);
          $tags = explode(',', $this->input->post('tags'));
          if(!empty($this->input->post('tags'))){
          $insertTag = array();
          if($res['type'] == 'SC' OR $res['type'] =="AM"){
          foreach($tags as $key =>$values){
            $insertTag[$key]['tagName'] =   $values;
            $post = $res['id'];
          }
          $this->group_model->check_tags($insertTag,$post,TAGS);
          }
          }
          if($res['type'] == 'SC'){
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(183),'data'=>$res['data']);
          }elseif($res['type'] == 'AM'){
             $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(187));
          }elseif($res['type'] == 'GF'){
             $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(188));
          }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(184)); 
          }
          $this->response($response);
        }
  }//end of functions

  function getGroup_post(){//select data of groups.
    // i inserted table name from here that will make it common for all tables.
      $this->check_service_auth();
        $limit = !empty($this->post('limit')) ? $this->post('limit') : '';
        $start = !empty($this->post('start')) ? $this->post('start') : 1;
        $search = !empty($this->post('search'))?$this->post('search'): '';
        $filter = !empty($this->post('filter'))?$this->post('filter'): '';
      //$search = $this->post('search');
      $table = USERS;
      $id = $this->authData->userId;
      $res = $this->group_model->group_get($table,$limit,$start,$search,$filter,$id);
      //print_r($res);
      if(!empty($res)){
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(519),'count'=>count($res),'data'=>$res);
      }else{
        $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(158));
      }
      $this->response($response);
    }//end of functions*/

    function deleteGroup_post(){//delete group
      $this->check_service_auth();
      $table = GROUPS;
      $groupId = $this->post('groupId');
      $where = array('groupId'=>$groupId);
      $id =$this->authData->userId;
      $res = $this->group_model->deleteGroup($table,$where);
      //print_r($res);
      if(!empty($res)){
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(522));
      }else{
        $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(158));
      }
      $this->response($response);
    }//end of functions*/


  function getGroupDetails_post(){//select data of loggedin user.
    // i inserted table name from here that will make it common for all tables.
     
      $this->check_service_auth();
      //$search = $this->post('search');
      $table = GROUPS;
      $groupId = $this->post('group_id');
      $where = $groupId;
      $offset = $this->post('offset');
      $id =$this->authData->userId;
      $res = $this->group_model->groupDetail_get($table,$where,$offset,$id);
      //print_r($res);
      if(!empty($res)){
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(519),'data'=>$res);
      }else{
        $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(528));
      }
      $this->response($response);
    }//end of functions*/


    function getPostDetails_post(){//select data of loggedin user.
    // i inserted table name from here that will make it common for all tables.
      
      $this->check_service_auth();
      $limit = !empty($this->post('limit')) ? $this->post('limit') : '';
      $start = !empty($this->post('start')) ? $this->post('start') : 0;
      //$search = $this->post('search');
      $table = POSTS;
      $postId = $this->post('post_id');
      $where = $postId;
      $offset = $this->post('offset');
      $id = $this->authData->userId;
      $res = $this->user_model->postDetail_get($table,$where,$offset,$id,$limit,$start);//
     // pr($res);
      if(!empty($res)){
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(165),'data'=>$res);
      }else{
        $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(527));
      }
      $this->response($response);
    }//end of functions*/

    

  function Groupcomment_post(){// add comment on Group by user
      $this->check_service_auth();
      $this->form_validation->set_rules('group_id', 'Post_id', 'trim|required');
      $this->form_validation->set_rules('comment', 'Comment', 'trim|required');
      if ($this->form_validation->run() == FALSE){
        $responseArray = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
        $response = $this->generate_response($responseArray);
        $this->response($response);
      }//validation if end here...
        else{
          $insertComment= array();
          $insertComment['group_id']            =   $this->input->post('group_id');
          $insertComment['comment']             =   $this->input->post('comment');
          $insertComment['user_id']             =   $this->authData->userId;
          $res = $this->group_model->groupComment($insertComment,GROUP_COMMENTS);
          if($res){
          $user_info = $this->user_model->getUserDetails(array('groupId'=>$insertComment['group_id']),array('user_id','groupName'),GROUPS);
          //pr($user_info);
          $userId = $this->authData->userId;
          //pr($userId);
          if(!empty($user_info) AND  $userId != $user_info->userId){
          $registrationIds = $user_info->deviceToken; 
          $title = $user_info->categoryName; 
          $body_send = $this->authData->fullname.' '."commented on your post."; 
          $notif_type = "mabwe_groupComment"; 
          $notif_id = $insertComment['group_id'] ; 
          if (!empty($user_info->profileImage) && filter_var($user_info->profileImage, FILTER_VALIDATE_URL) === false) {
                  $profileImage = base_url().UPLOAD_FOLDER.'/profile/'.$user_info->profileImage;
                }else{
                  $profileImage = base_url().PROFILE_DETAIL_DEFAULT;
                }
          $is_notify = $this->send_push_notification(array($registrationIds),$title,$body_send,$notif_type,$userId,$profileImage,$notif_id);
         //pr($is_notify);
          $data = array('notification_by'=>$this->authData->userId,'notification_for'=>$user_info->userId,'notification_message'=>json_encode($is_notify),'notification_type'=>$notif_type);
          $this->notification_model->save_notification(NOTIFICATIONS,$data);
          }
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(173));
          }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(147)); 
          }
          $this->response($response);
        }
  }//end of functions

  function Grouplikes_post(){// add like or dislike on post user
      $this->check_service_auth();
      $this->form_validation->set_rules('group_id', 'group id ','trim|required');
      if ($this->form_validation->run() == FALSE){
        $responseArray = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
        $response = $this->generate_response($responseArray);
        $this->response($response);
      }//validation if end here...
        else{
          $insertLike = array();
          $insertLike['group_id']            =   $this->input->post('group_id');
          $insertLike['user_id']             =   $this->authData->userId;
          $res = $this->group_model->insertLike($insertLike,GROUP_LIKES);
          if($res['type'] == 'LIKE'){
              $user_info = $this->user_model->getUserDetails(array('groupId'=>$insertLike['group_id']),array('user_id'),GROUPS);
              $userId = $this->authData->userId;
            //echo $userId;
            //pr($user_info->userId);
            if(!empty($user_info) AND  $userId != $user_info->userId){
              //echo "hello";
                $registrationIds = $user_info->deviceToken; 
                $title = $user_info->categoryName;
                $body_send = $this->authData->fullname.' '."liked on your post."; 
                $notif_type = "mabwe_groupLike"; 
                $notif_id = $insertLike['group_id']; 
                if (!empty($user_info->profileImage) && filter_var($user_info->profileImage, FILTER_VALIDATE_URL) === false) {
                  $profileImage = base_url().UPLOAD_FOLDER.'/profile/'.$user_info->profileImage;
                }else{
                  $profileImage = base_url().PROFILE_DETAIL_DEFAULT;
                }
                $is_notify = $this->send_push_notification(array($registrationIds),$title,$body_send,$notif_type,$userId,$profileImage,$notif_id);
                //pr($is_notify);
                $data = array('notification_by'=>$this->authData->userId,'notification_for'=>$user_info->userId,'notification_message'=>json_encode($is_notify),'notification_type'=>$notif_type);
                $this->notification_model->save_notification(NOTIFICATIONS,$data);
            }
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(166),'data'=>$res['data']);
            }elseif($res['type'] == 'UNLIKE'){
              //echo "hello";
              $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(167),'data'=>$res['data']); 
            }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(147)); 
          }
          $this->response($response);
        }
  }//end of functions

  function getGroupMembers_post(){// get listing of group members
      $this->check_service_auth();
      $this->form_validation->set_rules('group_id', 'group id ','trim|required');
      $search = !empty($this->post('search'))?$this->post('search'): '';
      if ($this->form_validation->run() == FALSE){
        $responseArray = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
        $response = $this->generate_response($responseArray);
        $this->response($response);
      }//validation if end here...
        else{
          $insertLike = array();
          $get['user_id']            =   $this->authData->userId;
          $get['group_id']            =   $this->input->post('group_id');
          $res = $this->group_model->get_members($get,GROUP_MEMBERS,$search);
          if($res['type'] == 'SE'){
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(520),'data'=>$res['data']);
          }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(147)); 
          }
          $this->response($response);
        }
  }//end of functions

  function getNotification_post(){// get listing of notification
      $this->check_service_auth();
      $this->form_validation->set_rules('user_id', 'user id ','trim|required');
      if ($this->form_validation->run() == FALSE){
        $responseArray = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
        $response = $this->generate_response($responseArray);
        $this->response($response);
      }//validation if end here...
        else{
          $userId = $this->authData->userId;
          $insertLike = array();
          $get['notification_for']            =   $this->input->post('user_id');
          $res = $this->user_model->getNotification(array('notification_for'=>$userId),NOTIFICATIONS);
          if($res['type'] == 'SE'){
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(521),'data'=>$res['data']);
          }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(526)); 
          }
          $this->response($response);
        }
  }//end of functions


  function getContent_post(){
        $type = $this->input->post('type');
        if ($type==1) {
        $term_and_condition = $this->user_model->getsingle(OPTIONS,array("option_name"=>"term_and_condition"),'option_value');
        }
        if ($type==2) {
        $policy = $this->user_model->getsingle(OPTIONS,array('option_name'=>'policy'),'option_value');
        }
        if ($type==3) {
        $aboutUs = $this->user_model->getsingle(OPTIONS,array('option_name'=>'about_us'),'option_value');
        }
        if(!empty($term_and_condition)){
            $data['term_and_condition'] = base_url().TERM_CONDITION.$term_and_condition->option_value;
        }
        if(!empty($policy)){
            $data['policy'] = base_url().TERM_CONDITION.$policy->option_value;
        }
        if(!empty($aboutUs)){
            $data['aboutUs'] = $aboutUs->option_value;
        }
        
        if(empty($data)){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(525));
            $this->response($response);
        }
        $response = array('status'=> SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(126),"Content"=>$data);
        $this->response($response);
    }





} //end of class


