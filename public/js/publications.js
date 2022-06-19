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
        deleteBtn();
        followBtn();
        unfollowBtn();
    })

    ias.on('binded', function() {
        // btn_tooltip();
        likeBtn();
        dislikeBtn();
        deleteBtn();
        followBtn();
        unfollowBtn();
    })


});


// function btn_tooltip() {
//     $('[data-toggle]="tooltip"').tooltip();
// }

function deleteBtn() {
    $(".btn-delete-pub").unbind('click').click(function() {
        $(this).parent().parent().addClass('hidden');

        $.ajax({
            url: '/publication/remove/' + $(this).attr("data-id"),
            type: 'POST',
            success: function(response) {
                console.log(response);
                location.reload();
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
                location.reload();
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
                location.reload();
            }
        });
    });
}


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