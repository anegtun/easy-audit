$(document).ready(function() {

    const cloneModal = $('#modal-clone-template');
    const sectionModal = $('#modal-section');
    
    $('#modal-section-button').click(function() {
        openModalToCreate(sectionModal, ['id']);
        sectionModal.find('*[name=position]').prop('disabled', false);
    });

    $('.modal-clone-button').click(function(e) {
        e.preventDefault();
        openModalToEdit(cloneModal, $(this), 'data-template-', ['id', 'name']);
        cloneModal.find('*[name=name_old]').prop('placeholder', $(this).attr('data-template-name'));
    });

    $('a[data-section-id]').click(function() {
        openModalToEdit(sectionModal, $(this), 'data-section-', ['id', 'name', 'weigth']);
        sectionModal.find('*[name=position]').val(Number($(this).attr('data-section-position')) + 1).prop('disabled', true);
    });
});