
/*----------------------------
        PerfectScrollbar
    ------------------------------ */




/*----------------------------
        Add Post Form
    ------------------------------ */
toastr.remove();
$('#post').on('change', function () {

        $("#Marketplace").css('display', (this.value == '3') ? 'block' : 'none');
        $("#Career").css('display', (this.value == '2') ? 'block' : 'none');
});



/*----------------------------
        Multi Select
    ------------------------------ */

$('.dropdownInt').dropdown({allowAdditions: true});

 
$( document ).ready(function() {
    $('#preloader').fadeOut(400);
});  

jQuery.validator.setDefaults({
    errorClass: "validate-error",
    errorElement: "span"
});

/* Validate plugin messages */
jQuery.extend(jQuery.validator.messages, {
    required: "This field is required.",
    email: "Please enter a valid email address.",
    url: "Please enter a valid URL.",
    date: "Please enter a valid date.",
    dateISO: "Please enter a valid date (ISO).",
    number: "Please enter a valid number.",
    digits: "Please enter only digits.",
    creditcard: "Please enter a valid credit card number.",
    equalTo: "Please enter the same value again.",
    accept: "Please enter a value with a valid extension.",
    maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
    minlength: jQuery.validator.format("Please enter at least {0} characters."),
    rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
    range: jQuery.validator.format("Please enter a value between {0} and {1}."),
    max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
    min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
});

jQuery('img.svg').each(function(){
    var $img = jQuery(this);
    var imgID = $img.attr('id');
    var imgClass = $img.attr('class');
    var imgURL = $img.attr('src');

    jQuery.get(imgURL, function(data) {
        // Get the SVG tag, ignore the rest
        var $svg = jQuery(data).find('svg');

        // Add replaced image's ID to the new SVG
        if(typeof imgID !== 'undefined') {
            $svg = $svg.attr('id', imgID);
        }
        // Add replaced image's classes to the new SVG
        if(typeof imgClass !== 'undefined') {
            $svg = $svg.attr('class', imgClass+' replaced-svg');
        }

        // Remove any invalid XML tags as per http://validator.w3.org
        $svg = $svg.removeAttr('xmlns:a');

        // Replace image with new SVG
        $img.replaceWith($svg);

    }, 'xml');

});

/* Common messages */
var proceed_err = 'Please fill required fields before proceeding.',
    err_unknown = 'Something went wrong. Please try again.',
    base_url = $('#tl_admin_main_body').attr('data-base-url');

/* Body loader show-hide fn */
function show_loader(){
    $('#biz_body_loader').show();
}



function hide_loader(){
    $('#biz_body_loader').hide();
}

/* override default settings of validate */
/* Toastr settings */
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

/* login form validation */


/* Signup form validation */
function logout(){
    var surl = base_url+'User/logout'; 
    window.location = surl;
}
function show_loader(){
    $('#preloader').show();
}

function hide_loader(){
    $('#preloader').hide();
}

jQuery.validator.addMethod("alphanumeric", function(value, element) {
    return this.optional(element) || /^[A-Za-z\d' ]+$/.test(value);
}, "Only Letters, numbers, Apostrophe allowed");

signUp_form = $("#signup");
signUp_form.validate({
        rules: {
            userName: {
                required: true,
                minlength: 2,
                maxlength: 50,
                alphanumeric: true
            },
            email: {
                required: true,
                email:true,
                remote: base_url+"user/User_authentication/check_unique_email/"
 
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 20,
            },
            
        }, 

        messages: {
        userName: {
            required: "Name field is required.", 
            maxlength:"Max characters should be 30.",
        },
        email: { 
            required: "Email field is required.",
            email:"Please enter valid email id.",
            remote: jQuery.validator.format("{0} is already exist")
        },
        
        password: {
                required: "Password field is required.",
  
        },
  

    },
    errorElement: 'span',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },

   
    });

function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}



$("#signup").submit(function(e){
  e.preventDefault();
  if($('#signup').valid()==false) {
      toastr.error('Please fill all fields properly before proceeding.');
        return false;
  }
  $(".error").html(''); 
  $.ajax({
    type:"POST",
    url: base_url +"user/User_authentication/userRegistration",
    cache:false,
    contentType: false,
    processData: false,
    data: new FormData(this),
    dataType: "json",
    beforeSend: function() {
            show_loader();                           
    },
    complete:function(){
            hide_loader(); 
    }, 
   success:function(res){

       $('#preloader').fadeOut(200);
    
        if(res.status == 'success'){
            var surl = base_url+"newsfeed"; 
            toastr.success(res.messages);
            window.setTimeout(function() { window.location = surl; },500);
        } else{
           toastr.error(res.messages);
           $("#csrf").val(res.csrf);
        }
        if(res.status == -1){
          toastr.success(res.msg);
          window.setTimeout(function() { window.location = res.url; },500);
        }
    },
    error: function (jqXHR, textStatus, errorThrown){
          toastr.error(err_unknown);
    }
  });
});



$("#Login").validate({
        rules: {
            email: {
                required: true,
                email:true,
                remote: base_url+"user/User_authentication/check_unique_email_login/"
 
            },
            password: {
                required: true,
                maxlength: 20,
                
            },
        }, 

        messages: {
        email: { 
            required: "Email field is required.",
            email:"Please enter valid email id.",
            remote: jQuery.validator.format("{0} is not registerd with us.")
        },
        
        password: {
                required: "Password field is required.",
        },


    },
    
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
      
    });


