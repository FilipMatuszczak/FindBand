$(document).ready(function(){

    var username_free = false;
    
    //////////////////function for end typing
    (function($) {
$.fn.donetyping = function(callback){
    var _this = $(this);
    var x_timer;    
    _this.keyup(function (){
        clearTimeout(x_timer);
        x_timer = setTimeout(clear_timer, 1000);
    }); 

    function clear_timer(){
        clearTimeout(x_timer);
        callback.call(_this);
    }
}
})(jQuery);
    $('#tabReg').hide();

/*
    //////Function for checking if username is available
  $('#username').donetyping(function(callback)           
                       {
  ///$('#username').css('border','none');
  var username = $('#username').val()
       
    
    if(username!=''){
        
         
        
        
        var request = new XMLHttpRequest()
        var exists = 0;
        
request.open('GET', 'http://127.0.0.1:8000/user_exists/'+username, true)
request.onload = function() {
    
  var data = JSON.parse(this.response)

  if (request.status >= 200 && request.status < 400) {
  
      if (data.user_exists == true){
            $('#status').css('color','darkred');
              $('#status').html('<b>Login jest już zajęty!</b>');
         username_free = false;
      }
      else {
          
            $('#status').css('color','black');
              $('#status').html('<b>Login dostępny!</b>');
          username_free = true;
      }
      
  } else {
    console.log('error')
  }
}

request.send()
        
        
    }
    else {
         $('#status').html('<b>Nazwa użytkownika</b>');
    $('#status').css('color','black');
    }
    
    });


  */
    ////////function for submiting form
    
    $('#register-form').click(function() {
        if(username_free){ //if form is valid submit form
			return true;
		}
         $('#username').css('border','1.5px solid red');
		event.preventDefault();
        
    }
           
        
        
    
                              );
    
    ///////////

    $("#prevBtn").on("click", function () {
        event.preventDefault();
        console.log("saa");
        $('#tabLogin').slideUp();
        $('#tabReg').slideDown();
    });

    $("#nextBtn").on("click", function () {
        event.preventDefault();
        $('#tabReg').slideUp();
        $('#tabLogin').slideDown();
    });

    $("#Pswdrem").on("click", function () {
        event.preventDefault();
        $('#tabLogin').slideUp();
        $('#tabForgot').slideDown();
    });

    $("#CancBtn").on("click", function () {
        event.preventDefault();
        $('#tabForgot').slideUp();
        $('#tabLogin').slideDown();
    });
    
});




