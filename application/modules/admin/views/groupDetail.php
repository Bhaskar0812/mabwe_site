<?php //echo count($groupsMembers);die; ?>
<input type="hidden" value="<?php echo $group_id; ?>" id='groupcomnt' name="">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        Group Detail
        </h1>
        <ol class="breadcrumb">
             <li><a href="<?php echo base_url();?>admin"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url();?>admin/groups/groupList">Group List</a></li>
            <li>Group Detail</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-2">
            </div>
            <div class="col-md-8">
                <!-- Box Comment -->
                <div class="box box-widget">
                    <div class="box-header with-border">
                        <div class="col-md-10">
                        <div class="user-block">
                            <?php 
                            if(!empty($groups['0']->profile_image)){ ?>
                                <img id="blah" class="img-responsive img-circle" src="<?php echo $groups['0']->profile_image; ?>" alt="User profile picture">
                                <? }else {
                                    $imgPath = base_url().DEFAULT_IMAGE; ?>
                                    <img id="blah" class="img-responsive img-circle" src="<?php echo $imgPath; ?>" alt="User profile picture">
                                    <?php

                                    }  
                                  ?>
                                <span class="username"><a href="<?php echo base_url().'admin/users/userDetail/.'.encoding($groups['0']->userId); ?>"><?php echo $groups['0']->fullname; ?></a></span>
                                <span class="description"><?php echo $groups['0']->Date; ?></p></span>
                        </div>
                        </div>
                        <div class="col-md-2">
                            <span class="username"><a href="" data-toggle="modal" data-target="#myModal"><?php echo !empty($groupsMembers)?count($groupsMembers):''; echo "&nbsp&nbsp"; ?>Members</a></span>
                        </div>
                    </div>
                    <div class="box-body img-box">
                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                            <!-- <div class="carousel-inner"> -->
                               
                                <?php if (!empty($groups['0']->groupImage)) { 
                                 $existThumb = base_url().UPLOAD_FOLDER.'/group/large/';
                                 ?>
                                <div class="item active">
                                     <img src="<?php echo isset($groups['0']->group_image) ? $groups['0']->group_image: '' ; ?>" alt="First slide" >
                                </div>
                                <?php } ?>
                                
                            <!-- </div> -->
                            <!-- <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                                <span class="fa fa-angle-left"></span>
                            </a>
                            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                                <span class="fa fa-angle-right"></span>
                            </a> -->
                        </div>

                        <p><?php echo $groups['0']->groupName; ?></p>

                        <span class="pull-right text-muted"><a href="" data-toggle="modal" data-target="#myModals"><?php echo !empty($likes)?count($likes):''; ?> likes -</a> <?php echo $groups['0']->comment_count; ?> comments</span>
                    </div>
                    
                    <div class="box-footer box-comments">
                        <div class="customerData"></div> <!-- Data append here -->
                        <div class="scroll_loader text-center" data-offset="0" data-is-next="1" style="display:none">
                               <img height="50" width="50" src="<?php echo base_url('uploads/group/loader.gif'); ?>">
                    
                         </div>
                    </div>

                    <div class="text-center" style="display:none">

                            <button type="button" class="load_more_customer btn btnTheme btn-bg " data-check="group">Load more
                            </button>
                    </div> 
                        
                </div>
            </div>
        </div>
      </section>
</div>

<!-- /.content-wrapper -->
<!-- /.LOAD MORE FOR GROUP MEMBERS IN GROUP START -->

<div class="modal fade" id="myModal" role="dialog">
<div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Group Members</h4>
  </div>
  <div id="qwerty" class="modal-body">
    <div class="box-footer box-comments">
    <div class="groupMemders"></div> <!-- Data append here -->
    <div class="scroll_loader_members text-center" data-offset="0" data-is-next="1" style="display:none">
           <img height="50" width="50" src="<?php echo base_url('uploads/group/loader.gif'); ?>">

     </div>
</div>

<div class="text-center" style="display:none">

        <button type="button" class="load_more_group_members btn btnTheme btn-bg " data-check="group">Load more
        </button>
</div> 

  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  </div>
</div>
</div>
</div>
<!-- /.LOAD MORE FOR GROUP MEMBERS IN GROUP END -->

<!-- /.LOAD MORE FOR GROUP LIKES IN GROUP START -->

<div class="modal fade" id="myModals" role="dialog">
<div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Group Likes</h4>
  </div>
  <div id="qwerty" class="modal-body">
    <div class="box-footer box-comments">
    <div class="groupLikes"></div> <!-- Data append here -->
    <div class="scroll_loader_likes text-center" data-offset="0" data-is-next="1" style="display:none">
           <img height="50" width="50" src="<?php echo base_url('uploads/group/loader.gif'); ?>">

     </div>
</div>

<div class="text-center" style="display:none">

        <button type="button" class="load_more_group_likes btn btnTheme btn-bg " data-check="group">Load more
        </button>
</div> 

  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  </div>
</div>
</div>
</div>
<!-- /.LOAD MORE FOR GROUP LIKES IN GROUP END -->

