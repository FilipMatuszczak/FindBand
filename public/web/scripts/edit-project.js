$(document).ready(function () {

/////////////wczytywanie technologii projektu
    var ProjectID=document.getElementById('ProjectID').value;

    var requestTechProject = new XMLHttpRequest()


    requestTechProject.open('GET', 'http://127.0.0.1:8000/project/technologies/' + ProjectID, true)
    requestTechProject.onload = function () {

        var data = JSON.parse(this.response)


        if (requestTechProject.status >= 200 && requestTechProject.status < 400) {
            //console.log(userID);
            for (i = 1; i < data.length; i++) {
                //console.log(data[i]);

                $('#tech-forms').append('<div> <input type="TechList" class="bio tech" id="tech" placeholder="Technologie" name = "technologies[]"  maxlength="50" list="TechList"><datalist id="TechList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');

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

    requestTechProject.send()

/////end technologii







            /////////////////

            $('#button-moret').click(function () {
                ///todo dodaje nowe okno formularza


                $('#tech-forms').append('<div> <input type="text" class="bio tech" id="tech" placeholder="Technologie" name = "technologies[]" maxlength="50" list="TechList"><datalist id="TechList"></datalist> <button type="button" id="button-less" class="less btad">-</button></div>');


                ///////////
            });


            $('#tech-forms').on("click", ".less", function (e) {
                e.preventDefault();
                $(this).parent('div').remove();

            })


            //////////////////////////////////
            //////Function for getting technologies
            $('#tech-forms').on("keyup", ".tech", function (callback) {

                var tech = $(this).val()

                ///console.log(tech);
                if (tech != '') {
                    var requestTech = new XMLHttpRequest()
                    var exists = 0;

                    requestTech.open('GET', 'http://127.0.0.1:8000/filter/technology/' + tech, true)
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
