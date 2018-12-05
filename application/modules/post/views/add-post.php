
<div class="wraper light-bg">
  <section class="mt-78 pb-20 post-section">
  	<div class="container">
  		<div class="row">
  			<div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-12 col-12">
  				<div class="add-post-sec mt-40">
  					<form action="add_post_submit" id="posts">
  						<div class="form-group">
	  						<h2>Add Post</h2>
	  					</div>
	  					<div class="form-group">
	  						<div class="add-icons">
		  						<a href="javascript:void(0);" id="Video" onclick="videoSelect();"><h6 id="clr-icon"><i class="fa fa-video-camera"></i></h6></a>
		  						<a href="javascript:void(0);" id="Imgs" ><h6 id="clr-icon1"><i class="fa fa-file-image-o"></i></h6></a>
	  						</div>
	  						<span id="msgText" style="color:gray;font-size: 12px; display:none;">Image should be atleast 700*466px, Maximum 5 Images can upload.</span><span id="msgVideoText" style="color:gray;font-size: 12px; display:none;">Video should be Maximum 10Mb.</span>
	  					</div>
	  					<video width="500" id="showVideo" controls ><source src="" id="video_here"></video>
	  					<div id="video-sec" class="form-group clearfix" style="display: none;">
	  						<div class="add-media">	  							
  								<div class="add-img addvideo-img">
									<label for="add-video">
						                <input class="form-control main-control" id="add-video" type="file" name="video" accept="video/mp4">
						                
						                <div class="add-icon">                    
						                  <i class="fa fa-plus"></i>
						                </div>
						            </label>
								</div>						
	  						</div>
  						</div>
  						
	  					<div id="img-sec" class="form-group clearfix" style="display: none;">
	  						<ul class="add-media" >
	  							<div id="imagePreview">
	  							</div>

	  							<!-- <li >
	  								<div class="upload-img">
	  									<div class="img-sec" >
											
										</div>
										
									</div>
	  							</li> -->

	  							<li>
	  								<div class="add-img" id="selectImageButton">
										<label for="add-img">
							                <input class="form-control main-control" id="add-img" type="file" name="postImage" accept="image/jpeg" multiple/ >
							                <div class="add-icon">                    
							                  <i class="fa fa-plus"></i>
							                </div>
							            </label>
									</div>
	  							</li>	  							
	  						</ul>
  						</div>
  						<div class="form-group">
  							<select id="post" name="category" class="form-control main-control custom-select">
  								<option value="">Select Post Category</option>
  								<?php $category = get_category(); foreach($category as $key => $val){?>
  								<option value="<?php echo $key;?>"><?php echo $val;?></option>
					      		<?php } ?>
					         </select>
  						</div>
  						<div class="form-group">
  							<input id="title" name="title" type="text" class="form-control main-control" placeholder="Post Title">
  						</div>
  						<div class="form-group tag multiple-tag multiple search selection">
	                        <select class="form-control main-control custom-select ui fluid search dropdownInt " name="tags" id="tags" multiple/ >
	                          <option value="">Add Tag</option>
	                          <?php if(!empty($tags)){

	                          	foreach($tags['result'] as $value){
	                          	?>
	                          <option><?php  echo '#'.$value->tagName ; ?></option>
	                          <?php } }?>
	                        </select ><span style="color: gray; font-size:12px;">Type and press enter to add your own tag.</span>
                        </div>
  						<div class="form-group">
  							<input type="text" id="address" name="address" class="form-control main-control" placeholder="Add Location">


  							<input type="hidden" name="fbUserLocLat" id="fbUserLocLat">
							<input type="hidden" name="fbUserLocLong" id="fbUserLocLong">
							<input type="hidden" name="country" id="country">
							<input type="hidden" name="country_short_name" id="country_short_name">
							<input type="hidden" name="state" id="state">
							<input type="hidden" name="city" id="city">

  						</div>  						
  						<div class="form-group">
  							<textarea placeholder="Description" name="description" class="form-control main-textarea"></textarea>
  						</div>
  						<div id="Marketplace" style="display: none;">
  							<div class="form-group">
	  							<div><label for="description">Are You Willing to ship?</label></div>
	  							<div class="form-check-inline">
								  <label class="form-check-label">
								    <input type="radio" class="form-check-input" value="1" name="whilingToship">Yes
								  </label>
								</div>
								<div class="form-check-inline">
								  <label class="form-check-label">
								    <input type="radio" class="form-check-input" name="whilingToship" value="0">No
								  </label>
								</div>
	  						</div>
	  						<div class="form-group">
	  							<label for="">Contact Information</label>
	  							<input type="text" placeholder="Email" name="email" class="form-control main-control">
	  							<input type="text" placeholder="Contact No." name="contact" class="form-control main-control mt-10">
	  						</div>
	  						<div class="form-group form-check">
	  							<label class="form-check-label">
				                    <input class="form-check-input" type="checkbox"> Reviews
				                </label>
	  						</div>
  						</div>
  						<div id="Career" style="display: none;">
	  						<div class="form-group">
	  							<div><label for="description">Are You legally authorized to work in this Country?</label></div>
	  							<div class="form-check-inline">
								  <label class="form-check-label">
								    <input type="radio" class="form-check-input" name="authorised">Yes
								  </label>
								</div>
								<div class="form-check-inline">
								  <label class="form-check-label">
								    <input type="radio" class="form-check-input" name="authorised">No
								  </label>
								</div>
	  						</div>
	  						<div class="form-group">
	  							<div><label for="description">Are You Willing to relocate?</label></div>
	  							<div class="form-check-inline">
								  <label class="form-check-label">
								    <input type="radio" class="form-check-input" name="relocate" value="1">Yes
								  </label>
								</div>
								<div class="form-check-inline">
								  <label class="form-check-label">
								    <input type="radio" class="form-check-input" name="relocate" value="0">No
								  </label>
								</div>
	  						</div>	
	  						<?php $csrf = get_csrf_token();
					         ?>
	  						<input id="csrf" type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>">
  						</div>  						
  						<div class="form-group text-center">
  							<button type="submit" class="btn thm-btn">Add</button>
  							<a href="<?php echo base_url();?>" class="btn thm-cncl">Cancel</a>
  							
  						</div>
  					</form>
  				</div>
  			</div>
  		</div>
  	</div>
  </section>




</div>
<script>
	$("#Video").click(function () {
	    $("#video-sec").show();
	    $("#clr-icon").addClass("active-icn");
	    $("#clr-icon1").removeClass("active-icn");
	    $("#img-sec").hide();
	  });
	  $("#Imgs").click(function () {
	    $("#img-sec").show();
	    $("#clr-icon").removeClass("active-icn");
	    $("#clr-icon1").addClass("active-icn");
	    $("#video-sec").hide();
	  });


$("#Imgs").click(function(e){
	e.preventDefault();
	$("#msgText").show();
	$("#msgVideoText").hide();
	$("#video_here").attr("src","")
	$("#showVideo").hide();
	$("#add-video").val('');
	
});
function videoSelect(){
	$("#msgText").hide();
	$("#msgVideoText").show();
	$(".img-fluid").attr('src','');
	$(".priviewImage").remove();
	
	//$(".img-fluid").attr('src','');

	
	//$("#imagePreview").remove();
}

var src = $("#video_here").attr('src');
if(src == ''){
	$("#showVideo").hide();
	
}


</script>


</html> 