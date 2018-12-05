//set google place autocomplete in new elements

$('#basic').flagStrap({
    placeholder: {
        value: "",
        text: "Select Country",
        buttonSize: "btn-lg",
        buttonType: "btn-primary",
        labelMargin: "20px",
        scrollable: false,
        scrollableHeight: "350px",
    },
    onSelect: function (value, element) {
            var getCountry = $(element).children("option:selected").text();
            $("#country_name").val(getCountry);
            $("#country").val(value);
    }
   
});
var getCountry = $("option:selected").val();
var getCountry_name = $("option:selected").text();
$("#country").val(getCountry);
$("#country_name").val(getCountry_name);

var loc_inputs = [
    'address',
    'fbUserLocLat',
    'fbUserLocLong'
];
var loc_inp_arr = [];
for (var i = 0; i < loc_inputs.length; i++) {
    loc_inp_arr.push(jQuery('#'+loc_inputs[i]));
}
setupAutocomplete(loc_inp_arr, 0);

function setupAutocomplete(the_input_arr, i) {

    var autocomplete = [];
    var the_input_loc = the_input_arr[0]; //location input jquery object
    var the_input_lat = the_input_arr[1]; //latitude input jquery object
    var the_input_long = the_input_arr[2]; //longitude input jquery object

    autocomplete.push(new google.maps.places.Autocomplete(the_input_loc[0]));
    var idx = autocomplete.length - 1;
    //clear old lat-long on change
    the_input_loc.keydown(function(){
        the_input_loc.removeClass('missfields');
        the_input_lat.val('');
        the_input_long.val('');
    });

    //google.maps.event.addListener(autocomplete[i], 'place_changed', function() {
    google.maps.event.addListener(autocomplete[idx], 'place_changed', function() {
        // Get the place details from the autocomplete object.
        var place = autocomplete[idx].getPlace();

        if (!place.geometry) {
            the_input_loc.addClass('missfields');
            toastr.error(valid_loc_msg);
            return;
        }

        //loaction is correct, grab lat long here
        the_input_loc.removeClass('missfields');
        var place_lat = place.geometry.location.lat();
        var place_long = place.geometry.location.lng();
        the_input_lat.val(place_lat);
        the_input_long.val(place_long);
        
        //get country from location and pre-set it to country dropdown
        var country_dd = jQuery("#country");
        if(country_dd.length>0){
            var componentForm = {
                country: 'long_name'
            };
            

            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];

                if (componentForm[addressType]) {
                    var val = place.address_components[i][componentForm[addressType]];
                    country_dd.val(val);
                }
               
            }
        }

        var country_ddd = jQuery("#country_short_name");
        if(country_ddd.length>0){
            var componentFormShort = {
                country: 'short_name',
            };

            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];

                if (componentFormShort[addressType]) {
                    var val = place.address_components[i][componentFormShort[addressType]];
                    
                    country_ddd.val(val);
                }
            }
        } 

        var state = jQuery("#state");
        if(state.length>0){
            var state_name = {
                country: 'long_name'
            };

            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];
                if (state_name[addressType]) {
                    var val = place.address_components[2][state_name[addressType]];
                    console.log(val);
                    state.val(val);
                }
            }
        }

        var city = jQuery("#city");
        if(city.length>0){
            var city_name = {
                country: 'long_name'
            };

            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];
                if (city_name[addressType]) {
                    var val = place.address_components[3][city_name[addressType]];
                    city.val(val);
                }
            }
        }
    });
}


update_profile = $("#updateProfile");
update_profile.validate({
        rules: {
            fullName: {
                required: true,
                minlength: 2,
                maxlength:50,
                alphanumeric: true
            }, 

            profession: {
                required: true,
                minlength:4,
                maxlength:20,
                //remote: base_url+"home/users/check_password_new/
            },
            address: {
                required: true,
          
            },
        }, 

        messages: {
        fullName: { 
            required: "Name field is required.", 
            maxlength:"Max characters should be 30.",
        },
        profession: { 
            required: "Profession field required.",
          
        },
        address: { 
            required: "Country field required.",
          
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

$("#updateProfile").submit(function(e){ //change USER password function...
   e.preventDefault();
  if (update_profile.valid()==false){
        toastr.error('All fields required.');
        return false;
  }
  var formData = new FormData(this);
  /*var img = $("#pImg").attr('src');
  alert(img);
  formData.append("profileImage", img);*/
    $.ajax({
    type:"POST",
    url:'updateProfile',
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
         toastr.success(res.messages);
         var surl = 'profile';
          window.setTimeout(function() { window.location = surl; }, 2000);
      } else{
          $("#csrfU").val(res.csrf);
          toastr.error(res.messages); 
           
      }
      if(res.status == -1){
          toastr.success(res.msg);
          window.setTimeout(function() { window.location = res.url; }, 1000);
      }
    }
  });     
});