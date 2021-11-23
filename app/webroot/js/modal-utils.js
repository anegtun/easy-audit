function openModalToCreate(modal, fieldsToManuallyReset) {
    modal.find('form')[0].reset();
    $.each(fieldsToManuallyReset, function(k, v) {
        modal.find('*[name='+v+']').val('');
    });
    modal.modal("show");
}

function openModalToEdit(modal, link, attrPrefix, values) {
    $.each(values, function(k, v) {
        modal.find('*[name='+v+']').val(link.attr(attrPrefix+''+v));
    });
    modal.modal("show");
}