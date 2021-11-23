$(document).ready(function() {

    $('#modal-section-button').click(function() {
        $('#modal-section form')[0].reset();
        $('#modal-section input[name=id]').val('');
        $('#modal-section select[name=position]').prop('disabled', false);
        $('#modal-section').modal("show");
    });

    $('#modal-field-button').click(function() {
        $('#modal-field form')[0].reset();
        $('#modal-field input[name=id]').val('');
        $('#modal-field select[name=position]').prop('disabled', false);
        $('#modal-field').modal("show");
    });

    $('a[data-section-id]').click(function() {
        const id = $(this).attr('data-section-id');
        const position = Number($('input[name=section-position-'+id+']').val()) + 1;
        const name = $('input[name=section-name-'+id+']').val();
        $('#modal-section input[name=id]').val(id);
        $('#modal-section select[name=position]').val(position).prop('disabled', true);
        $('#modal-section input[name=name]').val(name);
        $('#modal-section').modal("show");
    });

    $('a[data-field-id]').click(function() {
        const id = $(this).attr('data-field-id');
        const section = $('input[name=field-section-'+id+']').val();
        const position = Number($('input[name=field-position-'+id+']').val()) + 1;
        const text = $('input[name=field-text-'+id+']').val();
        $('#modal-field input[name=id]').val(id);
        $('#modal-field select[name=form_template_section_id]').val(section).trigger("change");
        $('#modal-field select[name=position]').val(position).prop('disabled', true);
        $('#modal-field input[name=text]').val(text);
        $('#modal-field').modal("show");
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