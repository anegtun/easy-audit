<div class="audit-field-select" data-optionset-id="<?= $field->optionset_id ?>">
    <?php foreach($optionset_values[$field->optionset_id] as $option) : ?>
        <label>
            <input
                type="radio"
                name="<?= "field_values[{$template->id}][{$field->id}]" ?>"
                value="<?= $option->id ?>"
                <?= !empty($value) && $value->optionset_value_id === $option->id ? 'checked="checked"' : '' ?>
                <?= empty($option->is_default) ? 'data-open-observations="true"' : '' ?>
            />
            <span><?= $option->label ?></span>
        </label>
    <?php endforeach ?>
    <label>
        <input
            type="radio"
            name="<?= "field_values[{$template->id}][{$field->id}]" ?>"
            value=""
            <?= empty($value) ? 'checked="checked"' : '' ?>
        />
        <span>N/A</span>
    </label>
</div>