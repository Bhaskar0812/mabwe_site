<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {


    function registration($data,$table){// function for user registration
        $userDetail = array();
      $res = $this->db->select('email')->where(array('email'=>$data['email']))->get($table);
      //check data exist or not
      if($res->num_rows() == 0 ) {
        if(!empty($data['socialId']) && !empty($data['socialType'])) {// this will check social type
            $check = $this->db->select('userId')->where(array('socialId'=>$data['socialId'],'socialType'=>$data['socialType']))->get($table);
            //check data exist using social id
            if($check->num_rows() == 1) {
                $id=$check->row();
                //update divice token and type
                $this->db->where(array('id'=>$id->id));
                $this->db->update($table,array('authToken'=>$data['authToken'],'deviceToken'=>$data['deviceToken'],'deviceType'=>$data['deviceType']));                 
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
                $user = $this->common_model->getsingle(USERS,array('userId'=>$userId));
                $session_data['userId']     =       $user->userId ;
                $session_data['emailId']    =       $user->email;
                $session_data['name']       =       $user->fullname;
                $session_data['profileImage']  =    $user->profileImage;
                $session_data['isLogin']    =       TRUE;
                $_SESSION[USER_SESS_KEY] = $session_data;
                $userDetail['userId'] = $userId;
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
                $user = $this->common_model->getsingle(USERS,array('userId'=>$userId));
                $session_data['userId']     =       $user->userId ;
                $session_data['emailId']    =       $user->email;
                $session_data['name']       =       $user->fullname;
                $session_data['profileImage']  =    $user->profileImage;
                //$session_data['userType']  =    $user->userType;
               
                $session_data['isLogin']    =       TRUE;

                $_SESSION[USER_SESS_KEY] = $session_data;
                
            if(empty($userId)){
                return "SGW";
            }
                                      
            $userDetail['regType'] = 'NR';
            return $userDetail;
        } else {
        //check social id or type
        if(!empty($data['socialId']) && !empty($data['socialType'])) {
            //get user info using socialid
            $check = $this->db->select('userId')->where(array('socialId'=>$data['socialId'],'socialType'=>$data['socialType']))->get($table);
            if($check->num_rows() == 1){
                $id=$check->row();
                $this->db->where(array('userId'=>$id->userId));
                $this->db->update($table,array('authToken'=>$data['authToken'],'deviceToken'=>$data['deviceToken'],'deviceType'=>$data['deviceType']));
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
    }//end of function

    function isLogin($data,$where,$table){
        $res = $this->db->select('*')->where($where)->get($table);
        if($res->num_rows() > 0){
            $result = $res->row();
            $password = $data['password'];
            //var_dump(password_verify($password,$result->password));die;
            if(password_verify($password,$result->password)){
                if($result->status == 1){//if user is active
                        $update_data = array();
                        $session_data['userId']     =       $result->userId ;
                        $session_data['emailId']    =       $result->email;
                        $session_data['name']       =       $result->fullname;
                        $session_data['profileImage']  =    $result->profileImage;
                        $session_data['isLogin']    =       TRUE;
                        $_SESSION[USER_SESS_KEY] = $session_data;
                        //pr($_SESSION[USER_SESS_KEY]);
                         return array('type'=>'LS'); //login successfull

                } else {
                    return array('type'=>'NA'); // not active
                }
            } else {
                return array('type'=>'WP'); //wrong password
            }
        } 
        return FALSE;
    }///end of function...


    function forgetPassword($table,$data){ //function for forget password 
        $get = array();
        $this->load->library('Smtp_email');
        //smtp email send library..
        $email = $data['email'];
        $query = $this->db->select('*')->where('email',$email)->get($table);
        if($query->num_rows()==1){
            $getData        =   $query->row();
            $get['userId']      =   $getData->userId;
            $temp_pass      =   md5(uniqid());
            $userUpdate     =   $this->db->set(array('forgetPass'=>$temp_pass))->where(array('userId'=>$get['userId']))->update($table);
            if($userUpdate){
                $query = $this->db->select('*')->where('userId',$get['userId'])->get($table);

                if($query->num_rows()==1){
                    $dataUser = $query->row();

                    $dataSend['name']       = $dataUser->fullname;
                    $dataSend['message']    = 'Reset Link for You Email:'.''.$data['email'].' '.'is'.'';
                    $dataSend['email']      = $data['email'];
                    $dataSend['link']       = base_url()."user/setPasswordUser/?token=".$dataUser->forgetPass."&userid=".encoding($get['userId']);//used md5 for encrypt user id becase it will give always same encode for same digit.
                    $message = $this->load->view('email/reset_password',$dataSend,TRUE);
                    //email template load from this.
                    $subject = "Reset password";
                    $isSent = $this->smtp_email->send_mail($data['email'],$subject,$message);
                   
                    //email sent to user email id.
                    if($isSent == 1){
                        return array('type'=>'ES');//Email Send.
                    }
                    return FALSE;
        
                } else{
                    return FALSE; //Not Sent
                }
            } 
        }//Email Found on database
        else{
            return array('type'=>'IE'); //Invalid Email
        }
    }//END OF FUNCTION..

}