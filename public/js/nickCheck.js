$(document).ready(function() { //valida el nick

    console.log("Script working properly");

    $('.nick-input').blur(function(event) {

        var nick = this.value;

        $.ajax({ //comunicación asincrona envia datos
            url: '/nickCheck',
            data: { nick: nick },
            type: 'POST',
            success: function(data) {
                if (data == 'used') { //si el nick esta en uso
                    $('.nick-input').css("border", "1px solid red");
                } else {
                    $('.nick-input').css("border", "1px solid green");
                }
            }
        });

    });

});