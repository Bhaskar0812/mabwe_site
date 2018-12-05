
<div class="wraper light-bg">
	<div class="mt-78 pb-20 profile-section">
		<div class="container">
			<div class="row ">
				<div class="col-lg-6 offset-lg-3">
					<div class="other-profile mt-40 ">
						<div class="usr-img">
							<img src="<?php if(!empty($user->profileImage) AND file_exists(USER_IMG_PATH_THUB.$user->profileImage)){echo base_url().USER_IMG_PATH_THUB.$user->profileImage; }else{echo base_url().PLACEHOLDER;}?>" class="rounded-circle" id="pImg" alt="">							         
			    		</div>
			    		<div class="other-profile-list">

			    			<h2><?php echo display_placeholder_text($user->fullname);?></h2> 
				    		<ul>
				    			<li>
				    				<h6><i class="fa fa-envelope"></i><?php echo  display_placeholder_text($user->email);?></h6>				    				
				    			</li>
				    			<li>
				    				<h6><i class="fa fa-flag"></i><?php echo display_placeholder_text(!empty($user->country)?$user->country:'');?></h6>				    				
				    			</li>
				    			<li>
				    				<h6><i class="fa fa-briefcase"></i> <?php echo display_placeholder_text(!empty($user->profession)?$user->profession:'');?></h6>
				    			</li>
				    		</ul>
			    		</div>
			    		<div class="profile-status mt-10 text-center">
							<div class="row">
								<div class="col grp-txt">
									<h4>Groups</h4>
									<p> <?php echo !empty($group_count)?$group_count:0;?></p>
								</div>
								<div class="col pst-txt">
									<h4>Posts</h4>
									<p><?php echo !empty($post_count)?$post_count:0;?></p>
								</div>
							</div>
						</div>
			    	</div>						
				</div>
			</div>
		</div>
	</div>
</div>	
