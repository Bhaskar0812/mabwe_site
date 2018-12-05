	
<div class="wraper light-bg">
	<div class="mt-78 pb-20 profile-section">
		<div class="container">
			<div class="row ">
				<div class="col-md-4 col-lg-4 col-sm-12">
					<div class="profile-details mt-40 ">
						<form id="profileImageUpdate" action="updateProfileImage" method="POST" enctype="multipart/form-data">
							<div class="form-group usr-grp">
			    				<div class="usr-img">
									<img src="<?php 
					                if(!empty($userData->profileImage) && file_exists(USER_IMG_PATH_THUB.$userData->profileImage)){ 
					                  echo base_url().USER_IMG_PATH_THUB.$userData->profileImage;
					                }else{
					                  echo base_url().PLACEHOLDER;
					                } 
					                ?>" class="rounded-circle" id="pImg" alt="">
				                    <div class="text-center upload_sec"> 
				                      <input accept="image/*" class="inputfile hideDiv" id="file-2" name="profileImage" onchange="document.getElementById('pImg').src = window.URL.createObjectURL(this.files[0])" style="display: none;" type="file">
				                      <label for="file-2" class="upload_pic">
				                        <span class="fa fa-camera"></span></label>
				                    </div>

				                    <?php $csrf = get_csrf_token();
					                  ?>
					                  <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>">
			    				</div>
			    				 <center><span style="color: gray;font-size: 11px;">Image should be atleast 600 * 600px</span></center>
			    			</div>					
	    				</form>
	    				<div class="details-txt text-center">
	    					<h2><?php if(!empty($userData)){ echo ucfirst($userData->fullname);}?></h2>
	    					<p><?php if(!empty($userData)){ echo display_placeholder_text($userData->email);}?></p>
	    					<div class="usr-more">
								<div class="row">
									<div class="col brif-txt">
										<i class="fa fa-briefcase"></i>
										<h4><?php if(!empty($userData)){ echo display_placeholder_text(ucfirst($userData->profession));}?></h4>
									</div>
									<div class="col">
										<i class="fa fa-flag"></i>
										<h4><?php if(!empty($userData)){ echo display_placeholder_text($userData->country);}?></h4>
									</div>
								</div>
							</div>
							<!-- <div>
								<a href="index.html" class="btn btn-block lgt-btn"><i class="fa fa-sign-out"></i>Logout</a>
							</div> -->
	    				</div>
			    	</div>						
				</div>
				<div class="col-md-8 col-lg-8 col-sm-12">
					<div class="edit-tabs-sec mt-40">
						<ul class="nav edit-nav nav-tabs nav-fill" id="pills-tab" role="tablist">
						  <li class="nav-item">
						    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"><i class="fa fa-edit"></i> Edit Profile</a>
						  </li>
						  <li class="nav-item">
						    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false"><i class="fa fa-lock"></i> Change Password</a>
						  </li>
						  <!-- <li class="nav-item">
						    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false"><i class="fa fa-bell-o"></i> Notification</a>
						  </li> -->
						</ul>
						<div class="tab-content user-content" id="pills-tabContent">
						  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
						  	<div class="col-lg-8 offset-lg-2">
							  	<form id="updateProfile">
									<div class="form-group">
									  <label for="unm">Name</label>
								      <input type="text" class="form-control main-control" id="fullName" name="fullName" value="<?php if(!empty($userData)){ echo ucfirst($userData->fullname);}?>" placeholder="Full Name">
								    </div>
								    <div class="form-group">
								      <label for="profession">Profession</label>
								      <input type="text" name="profession" class="form-control main-control" id="profession"  value="<?php if(!empty($userData)){ echo ucfirst($userData->profession);}?>" placeholder="Profession">
								    </div>
								     <?php $csrf = get_csrf_token();
					                  ?>
					                  <input id="csrfU" type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>">
								    <div class="form-group">
									    <label for="location">Country</label>
									    <input type="hidden" name="addresss" class="form-control main-control" id="address"  value="" placeholder="Country">
									    <input type="hidden" name="fbUserLocLat" id="fbUserLocLat">
									    <input type="hidden" name="fbUserLocLong" id="fbUserLocLong">

								     	<!-- <input type="hidden" name="country" id="country" > -->
								     	<!-- <input type="text" name="address" class="form-control main-control" id="basic"  value="" placeholder="Country"> -->
								     	<div id="basic" class="our-seclt" data-input-name="address" data-selected-country="<?php if(!empty($userData)){ echo $userData->countryShortName;}?>">
								     		<input type="hidden" name="country_name" id="country_name">
								     		<input type="hidden" name="country" id="country">

								     	</div>
								    </div>
								     <button type="submit" id="update-profile" class="btn thm-btn btn-block mt-30">Update</button>
								</form>	
							</div>
						  </div>
						  <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
						  	<div class="col-md-8 offset-md-2">
						  		<form id="changePassword">
					                <div class="form-group">
					                	<label for="oldpwd">Old Password</label>
					                	<input type="password" class="form-control main-control" name="password" id="password" placeholder="Old Password">
					                </div>
					                <div class="form-group">
					                  <label for="newpwd">New Password</label>
					                  <input type="password" class="form-control main-control" name="npassword" id="npassword" placeholder="New Password">
					                </div>
					                <div class="form-group">
					                  <label for="cnfmpwd">Confirm Password</label>
					                  <input type="password" class="form-control main-control" name="cpassword" id="cpassword" placeholder="Confirm Password">
					                  <?php $csrf = get_csrf_token();
					                  ?>
					                  <input id="csrf" type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>">
					                </div>					                
					                <button type="submit" class="btn thm-btn btn-block mt-30">Update</button>
					            </form>
						  	</div>
						  </div>
						  <!-- <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
						  	<div class="profile-notification">
						  		<ul>
						  					                <li>
						  					                  <a href="javascript:void(0);">
						  					                    <div class="section-left">
						  					                      <img src="img/8.jpg" alt="">
						  					                    </div>
						  					                    <div class="section-right">
						  					                      <h6> <span>Jack</span> Comment on your new post </h6>
						  					                      <p>1 min ago</p>
						  					                      <div class="icon">
						  					                        <i class="fa fa-comment-o"></i>
						  					                      </div>
						  					                    </div>                    
						  					                  </a>
						  					                </li>
						  					                <li>
						  					                  <a href="javascript:void(0);">
						  					                    <div class="section-left">
						  					                      <img src="img/9.jpg" alt="">
						  					                    </div>
						  					                    <div class="section-right">
						  					                      <h6><span>Kelly</span> Comment on your new post </h6>
						  					                      <p>4 min ago</p>
						  					                      <div class="icon">
						  					                        <i class="fa fa-comment-o"></i>
						  					                      </div>
						  					                    </div>                    
						  					                  </a>
						  					                </li>
						  					                <li>
						  					                  <a href="javascript:void(0);">
						  					                    <div class="section-left">
						  					                      <img src="img/10.jpg" alt="">
						  					                    </div>
						  					                    <div class="section-right">
						  					                      <h6><span>John Doe</span> like on your new post </h6>
						  					                      <p>6 min ago</p>
						  					                      <div class="icon">
						  					                        <i class="fa fa-heart-o"></i>
						  					                      </div>
						  					                    </div>                    
						  					                  </a>
						  					                </li>
						  					                <li>
						  					                  <a href="javascript:void(0);">
						  					                    <div class="section-left">
						  					                      <img src="img/10.jpg" alt="">
						  					                    </div>
						  					                    <div class="section-right">
						  					                      <h6><span>John Doe</span> like on your new post </h6>
						  					                      <p>4 min ago</p>
						  					                      <div class="icon">
						  					                        <i class="fa fa-heart-o"></i>
						  					                      </div>
						  					                    </div>                    
						  					                  </a>
						  					                </li>
						  					             </ul>
						  	</div>
						  </div> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	
<script type="text/javascript">
	document.getElementById("file-2").onchange = function() {
  show_loader();
  document.getElementById("profileImageUpdate").submit();
   
};

</script>