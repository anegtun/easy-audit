$(document).ready(function() {

    const sectionModal = $('#modal-section');
    const fieldModal = $('#modal-field');

    $('#modal-section-button').click(function() {
        openModalToCreate(sectionModal, ['id']);
        sectionModal.find('*[name=position]').prop('disabled', false);
    });

    $('#modal-field-button').click(function() {
        openModalToCreate(fieldModal, ['id']);
        fieldModal.find('*[name=position]').prop('disabled', false);
        fieldModal.find('*[name=form_template_section_id]').trigger("change");
    });

    $('a[data-section-id]').click(function() {
        openModalToEdit(sectionModal, $(this), 'data-section-', ['id', 'name']);
        sectionModal.find('*[name=position]').val(Number($(this).attr('data-section-position')) + 1).prop('disabled', true);
    });

    $('a[data-field-id]').click(function() {
        openModalToEdit(fieldModal, $(this), 'data-field-', ['id', 'form_template_section_id', 'text']);
        fieldModal.find('*[name=form_template_section_id]').trigger("change");
        fieldModal.find('*[name=position]').val(Number($(this).attr('data-field-position')) + 1).prop('disabled', true);
    });

    $('#modal-field select[name=form_template_section_id]').change(function() {
        const sectionId = $(this).val();
        const positionField = $('#modal-field select[name=position]').empty().append($('<option>'));
        $('#modal-field select[name=position]').text();
        $('#all-field-options input[data-section='+sectionId+']').each(function(index, value) {
            positionField.append($('<option>').text($(value).val()).val($(value).attr('data-position')));
        });
    });

});