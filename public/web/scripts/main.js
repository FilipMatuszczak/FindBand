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


    //////Function for checking if username is available
  $('.userConfirm').donetyping(function(callback)
                       {
  ///$('#username').css('border','none');
  var username = $('.userConfirm').val()

    console.log("elo " + username);
    if(username!=''){




        var request = new XMLHttpRequest()
        var exists = 0;

request.open('GET', 'http://127.0.0.1:8000/userExists/'+username, true)
request.onload = function() {

  var data = JSON.parse(this.response)

  if (request.status >= 200 && request.status < 400) {
  console.log(data);
      if (data[0] == true){
            $('#status').css('color','darkred');
              $('#status').html('<b>Login jest już zajęty!</b>');
         username_free = false;
      }
      else {
          $('#status').html('<b> </b>');
          $('#usernameicon').css('background-color','#7DA2AA');
          username_free = true;
      }

  } else {
    console.log('error')
  }
}

request.send()


    }
    else {
         $('#status').html('<b> </b>');
    $('#status').css('color','black');
    }

    });



    $('#registerForm').on("submit",function()
    {

        if (username_free) {
            return true;
        } else {
            $('#usernameicon').css('background-color','darkred');
            return false;
        }


    });




    $("#prevBtn").on("click", function () {
        event.preventDefault();
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




