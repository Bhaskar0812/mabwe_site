
					
<?php if(!empty($commentsDetail)){ foreach($commentsDetail as  $value_comments){?>
	
<li>
	<div class="media">
	  <img class="mr-2 rounded-circle" src="<?php if(!empty($value_comments->profile_image) AND file_exists(USER_IMG_PATH_THUB.$value_comments->profile_image)){echo base_url().USER_IMG_PATH_THUB.$value_comments->profile_image; }else{echo base_url().PLACEHOLDER;}?>" width="45px">
	  <div class="media-body">								  	
	    <h5 class="mt-0"><a href="<?php echo base_url().'user/userProfile/?user_id='. encoding($value_comments->userId);?>"><?php echo $value_comments->fullname;?></a></h5>
	    <span><?php echo time_elapsed_string($value_comments->date);?></span>							    
	  </div>
	</div>
	<div class="user-commnt">
    	<p><?php echo $value_comments->comment;?></p>
    </div>
</li>
<?php } } ?>

				