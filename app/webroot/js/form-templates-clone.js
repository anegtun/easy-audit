$(document).ready(function() {

    const cloneModal = $('#modal-clone-template');
    
    $('.modal-clone-template-button').click(function(e) {
        e.preventDefault();
        openModalToEdit(cloneModal, $(this), 'data-template-', ['id', 'name']);
        cloneModal.find('*[name=name_old]').prop('placeholder', $(this).attr('data-template-name'));
    });

    cloneModal.find('*[name=rename_old]').change(function() {
        const renameFields = cloneModal.find('.modal-clone-template-rename');
        if($(this).prop('checked')) {
            renameFields.show();
        } else {
            renameFields.hide();
            cloneModal.find('*[name=name_old]').val('');
        }
    }).change();
});