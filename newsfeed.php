<input type="hidden" id="rowCount" value="<?php echo $count;?>">
<input type="hidden" id="page" value="">
<div class="wraper light-bg">
  <div class="mt-78 pb-20 newsfeed-section">
    <div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-4 col-sm-12 col-12">
				<div class="user-profile sticky text-center mt-40">
					<div class="user-pic"> 
						<img src="<?php 
						if(!empty($userData->profileImage) && file_exists(USER_IMG_PATH_THUB.$userData->profileImage)){ 
							echo base_url().USER_IMG_PATH_THUB.$userData->profileImage;
						}else{
							echo base_url().MWIMAGES.'default1.jpg';
						} 
						?>" alt="">
					</div>
					<div class="user-txt mt-40 pb-10">
						<h3><?php if(!empty($userData)){echo $userData->fullname;}?></h3>
						<p><?php if(!empty($userData)){ $userData->profession;}?></p>
					</div>
					<div class="profile-status">
						<div class="row">
							<div class="col grp-txt">
								<h4>Groups</h4>
								<p>12</p>
							</div>
							<div class="col pst-txt">
								<h4>Posts</h4>
								<p><?php echo $count;?></p>
							</div>
						</div>
					</div>
					<div class="add-post">
						<a href="<?php echo base_url();?>post/addPost" class="btn pst-btn"><i class="fa fa-plus"></i> Add Post</a>
					</div>				
				</div>
			</div>     
			<div class="col-lg-6 col-md-8 col-sm-12 col-12">
				<div class="feed-sec mt-40">
					<div class="feed-lft">
					<form class="filterApply">
						<div class="home-search-box " style="">
			   				<div class="input-group">
							  <input type="text" name="search" class="form-control main-control" placeholder="Search">
							  <div class="input-group-append">
							    <button class="btn search-btn" type="submit"><i class="fa fa-search"></i></button>
							  </div>
							</div>
			      		</div>
			      	</form>
					</div>
					<div class="feed-rght">
						<div class="filter">
			      			<div class="add-group text-right">
				          		<a href="javascript:void(0);" class="" data-toggle="modal" data-target="#Filter" ><i class="fa fa-filter"></i></a>
				          	</div>
			      		</div>
					</div>
				</div>
				<div id="showNewsFedd"></div>
			</div>
			
			<div class="col-lg-3 col-md-8 col-sm-12 col-12 d-none d-sm-none d-md-none d-lg-block d-xl-block">
				<div class="div-fixed">
					<div class="group-section mt-40">
	    				<div class="group-head">
	    					<h2>Top Groups</h2>
	    				</div>
	    				<div class="group-list">
	    					<ul>
	    						<li>
	    							<div class="media">
	    							  <a href="javascript:void(0);"><img class="mr-2 rounded-circle" src="<?php echo base_url().MWIMAGES;?>7.jpg"></a>
	    							  <div class="media-body">
	    							    <a href="javascript:void(0);"><h5 class="mt-0">Mabwe411</h5></a>
	    							    <p>Lorem ipsum dolor sit amet.</p>
	    							  </div>
	    							</div>
	    						</li>
	    						<li>
	    							<div class="media">
	    							  <a href="javascript:void(0);"><img class="mr-2 rounded-circle" src="<?php echo base_url().MWIMAGES;?>6.jpg"></a>
	    							  <div class="media-body">
	    							    <a href="javascript:void(0);"><h5 class="mt-0">Career Hub</h5></a>
	    							    <p>Lorem ipsum dolor sit amet.</p>
	    							  </div>
	    							</div>
	    						</li>
	    						<li>
	    							<div class="media">
	    							  <a href="javascript:void(0);"><img class="mr-2 rounded-circle" src="<?php echo base_url().MWIMAGES;?>7.jpg"></a>
	    							  <div class="media-body">
	    							   <a href="javascript:void(0);"><h5 class="mt-0">#54 Consultancy</h5></a>
	    							    <p>Lorem ipsum dolor sit amet.</p>
	    							  </div>
	    							</div>
	    						</li>
	    					</ul>
	    				</div>
	    			</div>
					<div class="job-section mt-10">
						<div class="job-head">
							<h2>Top Career</h2>
						</div>
						<div class="job-list">
							<ul>
								<li>
									<a href="javascript:void(0);"><h6>Senior Product Designer</h6></a>
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
								</li>
								<li>
									<a href="javascript:void(0);"><h6>Senior Manager</h6></a>
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
								</li>
								<li>
									<a href="javascript:void(0);"><h6>Product Designer</h6></a>
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
								</li>
							</ul>
						</div>
					</div>			
				</div>	
			</div>
		</div>
    </div>
  </div>
</div>