$("#Login").submit(function(e){
  e.preventDefault();
  if ($('#Login').valid()==false) {
        toastr.error('Please fill all fields properly before proceeding.');
        return false;
  }
  $(".error").html(''); 
  $.ajax({
    type:"POST",
    url:base_url + "user/User_authentication/isLogin",
    cache:false,
    contentType: false,
    processData: false,
    data: new FormData(this),
    dataType: "json",
    beforeSend: function() {
            show_loader();                           
    },
    complete:function(){
            hide_loader(); 
    }, 
    success:function(res){
      $('#preloader').fadeOut(500);
      if(res.status == 'success'){
          $("#login_form").hide();
          var surl = 'newsfeed'; 
          toastr.success(res.messages);
          window.setTimeout(function() { window.location = surl; },500);
         
      } else{
      	$("#csrfL").val(res.csrf);
      		toastr.error(res.messages);
      }
      if(res.status == -1){
          toastr.success(res.msg);
          window.setTimeout(function() { window.location = res.url; },500);
      }
    },
    error: function (jqXHR, textStatus, errorThrown){
          toastr.error(err_unknown);
    }
  });
});

forget_password = $("#forgetPassword");
forget_password.validate({
        rules: {
            email: {
                required: true,
                email:true,
                remote: base_url+"user/User_authentication/check_unique_email_login/"
 
            },
        }, 

        messages: {
        email: { 
            required: "Email field is required.",
            email:"Please enter valid email id.",
            remote: jQuery.validator.format("{0} is not registerd with us.")
        },
    },
    errorElement: 'span',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
 });




forget_password.submit(function(e){
  e.preventDefault();
  /*$("#forgotpwd").modal('toggle');*/
  if (forget_password.valid()==false) {
    toastr.error('Please fill all fields properly before proceeding.');
    return false;
  }
  $(".error").html(''); 
  $.ajax({
    type:"POST",
    url:"user/User_authentication/forgetPassword",
    cache:false,
    contentType: false,
    processData: false,
    data: new FormData(this),
    dataType : "json",
    beforeSend: function() {
            show_loader();                           
    },
    complete:function(){
            hide_loader(); 
    },
    success:function(res){
      $('#preloader').fadeOut(200);
     
      if(res.status == 'success'){
        $("#forgotpwd").modal('hide');
        var form = document.getElementById("forgetPassword");
          form.reset();
        toastr.success(res.messages);
      } else{
          $("#csrfF").val(res.csrf);
          toastr.error(res.messages); 
           
      }
      if(res.status == -1){
          toastr.success(res.msg);
          window.setTimeout(function() { window.location = res.url; }, 1000);
      }
    },
    error: function (jqXHR, textStatus, errorThrown){
          toastr.error(err_unknown);
    }
  });
});


change_password = $("#changePassword");
change_password.validate({
        rules: {
            password: {
                required: true,
                minlength: 6,
                maxlength:20,
                remote: base_url+"user/check_password/"
            }, 

            npassword: {
                required: true,
                minlength:6,
                maxlength:12,
                //remote: base_url+"home/users/check_password_new/
            },
            cpassword: {
                required: true,
                minlength:6,
                maxlength:12,
                equalTo : "#npassword"
  
            },
        }, 

        messages: {
        password: { 
            required: "Old password field is required.",
            remote: "Old password do not match with your current password."
        },
        npassword: { 
            required: "New password field is required.",
          
        },
        cpassword: { 
            required: "Confirm password field is required.",
            equalTo :"Password does not match. Please re-enter same password again."
          
        },
    },
    errorElement: 'span',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
 });

change_password.submit(function(e){ //change USER password function...
   e.preventDefault();
   toastr.remove();
  if (change_password.valid()==false){
    toastr.error('Please fill all fields properly before proceeding.');
        return false;
    }
    $.ajax({
    type:"POST",
    url:'changePassword',
    cache:false,
    contentType: false,
    processData: false,
    data: new FormData(this),
    dataType: "json",
    beforeSend: function() {
            show_loader();                           
    },
    complete:function(){
            hide_loader(); 
    },
    success:function(res){
      $('#preloader').fadeOut(200);
      if(res.status == 'success'){
         toastr.success(res.messages);
         var form = document.getElementById("changePassword");
          form.reset();
         /*var surl = 'home/profile';
          window.setTimeout(function() { window.location = surl; });*/
      } else{
          $("#csrf").val(res.csrf);
          toastr.error(res.messages); 
           
      }
      if(res.status == -1){
          toastr.success(res.msg);
          window.setTimeout(function() { window.location = res.url; }, 1000);
      }
    },
    error: function (jqXHR, textStatus, errorThrown){
          toastr.error(err_unknown);
    }
  });     
});


$("#profileImageUpdate").submit(function(e){  //change USER password function...
   //var src = document.getElementById("profileImage");
    toastr.remove();
    $.ajax({
    type:"POST",
    url:'updateProfile',
    cache:false,
    contentType: false,
    processData: false,
    data: new FormData(this),
    dataType: "json",
    beforeSend: function() {
            show_loader();                           
    },
    complete:function(){
            hide_loader(); 
    },
    success:function(res){
      $('#preloader').fadeOut(200);
      if(res.status == 'success'){
         toastr.success(res.messages);
         var surl = 'user/profile';
          window.setTimeout(function() { window.location = surl; }, 2000);
      } else{
          $("#csrf").val(res.csrf);
          toastr.error(res.messages); 
      }
      if(res.status == -1){
          toastr.success(res.msg);
          window.setTimeout(function() { window.location = res.url; }, 1000);
      }
    },
    error: function (jqXHR, textStatus, errorThrown){
          toastr.error(err_unknown);
    }
  });     
});


