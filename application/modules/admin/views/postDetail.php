<?php 
//echo $posts['0']->profile_image;die;
//pr($posts);
foreach ($posts as $users) {
 if ($users->postimage) {
   
    foreach($users->postimage as $key=> $value){    

    }
 }
}  
    //pr($posts);  die;
?>
<input type="hidden" value="<?php echo $post_id; ?>" id='qwerty' name="">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        Post Detail
        </h1>
        <ol class="breadcrumb">
             <li><a href="<?php echo base_url();?>admin"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url();?>admin/users/postList_ajax">Post List</a></li>
            <li>Post Detail</li>
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
                        <div class="user-block">
                            <?php 
                            if(!empty($posts['0']->profile_image)){ ?>
                                <img id="blah" class="img-responsive img-circle" src="<?php echo $posts['0']->profile_image; ?>" alt="User profile picture">
                                <? }else {
                                    $imgPath = base_url().DEFAULT_IMAGE; ?>
                                    <img id="blah" class="img-responsive img-circle" src="<?php echo $imgPath; ?>" alt="User profile picture">
                                    <?php

                                    }  
                                  ?>
                                <span class="username"><a href="<?php echo base_url().'admin/users/userDetail/.'.encoding($posts['0']->user_id); ?>"><?php echo $posts['0']->fullname; ?></a></span>
                                <span class="description"><?php echo $users->crd; ?></span>
                        </div>
                    </div>
                    <div class="box-body img-box">
                        
                        <?php 
                        $oneRow = $posts[0]; 
                        if (!empty($oneRow->postimage)) {
                        ?>
                            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                        <?php
                            foreach($oneRow->postimage as $key=> $value){ 
                                        if (!empty($value->post_image)) {
                                        ?>
                                        <div class="item <?php if($key==0){echo "active";}?>">
                                        <img src="<?php echo $value->post_image; ?>" alt="First slide">
                                        </div>
                                        <?php }
                                        }
                                    
                        ?>
                            </div>
                            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                                <span class="fa fa-angle-left"></span>
                            </a>
                            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                                <span class="fa fa-angle-right"></span>
                            </a>
                        </div>
                        <?php
                        }
                        ?>
                        

                        <p><?php echo $users->description; ?></p>
                        <span class="pull-right text-muted"><a href="" data-toggle="modal" data-target="#myModals"><?php echo $oneRow->like_count; ?> likes -</a> - <?php echo $oneRow->comment_count; ?> comments</span>
                    </div>

                    <div class="box-footer box-comments">
                        <div class="postComentData"></div> <!-- Data append here -->
                    <div class="scroll_loader text-center" data-offset="0" data-is-next="1" style="display:none">
                    <img width="50" height="50" src="<?php echo base_url('uploads/group/loader.gif'); ?>">
                    </div>
                    </div>
                    <div class="text-center" style="display:none">
                            <button type="button" class="load_more_customer btn btnTheme btn-bg" data-check="post">Load more
                            </button>
                    </div>   
                </div>
                <div>
                    
                <p style="align:text-center"></p>
                </div>
            </div>
        </div>
      </section>
</div>

<!-- /.content-wrapper -->
<!-- /.LOAD MORE FOR POST LIKES IN POST START -->

<div class="modal fade" id="myModals" role="dialog">
<div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Post Likes</h4>
  </div>
  <div id="qwerty" class="modal-body">
    <div class="box-footer box-comments">
    <div class="postLikes"></div> <!-- Data append here -->
    <div class="scroll_loader_likes_post text-center" data-offset="0" data-is-next="1" style="display:none">
           <img height="50" width="50" src="<?php echo base_url('uploads/group/loader.gif'); ?>">

     </div>
</div>

<div class="text-center" style="display:none">

        <button type="button" class="load_more_post_likes btn btnTheme btn-bg " data-check="group">Load more
        </button>
</div> 

  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  </div>
</div>
</div>
</div>
<!-- /.LOAD MORE FOR POST LIKES IN POST END -->