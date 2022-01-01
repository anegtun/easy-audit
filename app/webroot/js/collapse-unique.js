$(document).ready(function() {

    $('[data-toggle="collapse-unique"]').each(function(i, item) {
        
        $(item.hash).on('show.bs.collapse', function(e) {
            if($(this).attr('data-collapse-scroll') !== 'false'){
                $('html, body').animate({ scrollTop: $(item).offset().top }, 200);
            }
        }).on('shown.bs.collapse', function(e) {
            if($(this).attr('data-collapse-scroll') !== 'false'){
                $('html, body').animate({ scrollTop: $(item).offset().top }, 200);
            }
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