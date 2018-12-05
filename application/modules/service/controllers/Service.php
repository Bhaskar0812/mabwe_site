<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service extends CommonService {

	function __construct(){
		parent::__construct();
		$this->load->model('common_model');
    $this->load->model('image_model');
    $this->load->model('user_model');
	}

  function userRegistration_post() {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
    $this->form_validation->set_rules('fullName', 'Name', 'trim|required');
    $this->form_validation->set_rules('password', 'Password', 'trim|required');
   
    if($this->form_validation->run() == FALSE){
      $response = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
      $this->response($response);
    }else {
      
      $userData = array();
      $authToken = $this->service_model->_generate_token();
      $dataImage = array();
        if(!empty($_FILES['photo']['name'])){ 
            $this->load->model('image_model');   
            $upload                      =   $_FILES['photo']['name'];
            $imageName                   =   'photo';
            $folder                      =   "profile";
            $dataImage['profile']        =   $this->image_model->updateMedia($imageName,$folder);//IMAGE UPLOAD.
          }if(!empty($dataImage['profile']) && is_array($dataImage['profile'])):
          $responseArray = array('status'=>FAIL,'message'=> $dataImage['profile']['error']);
          $response = $this->generate_response($responseArray);
          $this->response($response);
        endif;
      $userData['email']          = $this->post('email');
      $userData['fullname']       = $this->post('fullName');
      $userData['password']       = $this->post('password');
      $userData['deviceToken']    = $this->post('deviceToken');
      $userData['deviceType']     = $this->post('deviceType');
      if(!empty($_FILES['photo']['name'])){
        $userData['profileImage'] = $dataImage['profile'];
      }
      $userData['authToken']  = $authToken;
      $userData['crd']        = date('Y-m-d H:i:s');
      $userData['upd']        = date('Y-m-d H:i:s');
      $table = USERS;
      $isRegister = $this->user_model->registration($userData,$table);
      if(is_array($isRegister)){
        switch ($isRegister['regType']){
          case "NR":
           $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(139),'userDetail'=>$isRegister['data']);break;
          case "AE":
           $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(181));break;
          case "SL":
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(106),'userDetail'=>$isRegister['data']);break;
          case "SR":
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(110),'userDetail'=>$isRegister['data']);break;
          case "CNAE":
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(122));break;
          case "FT":
            $response = array('status'=>SUCCESS,'message'=>'User first time social register');break;
        }
      }else {
          $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
      }
      $this->response($response);
    }
  } //end function

  function userLogin_post(){  
    $this->load->library('form_validation');
    $this->form_validation->set_rules('email','Email','required');
    $this->form_validation->set_rules('password','Password','required');
      if($this->form_validation->run() == FALSE){
        $responseArray = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
        $response = $this->generate_response($responseArray);
        $this->response($response);
      } else{      
        $authToken = $this->service_model->_generate_token();
        $userData = array();
        $userData['password']   = $this->post('password');
        $email                  = $this->post('email');
        $userData['deviceToken']= $this->post('deviceToken');
        $userData['deviceType'] = $this->post('deviceType');
        $userData['authToken']  = $authToken;
        $where = array('email'=>$email);
        $table = USERS;
        $isLoggedIn = $this->user_model->isLogin($userData,$authToken,$where,$table);
        if(is_string($isLoggedIn['type']) && $isLoggedIn['type'] == 'NA'){
          $responseArray = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(121), 'userDetail'=>$isLoggedIn['userDetail']);
        } elseif(is_string($isLoggedIn['type']) && $isLoggedIn['type'] == 'WP'){
          $responseArray = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(105), 'userDetail'=>$isLoggedIn['userDetail']);
        } elseif(is_string($isLoggedIn['type']) && $isLoggedIn['type'] == 'LS'){
          $responseArray = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(106),'userDetail'=>$isLoggedIn['userDetail']);
        } else{
          $responseArray = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(105));
        }
        $response = $this->generate_response($responseArray);
        $this->response($response);
    }
  } //end function

  function forgetPassword_post() { //Forget Password Function.. Send Link by using this function.
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
    if ($this->form_validation->run() == FALSE){
      $responseArray = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
      $response = $this->generate_response($responseArray);
      $this->response($response);
    } //END OF FORM VALIDATION IF
    else{
      $dataSet['email'] = $this->input->post('email');
      $table = USERS;
      $isSent = $this->service_model->forgetPassword($table,$dataSet);
      //print_r($isSent);die;
      if(is_string($isSent['type']) && $isSent['type'] == 'ES'){
        $responseArray   =  array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(142));
      } elseif(is_string($isSent['type']) && $isSent['type'] == 'IE'){
        $responseArray = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(143));
      } elseif(is_string($isSent['type']) && $isSent['type'] == 'NS'){
        $responseArray = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
      } else{
        $responseArray = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(147));
      }
      $response = $this->generate_response($responseArray);
      $this->response($response); 
    }  
  }//END OF FUNCTION 

  function _alpha_spaces_check($string){
    if(alpha_spaces($string)){
      return true;
    }
    else{
      $this->form_validation->set_message('_alpha_spaces_check','Only alphabets and spaces are allowed in {field} field');
      return FALSE;
    }
  }

  function logout_get(){//logout function.
   $this->check_service_auth();
    //empty device token on when user logged out
    $logout = $this->common_model->updateFields(USERS,array('userId'=>$this->authData->userId),array('deviceToken' =>'','authToken'=>''));
    if($logout){
      $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(137));
      $this->response($response);
    }
  }//END OF FUNCTION.
  

  function changePassword_post(){ //Chnage Password SUBMIT FUNCTION.. 
    $this->check_service_auth();
    $this->load->library('form_validation');
    $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[6]',array('required'=>'Please enter current password','min_length'=>'Password Should be atleast 6 Character Long'));
    $this->form_validation->set_rules('npassword', 'new password', 'trim|required|matches[cpassword]|min_length[6]',array('required'=>'Please enter new password','min_length'=>'Password Should be atleast 6 Character Long','matches'=>'New Password does not match with retype password'));
    $this->form_validation->set_rules('cpassword', 'retype new password ', 'trim|required|min_length[6]',array('required'=>'Please retype new password','min_length'=>'Password Should be atleast 6 Character Long'));
    $this->form_validation->set_error_delimiters('<div class="err_msg">', '</div>');
    if ($this->form_validation->run() == FALSE)
    { 
      $responseArray = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
      $response = $this->generate_response($responseArray);
      $this->response($response);
    }else {
        $password =$this->input->post('password');
        $npassword =$this->input->post('npassword');
        $table  = USERS;
        $select = "password";
        $wheres = array('userId'=>$this->authData->userId);  
        $where = $this->authData->userId; 
        $user = $this->user_model->customGet($select,$wheres,$table); // password with select from here to check old passwod
        //print_r($user);
        $passwordc = $user->password;
        $passwordVerfied = password_verify($password, $passwordc); //verified password here. 
        if($passwordVerfied){
            $newPassword = password_hash($this->input->post('npassword') , PASSWORD_DEFAULT);//password hash encrypt.
            $data =array('password'=> $newPassword); 
            $table = USERS;
            $res = $this->user_model->updateProfile($table,$where, $data);
            if(is_string($res['type']) && $res['type'] == 'US'){
              $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(148),'updatedData'=>$res);
            } elseif(is_string($res) && $res == 'NU'){
              $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(149));
            } else{
              $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(147));  
            } 
    } else{
      $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(150));  
    }
    $this->response($response);
    }
  }//END OF FUNCTION

} //end of class
