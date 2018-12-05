<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CommonFront {

    public $data = "";

    function __construct() {
        parent::__construct();
        $this->load->model('post_model');
        $this->load->model('image_model');
        
    }

    public function addPost(){
        $this->check_user_session();
       
        $where = array('userId'=>$_SESSION[USER_SESS_KEY]['userId']);
        $data['userData'] = $this->common_model->getsingle(USERS,$where);
        $data['js']= array('front_assets/js/semantic.min.js','front_assets/custom_js/place.js');
        $data['title'] = 'ADDPOST';
        $data['tags'] = $this->common_model->getAll(TAGS);
        $this->load->front_render('add-post',$data);
    } //end function

    function add_post_submit(){ //when add post we will make this function, If will accept imgage or video uploading with tag.

        $this->check_ajax_auth();
        $this->check_user_session();
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('tags_data', 'Tags', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('category', 'Category', 'trim|required');
        if ($this->form_validation->run() == FALSE){
            $requireds = strip_tags($this->form_validation->error_string()) ? strip_tags($this->form_validation->error_string()) : ''; //validation error
            $response = array('status' => FAIL, 'messages' => $requireds ,'csrf'=>get_csrf_token()['hash']);    
        }
        $insertJob = array();
        $insertJob['title']                =   $this->input->post('title');
        $insertJob['description']          =   $this->input->post('description');
        $insertJob['category_id']          =   $this->input->post('category');
        $insertJob['latitude']             =   $this->input->post('fbUserLocLat');
        $insertJob['longitude']            =   $this->input->post('fbUserLocLong');
        $insertJob['country']              =   $this->input->post('country');
        $insertJob['city']                 =   $this->input->post('city');
        $insertJob['state']                =   $this->input->post('state');
        $insertJob['address']              =   $this->input->post('address');
        $insertJobs['willingToRelocate']   =   $this->input->post('relocate');
        $insertJobs['authorisedToWork']    =   $this->input->post('authorised');
        $insertJobs['whilingToship']       =   $this->input->post('whilingToship');
        $insertJobs['email']               =   $this->input->post('email');
        $insertJobs['contact']             =   $this->input->post('contact');
        $insertJob['user_id']              =   $_SESSION[USER_SESS_KEY]['userId'];
        $insertJob['crd']                  =   datetime();
        if(!empty($_POST['postImage']) AND empty($_FILES['video']['name'])){
            $files = $_POST['postImage']; 
            $filesCount = 0; $error_details = array(); $att_arr = array();
            $filesCount =  count($_POST['postImage']);
            if($filesCount <= 5){
                $post_id['postId'] = $this->post_model->job_add_post($insertJob, $insertJobs, POSTS); 
                for($i = 0; $i < $filesCount; $i++){
                    $rand  = rand().'.png';
                    $realImagePath = FCPATH.'uploads/postImages/'.$rand;
                    $res = $this->image_model->canvasImageUpload($_POST['postImage'][$i],'postImages',rand());
                    if(!empty($res['error'])){
                        $response = array('status'=>FAIL,'messages'=>$res['error'],'csrf'=>get_csrf_token()['hash']);
                        //echo json_encode($response);exit;
                    }
                    $attr_data['post_id']    = $post_id['postId'];
                    $attr_data['attachmentType'] = 'image';
                    $attr_data['attachmentName'] = $res['uploadfile'];
                     $this->db->insert(POST_ATTACHMENTS, $attr_data);
                    $last_id = $this->db->insert_id();
                }
            /**/
            if(!empty($this->input->post('tags_data')) AND !empty($post_id)){
              $tags = explode(',', $this->input->post('tags_data'));
              $insertTag = array();
                
              foreach($tags as $key =>$values){
                $str =  ltrim($values, '#');

                $insertTag[$key]['tagName'] =  $str;
                $post = $post_id['postId'];
              }
              
              //check tag exist or not if not exist insert it into databse and mapp it.
              $this->post_model->check_tags($insertTag,$post,TAGS);
            }
            if($post_id){
              $response = array('status'=>SUCCESS,'messages'=>ResponseMessages::getStatusCodeMessage(151),'csrf'=>get_csrf_token()['hash']);
            } else{
              $response = array('status'=>FAIL,'messages'=>ResponseMessages::getStatusCodeMessage(147),'csrf'=>get_csrf_token()['hash']);
            } 
            }else{
                $response = array('status'=>FAIL,'messages'=>'Maximum 5 Images can upload.','csrf'=>get_csrf_token()['hash']);
            }}
            elseif(empty($_POST['postImages']) AND !empty($_FILES['video']['name']) AND !empty($_POST['video_thumb'])){
               
                if($_FILES['video']['size'] <= 10000000){
                $post_id['postId'] = $this->post_model->job_add_post($insertJob, $insertJobs, POSTS);
                $this->load->model('upload_media_model');   
                $upload                           =   $_FILES['video']['name'];
                $imageName                        =   'postImages';
                $folder                           =   "postImages";
                $_FILES[$imageName]['name']       = $_FILES['video']['name'];
                $_FILES[$imageName]['type']       = $_FILES['video']['type'];
                $_FILES[$imageName]['tmp_name']   = $_FILES['video']['tmp_name'];
                $_FILES[$imageName]['error']      = $_FILES['video']['error'];
                $_FILES[$imageName]['size']       = $_FILES['video']['size'];
                $res['images'] =   $this->upload_media_model->upload_video($imageName,$folder);//IMAGE UPLOAD.
                if(!empty($res['images']['error']) && is_array($res['images']['error'])){
                    $response = array('status'=>FAIL,'messages'=> $response['images']['error']);
                    
                }
                $this->common_model->updateFields(POSTS,array('postId'=>$post_id['postId']),array('video'=> $res['images']));
                $attr_data['post_id']    = $post_id['postId'];
                $attr_data['attachmentType'] = 'video';
                $attr_data['attachmentName'] = $res['images'];
                 $this->db->insert(POST_ATTACHMENTS, $attr_data);
                $last_id = $this->db->insert_id();

                $rand  = rand().'_videoThumb'.'.png';
                $realImagePath = FCPATH.'uploads/postImages/'.$rand;
                $res = $this->image_model->canvasImageUpload($_POST['video_thumb'],'postImages',rand());
                $videoThumb['post_id']    = $post_id['postId'];
                $videoThumb['attachmentType'] = 'video_thumb';
                $videoThumb['attachmentName'] = $res['uploadfile'];
                $this->common_model->updateFields(POSTS,array('postId'=>$post_id['postId']),array('video_thumb'=>$res['uploadfile']));
                $this->db->insert(POST_ATTACHMENTS, $videoThumb);
                $last_id = $this->db->insert_id();
                 if(!empty($this->input->post('tags_data')) AND !empty($post_id)){
                  $tags = explode(',', $this->input->post('tags_data'));
                  $insertTag = array();
                  foreach($tags as $key =>$values){
                    $str =  ltrim($values, '#');

                    $insertTag[$key]['tagName'] =  $str;
                    $post = $post_id['postId'];
                  }
                  //check tag exist or not if not exist insert it into databse and mapp it.
                  $this->post_model->check_tags($insertTag,$post,TAGS);
                }
            if($post_id){
              $response = array('status'=>SUCCESS,'messages'=>ResponseMessages::getStatusCodeMessage(151),'csrf'=>get_csrf_token()['hash']);
            } else{
              $response = array('status'=>FAIL,'messages'=>ResponseMessages::getStatusCodeMessage(147),'csrf'=>get_csrf_token()['hash']);
            } 
            }else{
                $response = array('status'=>FAIL,'messages'=>'Video should not be greater then 10MB.','csrf'=>get_csrf_token()['hash']);
            }
            }else{
                $response = array('status'=>FAIL,'messages'=>'Please select atleast one media to upload.','csrf'=>get_csrf_token()['hash']);
            }
            
        echo json_encode($response);

    } //end function

    function postDetailView(){//function to view post Details
        $where =  decoding($this->input->get('post_id'));
        $id =     $_SESSION[USER_SESS_KEY]['userId'];
        $whereUser = array('userId'=>$_SESSION[USER_SESS_KEY]['userId']);
        $data['userData'] = $this->common_model->getsingle(USERS,$whereUser);
        $data['post_details'] = $this->post_model->select_post($where,$id);
        $data['js'] = array('front_assets/custom_js/commentView.js');
        $data['title'] = "POSTDETAIL";
        $this->load->front_render('newsfeedDetail',$data);
    }

    function getPostCommentsFromFeed(){//search post data and filter with initial data
        $post_id = $this->input->get('post_id');
        $where = array('post_id'=> $post_id,'comment.status'=>1);
        $result['page'] = $this->input->get('page');
        $limit = 5;
        $start = $result['page'];
        $id = $_SESSION[USER_SESS_KEY]['userId'];
        $table= COMMENTS;
        $result['comments'] = $this->post_model->postCommentsFeedDetails($table,$where,$start,$limit);
        if(!empty($result['comments'])){
          $result['commentsDetail']  = array_reverse($result['comments']);
        }else{
           $result['commentsDetail'] ='';
        }
       
        $count = $this->post_model->postCommentsFeedDetailsCount(COMMENTS,$where);
        $is_next = 0;
        $new_offset = $result['page'] + $limit;
        if($new_offset < $count){
            $is_next = 1;
        }

        $result['post_id'] = $post_id;
        $userView = $this->load->view('getCommentViewDetails',$result, true);

        echo json_encode( array('status'=>1, 'html'=>$userView, 'isNext'=>$is_next, 'newOffset'=>$new_offset,'count'=>$count)); exit;
    }


    function addCommentFromFeedDeatil(){//add comment and send notification using this function....
        $this->check_ajax_auth();
        $this->check_user_session();
        $insertComment['comment'] = $this->input->get('comment');
        $insertComment['post_id'] = $this->input->get('post_id');
        $insertComment['user_id'] = $_SESSION[USER_SESS_KEY]['userId'];
        $insertComment['crd'] = datetime();
        $res = $this->common_model->insertData(COMMENTS,$insertComment);
        if($res){
          $user_info = $this->post_model->getUserDetailsComment(array('postId'=>$insertComment['post_id']),array('user_id','postId'),POSTS);
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
          $getComment['comments'] = $this->post_model->getCommentsWithUser(COMMENTS,$where);
          $result = $this->load->view('comment_view',$getComment,true);
            $response = array('status'=>SUCCESS,'html'=>$result,'message'=>ResponseMessages::getStatusCodeMessage(173),'csrf'=>get_csrf_token()['hash']);
          }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(147),'csrf'=>get_csrf_token()['hash']); 
          }
          echo json_encode($response);
    }

}//END OF CLASS
