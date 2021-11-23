$(document).ready(function() {

    $('#modal-customer-button').click(function() {
        $('#modal-customer form')[0].reset();
        $('#modal-customer input[name=id]').val('');
        $('#modal-customer').modal("show");
    });

    $('a[data-customer-id]').click(function() {
        $('#modal-customer input[name=id]').val($(this).attr('data-customer-id'));
        $('#modal-customer input[name=name]').val($(this).attr('data-customer-name'));
        $('#modal-customer input[name=email]').val($(this).attr('data-customer-email'));
        $('#modal-customer').modal("show");
    });

});