var instrumentData;

$(document).ready(function() {



/////////////wczytywanie blokad uzytkownika
    var requestTechUser = new XMLHttpRequest();
    requestTechUser.open('GET', location.protocol + '//' + window.location.host + '/profile/edit' + '/blockedUsers', true);
    requestTechUser.onload = function () {

        var data = JSON.parse(this.response);


        if (requestTechUser.status >= 200 && requestTechUser.status < 400) {
            //console.log(userID);
            for (i = 1; i < data.length; i++) {
                console.log(data[i]);

                $('#tech-forms').append('<div> <input type="text" class="bio tech" id="tech" placeholder="Nazwa użytkownika do zignorowania" maxlength="50" name="usernames[]" list="TechList"><datalist id="TechList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');

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

    requestTechUser.send();
/////end blokad


/////////////wczytywanie instrumentow uzytkownika

    var requestLangUser = new XMLHttpRequest()


    requestLangUser.open('GET', location.protocol + '//' + window.location.host + '/profile/edit' + '/instruments', true)
    requestLangUser.onload = function () {

        instrumentData = JSON.parse(this.response)


        if (requestLangUser.status >= 200 && requestLangUser.status < 400) {
            //console.log(userID);
            for (i = 1; i < instrumentData.length; i++) {
                //console.log(data[i]);

                $('#instrument-forms').append('<div><input type="tel" class="bio lang down" placeholder="Nowy instrument" name="instruments[]" maxlength="50" id="lang" list="LangList"><datalist id="LangList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');

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

    requestLangUser.send();
//////end instrumentow


    document.getElementById("add-instruments").onclick = function () {
        updateInstruments()
    };

    function updateInstruments() {

        if (validateformCreateInstr()) {


            var currentInstruments = $('form[name="instrument-form"]').serializeArray();

            $.ajax({
                url: location.protocol + '//' + window.location.host + '/profile/edit' + '/update_instruments',
                type: 'PUT',
                data: {instruments: currentInstruments},
            });
            $.growl.notice({ message: "Instrumenty zostały zapisane." });

        } else
    {
        $.growl.error({ message: "Podane instrumenty nie znajdują się w bazie!" });
    }
}
    document.getElementById("add-genres").onclick = function() {updateGenres()};

    function updateGenres() {
        if (validateformCreateGenres()) {
        var currentMusicGenres = $('form[name="musicgenres-form"]').serializeArray();

        $.ajax({
            url: location.protocol + '//' + window.location.host + '/profile/edit' + '/update_genres',
            type: 'PUT',
            data: { musicGenres: currentMusicGenres },
        });
        $.growl.notice({ message: "Gatunki zostały zapisane." });}
        else
        {
            $.growl.error({ message: "Podane gatunki nie znajdują się w bazie!" });
        }
    }
    document.getElementById("add-blocked-users").onclick = function() {updateBlockerUsers()};

    function updateBlockerUsers() {
        if (validateformCreateBan()) {
            var currentBlockedUsers = $('form[name="blocked-users-form"]').serializeArray();

            $.ajax({
                url: location.protocol + '//' + window.location.host + '/profile/edit' + '/update_blockedUsers',
                type: 'PUT',
                data: { usernames: currentBlockedUsers },
            });
            $.growl.notice({ message: "Zablokowani użytkownicy zostali zapisani." });}
        else
        {
            $.growl.error({ message: "Podani użytkownicy nie istnieją!" });
        }
    }

/////////////wczytywanie gatunków uzytkownika

    var requestCityUser = new XMLHttpRequest()


    requestCityUser.open('GET', location.protocol + '//' + window.location.host + '/profile/edit' + '/musicGenres', true)
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

/////end gatunków

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

        $('#cities-forms').append('<div><input type="text" class="bio city down" placeholder="Nowy gatunek muzyczny" maxlength="50" id="lang" name="musicGenres[]" list="CityList"><datalist id="CityList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');


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


//////
    var changeTimer = false;
    ////getting instruments
    $('#instrument-forms').on("input", ".lang", function (callback) {
        if(changeTimer !== false) clearTimeout(changeTimer);
        changeTimer = setTimeout(function(){
        var lang = $(callback.target).val();

        ///console.log(lang);
        if (lang != '') {


            var requestLang = new XMLHttpRequest()
            var exists = 0;

            requestLang.open('GET', location.protocol + '//' + window.location.host + '/filter/instrument/' + lang, true)
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
                    //console.log('error')
                }


            }

            requestLang.send()


        } else {

        }
            changeTimer = false;
        },500);

    });
    /////////////////////////////

    var changeTimer2 = false;
    ////getting genres
    $('#cities-forms').on("input", ".city", function (callback) {

        if(changeTimer2 !== false) clearTimeout(changeTimer2);
        changeTimer2 = setTimeout(function(){
            var city = $(callback.target).val();
        ///console.log(city);
        if (city != '') {


            var request = new XMLHttpRequest()
            var exists = 0;

            request.open('GET', location.protocol + '//' + window.location.host + '/filter/musicGenre/' + city, true)
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
                    ///console.log('error')
                }


            }

            request.send()


        } else {

        }
            changeTimer2 = false;
        },500);
    });
    /////////////////////////////

/////////////////////////////

    var changeTimer3 = false;
    ////getting genres
    $('#names-container').on("input", ".ct", function (callback) {

        if(changeTimer2 !== false) clearTimeout(changeTimer2);
        changeTimer2 = setTimeout(function(){
            var ct = $(callback.target).val();
            ///console.log(city);
            if (ct != '') {


                var request = new XMLHttpRequest()
                var exists = 0;

                request.open('GET', location.protocol + '//' + window.location.host + '/filter/city/' + ct, true)
                request.onload = function () {

                    var data = JSON.parse(this.response)
                    $('#CityList').html('');

                    if (request.status >= 200 && request.status < 400) {

                        for (i = 0; i < data.names.length; i++) {
                            ////console.log(data.names[i].name);

                            if (data.names[i].name != ct) {

                                $('#CtList').append('<option value="' + data.names[i].name + '">');
                            }
                        }
                    } else {
                        ///console.log('error')
                    }


                }

                request.send()


            } else {

            }
            changeTimer2 = false;
        },500);
    });
    /////////////////////////////





    var i = 1;


    ////////

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

 //////////////////////////////

    function validateformCreateInstr() {


        var isValid;
        $(".lang").each(function () {

            var element = $(this);
//////
            element.css("border", "1px solid #7DA2AA");
            var requestExistsInstr = new XMLHttpRequest()

            if ((element.val() == "") || (element.val() == null)) {
                isValid = false;
                element.css("border", "1px solid red");

            } else {

                requestExistsInstr.open('GET', location.protocol + '//127.0.0.1:8000/filter/instrumentExists/' + element.val(), false)
                requestExistsInstr.onload = function () {

                    var dataE = JSON.parse(this.response)


                    if (requestExistsInstr.status >= 200 && requestExistsInstr.status < 400) {
                        ///console.log(dataE);


                        if ((element.val() == "") || dataE == "false" || (element.val() == null)) {
                            ////console.log("NOPEEEE" + element.val());
                            isValid = false;
                            element.css("border", "1px solid red");
                        }


                    } else {
                        isValid = false;
                        //console.log('error')
                    }


                }

                requestExistsInstr.send()
            }
        });


//////


        if (isValid == false) {
            return false;
        } else {
            return true;
        }


    }
////////////////////////////////////////////////////////////////////////


    function validateformCreateGenres() {


        var isValid;
        $(".city").each(function () {

            var element = $(this);
//////
            element.css("border", "1px solid #7DA2AA");
            var requestExistsGenre = new XMLHttpRequest()

            if ((element.val() == "") || (element.val() == null)) {
                isValid = false;
                element.css("border", "1px solid red");

            } else {

                requestExistsGenre.open('GET', location.protocol + '//127.0.0.1:8000/filter/musicGenreExists/' + element.val(), false)
                requestExistsGenre.onload = function () {

                    var dataE = JSON.parse(this.response)


                    if (requestExistsGenre.status >= 200 && requestExistsGenre.status < 400) {
                        ///console.log(dataE);


                        if ((element.val() == "") || dataE == "false" || (element.val() == null)) {
                            ////console.log("NOPEEEE" + element.val());
                            isValid = false;
                            element.css("border", "1px solid red");
                        }


                    } else {
                        isValid = false;
                        //console.log('error')
                    }


                }

                requestExistsGenre.send()
            }
        });


//////


        if (isValid == false) {
            return false;
        } else {
            return true;
        }


    }

    //////////////////////////////

    function validateformCreateBan() {


        var isValid;
        $(".tech").each(function () {

            var element = $(this);
//////
            element.css("border", "1px solid #7DA2AA");
            var requestExistsBan= new XMLHttpRequest()

            if ((element.val() == "") || (element.val() == null)) {
                isValid = false;
                element.css("border", "1px solid red");

            } else {

                requestExistsBan.open('GET', location.protocol + '//127.0.0.1:8000/userExists/' + element.val(), false)
                requestExistsBan.onload = function () {

                    var dataE = JSON.parse(this.response)


                    if (requestExistsBan.status >= 200 && requestExistsBan.status < 400) {
                        ///console.log(dataE);


                        if ((element.val() == "") || dataE == "false" || (element.val() == null)) {
                            ////console.log("NOPEEEE" + element.val());
                            isValid = false;
                            element.css("border", "1px solid red");
                        }


                    } else {
                        isValid = false;
                        //console.log('error')
                    }


                }

                requestExistsBan.send()
            }
        });


//////


        if (isValid == false) {
            return false;
        } else {
            return true;
        }


    }
////////////////////////////////////////////////////////////////////////
});




