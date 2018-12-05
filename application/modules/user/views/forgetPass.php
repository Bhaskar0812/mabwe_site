

<section class="section_2 sec-pd-50">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="banner-sec">
          <div class="bnnr">
            <div class="banner-lft">
              <img src="<?php echo base_url().MWIMAGES;?>icon.png" alt="">
            </div>
            <div class="banner-rht">
              <h2>Own it</h2>
              <h5>We are 54</h5>
              <h5>We are one</h5> 
              <h5>We are Africa</h5>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-5 offset-lg-1 col-md-6 col-sm-12 col-12">
        <div class="form-sec">        
          <h3><center>Set Password</center></h3>
          <div class="tab-content">
            <div id="home" class="container tab-pane active">
              
              <form id="setPassUser" class="mt-30">
                <div class="form-group">
                   <input type="password"  id="password" class="form-control" placeholder="Password" name="password">
                 
                </div>
                <div class="form-group">
                   <input type="password" class="form-control" placeholder="Confirm Password" name="cpassword">
                  <input type="hidden" class="form-control" placeholder="Confirm Password" name="id" value="<?php echo $admin->userId;?>">

                  <?php $csrf = get_csrf_token();
                  ?>
                  <input id="csrfL" type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>">
                </div>
                <div class="form-group form-check ">
                  <label class="form-check-label">
                    <!-- <input class="form-check-input" type="checkbox"> Remember me -->
                  </label>
                  
                </div>
                 <button type="submit" id="sub_btn" class="btn thm-btn btn-block mt-30">Set</button>
                
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