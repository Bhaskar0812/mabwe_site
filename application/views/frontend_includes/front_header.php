<!DOCTYPE html>

<?php $base_url = base_url();?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mabwe | 
<?php if($title){
    switch ($title){
    case "PROFILE":echo "Profile";break;case "NEWSFEED":echo "News Feeds";break;case "HOME":echo "Home";break; case "FORGETPASS":echo "Forget password";break;case "ADDPOST":echo "Add Post ";break;case "USERROFILE":echo "User Profile";break;case "POSTDETAIL":echo "Post Detail";break;case "GROUPDETAIL":echo "Group List";break;case "GROUPDETAILPAGE":echo "Group Detail";break;
    
    } } ?>

  </title>
  <link rel="icon" href="<?php echo $base_url.MWIMAGES;?>favicon.png" type="image/png">
  <link href="https://fonts.googleapis.com/css?family=Merienda+One|Muli:400,600,700,800,900" rel="stylesheet"> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="<?php echo $base_url.MWCSS;?>bootstrap.min.css" rel="stylesheet">
  <?php if(!empty($_SESSION[USER_SESS_KEY])){?>
  <link rel="stylesheet" href="<?php echo $base_url.MWCSS;?>perfect-scrollbar.css">
  <link rel="stylesheet" href="<?php echo $base_url.MWCSS;?>owl.carousel.min.css">
  <link rel="stylesheet" href="<?php echo $base_url.MWCSS;?>owl.theme.min.css">
  <?php } ?>
  <link rel="stylesheet" href="<?php echo $base_url.MWCSS;?>style.css">
  <link rel="stylesheet" href="<?php echo $base_url.MWCSS;?>responsive.css"> 
  <link rel="stylesheet" href="<?php echo $base_url.MWCCSS;?>custom.css"> 
  <link rel="stylesheet" href="<?php echo $base_url.MWCSS;?>semantic.min.css">
  <!-- <script src="jquery-3.3.1.min.js"></script> -->
  <link rel="stylesheet" href="<?php echo $base_url.MWTCSS;?>toastr.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script> <script src="<?php echo $base_url.MWTJS;?>toastr.min.js"></script>  
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJUXiRvFsWPITIcaFNCUUDxhgxDljdoUI&libraries=places"></script>
  <link href="<?php echo $base_url.MWCSS;?>flags.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.js"></script>
  <link href="<?php echo $base_url.MWCSS;?>flags.css" rel="stylesheet">
  <script src="<?php echo $base_url.MWJS;?>jquery.flagstrap.min.js"></script>  
  <script src="<?php echo $base_url.MWJS;?>owl.carousel.min.js"></script>
  <script src="<?php echo $base_url.MWJS;?>bootbox/bootbox.min.js"></script>

</head>

<body id="tl_admin_main_body" data-base-url="<?php echo base_url(); ?>">
<script type="text/javascript">
  toastr.options = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-top-center",
  "preventDuplicates": true,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}
</script>
<div id="preloader">
  <div id="preloader-status">
      <div class="spinner">
      
        <img src="<?php echo base_url().MWIMAGES;?>loaderIcon.apng" alt="">
      </div>
      <!-- <div id="preloader-title">Loading</div> -->
  </div>
</div>
<main>


<?php if(empty($_SESSION[USER_SESS_KEY])){?>
<script>
  <?php if(!empty($this->session->flashdata('error'))){?>
    toastr.error("<?php echo $this->session->flashdata('error');?>");
  <?php }?>
</script>
<section class="section_1">
  <header id="Home">
    <nav class="navbar navbar-expand-lg main-navbar navbar-light main-navbar fixed-top" >
      <div class="container">
        <a class="navbar-brand ml-auto" href="<?php echo base_url();?>"><img src="<?php echo base_url().MWIMAGES;?>mabwe-logo.png" alt=""></a>
      </div>
    </nav>
  </header>
</section>
<?php } else{?>

 <script>
  <?php if(!empty($this->session->flashdata('error'))){?>
    toastr.error("<?php echo $this->session->flashdata('error');?>");
  <?php }?>
</script>
<section class="section_1">
  <header>
    <nav class="navbar navbar-expand-lg main-navbar navbar-light main-navbar fixed-top" >
        <div class="container notif-header">
          <a class="navbar-brand" href="<?php echo base_url();?>"><img src="<?php echo base_url().MWIMAGES;?>mabwe-logo.png" alt=""></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
              <li class="nav-item <?php if($title != 'GROUPDETAIL'){echo "active";}?>">
                <a href="<?php echo base_url();?>" class="nav-link">News Feed<span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item"><a href="<?php echo base_url();?>groups/groups_views" class="nav-link <?php if($title == 'GROUPDETAIL'){echo "active";}?>">Groups</a></li>
              <li class="nav-item">
                <div class="profile dropdown">
                  <a id="dropdownMenuLink1" href="javascript:void(0);" class="img-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <!-- I have used thumb image to show profile image and checked if image not exist and empty show default image of mabwe -->
              <img src="<?php 
                if(!empty($userData->profileImage) && file_exists(USER_IMG_PATH_THUB.$userData->profileImage)){ 
                  echo base_url().USER_IMG_PATH_THUB.$userData->profileImage;
                }else{
                  echo base_url().PLACEHOLDER;
                } 
                ?>"></a>
                  <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink1" >
                    <li>
                      <a href="<?php echo base_url().'user/profile';?>"><i class="fa fa-user-o"></i>My Profile</a>
                    </li>
                    <li>
                      <a href="<?php echo base_url().'user/logout_user';?>"><i class="fa fa-sign-out"></i>Logout</a>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
          </div>
          <div class="dropdown notins-sec">
              <a class="notifications nav-link" href="javascript:void(0);" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-bell icon-bell"></i>
                <span class="num">5</span>
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                <div class="dropdown-header">
                  <h4>Notification</h4>
                </div>
                <ul class="dropdown-body perfectScrollbar">
                  <li>
                    <a href="javascript:void(0);" class="notif-sec">
                      <div class="section-left">
                        <img src="<?php echo base_url().MWIMAGES;?>8.jpg" alt="">
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
                    <a href="javascript:void(0);" class="notif-sec">
                      <div class="section-left">
                        <img src="<?php echo base_url().MWIMAGES;?>9.jpg" alt="">
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
                    <a href="javascript:void(0);" class="notif-sec">
                      <div class="section-left">
                        <img src="<?php echo base_url().MWIMAGES;?>10.jpg" alt="">
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
                    <a href="javascript:void(0);" class="notif-sec">
                      <div class="section-left">
                        <img src="<?php echo base_url().MWIMAGES;?>10.jpg" alt="">
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
                <div class="dropdown-footer">
                  <a href="javascript:void(0);">Load More</a>
                </div>
            </div>
          </div>               
      </div>      
    </nav>
  </header>
</section>
<?php } ?>