$(document).ready(function() {

    $('[data-toggle="collapse-unique"]').each(function(i, item) {
        const clicked = this;
        $(clicked.hash).on('shown.bs.collapse', function() {
            $('html, body').animate({ scrollTop: $(clicked).offset().top }, 200);
        }).on('show.bs.collapse', function() {
            $('html, body').animate({ scrollTop: $(clicked).offset().top }, 200);
        });
    });

    $('[data-toggle="collapse-unique"]').click(function(e) {
        e.preventDefault();
        const target = $(this.hash);
        if(!target.hasClass('in')) {
            $('[data-toggle="collapse-unique"]').each((i, item) => $(item.hash).collapse('hide'));
        }
        target.collapse('toggle');
    });

});