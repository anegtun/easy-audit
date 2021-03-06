$(document).ready(function() {

    $('#modal-new-audit select[name=customer_id]').change(function() {
        const templateContainer = $('#template-check-container').empty();
        const customerId = $(this).val();
        const url = "customers/templates/" + customerId + '.json';
        $.get(url, (data) => {
            $.each(data, (i, item) => {
                const div = $('<label class="checkbox-container">');
                div.append('<input type="checkbox" name="form_template_id[]" value="'+item.id+'">');
                div.append($('<span>').text(item.form_name+" - "+item.name));
                templateContainer.append(div);
            });
        });
    });

});