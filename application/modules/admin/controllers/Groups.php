<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends CommonBack {

    public $data = "";

    function __construct() {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('group_model');
        $this->check_admin_user_session();
    }

    public function editFn(){
        $data['parent'] = "Category";
        $data['title']  = "Category";
        $table = ADMIN;
        $catTable = CATEGORIES;
        $where = array('id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id']);
        $id=decoding($this->input->post('id'));
        $array = array('categoryId'=>$id);
        $data['admin'] = $this->common_model->select_row($where,$table);
        $data['categories'] = $this->common_model->select_row($array,$catTable);
        //pr($data['categories']);
        $this->load->view('groupMembers',$data,'');
    }

    public function groupDetail($id){
        
            $data['parent'] = "User";
            $data['title'] = "User";
            $table = ADMIN;
            $userTable = GROUPS;
            $where = array('id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id']);
            $data['user_id'] = $_SESSION[ADMIN_USER_SESS_KEY]['id'];
            $data['group_id'] = $group_id = decoding($this->uri->segment(4));
            //echo $group_id;die;
            $whereUser = array('groupId'=> $group_id);
            //echo $post_id;die;
            $data['admin'] = $this->common_model->select_row($where,$table);
            $data['groups'] = $this->group_model->group_get($group_id,$where);
            $data['likes'] = $this->group_model->getGroupLikesCount($group_id);
            $data['groupsMembers'] = $this->group_model->getGroupMembers($group_id);
            //echo count($data['groupsMembers']);die;
            //pr($data['groupsMembers']);
            $this->load->admin_render('groupDetail',$data,'');
    }

    public function get_comment_on_group(){//CODE FOR LOAD MORE ON GROUP DETAIL
        $where = array();
        $table = GROUP_COMMENTS;
        $group_id = $this->uri->segment(4);
        $offset = 0;
        extract($_POST);
        $limit = 5;
        $total_comment = $this->group_model->getComments($group_id);
        $is_next = 0;
        $new_offset = $offset + $limit;
        if($new_offset<count($total_comment)){
        $is_next = 1;
        }
        $data['is_next'] = $is_next;
        $data['new_offset'] = $new_offset;
        $data['comment_list'] = $this->group_model->getCommentsLists($group_id, $limit, $offset);
        $list_html = $this->load->view('groupDetailList',$data, true); //load event list view
        $res= array('status'=>1,'html'=>$list_html, 'is_next'=>$is_next, 'new_offset'=>$new_offset,'total'=>$total_comment);
        echo json_encode($res); exit; 
    }

    public function get_group_members(){//CODE FOR LOAD MORE ON GROUP DETAIL
        $where = array();
        $table = GROUP_COMMENTS;
        $group_id = $this->uri->segment(4);
        $offset = 0;
        extract($_POST);
        $limit = 5;
        $total_members = $this->group_model->getGroupMembers($group_id);
        $is_next = 0;
        $new_offset = $offset + $limit;
        if($new_offset<count($total_members)){
        $is_next = 1;
        }
        $data['is_next'] = $is_next;
        $data['new_offset'] = $new_offset;
        $data['member_list'] = $this->group_model->getMembersList($group_id, $limit, $offset);
        $list_html = $this->load->view('groupMembers',$data, true); //load event list view
        $res= array('status'=>1,'html'=>$list_html, 'is_next'=>$is_next, 'new_offset'=>$new_offset,'total'=>$total_members);
        echo json_encode($res); exit; 
    }

    public function get_group_likes(){//CODE FOR LOAD MORE ON GROUP DETAIL
        $where = array();
        $table = GROUP_COMMENTS;
        $group_id = $this->uri->segment(4);
        $offset = 0;
        extract($_POST);
        $limit = 5;
        $total_likes = $this->group_model->getGroupLikesCount($group_id);
        $is_next = 0;
        $new_offset = $offset + $limit;
        if($new_offset<count($total_likes)){
        $is_next = 1;
        }
        $data['is_next'] = $is_next;
        $data['new_offset'] = $new_offset;
        $data['groupLikesMember'] = $this->group_model->getGroupLikes($group_id, $limit, $offset);
        $list_html = $this->load->view('groupLikes',$data, true); //load event list view
        $res= array('status'=>1,'html'=>$list_html, 'is_next'=>$is_next, 'new_offset'=>$new_offset,'total'=>$total_likes);
        echo json_encode($res); exit; 
    }



    public function groupList(){
        
            $data['parent'] = "Groups";
            $data['title'] = "Groups";
            $table = ADMIN;
            $where = array('id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id']);
            $data['counts'] = $this->group_model->count_all('groups');
            $data['admin'] = $this->common_model->select_row($where,$table);
            $this->load->admin_render('groupList', $data, '');
    }

    public function getGroupList() { //get user list

        $list = $this->group_model->get_list(array('user_id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id'])); 
       
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