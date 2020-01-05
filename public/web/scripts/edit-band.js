$(document).ready(function () {

/////////////wczytywanie technologii projektu
    var bandId=document.getElementById('bandId').value;

    var requestTechProject = new XMLHttpRequest()


    requestTechProject.open('GET', location.protocol + '//' + window.location.host + '/band/edit' + '/musicGenres/' + bandId, true)
    requestTechProject.onload = function () {

        var data = JSON.parse(this.response)


        if (requestTechProject.status >= 200 && requestTechProject.status < 400) {
            //console.log(userID);
            for (i = 1; i < data.length; i++) {
                //console.log(data[i]);

                $('#tech-forms').append('<div> <input type="TechList" class="bio tech" id="tech" placeholder="Gatunek muzyczny" name = "musicGenres[]"  maxlength="50" list="TechList"><datalist id="TechList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');

            }
            if (data.length>0) {
                var i=0;
                $(".tech").each(function() {
                    var element = $(this);
                    if (element.val() == "") {
                        element.val(data[i]);
                        i++;
                    }
                });}

        } else {
            console.log('error')
        }


    }

    requestTechProject.send();
            $('#button-moret').click(function () {
                $('#tech-forms').append('<div> <input type="text" class="bio tech" id="tech" placeholder="Gatunek muzyczny" name = "musicGenres[]" maxlength="50" list="TechList"><datalist id="TechList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');
            });


            $('#tech-forms').on("click", ".less", function (e) {
                e.preventDefault();
                $(this).parent('div').remove();

            })


            //////////////////////////////////
            //////Function for getting genres
             var changeTimer = false;
            $('#tech-forms').on("keyup", ".tech", function (callback) {
                if(changeTimer !== false) clearTimeout(changeTimer);
                changeTimer = setTimeout(function(){
                var tech = $(callback.target).val();

                ///console.log(tech);
                if (tech != '') {
                    var requestTech = new XMLHttpRequest()
                    var exists = 0;

                    requestTech.open('GET', location.protocol + '//' + window.location.host + '/filter/musicGenre/' + tech, true)
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
                }changeTimer = false;
                },500);
            });
//////


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

            $(".fileToUpload").change(function () {
                readURL(this);
            });
            ////


        });
