base_url = $('#tl_admin_main_body').attr('data-base-url');
var pagionationUrl = base_url+"newsfeed/searchResult/";
var isFunctionCall = false;

postList(0); //load nearby user list initially when page is loaded
var isFilter = '';
function postList(is_scroll,is_filter)
{
   
    var popUploader = $('#showLoaderScrollFilter');
   if(is_filter == 1){
        popUploader.show();
   }

    var viewType = $('#viewType').val();
    //var urlImg = BASE_URL+'frontend_asset/img/Spinner-1s-80px.gif';
    
    var scroll_loader = $('#showLoader');
    //scroll_loader.hide();
    //for new request, reset isNext and offset
    if(is_scroll==0){
        scroll_loader.attr('data-isNext',1);
        scroll_loader.attr('data-offset',0); //set new offset
    }
    
    var offset = scroll_loader.attr('data-offset'),
        isNext = scroll_loader.attr('data-isNext'), //to see if we have next record to load
        list_cont = $('#rowCount'); //container where list will be appended
    
    //abort request if previous request is in progress OR next record is not available
    if(isFunctionCall || (isNext==0 && is_scroll==1)){
        return false;
    }

    isFunctionCall = true;
    $(".filter-icon").removeClass('open');
    $('.notFound').hide();
    var categoryId       = $("input:checkbox:checked").map(function(){return $(this).val();}).get();
        searchName       = $('#search').val();   
    var currentRequest = 0;  
    currentRequest = $.ajax({
        url: pagionationUrl,
        type: "POST",
        data:{page:offset,searchName:searchName,filter:categoryId},
        dataType: "json",
        beforeSend: function() {
          if(currentRequest != 0) {
              currentRequest.abort();
          }
            //$('body').css('overflow','hidden');
            scroll_loader.show();
        },         
        success: function(data){
            //let val = JSON.parse(data);
            popUploader.hide();
           $("#Filter").modal('hide');
            scroll_loader.hide();

           
            if (data.status == 1){
                
                scroll_loader.hide();

                if(offset == 0){
                    scroll_loader.hide();
                    $("#showNewsFedd").html(data.html);
                    //$("#total-near-count").val(data.totalNearUsers);
                }else{
                     scroll_loader.hide();
                    $("#showNewsFedd").append(data.html);
                        
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
               isFunctionCall = false;
            },
            error:function (){
                scroll_loader.hide();
                toastr.error(err_unknown);
            }
        }); 
     
}