set_password = $("#setPassUser");
set_password.validate({
        rules: {
            password: {
                required: true,
                minlength: 6,
                maxlength:20,
                
            }, 
            cpassword: {
                required: true,
                minlength: 6,
                maxlength:20,
                equalTo : "#password"
                
            },
        
        }, 

        messages: {
        password: { 
            required: "Password field is required.",
            
        },
        cpassword: { 
            required: "Confirm password field is required.",
            equalTo : "Password does not match with new password, Please re-enter same password again.",
        },
        
    },
    errorElement: 'span',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
 });


set_password.submit(function(e){
  if (set_password.valid()== false){
    toastr.error('Please fill all fields properly before proceeding.');
    return false;
  }
  e.preventDefault();
   toastr.remove();
  $.ajax({
    type:"POST",
    url:base_url+"user/setPassReset",
    cache:false,
    contentType: false,
    processData: false,
    data: new FormData(this),
    dataType : "json",
    beforeSend: function() {
            show_loader();                           
    },
    complete:function(){
            hide_loader(); 
    },
    success:function(res){
      if(res.status == 'success'){
        toastr.success(res.messages);
        var surl = base_url+'home'; 
        window.setTimeout(function() { window.location = surl; },1000);
      }else{
        toastr.error(res.messages);
      }
      if(res.status == -1){
          toastr.success(res.msg);
          window.setTimeout(function() { window.location = res.url; }, 1000);
      }
    },
    error: function (jqXHR, textStatus, errorThrown){
          toastr.error(err_unknown);
    }
  });
});

function priview_video(input){
    if (input.files) {
    var filesAmount = input.files.length;
    var fileType = input.files[0]['type'];
    var fileSize = input.files[0]['size'];
    if(fileSize <= 10000000){
    if(fileType == 'video/mp4' || fileType == 'video/3gp'){
          var $source = $('#video_here');
          $("#showVideo").show();
          $source[0].src = URL.createObjectURL(input.files[0]);
          $source.parent()[0].load();  
          
    }else{
      toastr.error('Please select only MP4 and 3GP video.');
      $("#add-video").val('');
    }
    }else{
      toastr.error('Video size should not be greater then 10MB.');
      $("#add-video").val('');
    }
  }
}

function readURL(input) {

  if (input.files) {
    var filesAmount = input.files.length;
    var fileType = input.files[0]['type'];

    
    for (i = 0; i < filesAmount; i++) {
      var timeId = Date.now()*Math.floor(Math.random() * 20);
      var reader = new FileReader();
      reader.onload = function(event) {
        var image = new Image(); // or document.createElement('img')
        var width, height;
        image.onload = function() {
          width = this.width;
          height = this.height;
            var imgs = $('img.img-fluid'),
            imageArrr = [];

            imgs.each(function () {
            imageArrr.push($(this).attr('src'));

            }); 
            var file_data1 =  imageArrr;
             if (file_data1){
               imageSelect = 1;
            }
            //alert(imageArr.length);
          if(imageArrr.length == 4){
            $("#selectImageButton").hide();
          }
          if((width >= 700) && (height >= 466)){
            $('#imagePreview').append('<li class="priviewImage"><div class="upload-img" id="'+timeId+'"><div class="img-sec uploaded_img"  ><img id="hw'+timeId+'" class="img-fluid" src="'+event.target.result+'"><a title="Remove" onclick="removeImage('+timeId+')" ><span><i class="fa fa-close"></i></span></a></div></div></li>')
          }else{
            toastr.error('Please upload atleast 700x466 dimensions image.');
            $("#selectImageButton").show();
          }
        };
        image.src = event.target.result;
      
      }

      reader.readAsDataURL(input.files[i]);
    }
  }
}


function readURLGroup(input) {
  if (input.files) {
    var filesAmount = input.files.length;
    var fileType = input.files[0]['type'];

      var timeId = Date.now()*Math.floor(Math.random() * 20);
      var reader = new FileReader();
      reader.onload = function(event) {
        var image = new Image(); // or document.createElement('img')
        var width, height;
        image.onload = function() {
          width = this.width;
          height = this.height;
          if((width >= 700) && (height >= 466)){
            $("#imgShow").hide();
            $('#imagePreview').html('<label for="" id="lb'+timeId+'"><div class="grp-icon1" id="'+timeId+'"><img id="hw'+timeId+'" class="img-fluid show-group_image" src="'+event.target.result+'"><a title="Remove" onclick="removeImageGroup('+timeId+')" ><span><i class="fa fa-close cls_icon"></i></span></a></div></lable>')
          }else{
            $("#grp-img1").val('');
            $("#grp-img").val('');
            $('.show-group_image').attr('src','');
            toastr.error('Please upload atleast 700x466 dimensions image.');
            $("#selectImageButton").show();
          }
        };
        image.src = event.target.result;
      
      }

      reader.readAsDataURL(input.files[0]);

  }
}

$("#grp-img").change(function() {
    readURLGroup(this);
});

$("#grp-img1").change(function() {

    readURLGroup(this);

    

});


$("#add-img").change(function() {

    readURL(this);
});

$("#add-video").change(function() {
   priview_video(this);
   
});

function removeImage(id){
  $('#'+id).remove();
  $("#selectImageButton").show();
}


function removeImageGroup(id){
  $('#'+id).remove();
  $("#grp-img1").val('');
  $('.show-group_image').attr('src','');
  $("#imgShow").show();
  $("#grp-img").val('');
}


function removeImageDefault(id){
  $('#'+id).remove();
  $("#imgShows").show();
  $("#grp-img").val('');
}


