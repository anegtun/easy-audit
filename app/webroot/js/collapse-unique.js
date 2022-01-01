$(document).ready(function() {
    
    $('[data-toggle="collapse-unique"]').click(function(e) {
        e.preventDefault();
        const clicked = this;
        const target = $(clicked.hash);
        if(!target.hasClass('in')) {
            $('[data-toggle="collapse-unique"]').each(function(i, item) {
                $(item.hash).collapse('hide');
            });
        }
        target.on('shown.bs.collapse', function() {
            $('html, body').animate({
                scrollTop: $(clicked).offset().top
            }, 200);
        }).collapse('toggle');
    });

});