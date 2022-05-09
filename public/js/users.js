$(document).ready(function() {

    let ias = new InfiniteAjaxScroll('.content-users', {
        item: '.user-item',
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
        followBtn();
    })
    ias.on('binded', function() {
        followBtn();
    })
});

function followBtn() {
    $(".btn-follow").unbind("click").click(function() {
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