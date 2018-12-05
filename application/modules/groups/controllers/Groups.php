<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends CommonFront {

    public $data = "";

    function __construct() {
        parent::__construct();
        $this->load->model('group_model');
        $this->load->model('image_model');
        
    }

    function groups_views(){
      $where = array('userId'=>$_SESSION[USER_SESS_KEY]['userId']);
      $data['userData'] = $this->common_model->getsingle(USERS,$where);
      $data['js']= array('front_assets/js/semantic.min.js','front_assets/custom_js/group.js');
      $data['title'] = 'GROUPDETAIL';
      $data['tags'] = $this->common_model->getAll(TAGS);
      $this->load->front_render('group',$data);
    }


    function addGroup(){

      $this->check_ajax_auth();
      $this->check_user_session();
      $this->form_validation->set_rules('title', 'Group title', 'trim|required|min_length[4]|max_length[40]');
      $this->form_validation->set_rules('category', 'Category Name', 'trim|required');
      //$this->form_validation->set_rules('tags', 'Tag Name', 'trim|required');
      if ($this->form_validation->run() == FALSE){
          $requireds = strip_tags($this->form_validation->error_string()) ? strip_tags($this->form_validation->error_string()) : ''; //validation error
          $response = array('status' => FAIL, 'message' => $requireds ,'csrf'=>get_csrf_token()['hash']);   
          echo json_encode($response); 
      }
      else{
          $response = array();
            if(!empty($_FILES['groupImage']['name'])){ 
              $this->load->model('image_model');   
              $upload                      =   $_FILES['groupImage']['name'];
              $imageName                   =   'groupImage';
              $folder                      =   "group";
              $response['image']           =   $this->image_model->updateMedia($imageName,$folder);//IMAGE UPLOAD.
          }
    
        if(!empty($response['image']['error'])){
          $response = array('status' => FAIL, 'message' => $response['image']['error'] ,'csrf'=>get_csrf_token()['hash']); 
        }else{
          $insertData = array();
          $image                      =   isset($response['image'])?($response['image']):"";
          $table                      =   USERS;
          $insertData['groupName']    =   $this->input->post('title');
          $insertData['category_id']  =   $this->input->post('category');
          $insertData['user_id']      =   $_SESSION[USER_SESS_KEY]['userId'];
          if(!empty($image)){
            $insertData['groupImage'] =   $image;
          }

          $res = $this->group_model->addGroup($insertData,GROUPS);
          if(!empty($this->input->post('tags_data')) AND !empty($res) AND $res['type'] == 'SC'){
              $tags = explode(',', $this->input->post('tags_data'));
              $insertTag = array();
                
              foreach($tags as $key =>$values){
                $str =  ltrim($values, '#');

                $insertTag[$key]['tagName'] =  $str;
                $post = $res['id'];
              }
              
              //check tag exist or not if not exist insert it into databse and mapp it.
              $this->group_model->check_tags($insertTag,$post,TAGS);
            }
          if($res['type'] == 'SC'){
            $getdata['groupData'] =$res['data'];
      
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(183),'csrf'=>get_csrf_token()['hash']);
          }elseif($res['type'] == 'AM'){
             $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(187),'csrf'=>get_csrf_token()['hash']);
          }elseif($res['type'] == 'GF'){

             $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(188),'csrf'=>get_csrf_token()['hash']);
          }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(184),'csrf'=>get_csrf_token()['hash']); 
          }
         
        }
        echo json_encode($response);
        }
        
    }

    function searchResultGroup(){//search post data and filter with initial data
        $searchArray = $this->input->post('searchName');
        $result['page'] = $this->input->post('page');
        $filter = $this->input->post('filter');

        $limit = 6;
        //$start = $result['page']*$limit;
        $start = $result['page'];
        $id = $_SESSION[USER_SESS_KEY]['userId'];
        $table= USERS;
        $result['group_data'] = $this->group_model->group_get($table,$limit,$start,$searchArray,$filter,$id);
        //lq($result['postDetail']);
        $limi ='';
        $star = '';
        $count = $this->group_model->group_get($table,$limi,$star,$searchArray,$filter,$id);
        /* is_next: var to check we have records available in next set or not
         * 0: NO, 1: YES
         */
        $countData = !empty($count['count'])?$count['count']:0;
        $is_next = 0;
        $new_offset = $result['page'] + $limit;
        if($new_offset < $countData){
            $is_next = 1;
        }

        if((!empty($searchArray) OR !empty($filter)) AND $result['page'] < 5){
            $result['isFilter'] = 1;
            $result['record_count'] = !empty($countData)?$countData:strval(0);
        }
        
        $groupView = $this->load->view('all_group_view',$result, true);
        echo json_encode( array('status'=>1, 'html'=>$groupView, 'isNext'=>$is_next, 'newOffset'=>$new_offset)); exit;
    }

    function likeUnlike(){
        $value = $this->input->get('value');
        $groupId = $this->input->get('group_id');
        $insertLike = array();
          $insertLike['group_id']             =   $groupId;
          $insertLike['user_id']             =   $_SESSION[USER_SESS_KEY]['userId'];
          $res = $this->group_model->insertLike($insertLike,GROUP_LIKES);
          //pr($res);
            if($res['type'] == 'LIKE'){
              $user_info = $this->group_model->getUserDetails(array('groupId'=>$insertLike['group_id']),array('user_id'),GROUPS);
              $userId = $_SESSION[USER_SESS_KEY]['userId'];
            //echo $userId;
            //pr($user_info->userId);
            if(!empty($user_info) AND  $userId != $user_info->userId){
              //echo "hello";
                $registrationIds = $user_info->deviceToken; 
                $title = $user_info->categoryName; 
                $body_send = $_SESSION[USER_SESS_KEY]['name'].' '."liked on your post."; 
                $notif_type = "mabwe_Like"; 
                $notif_id = $insertLike['group_id']; 
                if (!empty($user_info->profileImage) && filter_var($user_info->profileImage, FILTER_VALIDATE_URL) === false) {
                  $profileImage = base_url().UPLOAD_FOLDER.'/profile/'.$user_info->profileImage;
                }else{
                  $profileImage = base_url().PROFILE_DETAIL_DEFAULT;
                }
                $is_notify = $this->send_push_notification(array($registrationIds),$title,$body_send,$notif_type,$userId,$profileImage,$notif_id);
                //pr($is_notify);
                $data = array('notification_by'=>$_SESSION[USER_SESS_KEY]['userId'],'notification_for'=>$user_info->userId,'notification_message'=>json_encode($is_notify),'notification_type'=>$notif_type);
                $this->notification_model->save_notification(NOTIFICATIONS,$data);
            }
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(166),'data'=>$res['data']);
            }elseif($res['type'] == 'UNLIKE'){
              //echo "hello";
              $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(167),'data'=>$res['data']); 
            }else{
              $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(147)); 
            }
          echo json_encode($response);
    }//end of function 


    function getGroupLikeUser(){//search post data and filter with initial data
        $group_id = $this->input->get('group_id');
        $where = array('group_id'=> $group_id,'group_likes.status'=>1);
        $result['page'] = $this->input->get('page');

        $limit = 8;
        //$start = $result['page']*$limit;
        $start = $result['page'];
        $id = $_SESSION[USER_SESS_KEY]['userId'];
        $table= GROUP_LIKES;
        $result['userDetail'] = $this->group_model->likedUser($table,$where,$start,$limit);
     
         $count = $this->group_model->getCountLikes(GROUP_LIKES,$where);
        /* is_next: var to check we have records available in next set or not
         * 0: NO, 1: YES
         */
        $is_next = 0;
        $new_offset = $result['page'] + $limit;
        if($new_offset < $count){
            $is_next = 1;
        }

        if((!empty($searchArray) OR !empty($filter)) AND $result['page'] < 8){
            $result['isFilter'] = 1;
            $result['record_count'] = !empty($result['userDetail'])?count($result['userDetail']):strval(0);
        }
        $result['group_id'] = $group_id;
        $userView = $this->load->view('likeUserList',$result, true);

        echo json_encode( array('status'=>1, 'html'=>$userView, 'isNext'=>$is_next, 'newOffset'=>$new_offset,'count'=>$count['count'])); exit;
    }

    function groupDetail(){
      $group_id = decoding($this->input->get('group_id'));
      $where = array('userId'=>$_SESSION[USER_SESS_KEY]['userId']);
      $data['userData'] = $this->common_model->getsingle(USERS,$where);
      $data['js']= array('front_assets/js/semantic.min.js','front_assets/custom_js/commentViewGroup.js');
      $data['title'] = 'GROUPDETAILPAGE';
      $table = GROUPS;
      $groupId = $group_id;
      $where = $groupId;
      $data['commentCount'] = $this->group_model->GroupCommentsCounts(GROUP_COMMENTS,array('group_id'=>$groupId));
      $id =$_SESSION[USER_SESS_KEY]['userId'];
      $data['group_detail'] = $this->group_model->groupDetail_get($table,$where,$id);
      $data['tags'] = $this->common_model->getAll(TAGS);
      $this->load->front_render('group-detail',$data);
    }

    function getCommentsFromGroup(){//search post data and filter with initial data
        $group_id = $this->input->get('group_id');
        $where = array('group_id'=> $group_id,'group_comment.status'=>1);
        $result['page'] = $this->input->get('page');
        $limit = 5;
        $start = $result['page'];
        $id = $_SESSION[USER_SESS_KEY]['userId'];
        $table= GROUP_COMMENTS;
        $results['comments'] = $this->group_model->GroupCommentsDetails($table,$where,$start,$limit);

        if(!empty($results['comments'])){
           $resultData['commentsDetail']  = array_reverse($results['comments']);
         }else{
          $resultData['commentsDetail'] ='';
        }
        $count = $this->group_model->GroupCommentsCounts($table,$where);
        $is_next = 0;
        $new_offset = $result['page'] + $limit;
        if($new_offset < $count){
            $is_next = 1;
        }

        $result['group_id'] = $group_id;
        $userView = $this->load->view('getCommentViewDetails',$resultData, true);

        echo json_encode( array('status'=>1, 'html'=>$userView, 'isNext'=>$is_next, 'newOffset'=>$new_offset,'count'=>$count)); exit;
    }


    function addCommentFromGroupDeatil(){
      $this->check_ajax_auth();
        $this->check_user_session();
        $insertComment['comment'] = $this->input->get('comment');
        $insertComment['group_id'] = $this->input->get('group_id');
        $insertComment['user_id'] = $_SESSION[USER_SESS_KEY]['userId'];
        $insertComment['crd'] = datetime();

        $res = $this->group_model->groupComment($insertComment,GROUP_COMMENTS);
        if($res){
          $user_info = $this->group_model->getUserDetails(array('groupId'=>$insertComment['group_id']),array('user_id','groupName'),GROUPS);
          //pr($user_info);
          $userId = $_SESSION[USER_SESS_KEY]['userId'];
          //pr($userId);
          if(!empty($user_info) AND  $userId != $user_info->userId){
          $registrationIds = $user_info->deviceToken; 
          $title = $user_info->categoryName; 
          $body_send =$_SESSION[USER_SESS_KEY]['name'].' '."commented on your post."; 
          $notif_type = "mabwe_groupComment"; 
          $notif_id = $insertComment['group_id'] ; 
          if (!empty($user_info->profileImage) && filter_var($user_info->profileImage, FILTER_VALIDATE_URL) === false) {
                  $profileImage = base_url().UPLOAD_FOLDER.'/profile/'.$user_info->profileImage;
                }else{
                  $profileImage = base_url().PROFILE_DETAIL_DEFAULT;
                }
          $is_notify = $this->send_push_notification(array($registrationIds),$title,$body_send,$notif_type,$userId,$profileImage,$notif_id);
         //pr($is_notify);
          $data = array('notification_by'=>$_SESSION[USER_SESS_KEY]['userId'],'notification_for'=>$user_info->userId,'notification_message'=>json_encode($is_notify),'notification_type'=>$notif_type);
          $this->notification_model->save_notification(NOTIFICATIONS,$data);
          }

          $where = array('commentId'=>$res);
          $getComment['comments'] = $this->group_model->getCommentsWithUser(GROUP_COMMENTS,$where);
          $result = $this->load->view('comment_view',$getComment,true);
            $response = array('status'=>SUCCESS,'html'=>$result,'message'=>ResponseMessages::getStatusCodeMessage(173),'csrf'=>get_csrf_token()['hash']);
          }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(147),'csrf'=>get_csrf_token()['hash']); 
          }
          echo json_encode($response);
    }


    function getGroupMembers(){//search post data and filter with initial data
        $data['group_id'] = $this->input->get('group_id');
        $where = array('group_id'=> $data['group_id'],'group_members.status'=>1);
        $result['page'] = $this->input->get('page');
        $searchArray = !empty($this->input->get('searchName'))?$this->input->get('searchName'):'';
        $limit = 8;
        //$start = $result['page']*$limit;
        $start = $result['page'];
        $data['user_id'] = $_SESSION[USER_SESS_KEY]['userId'];
        $table= GROUP_MEMBERS;
        $result['data'] = $this->group_model->groupMembers($data,$table,$searchArray,$start,$limit);
        $count = $this->group_model->groupMembers($data,$table,$searchArray);
       //lq();
        /* is_next: var to check we have records available in next set or not
         * 0: NO, 1: YES
         */
        $is_next = 0;
        $new_offset = $result['page'] + $limit;
        if($new_offset < $count['count']){
            $is_next = 1;
        }
        $result['userDetail'] = $result['data']['data'];
        if((!empty($searchArray) OR !empty($filter)) AND $result['page'] < 8){
            $result['isFilter'] = 1;
            $result['record_count'] = !empty($result['userDetail'])?count($result['userDetail']):strval(0);
        }
        $result['group_id'] = $data['group_id'];
        $userView = $this->load->view('groupUserList',$result, true);

        echo json_encode( array('status'=>1, 'html'=>$userView, 'isNext'=>$is_next, 'newOffset'=>$new_offset,'count'=>$count['count'])); exit;
    }


    function deleteGroup(){
        $groupId = $this->input->get('group_id');
        $res =  $this->common_model->deleteData(GROUPS,array('groupId'=>$groupId));
        if(!empty($res)){
          $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(522));
        }else{
          $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(158));
        }
      
        echo json_encode($response);
    }

    function updateGroupDetail(){
        $insertDataP = array();
        $insertDataT = array();
        $this->form_validation->set_rules('group_id', 'Group Id', 'required');
        $insertData = array();
        if($this->form_validation->run() == FALSE){
          $requireds = strip_tags($this->form_validation->error_string()) ? strip_tags($this->form_validation->error_string()) : ''; //validation error
          $response = array('status' => FAIL, 'message' => $requireds ,'csrf'=>get_csrf_token()['hash']); 
    
        }
        else{
            $response = array();
           // print_r($_FILES['groupImage']['name']);die;
            if(!empty($_FILES['groupImage']['name'])){ 
              $this->load->model('image_model');   
              $upl                          =   $_FILES['groupImage']['name'];
              $imageName                    =   'groupImage';
              $folder                       =   'group';
              $response['image']            =   $this->image_model->updateMedia('groupImage',$folder);//IMAGE UPLOAD..
            }if(!empty($response['image']) && is_array($response['image'])):
            $response= array('status'=>FAIL,'message'=> $response['image']['error'],'csrf'=>get_csrf_token()['hash']);
            endif;
            $insertData = array();
            $image                      =   isset($response['image'])?($response['image']):"";
            $insertData['groupName']    =   $this->input->post('groupName');
            $insertData['category_id']  =   $this->input->post('category');
            if(!empty($image)){
              $insertData['groupImage'] =   $image;
            }
            $groupId = $this->input->post('group_id');
            $where  = array('groupId'=>$groupId);
            $responses = $this->common_model->updateFields(GROUPS,$where,$insertData);//UPDATE GROUP NAME AND GROUP IMAGE
            //lq();
            $groupId = $this->input->post('group_id');
            if(!empty($this->input->post('tags_data'))){
            $tagName = explode(',',$this->input->post('tags_data'));
            $dbTags = $this->group_model->getAllTags();
            //pr($tagName);
            $dbTagsExp = explode(',', $dbTags->tagName);
            $arrayTags = array();
            foreach($tagName as $values){
              $str =  ltrim($values,'#');
              //$count = count($str);
              $arrayData[] = $str;


            }
          
              $commonTags = array_intersect($arrayData, $dbTagsExp);//common data
              $dbTagsExp = explode(',', $dbTags->tagName);
              $insertDataP = array_diff($arrayData,$commonTags);//different datas
           //pr($insertData);
            if(!empty($insertDataP)){ 
                $insertDataP = array_values($insertDataP);
                for($i=0; $i < count($insertDataP); $i++){
                    $insertTag['tagName'] = $insertDataP[$i];
                    $response = $this->common_model->insert_data(TAGS,$insertTag);
                    
                    //print_r($response);
                }
                    
            } 
            $tagId = $this->group_model->getTagsId($arrayData);
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
                    $insertT['group_id'] = $this->input->post('group_id');
                    //$insertsData['group_id'] = $insertData[$i];
                    $resp1 = $this->common_model->insert_data('group_tag_mapping',$insertT);
                    //print_r($resp1);
                   
                  // print_r($ans);
                }
            }
            }
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(523),'csrf'=>get_csrf_token()['hash']);
        }   
        echo json_encode($response);            
    }




}//END OF CLASS
