$(document).ready(function() {

    const customerModal = $('#modal-customer');

    $('#modal-customer-button').click(function() {
        openModalToCreate(customerModal, ['id']);
    });

    $('a[data-customer-id]').click(function() {
        openModalToEdit(customerModal, $(this), 'data-customer-', ['id', 'name', 'email']);
    });

});