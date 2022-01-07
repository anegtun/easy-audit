$(document).ready(function() {

    let editor;
    ClassicEditor
        .create(document.querySelector('#field-text'), { toolbar: [ 'bold', 'italic' ] })
        .then(newEditor => editor = newEditor)
        .catch(error => console.error(error));

    const cloneModal = $('#modal-clone-template');
    const fieldModal = $('#modal-field');

    $('#modal-field-button').click(function() {
        openModalToCreate(fieldModal, ['id']);
        fieldModal.find('*[name=position]').prop('disabled', false);
        fieldModal.find('*[name=form_section_id]').trigger("change");
    });

    $('.modal-clone-template-button').click(function(e) {
        e.preventDefault();
        openModalToEdit(cloneModal, $(this), 'data-template-', ['id', 'name']);
        cloneModal.find('*[name=name_old]').prop('placeholder', $(this).attr('data-template-name'));
    });

    $('a[data-field-id]').click(function() {
        openModalToEdit(fieldModal, $(this), 'data-field-', ['id', 'form_section_id', 'text', 'type']);
        editor.setData($(this).attr('data-field-text'));
        fieldModal.find('*[name=type]').trigger("change");
        fieldModal.find('*[name=form_section_id]').trigger("change");
        fieldModal.find('*[name=position]').val(Number($(this).attr('data-field-position')) + 1).prop('disabled', true);
    });

    $('#modal-field select[name=type]').change(function() {
        const optionsetField = $('#modal-field select[name=optionset_id]').parents('.form-group');
        $(this).val() === 'select' ? optionsetField.show() : optionsetField.hide();
    }).change();

    $('#modal-field select[name=form_section_id]').change(function() {
        const sectionId = $(this).val();
        const positionField = $('#modal-field select[name=position]').empty().append($('<option>'));
        $('#modal-field select[name=position]').text();
        if(sectionId) {
            $('#all-field-options input[data-section='+sectionId+']').each(function(index, value) {
                positionField.append($('<option>').text($(value).val()).val($(value).attr('data-position')));
            });
        }
    });
});