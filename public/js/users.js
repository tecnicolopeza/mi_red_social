$(document).ready(function() {

    //https://docs.infiniteajaxscroll.com/reference/options
    //https://docs.infiniteajaxscroll.com/getting-started para el codigo js

    //hay que editar: vendor/knplabs/knp-paginator-bundle/templates/Pagination/twitter_bootstrap_v4_pagination.html.twig -> de aqui elegimos el link-netx en next 

    console.log("user Script working properly");

    //realiza el cargado y muestra el spinner
    let ias = new InfiniteAjaxScroll('.content-users', { //content-users es el contenedor donde hace el scroll infinito
        item: '.user-item', //clase de usuario por el que muestra cada usuario
        next: '.pagination .link-next',
        pagination: '.pagination',
        spinner: {
            element: '.spinner',
            delay: 1000,
            show: function(element) {
                element.style.opacity = '1';
            },
            hide: function(element) {
                element.style.opacity = '0';
            }
        }
    });

    //ultimo mensaje en caso de que no haya mas usuarios
    ias.on('last', function() {
        let el = document.querySelector('.no-more');

        el.style.opacity = '1';
    })

    ias.on('appended', function() {
        followBtn();
        unfollowBtn();
    })
    ias.on('binded', function() {
        followBtn();
        unfollowBtn();
    })
});

function followBtn() {
    $(".btn-follow").unbind("click").click(function() {
        $(this).addClass("d-none");
        $(this).parent().find(".btn-unfollow").removeClass("d-none");
        $.ajax({
            url: '/follow',
            data: { followed: $(this).attr('data-btn-follow') },
            type: 'POST',
            success: function(data) {
                console.log(data);
            }
        });
    });
}

function unfollowBtn() {
    $(".btn-unfollow").unbind("click").click(function() {
        $(this).addClass("d-none");
        $(this).parent().find(".btn-follow").removeClass("d-none");
        $.ajax({
            url: '/unfollow',
            data: { followed: $(this).attr('data-btn-unfollow') },
            type: 'POST',
            success: function(data) {
                console.log(data);
            }
        });
    });
}