let auditDirty = false;
window.onbeforeunload = function() {
    if(auditDirty) {
        return $('#dirtyFormMsg').text();
    }
    return;
};

$(document).ready(function() {

    const onRemoveMeasure = function(e) {
        e.preventDefault();
        $(e.currentTarget).parents('.audit-measure').remove();
    };

    const onChangeMeasures = function(e) {
        const parent = $(e.currentTarget).parents('.audit-measure');
        const expected = parent.find('.audit-measure-expected input').val() || 0;
        const actual = parent.find('.audit-measure-actual input').val() || 0;
        const thredshold = parent.find('.audit-measure-thredshold input').val() || 0;
        const difference = Math.round((expected - actual) * 100) / 100;
        const isOk = Math.abs(difference) <= thredshold;
        parent.find('.audit-measure-difference input').val(difference);
        if(isOk) {
            parent.find('.audit-measure-result .glyphicon-ok-sign').show();
            parent.find('.audit-measure-result .glyphicon-remove-sign').hide();
        } else {
            parent.find('.audit-measure-result .glyphicon-remove-sign').show();
            parent.find('.audit-measure-result .glyphicon-ok-sign').hide();
        }
    };

    const onAddPhoto = function() {
        const file = this.files && this.files[0];
        const inputsDiv = $(this).parents('.audit-img-inputs');
        const previewBox = inputsDiv.siblings('.audit-img-preview');
        previewBox.find('p').show();

        const reader = new FileReader();
        reader.onload = (e) => {
            const img = $('<img>').attr('src', e.target.result).attr('data-name', file.name).click(function() {
                if($(this).hasClass('to-remove')) {
                    $(this).removeClass('to-remove');
                    inputsDiv.find('[data-photo-name="'+$(this).attr('data-name')+'"] input').prop('disabled', false);
                } else {
                    $(this).addClass('to-remove');
                    inputsDiv.find('[data-photo-name="'+$(this).attr('data-name')+'"] input').prop('disabled', true);
                }
            });
            previewBox.append(img);
        }
        reader.readAsDataURL(file);

        const newId = 'photo-' + Math.random();
        const newInput = $(this).parents('.audit-img-input').clone();
        newInput.find('input[type="file"]').attr('id',newId).val('').change(onAddPhoto);
        newInput.find('label').attr('for',newId);
        $(this).parents('.audit-img-input').attr('data-photo-name', file.name).hide();
        inputsDiv.append(newInput);
    };

    $('#modal-new-audit select[name=customer_id]').change(function() {
        const templateContainer = $('#template-check-container').empty();
        const customerId = $(this).val();
        const url = "customers/templates/" + customerId + '.json';
        $.get(url, (data) => {
            $.each(data, (i, item) => {
                const div = $('<label class="checkbox-container">');
                div.append('<input type="checkbox" name="form_template_id[]" value="'+item.id+'">');
                div.append($('<span>').text(item.name));
                templateContainer.append(div);
            });
        });
    });

    $('.audit-open-all .open-button').click(function(e) {
        e.preventDefault();
        const fieldsset = $(this).parents('.audit-open-all').siblings('fieldset').find('.collapse').attr('data-collapse-scroll', false).collapse("show");
        fieldsset.find("textarea")
            .filter(function() { return $(this).val() != ""; })
            .parents('.audit-observations')
            .find('.audit-observations-open')
            .click();
        setTimeout(function() {
            fieldsset.attr('data-collapse-scroll','');
        }, 1000);
    });

    $('.audit-open-all .close-button').click(function(e) {
        e.preventDefault();
        const fieldsset = $(this).parents('.audit-open-all').siblings('fieldset').find('.collapse').collapse("hide");
        fieldsset.find("textarea")
            .parents('.audit-observations')
            .find('.audit-observations-close')
            .click();
    });

    $('.audit-field-select input').change(function() {
        const value = $(this).val();
        const container = $(this).parents('.audit-field-select');
        const fieldDiv = container.parents('.audit-field');
        const optionsConfig = $('#optionset-'+container.attr('data-optionset-id'));
        if(optionsConfig) {
            optionsConfig.find('div[data-opt-color]').each(function(i, div) {
                const color = $(div).attr('data-opt-color');
                if(color) {
                    fieldDiv.removeClass('audit-field-'+color);
                }
            });
            if(value) {
                optionsConfig.find('div[data-opt-id='+value+']').each(function(i, div) {
                    const color = $(div).attr('data-opt-color');
                    if(color) {
                        fieldDiv.addClass('audit-field-'+color);
                    }
                });
            }
        }
        if($(this).attr('data-open-observations')) {
            fieldDiv.find('.audit-observations-open').click();
            fieldDiv.find('.audit-observations textarea').focus();
        }
    })
    $('.audit-field-select input:checked').change();

    $('.add-measure').click(function(e) {
        e.preventDefault();
        const container = $(e.currentTarget).siblings('.audit-measures');
        const count = container.find('.audit-measure').length;
        const field = container.find('.audit-measure-template').clone().removeClass('audit-measure-template').attr('style', '');
        field.find('.remove-measure').click(onRemoveMeasure);
        field.find('.audit-measure-expected input, .audit-measure-actual input, .audit-measure-thredshold input').change(onChangeMeasures).change();
        field.find('input').each(function(i, input) {
            const name = $(input).attr('name');
            if(name) {
                $(input).attr('name', name.replace('[0]', '['+count+']'));
            }
        });
        container.append(field);
    });

    $('.remove-measure').click(onRemoveMeasure);

    $('.audit-measure-expected input, .audit-measure-actual input, .audit-measure-thredshold input').change(onChangeMeasures).change();

    $('.audit-observations > a.audit-observations-open').click(function(e) {
        e.preventDefault();
        const link = $(e.currentTarget).removeClass('show').addClass('hide');
        link.siblings('.audit-observations-input, .audit-observations-close').removeClass('hide').addClass('show');
    });

    $('.audit-observations > a.audit-observations-close').click(function(e) {
        e.preventDefault();
        const link = $(e.currentTarget).removeClass('show').addClass('hide');
        link.siblings('.audit-observations-input').removeClass('show').addClass('hide');
        link.siblings('.audit-observations-open').removeClass('hide').addClass('show');
    });

    $('input[type="file"]').change(onAddPhoto);

    $('.audit-img-current img').click(function() {
        const img = $(this);
        const templateId = img.attr('data-template-id');
        const fieldId = img.attr('data-field-id');
        const src = img.attr('src');
        const filename = src.substring(src.lastIndexOf('/') + 1);
        if(img.hasClass('to-remove')) {
            img.removeClass('to-remove');
            $('form').find('input[data-template-id="'+templateId+'"][data-field-id="'+fieldId+'"][data-filename="'+filename+'"]').remove();
        } else {
            img.addClass('to-remove');
            $('form').append($('<input type="hidden" name="field_img_removed['+templateId+']['+fieldId+'][]" data-template-id='+templateId+' data-field-id='+fieldId+' data-filename='+filename+' value="'+filename+'">'));
        }
        auditDirty = true;
    });

    $('#auditForm input, #auditForm select, #auditForm textarea').change(function() {
        auditDirty = true;
    });

    $('#auditForm').submit(function() {
        auditDirty = false;
    });

});