post = $("#posts");
post.validate({
        rules: {
           tags: "required needsSelection",

            description: {
                required: true,
                minlength: 6,
            }, 

            title: {
                required: true,
                minlength:6,
                //remote: base_url+"home/users/check_password_new/
            }, 
            /*tags: {
                required: true,
                //remote: base_url+"home/users/check_password_new/
            },*/

            category: {
                required: true,
                //remote: base_url+"home/users/check_password_new/
            }, 

            address: {
                required: true,
                //remote: base_url+"home/users/check_password_new/
            },
           
        }, 

        messages: {
        description: { 
            required: "Description field is required.",
            
        },
        title: { 
            required: "Title field is required.",
          
        },
        tags: { 
            required: "Tag field is required.",
          
        },
        category: {
                 required: "Category field is required.",
                //remote: base_url+"home/users/check_password_new/
        },

        address: {
                 required: "Location field is required.",
                //remote: base_url+"home/users/check_password_new/
        },
    },

 

   
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }, 


 });

$("#posts").submit(function(e){
  e.preventDefault();
   toastr.remove();
  var long = $("#fbUserLocLong").val();
  var lat = $("#fbUserLocLat").val();
  
  if($('#posts').valid()==false) {
      toastr.error('Please fill all fields properly before proceeding.');
        return false;
  }

  if($('#posts').valid()==true) {
      if(lat == "" || long == ""){
        toastr.error('Please Enter valid location to proceed.');
            return false;
      }

  } 

  var tags = $("#tags").val();

  var video = document.getElementById('showVideo');    
  var canvas = document.createElement('canvas');
  canvas.id = "CursorLayer";
  canvas.width = 640;
  canvas.height = 360;
  var context = canvas.getContext('2d');
  context.drawImage(video, 0, 0, canvas.width, canvas.height);
  var dataURL = canvas.toDataURL();
  

  var formData = new FormData(this);
  var imgs = $('img.img-fluid'),
        imageArr = [];

        imgs.each(function () {
        imageArr.push($(this).attr('src'));

        }); 
      var file_data1 =  imageArr;
       if (file_data1){
         imageSelect = 1;
      }
        //alert(imageArr.length);
      if(imageArr.length <= 5){
        for (i = 0; i < imageArr.length; ++i) {
          var obj = {key :imageArr};
          //alert(obj["key"][i]);
          formData.append("postImage[]", obj["key"][i]);
        }
      }else{
         toastr.error("Maximum 5 image can upload.");
          return false;
      }
      formData.append("video_thumb",dataURL);
      tags = $("a.visible");
      tagsArr = [];
      tags.each(function () {
        tagsArr.push($(this).attr('data-value'));

        }); 
      formData.append("tags_data",tagsArr);
    var url = $("form").attr('action');

  $(".error").html(''); 
  $.ajax({
    type:"POST",
    url: url,
    cache:false,
    contentType: false,
    processData: false,
    data: formData,
    dataType: "json",
    beforeSend: function() {
            show_loader();                           
    },
    complete:function(){
            hide_loader(); 
    }, 
   success:function(res){

       $('#preloader').fadeOut(200);
    
        if(res.status == 'success'){
            var surl = base_url+"newsfeed"; 
            toastr.success(res.messages);
            window.setTimeout(function() { window.location = surl; },500);
        } else{
           toastr.error(res.messages);
           $("#csrf").val(res.csrf);
        }
        if(res.status == -1){
          toastr.success(res.msg);
          window.setTimeout(function() { window.location = res.url; },500);
        }
    },
    error: function (jqXHR, textStatus, errorThrown){
          toastr.error(err_unknown);
    }
  });
});


function clearFilter(){
  $("#Filter").modal('hide');
  location.reload();
}


function submitComment(postId){
   toastr.remove();
  $("#buttonComment"+postId).text("Loading..");
  $("#buttonComment"+postId).attr("disabled", true);
  var comment_count = $("#commentCountView"+postId).data('value');
  var comment = $("#comment_text"+postId).val();
  var csrf = $("#csrf"+postId).val();
  var dataUrl = $("#doComment").attr('action');
  var url = dataUrl+'?comment='+comment+'&post_id='+postId;
  if(comment.length > 200){
    toastr.error("You are trying to submit more then 200 characters.");
    return false;
    
  }
    $.ajax({
      type:"GET",
      url:url,
      contentType:false,
      processData:false,
      cache:false,
      dataType:"json",
      
      success: function(data){
        if(data.status == 'success'){

            $("#buttonComment"+postId).text("Comment");
            $("#buttonComment"+postId).attr("disabled", false);
            var dataCommentIncrease = comment_count + 1;
            var dataLikesIncreaseShow = comment_count + 1 + ' Likes';
            $("#commentCountView"+postId).text(dataCommentIncrease);
            $("#commentCountView"+postId).data('value',dataCommentIncrease);
            $("#comment_show"+postId).html(data.html);
            $('#comment_text'+postId).val('');
            $("#comment_show"+postId).removeAttr("style");
            getCommentButton(postId,length);
        } else{
           $("#buttonComment"+postId).attr("disabled", false);
          $("#buttonComment"+postId).text("Comment");
           toastr.error(data.messages);
           $("#csrf").val(data.csrf);
        }
        if(data.status == -1){
          toastr.success(data.msg);
          window.setTimeout(function() { window.location = res.url; },500);
        }
      },
      error:function (){
         $("#buttonComment"+postId).attr("disabled", false);
          $("#buttonComment"+postId).text("Comment");
          toastr.error(err_unknown);
      }
    });
};


