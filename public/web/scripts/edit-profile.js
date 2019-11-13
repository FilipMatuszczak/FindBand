var instrumentData;

$(document).ready(function(){


/////////////wczytywanie technologii uzytkownika
    var requestTechUser = new XMLHttpRequest();
    requestTechUser.open('GET', 'http://' + window.location.host + '/profile/edit' + '/blockedUsers', true);
    requestTechUser.onload = function () {

        //var data = JSON.parse(this.response);


        if (requestTechUser.status >= 200 && requestTechUser.status < 400) {
            //console.log(userID);
            for (i = 1; i < data.length; i++) {
                //console.log(data[i]);

                $('#tech-forms').append('<div> <input type="TechList" class="bio tech" id="tech" placeholder="Nazwa użytkownika do zignorowania" name = "usernames[]"  maxlength="50" list="TechList"><datalist id="TechList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');

            }
            if (data.length > 0) {
                var i = 0;
                $(".tech").each(function () {
                    var element = $(this);
                    if (element.val() == "") {
                        element.val(data[i]);
                        i++;
                    }
                });
            }

        } else {

        }


    }

    requestTechUser.send()

/////end technologii


/////////////wczytywanie języków uzytkownika

    var requestLangUser = new XMLHttpRequest()


    requestLangUser.open('GET', 'http://' + window.location.host + '/profile/edit' + '/instruments', true)
    requestLangUser.onload = function () {

        instrumentData = JSON.parse(this.response)


        if (requestLangUser.status >= 200 && requestLangUser.status < 400) {
            //console.log(userID);
            for (i = 1; i < instrumentData.length; i++) {
                //console.log(data[i]);

                $('#instrument-forms').append('<div> <input type="LangList" class="bio lang down" placeholder="Nowy instrument" name="instruments[]" maxlength="50" id="lang" list="LangList"><datalist id="LangList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');

            }
            if (instrumentData.length > 0) {
                var i = 0;
                $(".lang").each(function () {
                    var element = $(this);
                    if (element.val() == "") {
                        element.val(instrumentData[i]);
                        i++;
                    }
                });
            }
        } else {

        }


    }

    requestLangUser.send()

/////end technologii
    document.getElementById("add-instruments").onclick = function() {updateInstruments()};

    function updateInstruments() {

        var currentInstruments = $('form[name="instrument-form"]').find('input[name="instruments[]]"').val();
        console.log(currentInstruments);

        $.ajax({
            url: 'http://' + window.location.host + '/profile/edit' + '/update_instruments',
            type: 'PUT',
            data: { instruments: instrumentData },
            success: function(data) {
                alert('Load was performed.');
            }
        });
    }

/////////////wczytywanie miast uzytkownika

    var requestCityUser = new XMLHttpRequest()


    requestCityUser.open('GET', 'http://' + window.location.host + '/profile/edit' + '/musicGenres', true)
    requestCityUser.onload = function () {

        var dataC = JSON.parse(this.response)


        if (requestCityUser.status >= 200 && requestCityUser.status < 400) {
            //console.log(userID);
            for (i = 1; i < dataC.length; i++) {
                //console.log(data[i]);

                $('#cities-forms').append('<div> <input type="CityList" class="bio city down" placeholder="Nowy gatunek muzyczny" maxlength="50" id="city" name="musicGenres[]" list="CityList"><datalist id="CityList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');

            }
            if (dataC.length > 0) {

                var i = 0;
                $(".city").each(function () {
                    var element = $(this);
                    if (element.val() == "") {
                        element.val(dataC[i]);
                        i++;
                    }
                });
            }
        } else {
            console.log('error')
        }


    }

    requestCityUser.send()

/////end miast

////photopreviews
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#photo-edit').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(".fileToUpload").change(function () {
        readURL(this);
    });
    ////


/////////more forms
    $('#button-moreC').click(function () {
        ///todo dodaje nowe okno formularza
        i++;

        $('#cities-forms').append('<div> <input type="text" class="bio city down" placeholder="Nowy gatunek muzyczny" maxlength="50" id="lang" name="musicGenres[]" list="CityList"><datalist id="CityList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');


        ///////////
    });


    /////////more forms
    $('#button-more').click(function () {
        ///todo dodaje nowe okno formularza
        i++;

        $('#instrument-forms').append('<div> <input type="text" class="bio lang down" placeholder="Nowy instrument" maxlength="50" id="lang" name="instruments[]" list="LangList"><datalist id="LangList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');


        ///////////
    });


    $('#instrument-forms').on("click", ".less", function (e) {
        e.preventDefault();
        $(this).parent('div').remove();
        i--;
    })

    /////////////////

    $('#button-moret').click(function () {
        ///todo dodaje nowe okno formularza
        i++;

        $('#tech-forms').append('<div> <input type="text" class="bio tech" id="tech" placeholder="Nazwa użytkownika do zignorowania" maxlength="50" name="usernames[]" list="TechList"><datalist id="TechList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');


        ///////////
    });


    $('#tech-forms').on("click", ".less", function (e) {
        e.preventDefault();
        $(this).parent('div').remove();
        i--;
    })


    $('#cities-forms').on("click", ".less", function (e) {
        e.preventDefault();
        $(this).parent('div').remove();
        i--;
    })

    //////Function for getting technologies
    $('#tech-forms').on("keyup", ".tech", function (callback) {

        var tech = $(this).val()

        ///console.log(tech);
        if (tech != '') {
            var requestTech = new XMLHttpRequest()
            var exists = 0;

            requestTech.open('GET', 'http://' + window.location.host + '/filter/technology/' + tech, true)
            requestTech.onload = function () {

                var data = JSON.parse(this.response)
                $('#TechList').html('');

                if (requestTech.status >= 200 && requestTech.status < 400) {

                    for (i = 0; i < data.names.length; i++) {
                        ////console.log(data.names[i].name);

                        if (data.names[i].name != tech) {

                            $('#TechList').append('<option value="' + data.names[i].name + '">');
                        }
                    }
                } else {
                    console.log('error')
                }


            }

            requestTech.send()
        } else {
        }
    });
