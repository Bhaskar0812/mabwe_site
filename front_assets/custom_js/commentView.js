base_url = $('#tl_admin_main_body').attr('data-base-url');
var pagionationUrl = base_url+"post/getPostCommentsFromFeed/";
var isFunctionCall = false;

getPostComments(0); //load nearby user list initially when page is loaded
var isFilter = '';

function getPostComments(is_scroll)
{
    //var urlImg = BASE_URL+'frontend_asset/img/Spinner-1s-80px.gif';
    
    var scroll_loader = $('#showLoaderComment');
    //scroll_loader.hide();
    //for new request, reset isNext and offset

    var loadMoreComment = $("#showLoadMoreComment");
    
    var offset = scroll_loader.attr('data-offset'),
        isNext = scroll_loader.attr('data-isNext'), //to see if we have next record to load
        list_cont = $('#rowCountComment'); //container where list will be appended
        postId = scroll_loader.attr('data-id');

    var pagionationUrlComment = pagionationUrl+"?post_id="+postId+'&page='+offset;
    //abort request if previous request is in progress OR next record is not available


    isFunctionCallLikes = true;
    $.ajax({
        url: pagionationUrlComment,
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
                    
                    $("#showCommentFeedDetails li:first-child").after(data.html);
                    $('#showCommentFeedDetails').animate({
                    scrollTop: $('#showCommentFeedDetails')[0].scrollHeight}, 2000);
                    //$("#Likes").modal('show');
                    //$("#total-near-count").val(data.totalNearUsers);
                }else{
                    scroll_loader.hide();
                    $('#showCommentFeedDetails').animate({'scrollTop':0},800);
                    $("#showCommentFeedDetails li:first-child").after(data.html);
                   
                        
                }
                if(data.isNext == 0){
                  $("#showMsg").text("No Comment.");
                  loadMoreComment.hide();
                }else{
                    loadMoreComment.show();

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




