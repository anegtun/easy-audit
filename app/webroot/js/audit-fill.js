let auditDirty = false;
window.onbeforeunload = function() {
    if(auditDirty) {
        return $('#dirtyFormMsg').text();
    }
    return;
};

$(document).ready(function() {

    /****************/
    /*** MEASURES ***/
    /****************/

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





    /**************/
    /*** SELECT ***/
    /**************/

    $('.audit-field-select input').change(function() {
        const value = $(this).val();
        const container = $(this).parents('.audit-field-select');
        const fieldDiv = container.parents('.audit-field');
        const optionsConfig = $('#optionset-'+container.attr('data-optionset-id'));
        if(optionsConfig) {
            optionsConfig.find('div[data-opt-color]').each(function(i, div) {
                const color = $(div).attr('data-opt-color');
                color && fieldDiv.removeClass('audit-field-'+color);
            });
            value && optionsConfig.find('div[data-opt-id='+value+']').each(function(i, div) {
                const color = $(div).attr('data-opt-color');
                color && fieldDiv.addClass('audit-field-'+color);
            });
        }
        $(this).attr('data-open-observations') && fieldDiv.find('.audit-observations-open').click();
    });
    $('.audit-field-select input:checked').change();

    $('.audit-observations > a.audit-observations-open').click(function(e) {
        e.preventDefault();
        const link = $(e.currentTarget).hide();
        link.siblings('.audit-observations-input, .audit-observations-close').show();
    });

    $('.audit-observations > a.audit-observations-close').click(function(e) {
        e.preventDefault();
        const link = $(e.currentTarget).hide();
        link.siblings('.audit-observations-input').hide();
        link.siblings('.audit-observations-open').show();
    });

    const onPhotoToggleSelected = function() {
        const img = $(this);
        const templateId = img.parents('.audit-img-display').attr('data-template-id');
        const fieldId = img.parents('.audit-img-display').attr('data-field-id');
        const src = img.attr('src');
        const filename = src.substring(src.lastIndexOf('/') + 1);
        if(img.hasClass('to-remove')) {
            img.removeClass('to-remove');
            $('form').find('input[name="field_img_removed['+templateId+']['+fieldId+'][]"][value="'+filename+'"]').remove();
        } else {
            img.addClass('to-remove');
            $('form').append($('<input type="hidden" name="field_img_removed['+templateId+']['+fieldId+'][]" value="'+filename+'" data-img-remove>'));
        }
        auditDirty = true;
    }

    const onPhotoUpload = (url, imageUri, inputConainer) => {
        return new Promise((resolve, reject) => {
            const imgContainer = inputConainer.siblings('.audit-img-display').show();
            const loadingBox = inputConainer.siblings('.audit-img-aux-container').find('.audit-img-loader').clone().appendTo(imgContainer);
            const errorPhoto = inputConainer.siblings('.audit-img-aux-container').find('.audit-img-photo-error img');
            $.ajax({
                url: url,
                processData: false,
                data: imageUri,
                type: 'POST',
                success : (result) => {
                    const img = $('<img>').attr('src', result).click(onPhotoToggleSelected).appendTo(imgContainer);
                    loadingBox.remove();
                    resolve(img);
                },
                error : (error) => {
                    console.log('Error uploading photo ', error);
                    const placeholder = errorPhoto
                        .clone()
                        .appendTo(imgContainer)
                        .attr('data-photo-uri', imageUri)
                        .click((e) => onPhotoRetryUpload(e.currentTarget, url, imageUri, inputConainer).catch(() => {}));
                    loadingBox.remove();
                    $('.audit-retry-photos').show();
                    reject(placeholder);
                }
            });
        });
    }

    const onPhotoRetryUpload = (placeholder, url, imageUri, inputConainer) => {
        $(placeholder).remove();
        return onPhotoUpload(url, imageUri, inputConainer);
    }

    $('input[type="file"]').change(function() {
        const file = this.files && this.files[0];
        const input = $(this).val('');
        const inputConainer = $(this).parents('.audit-img-input');
        EasyAuditCompress.compress(file, 1200, 0.8, 'image/jpeg').then((imageUri) => {
            onPhotoUpload(input.attr('data-post-url'), imageUri, inputConainer).catch(() => {});
        });
    });

    $('.audit-img-display > img').click(onPhotoToggleSelected);

    $('.audit-retry-photos').click(function() {
        const button = $(this).prop('disabled', true);
        const toRetry = $('[data-photo-uri]');
        let ok = 0, nok = 0;
        toRetry.each((i, item) => {
            const inputContainer = $(item).parents('.audit-img-display').siblings('.audit-img-input');
            onPhotoRetryUpload(item, $(item).attr('data-post-url'), $(item).attr('data-photo-uri'), inputContainer)
                .then(() => ok++)
                .catch(() => nok++);
        });
        waitFor(() => (ok+nok) >= toRetry.length).then(() => {
            button.prop('disabled', false);
            if(!nok) {
                button.hide();
            }
        });
    });





    /*****************************/
    /*** GENERAL FORM HANDLING ***/
    /*****************************/

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

    $('#auditForm input, #auditForm select, #auditForm textarea').not(':input[type=file]').change(function() {
        auditDirty = true;
    });

    $('#auditForm').submit(function() {
        auditDirty = false;
    });

    setInterval(function() {
        const form = $('#auditForm');
        $('input[data-img-remove]').prop('disabled', true);

        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: form.serialize(),
            complete: function() {
                $('input[data-img-remove]').prop('disabled','');
            },
            success: function() {
                console.log("Audit auto-saved");
                auditDirty = false;
            },
            error: function(err) {
                console.log("Error submitting form", err);
            }
        });
    }, 60000);

});