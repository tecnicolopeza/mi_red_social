$(document).ready(function(){

    
    var publication = $('.publication_id').html();

    countLikes(publication);

    // Se va a estar recargando cada 1min
    setInterval(function(){
        countLikes();
    }, 60000);
    
});

// Obtiene la cantidad de likes y lo muestra
function countLikes(publication){

    
    console.log(publication);

    $.ajax({
        url: '/likesPublication/'+publication,
        type: 'GET',
        // data: { publication: publication },
        success: function(response){
            $('.countLikes').html(response);

            // if(response == 0){
            //     $('.countLikes').css("visibility", "hidden");
            // }else{
            //     $('.countLikes').css("visibility", "visible");
            // }
        }
        
    });
    
}