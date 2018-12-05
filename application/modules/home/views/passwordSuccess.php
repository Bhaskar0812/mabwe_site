<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Trade & Control | Password</title>
  <?php $backend = base_url()."backend_assets/";?>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo $backend;?>bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $backend;?>dist/css/AdminLTE.min.css">
  <!-- Material Design -->
  <link rel="stylesheet" href="<?php echo $backend;?>dist/css/bootstrap-material-design.min.css">
  <link rel="stylesheet" href="<?php echo $backend;?>dist/css/ripples.min.css">
  <link rel="stylesheet" href="<?php echo $backend;?>dist/css/MaterialAdminLTE.min.css">

</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
     <a href="<?php echo base_url();?>admin"><img src="<?php echo base_url();?>backend_assets/logo/logo_1.png"></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"></p>
     <?php  if($this->session->flashdata('success') != null OR !empty($success)){ ?>
          <div class="alert alert-success">
                 
                  <!-- <h4><i class="icon fa fa-check"></i> Alert!</h4> -->
                   <?php echo !empty($success)? $success:''; ?>
                   <?php echo ($this->session->flashdata('success') != null) ?$this->session->flashdata('success'):''; ?>
                </div>
        <?php }else{ ?>
        	<?php echo '<div class="alert alert-success"><div ><h4>Please Use Application For Signup With New Password.</div></div></h4>'; ?>
        <?php } ?>


        <?php  if($this->session->flashdata('unsuccess') != null OR !empty($success)) : ?>
          <div class="alert alert-danger">
                  
                  <!-- <h4><i class="icon fa fa-check"></i> Alert!</h4> -->
                   <?php echo !empty($success)? $success:''; ?>
                   <?php echo ($this->session->flashdata('unsuccess') != null) ?$this->session->flashdata('success'):''; ?>
                </div>
        <?php endif; ?>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="<?php echo $backend;?>plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo $backend;?>bootstrap/js/bootstrap.min.js"></script>
<!-- Material Design -->
<script src="<?php echo $backend;?>dist/js/material.min.js"></script>
<script src="<?php echo $backend;?>dist/js/ripples.min.js"></script>
 <script src="<?php echo $backend;?>custom_js/custom_ajax.js"></script>
 <script src="<?php echo $backend; ?>toaster/jquery.toaster.js"></script>
 <script src="<?php echo $backend_assets;?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $backend_assets;?>plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
    $.material.init();
</script>
</body>
</html>
