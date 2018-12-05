

<div class="media">
  <a href="other-profile.html"><img class="mr-2 rounded-circle" src="<?php if(!empty($comments->profileImage) AND file_exists(USER_IMG_PATH_THUB.$comments->profileImage)){echo base_url().USER_IMG_PATH_THUB.$comments->profileImage; }else{echo base_url().PLACEHOLDER;}?>" width="40px"></a>
  <div class="media-body comments-media-body">
  	<div class="comments-media-lft">
	    <a href="<?php echo base_url().'user/userProfile/?user_id='. encoding($comments->comment_user_id);?>"><h5 class="mt-0"><?php echo $comments->fullname;?></h5></a>
	    <p><?php echo time_elapsed_string($comments->comment_crd);?></p>
    </div>							    
  </div>
</div>
<div class="comments-txt">
	<p><?php echo $comments->comment;?></p>
</div>