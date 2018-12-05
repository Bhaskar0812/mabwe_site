<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends CommonBack {

    public $data = "";

    function __construct() {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('group_model');
        $this->check_user_session();
    }

    public function groupDetail($id){
        
            $data['parent'] = "User";
            $data['title'] = "User";
            $table = ADMIN;
            $userTable = GROUPS;
            $where = array('id'=> $_SESSION['biz_user_sess']['id']);
            $group_id = decoding($this->uri->segment(4));
            //echo $group_id;die;
            $whereUser = array('groupId'=> $group_id);
            //echo $post_id;die;
            $data['admin'] = $this->common_model->select_row($where,$table);
            $data['groups'] = $this->group_model->group_get($group_id,$where);
            //pr($data['groups']  );
            $this->load->admin_render('groupDetail',$data,'');
    }


    public function groupList(){
        
            $data['parent'] = "Group";
            $data['title'] = "Group";
            $table = ADMIN;
            $where = array('id'=> $_SESSION['biz_user_sess']['id']);
            $data['counts'] = $this->group_model->count_all('groups');
            //$data['count'] = $this->group_model->select_result('groups');
            //pr($data['count']);
            $data['admin'] = $this->common_model->select_row($where,$table);
            $this->load->admin_render('groupList', $data, '');
    }

    public function getGroupList() { //get user list

        $list = $this->group_model->get_list(array('user_id'=> $_SESSION['biz_user_sess']['id'])); 
       
        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            // print_r($data);die;
            $action ='';
            $no++;
            $row = array();
            if(empty($get->groupImage)){
                $imgPath = base_url().DEFAULT_IMAGE;
            }else{
                $imgPath = base_url().GROUP_IMAGE."/".$get->groupImage;
            }
            
            $row[] = $no;
            $row[] = '<a><img src="'.$imgPath.'" class="ListImage"></a>';
          
            $row[] = display_placeholder_text($get->groupName); 
            $row[] = display_placeholder_text($get->categoryName);
            $encoded = encoding($get->groupId);
            if($get->status){
                 
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Active'.'</span>';
                 $row[] = $status;
                 $title = 'Inactive';
                 $clkStatus = "statusFn('".GROUPS."','groupId','".$encoded."','$get->status','group','Group')" ;
                 $class = 'fa fa-times';

            }else{
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Inactive'.'</span>';
                 $row[] = $status;
                 $title = 'Active';
                 $clkStatus = "statusFn('".GROUPS."','groupId','".$encoded."','$get->status','group','Group')" ;
                 $class = 'fa fa-check';
            }
           $viewUrl = base_url().'admin/groups/groupDetail/'.encoding($get->groupId);
           $action .= '<a href="javascript:void(0)" onclick="'.$clkStatus.'" title="'.$title.'" class="on-default edit-row table_action" >'.'<i class="'.$class.'" aria-hidden="true"></i>'.'</a>';
           $action .= '<a href="'.$viewUrl.'" title="View" class="on-default edit-row table_action" >'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';
          

           $row[] = $action;
             $data[] = $row;
            $_POST['draw']='';
        }

        $output = array(
                "draw" => $_POST['draw'], 
                "recordsTotal" => $this->group_model->count_all(),
                "recordsFiltered" => $this->group_model->count_filtered(),
                "data" => $data
        );

        //output to json format
       echo json_encode($output);

    }//End function

    

}//end of class