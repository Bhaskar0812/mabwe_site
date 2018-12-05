<?php 
class Service_model extends CI_Model {
	//Generate token for user
	function _generate_token(){
		$this->load->helper('security');
		$salt = do_hash(time().mt_rand());
		$new_key = substr($salt, 0, 20);
		return $new_key;
	}
	
	//Function for check provided token is valid or not
	function isValidToken($authToken,$table){
		$this->db->select('*');
		$this->db->where('authToken',$authToken);
		if($query = $this->db->get($table)){
			if($query->num_rows() > 0){
				return $query->row();
			}
		}		
		return FALSE;
	}

	function random_password( $length = 8 ) {
	    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,?";
	    $password = substr( str_shuffle( $chars ), 0, $length );
	    return $password;
	}

	function userInfo($id,$table){
		$res = $this->db->select('id,fullName,email,authToken,deviceToken,deviceType,profileImage')->where($id)->get($table);
		if($res->num_rows()){
			$result = $res->row();
			if (!empty($result->profileImage) && filter_var($result->profileImage, FILTER_VALIDATE_URL) === false) {
				//$result->profileImage = base_url().UPLOAD_FOLDER.'/profile/thumb/'.$result->profileImage;
				$result->profileImage = base_url().UPLOAD_FOLDER.'/profile/'.$result->profileImage;
			}			
			return $result;
		} else{
			return false;
		}
	}

	//get user detail
	function isLogin($data,$authToken,$where,$table){
		$res = $this->db->select('*')->where($where)->get($table);
		if($res->num_rows() > 0){
			$result = $res->row();
			$password = $data['password'];
			//var_dump(password_verify($password,$result->password));die;
			if(password_verify($password,$result->password)){
				if($result->status == 1){//if user is active
						$update_data = array();
						$update_data['deviceToken'] = $data['deviceToken'];
						$update_data['deviceType'] = $data['deviceType'];
						$update_data['authToken'] = $authToken;
					if(!empty($update_data['deviceToken'])){
						$this->db->update($table,array('deviceToken' => ''),array('deviceToken'=>$update_data['deviceToken']));
						$this->db->update($table,$update_data,array('id'=>$result->id));
						$userDetail = $this->businessInfo($result->id,$table);
						return array('type'=>'LS','userDetail'=>$userDetail); //login successfull
					} else{
						$this->db->update($table,array('authToken'=>$data['authToken']),array('id'=>$result->id));
						$userDetail = $this->businessInfo($result->id,$table);
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
	}
	
	function forgetPassword($table,$data){ 
    //function for forget password 
        $get = array();
        $this->load->library('Smtp_email');
        //smtp email send library..
        $email = $data['email'];
        $query = $this->db->select('*')->where('email',$email)->get($table);
        if($query->num_rows()==1){
            $getData 		= 	$query->row();
            $get['userId'] 		= 	$getData->userId;
            $temp_pass 		= 	md5(uniqid());
            $userUpdate		= 	$this->db->set(array('forgetPass'=>$temp_pass))->where(array('userId'=>$get['userId']))->update($table);
            if($userUpdate){
                $query = $this->db->select('*')->where('userId',$get['userId'])->get($table);
                if($query->num_rows()==1){
	                $dataUser = $query->row();
	                $dataSend['name']       = $dataUser->fullname;
	                $dataSend['message']    = 'Reset Link for You Email:'.''.$data['email'].' '.'is'.'';
	                $dataSend['email']      = $data['email'];
	                $dataSend['link']       = base_url()."user/setPasswordUser/?token=".$dataUser->forgetPass."&userid=".encoding($get['userId']);//used encoding for encrypt user id becase it will give always same encode for same digit.
	                 $dataSend['browser']   = $_SERVER['HTTP_USER_AGENT'];
	                 // this will give browser name and os detail.
	                $message = $this->load->view('email/reset_password',$dataSend,TRUE);
	                //email template load from this.
	                $subject = "Reset password";
	                $isSend = $this->smtp_email->send_mail($data['email'],$subject,$message);
	                //email sent to user email id..
	                if($isSend){
	                	return array('type'=>'ES','userDetail'=>$dataUser);//Email Send.
	            	}else{
	            		return FALSE;
	            	}
                } else{
                    return FALSE; //Not Sent
                }
            } 
        }//Email Found on database
        else{
            return array('type'=>'IE','userDetail'=>array()); //Invalid Email
        }
    }//END OF FUNCTION..

    

  	function customGet($select,$where,$table){
        //select single data..with given criteria..
        $this->db->select($select);
        $this->db->where($where);
        $query = $this->db->get($table);
        if($query->num_rows()==1){
        $user = $query->row();
        return $user;
        }
    }//END OF FUNCTION..

	function generateAuthToken(){
    	$authToken 	= $this->_generate_token();
    	$existToken = $this->common_model->get_records_by_id(ADMIN,true,array('authToken'=>$authToken),"*","","");
    	if(!empty($existToken)){
    	 	$this->_generate_token(); 	
    	} else{
    	 	$authToken = $authToken;
    	}
    	return $authToken;
    }
	
}//END OF CLASS.

