base_url = $('#tl_admin_main_body').attr('data-base-url');
var pagionationUrl = base_url+"groups/searchResultGroup/";
var isFunctionCall = false;

groupList(0); //load nearby user list initially when page is loaded
var isFilter = '';
function groupList(is_scroll,isFilter)
{
    
    var scroll_loader = $('#showLoaderGroup');
    //scroll_loader.hide();
    //for new request, reset isNext and offset
    if(is_scroll==0){
        scroll_loader.attr('data-isNext',1);
        scroll_loader.attr('data-offset',0); //set new offset
    }
    
    var offset = scroll_loader.attr('data-offset'),
        isNext = scroll_loader.attr('data-isNext'); //to see if we have next record to load
       
    
    //abort request if previous request is in progress OR next record is not available
    if(isFunctionCall || (isNext==0 && is_scroll==1)){
        return false;
    }

    FunctionCall = true;
    $(".filter-icon").removeClass('open');
    $('.notFound').hide();
    var categoryId       = $("input:checkbox:checked").map(function(){return $(this).val();}).get();
        searchName       = $('#search').val();   
    var currentRequest = 0; 
    if(isFilter == 1 && searchName ==''){
        return false;
    }

    if(searchName == ''){
        $("#clearButton").hide();
    } else{
         $("#clearButton").show();
         $("#showGroupList").html('');
    }
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
           $("#Filter").modal('hide');
            scroll_loader.hide();

           
            if (data.status == 1){

                scroll_loader.hide();

                if(offset == 0){
                    scroll_loader.hide();

                    $("#showGroupList").html(data.html);
                    //$("#total-near-count").val(data.totalNearUsers);
                }else{
                     scroll_loader.hide();
                    $("#showGroupList").append(data.html);
                        
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
                window.setTimeout(function () {
                    location.reload();
                }, 2000);
            }
        }); 
     
}

$(window).scroll(function(){
   if ($(window).scrollTop() >= ($(document).height() - $(window).height())*0.7){
           groupList(1);
   }
 });
