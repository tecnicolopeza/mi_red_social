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
        btn_tooltip();
    })

    ias.on('binded', function() {
        btn_tooltip();
    })


});


function btn_tooltip() {
    $('[data-toggle]="tooltip"').tooltip();
}

function buttons() {
    $(".btn-delete-pub").unbind('click').click(function() {
        $(this).parent().parent().addClass('hidden');
    });
}