function submitCommentFromPostDetails(postId){
   toastr.remove();
  $("#buttonComment"+postId).text("Loading..");
  $("#buttonComment"+postId).attr("disabled", true);
  var comment_count = $("#commentCountView"+postId).data('value');
  var comment = $("#comment_text"+postId).val();
  var csrf = $("#csrf"+postId).val();
  var dataUrl = $("#doComment").attr('action');
  var url = dataUrl+'?comment='+comment+'&post_id='+postId;
  if(comment.length > 200){
    toastr.error("You are trying to submit more then 200 characters.");
    return false;
    
  }
    $.ajax({
      type:"GET",
      url:url,
      contentType:false,
      processData:false,
      cache:false,
      dataType:"json",
      
      success: function(data){
        if(data.status == 'success'){

            $("#buttonComment"+postId).text("Comment");
            $("#buttonComment"+postId).attr("disabled", false);
            var dataCommentIncrease = comment_count + 1;
            var dataLikesIncreaseShow = comment_count + 1 + ' Likes';
            $("#commentCountView"+postId).text(dataCommentIncrease);
            $("#commentCountView"+postId).data('value',dataCommentIncrease);
            $("#showCommentFeedDetails").append(data.html);
            $('#comment_text'+postId).val('');
            $("#showCommentFeedDetails").removeAttr("style");
            getCommentButton(postId,length);
            $('#showCommentFeedDetails').animate({
            scrollTop: $('#showCommentFeedDetails')[0].scrollHeight}, 2000);
        } else{
           $("#buttonComment"+postId).attr("disabled", false);
          $("#buttonComment"+postId).text("Comment");
           toastr.error(data.messages);
           $("#csrf").val(data.csrf);
        }
        if(data.status == -1){
          toastr.success(data.msg);
          window.setTimeout(function() { window.location = res.url; },500);
        }
      },
      error:function (){
         $("#buttonComment"+postId).attr("disabled", false);
          $("#buttonComment"+postId).text("Comment");
          toastr.error(err_unknown);
      }
    });
};


function submitCommentFromGroupDetails(groupId){
  $("#buttonComment"+groupId).text("Loading..");
  $("#buttonComment"+groupId).attr("disabled", true);
  var comment_count = $("#commentCountView"+groupId).data('value');
  var comment = $("#comment_text"+groupId).val();
  var csrf = $("#csrf"+groupId).val();
  var dataUrl = $("#doComment").attr('action');
  var url = dataUrl+'?comment='+comment+'&group_id='+groupId;
  if(comment.length > 200){
    toastr.error("You are trying to submit more then 200 characters.");
    return false;
    
  }
    $.ajax({
      type:"GET",
      url:url,
      contentType:false,
      processData:false,
      cache:false,
      dataType:"json",
      
      success: function(data){
        if(data.status == 'success'){

            $("#buttonComment"+groupId).text("Comment");
            $("#buttonComment"+groupId).attr("disabled", false);
            var dataCommentIncrease = comment_count + 1;
            var dataLikesIncreaseShow = comment_count + 1 + ' Likes';
            $("#commentCountView"+groupId).text(dataCommentIncrease);
            $("#commentCountView"+groupId).data('value',dataCommentIncrease);
            $("#showCommentGroupDetails").append(data.html);
            $('#comment_text'+groupId).val('');
            $("#showCommentGroupDetails").removeAttr("style");
            getCommentButton(groupId,length);
            $('#showCommentGroupDetails').animate({
            scrollTop: $('#showCommentGroupDetails')[0].scrollHeight}, 2000);
        } else{
           $("#buttonComment"+groupId).attr("disabled", false);
          $("#buttonComment"+groupId).text("Comment");
           toastr.error(data.messages);
           $("#csrf").val(data.csrf);
        }
        if(data.status == -1){
          toastr.success(data.msg);
          window.setTimeout(function() { window.location = res.url; },500);
        }
      },
      error:function (){
         $("#buttonComment"+groupId).attr("disabled", false);
          $("#buttonComment"+groupId).text("Comment");
          toastr.error(err_unknown);
      }
    });
};


$('#comment_text').keyup(function() {
  alert();
    var textInputValue = $("#comment_text").val();
    if(textInputValue != ''){
      $("#buttonComment").show();
    }else{
       $("#buttonComment").hide();
    }
});

function likeUnlike(postId,likeCount){
 var dataValue =  $("#likesIconVisible"+postId).attr('data-value');
 var dataValueLikes =  $("#likesCount"+postId).attr('data-value');
 var dataUrl=  $("#likesIconVisible"+postId).attr('data-url');  
var LikeCountVisible = $("#likesIconVisible"+postId);
  if(dataValue  == '' || dataValue == 0){
    var dataLikesIncrease = parseFloat(dataValueLikes) + 1;
    var dataLikesIncreaseShow = parseFloat(dataValueLikes) + 1 + ' Likes';
    $("#likeDisabled"+postId).hide();
    $("#likesCount"+postId).text(dataLikesIncreaseShow);
    $("#likesCount"+postId).attr('data-value',dataLikesIncrease);
    LikeCountVisible.attr('data-value',1);
    LikeCountVisible.removeClass("fa fa-heart-o");
    LikeCountVisible.addClass("fa fa-heart");
    
  }else{
    $("#likeDisabled"+postId).hide();
    var dataLikesDecrease = parseFloat(dataValueLikes) - 1;
    if(dataLikesDecrease !=0){
    var decreaseShow = dataLikesDecrease + ' Likes'; 
      $("#likesCount"+postId).text(decreaseShow);

    }else{
      $("#likesCount"+postId).text('');
    }
   
    $("#likesCount"+postId).attr('data-value',dataLikesDecrease);
    LikeCountVisible.attr('data-value',0);
    LikeCountVisible.removeClass("fa fa-heart");
    LikeCountVisible.addClass("fa fa-heart-o");
  }
  //var url = base_url+'newsfeed/likeUnlike?post_id='+postId+'&value='+dataValue;

  var urlLike = dataUrl+'?post_id='+postId+'&value='+dataValue;
  $.ajax({
      type:"GET",
      url:urlLike,
      contentType:false,
      processData:false,
      cache:false,
      dataType:"json",
      success: function(data){
        if(data.status == 'success'){
            var liked = 'like-unlike';
        } else{
           toastr.error(data.messages);
           $("#csrf").val(data.csrf);
        }
        if(data.status == -1){
          toastr.success(data.msg);
          window.setTimeout(function() { window.location = res.url; },500);
        }
      },
      error:function (){
          toastr.error(err_unknown);
      }
    });


}



