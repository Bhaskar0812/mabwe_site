<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_authentication extends CommonFront {

    public $data = "";

    function __construct() {
        parent::__construct();
       
        $this->load->model('Login_model');
        
    }
    public function userRegistration(){//user Registration function
        $this->check_user_session();
        //$this->check_ajax_auth();
        $this->load->library('form_validation');
       
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('userName', 'User Name', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
       
       if ($this->form_validation->run() == FALSE){
            $requireds = strip_tags($this->form_validation->error_string()) ? strip_tags($this->form_validation->error_string()) : ''; //validation error
            $response = array('status' => FAIL, 'messages' => $requireds ,'csrf'=>get_csrf_token()['hash']); 
        }else {
          $userData = array();
  
          $dataImage = array();
            if(!empty($_FILES['profileImage']['name'])){ 
                $this->load->model('image_model');   
                $upload                      =   $_FILES['profileImage']['name'];
                $imageName                   =   'profileImage';
                $folder                      =   "profile";
                $dataImage                   =   $this->image_model->updateMedia($imageName,$folder);//IMAGE UPLOAD.
                //pr($dataImage['profile']);
              }if(!empty($dataImage['error'])):
                $response = array('status'=>FAIL,'messages'=> $dataImage['error'],'csrf'=>get_csrf_token()['hash']);

          endif;

          if(empty($dataImage['error'])){
          $userData['email']          = $this->input->post('email');
          $userData['fullname']       = $this->input->post('userName');
          $userData['password']       = $this->input->post('password');
          $userData['deviceType']     = 0;
          if(!empty($dataImage)){
          $userData['profileImage'] = isset($dataImage) ? $dataImage :"";
          }
          $userData['crd']        = date('Y-m-d H:i:s');
          $userData['upd']        = date('Y-m-d H:i:s');
          $table = USERS;

          $filterd_data = sanitize_input_text($userData);
          $isRegister = $this->Login_model->registration($filterd_data,$table);
          if(is_array($isRegister)){
            switch ($isRegister['regType']){
              case "NR":
               $response = array('status'=>SUCCESS,'messages'=>"Account created successfully. Redirecting...
              ");break;
              case "AE":
               $response = array('status'=>FAIL,'messages'=>ResponseMessages::getStatusCodeMessage(181),'csrf'=>get_csrf_token()['hash']);break;
              case "SL":
                $response = array('status'=>SUCCESS,'messages'=>ResponseMessages::getStatusCodeMessage(106));break;
              case "SR":
                $response = array('status'=>SUCCESS,'messages'=>ResponseMessages::getStatusCodeMessage(110));break;
              case "CNAE":
                $response = array('status'=>FAIL,'messages'=>ResponseMessages::getStatusCodeMessage(122),'csrf'=>get_csrf_token()['hash']);break;
              case "FT":
                $response = array('status'=>SUCCESS,'messages'=>'User first time social register');break;
            }
          }else {
              $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118),'csrf'=>get_csrf_token()['hash']);
          }
        }else{
        $response = array('status'=>FAIL,'messages'=> $dataImage['error'],'csrf'=>get_csrf_token()['hash']);
        }

        }
        echo json_encode($response);
    } //end function


    function check_unique_email(){//this function will check the email already exist or not.. using jquery remote validator. not need to submit to check already exist mail id.
        //$this->check_user_session();
        $email = $_GET['email'];
        $check = $this->common_model->is_id_exist(USERS,'email',$email);
        if($check){
            echo "false"; die;
        }
         echo "true"; die;
    }

    function check_unique_email_login(){//this function will check the email already exist or not.. using jquery remote validator. not need to submit to check already exist mail id.
        //$this->check_user_session();
        $email = $_GET['email'];
        $check = $this->common_model->is_id_exist(USERS,'email',$email);
        if($check){
            echo "true"; die;
        }
         echo "false"; die;
    }


  function isLogin(){  //user login function 
      $this->check_user_session();
      //$this->check_ajax_auth();
      $this->load->library('form_validation');
      $this->form_validation->set_rules('email','Email','required');
      $this->form_validation->set_rules('password','Password','required');
        if($this->form_validation->run() == FALSE){
          $requireds = strip_tags($this->form_validation->error_string()) ? strip_tags($this->form_validation->error_string()) : ''; //validation error
          $response = array('status' => FAIL, 'messages' => $requireds ,'csrf'=>get_csrf_token()['hash']); 
        } else{      
       
          $userData = array();
          $userData['password']   = $this->input->post('password');
          $email                  = $this->input->post('email');

          $where = array('email'=>$email);
          $table = USERS;
          $filterd_data = sanitize_input_text($userData);
          $where_filtered = sanitize_input_text($where);
          $isLoggedIn = $this->Login_model->isLogin($filterd_data,$where_filtered,$table);
          if(is_string($isLoggedIn['type']) && $isLoggedIn['type'] == 'NA'){
            $response = array('status'=>FAIL,'messages'=>ResponseMessages::getStatusCodeMessage(121),'csrf'=>get_csrf_token()['hash']);
          } elseif(is_string($isLoggedIn['type']) && $isLoggedIn['type'] == 'WP'){
            $response = array('status'=>FAIL,'messages'=>ResponseMessages::getStatusCodeMessage(105),'csrf'=>get_csrf_token()['hash']);
          } elseif(is_string($isLoggedIn['type']) && $isLoggedIn['type'] == 'LS'){
            $response = array('status'=>SUCCESS,'messages'=>"Logged in successfully. Redirecting...");
          } else{
            $response = array('status'=>FAIL,'messages'=>ResponseMessages::getStatusCodeMessage(105),'csrf'=>get_csrf_token()['hash']);
          }
         
      }
      echo json_encode($response);
  } //end function


  function forgetPassword() { //Forget Password Function.. Send Link by using this function.
    $this->check_user_session();
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
    if ($this->form_validation->run() == FALSE){
      $requireds = strip_tags($this->form_validation->error_string()) ? strip_tags($this->form_validation->error_string()) : ''; //validation error
      $response = array('status' => FAIL, 'messages' => $requireds ,'csrf'=>get_csrf_token()['hash']); 
    } //END OF FORM VALIDATION IF
    else{
      $dataSet['email'] = $this->input->post('email');
      $table = USERS;
      $filterd_data = sanitize_input_text($dataSet);
      $isSent = $this->Login_model->forgetPassword($table,$filterd_data);
      if($isSent['type'] == 'ES'){
        $response   =  array('status'=>SUCCESS,'messages'=>ResponseMessages::getStatusCodeMessage(142));
      } elseif($isSent['type'] == 'IE'){
        $response = array('status'=>FAIL,'messages'=>ResponseMessages::getStatusCodeMessage(143),'csrf'=>get_csrf_token()['hash']);
      } else{
        $response = array('status'=>FAIL,'messages'=>ResponseMessages::getStatusCodeMessage(147),'csrf'=>get_csrf_token()['hash']);
      }
    }  
    echo json_encode($response);
  }//END OF FUNCTION 


}//END OF CLASS
