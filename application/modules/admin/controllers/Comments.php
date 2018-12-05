<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Comments extends CommonBack {

    public $data = "";

    function __construct() {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('comment_model');
        $this->check_admin_user_session();
    }

    public function commentDetail($id){
        
            $data['parent'] = "User";
            $data['title'] = "User";
            $table = ADMIN;
            $userTable = COMMENTS;
            $where = array('id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id']);
            $group_id = decoding($this->uri->segment(4));
            //echo $group_id;die;
            $whereUser = array('groupId'=> $group_id);
            //echo $post_id;die;
            $data['admin'] = $this->common_model->select_row($where,$table);
            $data['groups'] = $this->comment_model->count_all($group_id,$where);
            //pr($data['groups']  );
            $this->load->admin_render('commentDetail',$data,'');
    }


    public function commentList(){
        
            $data['parent'] = "Comment";
            $data['title'] = "Comment";
            $table = ADMIN;
            $where = array('id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id']);
            $data['counts'] = $this->comment_model->count_all('comments');
            //$data['count'] = $this->group_model->select_result('groups');
            //pr($data['count']);
            $data['admin'] = $this->common_model->select_row($where,$table);
            $this->load->admin_render('commentLIst', $data, '');
    }

    public function getCommentList() { //get user list

        $this->load->model('users_model');
        $list = $this->comment_model->get_list(array('user_id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id'])); 
       //pr($list);
        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            // print_r($data);die;
            $action ='';
            $no++;
            $row = array();
            /*if(empty($get->postImage)){
                $imgPath = base_url().DEFAULT_IMAGE;
            }else{
                $imgPath = base_url().GROUP_IMAGE."/".$get->postImage;
            }*/
            
            $row[] = $no;
            /*$row[] = '<a><img src="'.$imgPath.'" class="ListImage"></a>';*/
          
            $row[] = display_placeholder_text($get->comment); 
            $row[] = display_placeholder_text($get->title); 
            //$row[] = display_placeholder_text($get->commentId);
            $encoded = encoding($get->commentId);
            if($get->status){
                 
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Active'.'</span>';
                 $row[] = $status;
                 $title = 'Inactive';
                 $clkStatus = "statusFn('".COMMENTS."','commentId','".$encoded."','$get->status','comment','Comment')" ;
                 $class = 'fa fa-times';

            }else{
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Inactive'.'</span>';
                 $row[] = $status;
                 $title = 'Active';
                 $clkStatus = "statusFn('".COMMENTS."','commentId','".$encoded."','$get->status','comment','Comment')" ;
                 $class = 'fa fa-check';
            }
           $viewUrl = base_url().'admin/comments/commentDetail/'.encoding($get->commentId);
           $action .= '<a href="javascript:void(0)" onclick="'.$clkStatus.'" title="'.$title.'" class="on-default edit-row table_action" >'.'<i class="'.$class.'" aria-hidden="true"></i>'.'</a>';
           $action .= '<a href="'.$viewUrl.'" title="View" class="on-default edit-row table_action" >'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';
          

           $row[] = $action;
             $data[] = $row;
            $_POST['draw']='';
        }

        $output = array(
                "draw" => $_POST['draw'], 
                "recordsTotal" => $this->users_model->count_all(),
                "recordsFiltered" => $this->users_model->count_filtered(),
                "data" => $data
        );

        //output to json format
       echo json_encode($output);

    }//End function

    

}//end of class