

<section class="section_2 sec-pd-50">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="banner-sec">
          <div class="bnnr">
            <div class="banner-lft">
              <img src="<?php echo base_url().MWIMAGES;?>icon.png" alt="">
            </div>
            
          </div>
        </div>
      </div>
      <div class="col-lg-5 offset-lg-1 col-md-6 col-sm-12 col-12">
        <div class="form-sec">        
          <ul class="nav nav-pills nav-justified justify-content-center" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="login1" data-toggle="tab" href="#home">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="signup1" data-toggle="tab" href="#menu1">Signup</a>
            </li>
          </ul>
          <div class="tab-content">
            <div id="home" class="container tab-pane active">
              <form id="Login" class="mt-30">
                <div class="form-group">
                  <input type="text" class="form-control main-control"  placeholder="Email" name="email">
                </div>
                <div class="form-group">
                  <input type="password" name="password" class="form-control main-control"  placeholder="Password">

                  <?php $csrf = get_csrf_token();
                  ?>
                  <input id="csrfL" type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>">
                </div>
                <div class="form-group form-check ">
                  <label class="form-check-label">
                    <!-- <input class="form-check-input" type="checkbox"> Remember me -->
                  </label>
                  <label class="pull-right forgot-txt">
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#forgotpwd" >Forgot Password?</a>
                  </label>
                </div>
                 <button type="submit" class="btn thm-btn btn-block mt-30">Login</button>
                <div class="accnt-txt mt-20">
                  <p> Don't have an account? <a onclick="signupTab();" style="cursor: pointer;">Signup</a></p>
                </div>
              </form>
            </div>
            <div id="menu1" class="container tab-pane fade">
              <form id="signup" class="mt-30">
                <div class="form-group text-center">
                  <div class="profile-sec">
                    <img src="<?php echo base_url().MWIMAGES;?>user-icn.png" id="pImg">
                    <div class="text-center upload_sec"> 
                      <input accept="image/*" class="inputfile hideDiv" id="file-2" name="profileImage" onchange="document.getElementById('pImg').src = window.URL.createObjectURL(this.files[0])" style="display: none;" type="file">
                      <label for="file-2" class="upload_pic">
                        <span class="fa fa-camera"></span></label>
                    </div>
                    
                  </div><center><span style="color: gray;font-size: 11px;">Image should be atleast 600 * 600px</span></center>
                </div>
                
                <div class="form-group">
                  <input type="text" class="form-control main-control" placeholder="John Doe" name="userName">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control main-control"  placeholder="john@gmail.com" name="email">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control main-control"  placeholder="Password" name="password">
                  <?php $csrf = get_csrf_token();
                  ?>
                  <input id="csrf" type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>">
                </div>
               <button type="submit" class="btn thm-btn btn-block mt-30">Signup</button>
                <div class="accnt-txt mt-20">
                  <p>Already have an account? <a onclick="loginTab();" style="cursor: pointer;">Login</a></p>
                </div>
              </form>            
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="section_4" id="Features">  
  <div class="service-tab ">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <ul class="features">
            <li>
              <h4>Connect</h4>
            </li>
            <li>
              <h4 class="blue-clr">Career</h4>
            </li>
            <li class="mab-line">
              <h4 class="blue-clr">Mabwe411</h4>
            </li>
            <li>
              <h4>Marketplace Africa</h4>
            </li>
          </ul>
        </div>
      </div>        
    </div>
  </div>
</section>
<section class="abut_sec">
  <div class="container">
    <div class="row text-center">
      <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <h2><a href="javascript:void(0);">About Us :</a></h2>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <h2><a href="javascript:void(0);">How Mabwe works :</a></h2>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <h2><a href="javascript:void(0);">Download App :</a></h2>
      </div>
    </div>
  </div>
</section>
</main>
</body>
<div class="modal fade" id="forgotpwd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Forgot Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="forgetPassword" class="forgot-form">
          <div class="form-group">
            <h6>Can't remember your Password ? Don't wory- we're here to help.</h6>
          </div>  
          <div class="form-group">
            <input type="email" name="email" placeholder="Enter Your Email Address" class="form-control main-control">
          </div>
           <?php $csrf = get_csrf_token();?>
            <input id="csrfF" type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>">
          <div class="text-center">
            <button type="submit" class="btn thm-btn">Submit</button>
          </div>  
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  function signupTab(){
    $("#login1").removeClass('active');
    $("#signup1").addClass('active');
    $("#menu1").addClass('active');
    $("#menu1").addClass('show');
    $("#home").removeClass('active');
    $("#home").removeClass('show');
  }

  function loginTab(){
    $("#signup1").removeClass('active');
    $("#login1").addClass('active');
    $("#home").addClass('active');
    $("#home").addClass('show');
    $("#menu1").removeClass('active');
    $("#menu1").removeClass('show');
  }
</script>