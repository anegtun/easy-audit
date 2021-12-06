$(document).ready(function() {

    $('#modal-new-audit select[name=customer_id]').change(function() {
        const templateField = $('#modal-new-audit select[name=form_template_id]').empty().append($('<option>'));
        const customerId = $(this).val();
        const url = "customers/templates/" + customerId + '.json';
        $.get(url, (data) => {
            $.each(data, (i, item) => templateField.append($('<option>').text(item.name).val(item.id)));
        });
    });

});