//////


    ////getting languages
    $('#instrument-forms').on("keyup", ".lang", function (callback) {
        // console.log("a");
        var lang = $(this).val()

        // console.log(lang);
        if (lang != '') {


            var requestLang = new XMLHttpRequest()
            var exists = 0;

            requestLang.open('GET', 'http://' + window.location.host + '/filter/language/' + lang, true)
            requestLang.onload = function () {

                var data = JSON.parse(this.response)
                $('#LangList').html('');

                if (requestLang.status >= 200 && requestLang.status < 400) {

                    for (i = 0; i < data.names.length; i++) {
                        ////console.log(data.names[i].name);

                        if (data.names[i].name != lang) {

                            $('#LangList').append('<option value="' + data.names[i].name + '">');
                        }
                    }
                } else {
                    console.log('error')
                }


            }

            requestLang.send()


        } else {

        }

    });
    /////////////////////////////

/*
    ////getting cities
    $('#cities-forms').on("keyup", ".city", function (callback) {

        var city = $(this).val()
        ///console.log(lang);
        if (city != '') {


            var request = new XMLHttpRequest()
            var exists = 0;

            request.open('GET', 'http://' + window.location.host + '/filter/city/' + city, true)
            request.onload = function () {

                var data = JSON.parse(this.response)
                $('#CityList').html('');

                if (request.status >= 200 && request.status < 400) {

                    for (i = 0; i < data.names.length; i++) {
                        ////console.log(data.names[i].name);

                        if (data.names[i].name != city) {

                            $('#CityList').append('<option value="' + data.names[i].name + '">');
                        }
                    }
                } else {
                    console.log('error')
                }


            }

            request.send()


        } else {

        }

    });*/
    /////////////////////////////


    //////////////photo preview
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#photo-edit').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#fileToUpload").change(function () {
        readURL(this);
    });
    ////


    var i = 1;


    //////// TODO  function for submiting form

    $('#register-form').click(function () {
            if (username_free) { //if form is valid submit form
                return true;
            }
            $('#username').css('border', '1.5px solid red');
            event.preventDefault();

        }
    );
    ////////////////////////buttons

    $('#GenresTab').hide();
    $('#InstrumentsTab').hide();
    $('#BlockedTab').hide();
    $('#ProfileTab').show();

    $("#ProfileButton").on("click", function () {
        event.preventDefault();
        $("#GenreButton").removeClass("actives").addClass("nonactives");
        $("#InstrumentsButton").removeClass("actives").addClass("nonactives");
        $("#BlockedButton").removeClass("actives").addClass("nonactives");
        $(this).removeClass("nonactives").addClass("actives");
        $('#GenresTab').hide();
        $('#InstrumentsTab').hide();
        $('#BlockedTab').hide();
        $('#ProfileTab').show();
    });

    $("#GenreButton").on("click", function () {
        event.preventDefault();
        $("#ProfileButton").removeClass("actives").addClass("nonactives");
        $("#InstrumentsButton").removeClass("actives").addClass("nonactives");
        $("#BlockedButton").removeClass("actives").addClass("nonactives");
        $(this).removeClass("nonactives").addClass("actives");
        $('#ProfileTab').hide();
        $('#InstrumentsTab').hide();
        $('#BlockedTab').hide();
        $('#GenresTab').show();
    });

    $("#InstrumentsButton").on("click", function () {
        event.preventDefault();
        $("#GenreButton").removeClass("actives").addClass("nonactives");
        $("#ProfileButton").removeClass("actives").addClass("nonactives");
        $("#BlockedButton").removeClass("actives").addClass("nonactives");
        $(this).removeClass("nonactives").addClass("actives");
        $('#ProfileTab').hide();
        $('#GenresTab').hide();
        $('#BlockedTab').hide();
        $('#InstrumentsTab').show();
    });

    $("#BlockedButton").on("click", function () {
        event.preventDefault();
        $("#GenreButton").removeClass("actives").addClass("nonactives");
        $("#InstrumentsButton").removeClass("actives").addClass("nonactives");
        $("#ProfileButton").removeClass("actives").addClass("nonactives");
        $(this).removeClass("nonactives").addClass("actives");
        $('#ProfileTab').hide();
        $('#GenresTab').hide();
        $('#InstrumentsTab').hide();
        $('#BlockedTab').show();
    });


});




