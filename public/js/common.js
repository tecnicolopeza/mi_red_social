// Cuando la pagina carge lanzamos una funcion
$(document).ready(function(){
    // Si hay 0 notificaciones lo oculta, sino lo muestra
    if($('.label-notifications').text() == 0){
        $('.label-notifications').css("visibility", "hidden");
    }else{
        $('.label-notifications').css("visibility", "visible");
    }

    if($('.label-notifications-msg').text() == 0){
        $('.label-notifications-msg').css("visibility", "hidden");
    }else{
        $('.label-notifications-msg').css("visibility", "visible");
    }

    notifications();

    // Se va a estar recargando cada 1min
    setInterval(function(){
        notifications();
    }, 60000);
    
});

// Obtiene la cantidad de notificaciones y lo muestra
function notifications(){
    $.ajax({
        url: '/notifications/get',
        type: 'GET',
        success: function(response){
            $('.label-notifications').html(response);

            if(response == 0){
                $('.label-notifications').css("visibility", "hidden");
            }else{
                $('.label-notifications').css("visibility", "visible");
            }
        }
    });

    $.ajax({
        url: '/private-message/notification/get',
        type: 'GET',
        success: function(response){
            $('.label-notifications-msg').html(response);

            if(response == 0){
                $('.label-notifications-msg').css("visibility", "hidden");
            }else{
                $('.label-notifications-msg').css("visibility", "visible");
            }
        }
    });
}