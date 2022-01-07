$(document).ready(function() {

    const sectionModal = $('#modal-section');

    $('#modal-section-button').click(function() {
        openModalToCreate(sectionModal, ['id']);
        sectionModal.find('*[name=position]').prop('disabled', false);
    });

    $('a[data-section-id]').click(function() {
        openModalToEdit(sectionModal, $(this), 'data-section-', ['id', 'name', 'weigth']);
        sectionModal.find('*[name=position]').val(Number($(this).attr('data-section-position')) + 1).prop('disabled', true);
    });
});