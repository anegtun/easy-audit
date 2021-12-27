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

    $('.audit-observations > a').click(function(e) {
        e.preventDefault();
        $(e.currentTarget).hide().siblings('.audit-observations-input').show();
    });

    $('input[type="file"]').change(function() {
        const previewBox = $(this).parents('.audit-img-input').siblings('.audit-img-preview');
        previewBox.find('p').show();
        previewBox.find('img').remove();
        $.each(this.files, function(i, item) {
            var reader = new FileReader();
            reader.onload = (e) => previewBox.append($('<img>').attr('src', e.target.result));
            reader.readAsDataURL(item);
        });
    });

    $('.audit-img-current img').click(function() {
        const img = $(this);
        const fieldId = img.attr('data-field-id');
        const src = img.attr('src');
        const filename = src.substring(src.lastIndexOf('/') + 1);
        if(img.hasClass('to-remove')) {
            img.removeClass('to-remove');
            $('form').find('input[data-field-id="'+fieldId+'"][data-filename="'+filename+'"]').remove();
        } else {
            img.addClass('to-remove');
            $('form').append($('<input type="hidden" name="field_img_removed['+fieldId+'][]" data-field-id='+fieldId+' data-filename='+filename+' value="'+filename+'">'));
        }
    });

});