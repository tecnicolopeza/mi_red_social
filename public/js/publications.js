$(document).ready(function() {

    console.log("user Script working properly");

    let ias = new InfiniteAjaxScroll('.content-publications', {
        item: '.publication-item',
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
    ias.on('last', function() {
        let el = document.querySelector('.no-more');

        el.style.opacity = '1';
    })

    ias.on('appended', function() {
        // btn_tooltip();
        likeBtn();
        dislikeBtn();
    })

    ias.on('binded', function() {
        // btn_tooltip();
        likeBtn();
        dislikeBtn();
    })


});


// function btn_tooltip() {
//     $('[data-toggle]="tooltip"').tooltip();
// }

function buttons() {
    $(".btn-delete-pub").unbind('click').click(function() {
        $(this).parent().parent().addClass('hidden');

        $.ajax({
            url: URL+'/publication/remove/'+$(this).attr("data-id"),
            type: 'GET',
            success: function(response){
                console.log(response);
            }
        });
    });
}

function likeBtn() {
    $(".btn-like").unbind("click").click(function() {
        $(this).addClass("d-none");
        $(this).parent().find(".btn-dislike").removeClass("d-none");
        $.ajax({
            url: '/like',
            data: { publication: $(this).attr('data-btn-like') },
            type: 'POST',
            success: function(data) {
                console.log(data);
            }
        });
    });
}

function dislikeBtn() {
    $(".btn-dislike").unbind("click").click(function() {
        $(this).addClass("d-none");
        $(this).parent().find(".btn-like").removeClass("d-none");
        $.ajax({
            url: '/dislike',
            data: { publication: $(this).attr('data-btn-dislike') },
            type: 'POST',
            success: function(data) {
                console.log(data);
            }
        });
    });
}