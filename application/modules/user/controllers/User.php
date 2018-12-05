<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CommonFront {

    public $data = "";

    function __construct() {
       
        parent::__construct();
         
        
    }
    
    function logout_user(){
       $this->logout(TRUE);  //redirect only when $is_redirect is set to TRUE
    }

    function check_password(){//this function will check the email already exist or not.. using jquery remote validator. not need to submit to check already exist mail id.
        $password = $_GET['password'];
        $userId = $_SESSION[USER_SESS_KEY]['userId'];
        $passwordc = $this->common_model->is_id_exist(USERS,'userId',$userId);
        $passwordVerfied = password_verify($password, $passwordc->password);
        
        if($passwordVerfied){
            echo "true"; die;
        }
         echo "false"; die;
    }


    function userProfile(){//this function will check the email already exist or not.. using jquery remote validator. not need to submit to check already exist mail id.
        $where = array('userId'=>$_SESSION[USER_SESS_KEY]['userId']);
        $getUserProfile['userData'] = $this->common_model->getsingle(USERS,$where);
        $user_id = decoding($_GET['user_id']);
        $where = array('userId'=>$user_id);
        $getUserProfile['title'] = 'USERROFILE';
        $getUserProfile['user']  = $this->common_model->getsingle(USERS,$where);
        $getUserProfile['post_count'] = $this->common_model->getCountByData(POSTS,array('user_id'=>$user_id));
        $getUserProfile['group_count'] = $this->common_model->getCountByData(GROUPS,array('user_id'=>$user_id));
        $this->load->front_render('user-profile',$getUserProfile);
       
    }


    function profile(){
        $this->check_user_session();
        $where = array('userId'=>$_SESSION[USER_SESS_KEY]['userId']);
        $data['userData'] = $this->common_model->getsingle(USERS,$where);
        $data['js']= array('front_assets/custom_js/place.js');
        $where = array('userId'=>$_SESSION[USER_SESS_KEY]['userId']);
        $data['userData'] = $this->common_model->getsingle(USERS,$where);
        $data['title'] = 'PROFILE';
        $this->load->front_render('profile',$data);
    }

    function updateProfileImage(){
         $this->check_user_session();
        if(!empty($_FILES['profileImage']['name'])){ 
                $this->load->model('image_model');   
                $upload                      =   $_FILES['profileImage']['name'];
                $imageName                   =   'profileImage';
                $folder                      =   "profile";
                $response                    =   $this->image_model->updateMedia($imageName,$folder);//IMAGE UPLOAD.
        }if(!empty($response['error'])):
            $this->session->set_flashdata('error',strip_tags($response['error']));
            redirect('user/profile');
        endif;
        $dataUpdate = array();
        $image                      =   isset($response)?($response):"";
        if(!empty($image)){
            $dataUpdate['profileImage'] =   $image;
            $table                      =   USERS;

            $where = array('userId'=>$_SESSION[USER_SESS_KEY]['userId']); 
            $res = $this->common_model->updateFields($table,$where,$dataUpdate);
        }
        if($res){
            $this->session->set_flashdata('success',"Image successfully changed.");
            redirect('user/profile');
        } else{
            $this->session->set_flashdata('error',"Image successfully changed.");
            redirect('user/profile');  
        }
    }

    function updateProfile(){
        $this->check_ajax_auth();
        $this->check_user_session();
        
       //update user profile function
        $this->form_validation->set_rules('fullName', 'Full name', 'trim|required');
        $this->form_validation->set_rules('country', 'Country', 'trim|required');
        $this->form_validation->set_rules('profession', 'Profession', 'trim|required');
        if ($this->form_validation->run() == FALSE){
            $requireds = strip_tags($this->form_validation->error_string()) ? strip_tags($this->form_validation->error_string()) : ''; //validation error
            $response = array('status' => FAIL, 'messages' => $requireds ,'csrf'=>get_csrf_token()['hash']);    
        }
        else{
            $response = array();
            if(!empty($_FILES['profileImage']['name'])){ 
                $this->load->model('image_model');   
                $upload                      =   $_FILES['profileImage']['name'];
                $imageName                   =   'profileImage';
                $folder                      =   "profile";
                $response                    =   $this->image_model->updateMedia($imageName,$folder);//IMAGE UPLOAD.
          }if(!empty($response['error']) && is_array($response['error'])):
          $response = array('status'=>FAIL,'messages'=> $response['error'],'csrf'=>get_csrf_token()['hash']);
          endif;
          $dataUpdate = array();
          $image                      =   isset($response)?($response):"";
          $table                      =   USERS;
          $dataUpdate['fullname']     =   $this->input->post('fullName');
          $dataUpdate['country']      =   $this->input->post('country_name');
          $dataUpdate['profession']   =   $this->input->post('profession');
          $dataUpdate['countryShortName']   =   $this->input->post('country');

          if(!empty($image)){
            $dataUpdate['profileImage'] =   $image;
          }
          $where = array('userId'=>$_SESSION[USER_SESS_KEY]['userId']); 
          $res = $this->common_model->updateFields($table,$where,$dataUpdate);
         
          if($res){
            $response = array('status'=>SUCCESS,'messages'=>ResponseMessages::getStatusCodeMessage(108));
          } else{
            $response = array('status'=>FAIL,'messages'=>'No changes found.','csrf'=>get_csrf_token()['hash']);  
          }
      }
      echo json_encode($response);
    }//end of fun


    function changePassword(){//change password function of user
         $this->check_ajax_auth();
        $this->check_user_session();
       
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[6]',array('required'=>'Please enter current password','min_length'=>'Password Should be atleast 6 Character Long'));
        $this->form_validation->set_rules('npassword', 'new password', 'trim|required|matches[cpassword]|min_length[6]',array('required'=>'Please enter new password','min_length'=>'Password Should be atleast 6 Character Long','matches'=>'New Password does not match with retype password'));
        $this->form_validation->set_rules('cpassword', 'retype new password ', 'trim|required|min_length[6]',array('required'=>'Please retype new password','min_length'=>'Password Should be atleast 6 Character Long'));
        $this->form_validation->set_error_delimiters('<div class="err_msg">', '</div>');
        if ($this->form_validation->run() == FALSE){ 
            $requireds = strip_tags($this->form_validation->error_string()) ? strip_tags($this->form_validation->error_string()) : ''; //validation error
            $response = array('status' => FAIL, 'messages' => $requireds ,'csrf'=>get_csrf_token()['hash']); 
        }else {
            $password =$this->input->post('password');
            $npassword =$this->input->post('npassword');
            $table  = USERS;
            $select = "password";
            $wheres = array('userId'=>$_SESSION[USER_SESS_KEY]['userId']);  
            $user = $this->common_model->getsingle($table,$wheres); // password with select from here to check old passwod
            //print_r($user);
            $passwordc = $user->password;
            $passwordVerfied = password_verify($password, $passwordc); //verified password here. 
            if($passwordVerfied){
                $newPassword = password_hash($this->input->post('npassword') , PASSWORD_DEFAULT);//password hash encrypt.
                $data =array('password'=> $newPassword); 
                $table = USERS;
                $res = $this->common_model->updateFields($table,$wheres, $data);
                if($res){
                  $response = array('status'=>SUCCESS,'messages'=>ResponseMessages::getStatusCodeMessage(148));
                } else{
                  $response = array('status'=>FAIL,'messages'=>ResponseMessages::getStatusCodeMessage(147),'csrf'=>get_csrf_token()['hash']);  
                } 
        } else{
          $response = array('status'=>FAIL,'messages'=>ResponseMessages::getStatusCodeMessage(150),'csrf'=>get_csrf_token()['hash']);  
        }
        }
        echo json_encode($response);
  }//END OF FUNCTION
   


    public function setPasswordUser() { //SET PASSWORD VIEW FUNCTION.
    
            $table= USERS;
            $token = $_GET['token'];
            $userId = decoding($_GET['userid']);
            $where = array('forgetPass'=> $token,'userId'=>$userId);
            $dataUser['title']='FORGETPASS';
            $dataUser['admin'] = $this->common_model->select_row($where,$table); 
            if(!empty($dataUser['admin'])){
             $this->load->front_render('forgetPass',$dataUser);
            }else{
               
                $this->session->set_flashdata('error',"Link has been expired.");
                redirect("home");
            }
            
    }//END OF FUNCTION 

    public function passwordSet($data="") { //SET PASSWORD VIEW FUNCTION.
            $table= USERS;
            $where = array('forgetPass'=> $data);
            $dataUser['admin'] = $this->common_model->select_row($where,$table);
             $this->load->view('passwordSuccess',$dataUser);
           

    }//END OF FUNCTION 

    public function setPassReset() {
        $res = array();
        //pr($_POST);
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]');
        if ($this->form_validation->run() == FALSE){
            $requireds = strip_tags($this->form_validation->error_string()) ? strip_tags($this->form_validation->error_string()) : ''; //validation 
            $response = array('status' => FAIL, 'messages' => $requireds ,'csrf'=>get_csrf_token()['hash']); 
        } //END OF FORM VALIDATION IF
        else{
            
            $dataSet['password'] = password_hash($this->input->post('password'),PASSWORD_DEFAULT);
            $dataSet['forgetPass'] = '';
            $dataSet['upd'] = date("Y-m-d H:m:s");
            //$dataSet['emailLink'] = '';
            $table = USERS;
            $where = array('userId' => $this->input->post('id'));
            $response = $this->common_model->update($where,$dataSet,$table);
          
            if($response == TRUE){
                $response = array('status'=>SUCCESS,'messages'=>ResponseMessages::getStatusCodeMessage(145),'csrf'=>get_csrf_token()['hash']);
            }else{
                $response = array('status'=>FAIL,'messages'=>ResponseMessages::getStatusCodeMessage(146),'csrf'=>get_csrf_token()['hash']);
              
            }
        }
        echo json_encode($response);     
    }//END OF FUNCTION
   
}//END OF CLASS