var isFunctionCallLikes = false;

 //load nearby user list initially when page is loaded

function getPostLikes(is_scroll,postId)
{
    //var urlImg = BASE_URL+'frontend_asset/img/Spinner-1s-80px.gif';
    if(is_scroll == 0){
      ps.update();
      $('#showUserLikedList').html("");
    }
    var scroll_loader = $('#showLoaderScroll');
    //scroll_loader.hide();
    //for new request, reset isNext and offset
    if(is_scroll==0){
        scroll_loader.attr('data-isNextData',1);
        scroll_loader.attr('data-offset',0); //set new offset

    }
    
    var offset = scroll_loader.attr('data-offset'),
        isNext = scroll_loader.attr('data-isNext'), //to see if we have next record to load
        list_cont = $('#rowCountLikes'); //container where list will be appended

    var pagionationUrlLikes = base_url+"newsfeed/getPostLikeUser?post_id="+postId+'&page='+offset;
    //abort request if previous request is in progress OR next record is not available
    if(isFunctionCallLikes || (isNext==0 && is_scroll==1)){
        return false;
    }

    isFunctionCallLikes = true;
    $.ajax({
        url: pagionationUrlLikes,
        type: "GET",
        dataType: "json",
        beforeSend: function() {
            //$('body').css('overflow','hidden');
            scroll_loader.show();
        },         
        success: function(data){
            //let val = JSON.parse(data);
            scroll_loader.hide();

            if (data.status == 1){
                
                scroll_loader.hide();

                if(offset == 0){
                 
                    scroll_loader.hide();
                    
                    $("#showUserLikedList").html(data.html);
                    $("#likeCount").text(data.count);
                    //$("#Likes").modal('show');
                    //$("#total-near-count").val(data.totalNearUsers);
                }else{
                    scroll_loader.hide();
                   
                    $("#showUserLikedList").append(data.html);

                        
                }
                if(data.isNext == 0){
                  $("#showMsg").text("No likes found.");
                }
                
                scroll_loader.attr('data-isNext',data.isNext);
                scroll_loader.attr('data-offset',data.newOffset); //set new offset
                
            }else if(data.status == -1) {
                //session exipred
                toastr.error(data.msg);
                if(data.url){
                    window.setTimeout(function () {
                        window.location = data.url;
                    }, 2000);
                }
            }else{
                toastr.error(data.msg);
            }
            
            },
            complete:function(){
               isFunctionCallLikes = false;
            },
            error:function (){
                scroll_loader.hide();
                toastr.error(err_unknown);
            }
        }); 
     
}

group = $("#groupForm");
group.validate({
        rules: {
            title: {
                required: true,
                minlength: 4,
                maxlength: 40,
            }, 

            category: {
                required: true,
                //remote: base_url+"home/users/check_password_new/
            }, 
            groupImage: {
                required: true,
                //remote: base_url+"home/users/check_password_new/
            }, 
            fields: {
              tags   : ['minCount[2]', 'empty'],
              },
           
        }, 
        messages: {
        
        title: { 
            required: "Title field is required.",
          
        },
        category: {
                 required: "Category field is required.",
                //remote: base_url+"home/users/check_password_new/
        },
        groupImage: {
                 required: "Please select group image.",
                //remote: base_url+"home/users/check_password_new/
        },
    },
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }, 


 });


$('#groupFormSubmit').click(function(){
 
 /* $(window).keydown('keypress', function(event) {
    if(event.keyCode == 13 ){
        event.preventDefault();
        return false;
    }
  });*/
  if(group.valid()==false) {
      toastr.error('Please fill all fields properly before proceeding.');
        return false;
  }

  var groupImage = $("#grp-img").val();
  if(groupImage == ''){
      toastr.error('Please select group image before proceeding.');
        return false;
  }


  //$("#"+remove).remove();
  var tags = $("#tags").val();
  /*var formData = new FormData(this);*/
   var _that = $(this); 
    form = _that.closest('form');
    formData = new FormData(form[0]);  
  var imgs = $('img.img-fluid'),
        imageArr = [];

        imgs.each(function () {
        imageArr.push($(this).attr('src'));

        }); 
      var file_data1 =  imageArr;
       if (file_data1){
         imageSelect = 1;
      }
      
      tags = $("a.visible");
      tagsArr = [];
      tags.each(function () {
        tagsArr.push($(this).attr('data-value'));

        }); 

      if(tags == ''){

      }
      formData.append("tags_data",tagsArr);
    var url = $("#groupForm").attr('action');

  $(".error").html(''); 
  $.ajax({
    type:"POST",
    url: url,
    cache:false,
    contentType: false,
    processData: false,
    data: formData,
    dataType: "json",
    beforeSend: function() {
            show_loader();                           
    },
    complete:function(){
            hide_loader(); 
    }, 
   success:function(res){
       $('#preloader').fadeOut(200);
        if(res.status == 'success'){
           toastr.success(res.message);
          window.setTimeout(function() {
          location.reload();},500);
          $("#csrf").val(res.csrf);
          var form = document.getElementById('groupForm');
          form.reset();
           
            $("#showGroupList").prepend(res.html);
            $("#showGroupList").fadeIn('slow');
            $("#exampleModal").modal('hide');
            toastr.success(res.message);
        } else{
           toastr.error(res.message);
           $("#csrf").val(res.csrf);
        }
        if(res.status == -1){
          toastr.success(res.msg);
          window.setTimeout(function() { window.location = res.url; },500);
        }
    },
    error: function (jqXHR, textStatus, errorThrown){
          toastr.error(err_unknown);
         // window.setTimeout(function() { location.reload(); },500);
    }
  });
});



