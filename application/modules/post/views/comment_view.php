<?php if(!empty($comments)){?>

<li>
	<div class="media">
	  <img class="mr-2 rounded-circle" src="<?php if(!empty($comments->profileImage) AND file_exists(USER_IMG_PATH_THUB.$comments->profileImage)){echo base_url().USER_IMG_PATH_THUB.$comments->profileImage; }else{echo base_url().PLACEHOLDER;}?>" width="45px">
	  <div class="media-body">								  	
	    <h5 class="mt-0"><a href="<?php echo base_url().'user/userProfile/?user_id='. encoding($comments->comment_user_id);?>"><?php echo $comments->fullname;?></a></h5>
	    <span><?php echo time_elapsed_string($comments->comment_crd);?></span>							    
	  </div>
	</div>
	<div class="user-commnt">
    	<p><?php echo $comments->comment;?></p>
    </div>
</li>
<?php } ?>


				