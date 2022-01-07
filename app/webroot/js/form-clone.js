$(document).ready(function() {

    const cloneFormModal = $('#modal-clone-form');
    const cloneTemplateModal = $('#modal-clone-template');

    $('.modal-clone-form-button').click(function(e) {
        e.preventDefault();
        openModalToEdit(cloneFormModal, $(this), 'data-form-', ['id', 'name', 'public_name']);
        cloneFormModal.find('*[name=name_old]').prop('placeholder', $(this).attr('data-form-name')).val('');
        cloneFormModal.find('*[name=public_name_old]').prop('placeholder', $(this).attr('data-form-public_name')).val('');
    });

    $('.modal-clone-template-button').click(function(e) {
        e.preventDefault();
        openModalToEdit(cloneTemplateModal, $(this), 'data-template-', ['id', 'name']);
        cloneTemplateModal.find('*[name=name_old]').prop('placeholder', $(this).attr('data-template-name')).val('');
    });

    cloneFormModal.find('*[name=rename_old]').change(function() {
        const renameFields = cloneFormModal.find('.modal-clone-form-rename');
        if($(this).prop('checked')) {
            renameFields.show();
        } else {
            renameFields.hide();
            cloneFormModal.find('*[name=name_old], *[name=public_name_old]').val('');
        }
    }).change();

    cloneTemplateModal.find('*[name=rename_old]').change(function() {
        const renameFields = cloneTemplateModal.find('.modal-clone-template-rename');
        if($(this).prop('checked')) {
            renameFields.show();
        } else {
            renameFields.hide();
            cloneTemplateModal.find('*[name=name_old]').val('');
        }
    }).change();
});