function GrouplikeUnlike(groupId,likeCount){
 var dataValue =  $("#likesIconVisible"+groupId).attr('data-value');
 var dataValueLikes =  $("#likesCount"+groupId).attr('data-value');
 var dataUrl=  $("#likesIconVisible"+groupId).attr('data-url');  
var LikeCountVisible = $("#likesIconVisible"+groupId);
  if(dataValue  == '' || dataValue == 0){
    var dataLikesIncrease = parseFloat(dataValueLikes) + 1;
    var dataLikesIncreaseShow = parseFloat(dataValueLikes) + 1 + ' Likes';
    $("#likeDisabled"+groupId).hide();
    $("#likesCount"+groupId).text(dataLikesIncreaseShow);
    $("#likesCount"+groupId).attr('data-value',dataLikesIncrease);
    LikeCountVisible.attr('data-value',1);
    LikeCountVisible.removeClass("fa fa-heart-o");
    LikeCountVisible.addClass("fa fa-heart");
    
  }else{
    $("#likeDisabled"+groupId).hide();
    var dataLikesDecrease = parseFloat(dataValueLikes) - 1;
    if(dataLikesDecrease !=0){
    var decreaseShow = dataLikesDecrease + ' Likes'; 
      $("#likesCount"+groupId).text(decreaseShow);

    }else{
      $("#likesCount"+groupId).text('');
    }
   
    $("#likesCount"+groupId).attr('data-value',dataLikesDecrease);
    LikeCountVisible.attr('data-value',0);
    LikeCountVisible.removeClass("fa fa-heart");
    LikeCountVisible.addClass("fa fa-heart-o");
  }
  //var url = base_url+'newsfeed/likeUnlike?post_id='+postId+'&value='+dataValue;

  var urlLike = dataUrl+'?group_id='+groupId+'&value='+dataValue;
  $.ajax({
      type:"GET",
      url:urlLike,
      contentType:false,
      processData:false,
      cache:false,
      dataType:"json",
      success: function(data){
        if(data.status == 'success'){
            var liked = 'like-unlike';
        } else{
           toastr.error(data.messages);
           $("#csrf").val(data.csrf);
        }
        if(data.status == -1){
          toastr.success(data.msg);
          window.setTimeout(function() { window.location = res.url; },500);
        }
      },
      error:function (){
          toastr.error(err_unknown);
      }
    });


}


function getGroupLikes(is_scroll,groupId)
{
    //var urlImg = BASE_URL+'frontend_asset/img/Spinner-1s-80px.gif';
    if(is_scroll == 0){
      ps.update();
      $('#showUserLikedList').html("");
    }
    var scroll_loader = $('#showLoaderScroll');
    //scroll_loader.hide();
    //for new request, reset isNext and offset
    if(is_scroll==0){
        scroll_loader.attr('data-isNextData',1);
        scroll_loader.attr('data-offset',0); //set new offset

    }
    
    var offset = scroll_loader.attr('data-offset'),
        isNext = scroll_loader.attr('data-isNext'), //to see if we have next record to load
        list_cont = $('#rowCountLikes'); //container where list will be appended

    var pagionationUrlLikes = base_url+"groups/getGroupLikeUser?group_id="+groupId+'&page='+offset;
    //abort request if previous request is in progress OR next record is not available
    if(isFunctionCallLikes || (isNext==0 && is_scroll==1)){
        return false;
    }

    isFunctionCallLikes = true;
    $.ajax({
        url: pagionationUrlLikes,
        type: "GET",
        dataType: "json",
        beforeSend: function() {
            //$('body').css('overflow','hidden');
            scroll_loader.show();
        },         
        success: function(data){
            //let val = JSON.parse(data);
            scroll_loader.hide();

            if (data.status == 1){
                
                scroll_loader.hide();

                if(offset == 0){
                 
                    scroll_loader.hide();
                    
                    $("#showUserLikedList").html(data.html);
                    $("#likeCount").text(data.count);
                    //$("#Likes").modal('show');
                    //$("#total-near-count").val(data.totalNearUsers);
                }else{
                    scroll_loader.hide();
                   
                    $("#showUserLikedList").append(data.html);

                        
                }
                if(data.isNext == 0){
                  $("#showMsg").text("No likes found.");
                }
                
                scroll_loader.attr('data-isNext',data.isNext);
                scroll_loader.attr('data-offset',data.newOffset); //set new offset
                
            }else if(data.status == -1) {
                //session exipred
                toastr.error(data.msg);
                if(data.url){
                    window.setTimeout(function () {
                        window.location = data.url;
                    }, 2000);
                }
            }else{
                toastr.error(data.msg);
            }
            
            },
            complete:function(){
               isFunctionCallLikes = false;
            },
            error:function (){
                scroll_loader.hide();
                toastr.error(err_unknown);
            }
        }); 
     
}


