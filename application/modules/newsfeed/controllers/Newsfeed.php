<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Newsfeed extends CommonFront {

    public $data = "";
    private $perPage = 5;
    function __construct() {
    	$this->check_user_session();
        parent::__construct();
        $this->load->model('newsfeed_model');
        
    }

    public function index(){

        $where = array('userId'=>$_SESSION[USER_SESS_KEY]['userId']);
        $data['userData'] = $this->common_model->getsingle(USERS,$where);
        $data['js']= array('front_assets/custom_js/search.js');
        $data['title'] = 'NEWSFEED';
        $data['count'] = $this->newsfeed_model->getCount(POSTS,array('user_id'=>$_SESSION[USER_SESS_KEY]['userId']));
         $data['group_count'] = $this->newsfeed_model->getCount(GROUPS,array('user_id'=>$_SESSION[USER_SESS_KEY]['userId']));
        $data['categories'] = $this->common_model->getAll(CATEGORIES);
        $this->load->front_render('newsfeed',$data);
    } //end function


    function searchResult(){//search post data and filter with initial data
        $searchArray = $this->input->post('searchName');
        $result['page'] = $this->input->post('page');
        $filter = $this->input->post('filter');

        $limit = 5;
        //$start = $result['page']*$limit;
        $start = $result['page'];
        $id = $_SESSION[USER_SESS_KEY]['userId'];
        $table= USERS;
        $result['postDetail'] = $this->newsfeed_model->getPost($table,$searchArray,$filter,$id,$start,$limit);
        //lq($result['postDetail']);
        $count = $this->newsfeed_model->getPost($table,$searchArray,$filter,$id);
        /* is_next: var to check we have records available in next set or not
         * 0: NO, 1: YES
         */
        $countData = !empty($count)?count($count):0;
        $is_next = 0;
        $new_offset = $result['page'] + $limit;
        if($new_offset < $countData){
            $is_next = 1;
        }

        if((!empty($searchArray) OR !empty($filter)) AND $result['page'] < 5){
            $result['isFilter'] = 1;
            $result['record_count'] = !empty($countData)?$countData:strval(0);
        }
        
        $postView = $this->load->view('post_creation_page',$result, true);
        echo json_encode( array('status'=>1, 'html'=>$postView, 'isNext'=>$is_next, 'newOffset'=>$new_offset) ); exit;
    }

    function getPostLikeUser(){//search post data and filter with initial data
        $post_id = $this->input->get('post_id');
        $where = array('post_id'=> $post_id,'likes.status'=>1);
        $result['page'] = $this->input->get('page');

        $limit = 8;
        //$start = $result['page']*$limit;
        $start = $result['page'];
        $id = $_SESSION[USER_SESS_KEY]['userId'];
        $table= LIKES;
        $result['userDetail'] = $this->newsfeed_model->likedUser($table,$where,$start,$limit);
     
         $count = $this->newsfeed_model->getCountLikes(LIKES,$where);
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
        $result['post_id'] = $post_id;
        $userView = $this->load->view('likeUserList',$result, true);

        echo json_encode( array('status'=>1, 'html'=>$userView, 'isNext'=>$is_next, 'newOffset'=>$new_offset,'count'=>$count)); exit;
    }

    function addComment(){//add comment and send notification using this function....
        $this->check_ajax_auth();
        $this->check_user_session();
        $insertComment['comment'] = $this->input->get('comment');
        $insertComment['post_id'] = $this->input->get('post_id');
        $insertComment['user_id'] = $_SESSION[USER_SESS_KEY]['userId'];
        $insertComment['crd'] = datetime();
        $res = $this->common_model->insertData(COMMENTS,$insertComment);
        if($res){
          $user_info = $this->newsfeed_model->getUserDetailsComment(array('postId'=>$insertComment['post_id']),array('user_id','postId'),POSTS);
          $userId = $_SESSION[USER_SESS_KEY]['userId'];
          if(!empty($user_info) AND  $userId != $user_info->userId){
          $registrationIds = $user_info->deviceToken; 
          $title = $user_info->categoryName;
          $notify_id = $insertComment['post_id']; 
          $body_send = $_SESSION[USER_SESS_KEY]['name'].' '."commented on your post."; 
          $notif_type = "mabwe_comment"; 
          if (!empty($user_info->profileImage) && filter_var($user_info->profileImage, FILTER_VALIDATE_URL) === false) {
                  $profileImage = base_url().UPLOAD_FOLDER.'/profile/'.$user_info->profileImage;
                }else{
                  $profileImage = base_url().PROFILE_DETAIL_DEFAULT;
                }
          $is_notify = $this->send_push_notification(array($registrationIds),$title,$body_send,$notif_type,$userId,$profileImage,$notify_id);
          //pr($is_notify);
         // pr($is_notify);
          $data = array('notification_by'=>$_SESSION[USER_SESS_KEY]['userId'],'notification_for'=>$user_info->userId,'notification_message'=>json_encode($is_notify),'notification_type'=>$notif_type);
          $this->notification_model->save_notification(NOTIFICATIONS,$data);
          }
          $where = array('commentId'=>$res);
          $getComment['comments'] = $this->newsfeed_model->getCommentsWithUser(COMMENTS,$where);
          $result = $this->load->view('comment_view',$getComment,true);
            $response = array('status'=>SUCCESS,'html'=>$result,'message'=>ResponseMessages::getStatusCodeMessage(173),'csrf'=>get_csrf_token()['hash']);
          }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(147),'csrf'=>get_csrf_token()['hash']); 
          }
          echo json_encode($response);
    }

    function likeUnlike(){
        $value = $this->input->get('value');
        $postId = $this->input->get('post_id');
        $insertLike = array();
          $insertLike['post_id']             =   $postId;
          $insertLike['user_id']             =   $_SESSION[USER_SESS_KEY]['userId'];
          $res = $this->newsfeed_model->insertLike($insertLike,LIKES);
          //pr($res);
            if($res['type'] == 'LIKE'){
              $user_info = $this->newsfeed_model->getUserDetailsComment(array('postId'=>$insertLike['post_id']),array('user_id'),POSTS);
              $userId = $_SESSION[USER_SESS_KEY]['userId'];
            //echo $userId;
            //pr($user_info->userId);
            if(!empty($user_info) AND  $userId != $user_info->userId){
              //echo "hello";
                $registrationIds = $user_info->deviceToken; 
                $title = $user_info->categoryName; 
                $body_send = $_SESSION[USER_SESS_KEY]['name'].' '."liked on your post."; 
                $notif_type = "mabwe_Like"; 
                $notif_id = $insertLike['post_id']; 
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
    }


}//END OF CLASS
