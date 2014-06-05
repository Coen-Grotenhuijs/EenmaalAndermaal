$(document).ready(function(){
        $("#registreren .preview").click(function(){
                $(this).parent().parent().remove();
        });

        $(".fileSelector").click(function(){
                $(this).parent().find('input').click();
        });
});

function readURL(input) {
        if (input.files && input.files[0]) {
                var reader = new FileReader();

                var row = $(input).parent().parent();

                $("#registreren table").append(row.clone());

                $(".fileSelector").click(function(){
                        $(this).parent().find('input').click();
                });
                $("#registreren .preview").click(function(){
                        $(this).parent().parent().remove();
                });

                reader.onload = function (e) {
                        $(input).parent().find('.preview')
                                .attr('src', e.target.result)
                                .width(150)
                                .height(150)
                                .css("display", "block");
                        $(input).parent().find('.fileSelector').css("display", "none");
                };

                reader.readAsDataURL(input.files[0]);
        }
}