function getGroupUsers(is_scroll,groupId)
{
    //var urlImg = BASE_URL+'frontend_asset/img/Spinner-1s-80px.gif';
    if(is_scroll == 0){
      ps.update();
      $('#showUserGroupList').html("");
    }
    var scroll_loader = $('#showLoaderScrollList');
    //scroll_loader.hide();
    //for new request, reset isNext and offset
    if(is_scroll==0){
        scroll_loader.attr('data-isNextData',1);
        scroll_loader.attr('data-offset',0); //set new offset

    }
    
    var offset = scroll_loader.attr('data-offset'),
        isNext = scroll_loader.attr('data-isNext'), //to see if we have next record to load
        list_cont = $('#rowCountUsers'); //container where list will be appended

    var pagionationUrlLikes = base_url+"groups/getGroupMembers?group_id="+groupId+'&page='+offset;
    //abort request if previous request is in progress OR next record is not available
    if(isFunctionCallLikes || (isNext==0 && is_scroll==1)){
        return false;
    }

    isFunctionCallLikes = true;
    $.ajax({
        url: pagionationUrlLikes,
        type: "GET",
        dataType: "json",
        beforeSend: function() {
            //$('body').css('overflow','hidden');
            scroll_loader.show();
        },         
        success: function(data){
            //let val = JSON.parse(data);
            scroll_loader.hide();

            if (data.status == 1){
                
                scroll_loader.hide();

                if(offset == 0){
                 
                    scroll_loader.hide();
                    
                    $("#showUserGroupList").html(data.html);
                    $("#userCount").text(data.count);
                    //$("#Likes").modal('show');
                    //$("#total-near-count").val(data.totalNearUsers);
                }else{
                    scroll_loader.hide();
                   
                    $("#showUserGroupList").append(data.html);

                        
                }
                if(data.isNext == 0){
                  $("#showMsg").text("No members found.");
                }
                
                scroll_loader.attr('data-isNext',data.isNext);
                scroll_loader.attr('data-offset',data.newOffset); //set new offset
                
            }else if(data.status == -1) {
                //session exipred
                toastr.error(data.msg);
                if(data.url){
                    window.setTimeout(function () {
                        window.location = data.url;
                    }, 2000);
                }
            }else{
                toastr.error(data.msg);
            }
            
            },
            complete:function(){
               isFunctionCallLikes = false;
            },
            error:function (){
                scroll_loader.hide();
                toastr.error(err_unknown);
            }
        }); 
     
}

function groupDelete(groupId){
  bootbox.confirm({
           message: "Are you sure, you want to delete this Group. ?",
            buttons: {
                confirm: {
                    label: 'OK',
                    className: 'btn-primary'
                },
                cancel: {
                    label: 'Cancel',
                    className: 'btn-danger'
                }
            },
             callback: function (result) {
                if (result) {
                   show_loader();
                    var url = 'groups/deleteGroup?group_id='+groupId;
                    $.ajax({
                        method: "GET",
                        url: url,
                        dataType: "json",
                        success: function (response) {
                            hide_loader();
                            if (response.status == 'success') {
                                toastr.success("Group deleted successfully");
                                surl = 'groups_views';
                                window.setTimeout(function () {
                                 window.location.href  = surl;
                                }, 2000);
                            }
                        },
                        error: function (error, ror, r) {
                            bootbox.alert(error);
                        },
                    });
                }
              }
  });//end of bootbox
}//end of delete function



groupUpdate = $("#groupFormUpdate");
groupUpdate.validate({
        rules: {
            title: {
                required: true,
                minlength: 4,
                maxlength: 20,
            }, 

            category: {
                required: true,
                //remote: base_url+"home/users/check_password_new/
            }, 
            groupImage: {
                required: true,
                //remote: base_url+"home/users/check_password_new/
            }, 
            fields: {
              tags   : ['minCount[2]', 'empty'],
              },
           
        }, 
        messages: {
        
        title: { 
            required: "Title field is required.",
          
        },
        category: {
                 required: "Category field is required.",
                //remote: base_url+"home/users/check_password_new/
        },
        groupImage: {
                 required: "Please select group image.",
                //remote: base_url+"home/users/check_password_new/
        },
    },
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }, 


 });


$('#groupEditSubmit').click(function(){
 
  if(groupUpdate.valid()==false) {
      toastr.error('Please fill all fields properly before proceeding.');
        return false;
  }

  var groupImage = $("#grp-img1").val();
  var imageSrc = $("#hwremove111").attr('src');
  if(groupImage == '' && imageSrc == ''){
      toastr.error('Please select group image before proceeding.');
        return false;
  }


  //$("#"+remove).remove();
  var tags = $("#tags").val();
  /*var formData = new FormData(this);*/
  var _that = $(this); 
    form = _that.closest('form');
    formData = new FormData(form[0]);  
  var imgs = $('img.img-fluid'),
        imageArr = [];

        imgs.each(function () {
        imageArr.push($(this).attr('src'));

        }); 
      var file_data1 =  imageArr;
       if (file_data1){
         imageSelect = 1;
      }
      
      tags = $(".ui.label");
      tagsArr = [];
      tags.each(function () {
        tagsArr.push($(this).attr('data-value'));

        }); 

      if(tags == ''){

      }
    formData.append("tags_data",tagsArr);
    var url = $("#groupFormUpdate").attr('action');

  $(".error").html(''); 
  $.ajax({
    type:"POST",
    url: url,
    cache:false,
    contentType: false,
    processData: false,
    data: formData,
    dataType: "json",
    beforeSend: function() {
            show_loader();                           
    },
    complete:function(){
            hide_loader(); 
    }, 
   success:function(res){
       $('#preloader').fadeOut(200);
        if(res.status == 'success'){
          toastr.success(res.message);
          window.setTimeout(function() {
          location.reload();},500);
         
        } else{
           toastr.error(res.message);
           $("#csrf").val(res.csrf);
        }
        if(res.status == -1){
          toastr.success(res.msg);
          window.setTimeout(function() { window.location = res.url; },500);
        }
    },
    error: function (jqXHR, textStatus, errorThrown){
          toastr.error(err_unknown);
         // window.setTimeout(function() { location.reload(); },500);
    }
  });
});


