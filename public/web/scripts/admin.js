$(document).ready(function(){
      
      
      
      
             ////popup delete
            $(".delete").on("click", function () {
                event.preventDefault();
                $('#modal-delete').show();
            });


            //close modals
            $(".cancel-btn").on("click", function () {
                event.preventDefault();
                $('#mssgtxt').val("");
                $('#modal-delete').hide();
                $('#modal-ban').hide();
                $('#modal-news').hide()
            });
      
      
         ////popup delete
            $("#newsletter").on("click", function () {
                event.preventDefault();
                $('#modal-news').show();
            });
      
      
      
      
      
      
      
       $('img[image-zoom]').addClass('img-enlargable').click(function(){
    var src = $(this).attr('src');
    $('<div>').css({
        background: 'RGBA(0,0,0,.7) url('+src+') no-repeat center',
        backgroundSize: 'contain',
        width:'100%', height:'100%',
        position:'fixed',
        zIndex:'10000',
        top:'0', left:'0',
        cursor: 'zoom-out'
    }).click(function(){
        $(this).remove();
    }).appendTo('body');
      
      
      
      
      });
         });