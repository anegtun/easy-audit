<div class="audit-field-text">
    <?= $this->Form->control("field_values[{$template->id}][{$field->id}]", [
        'label' => false,
        'value' => empty($value) ? '' : $value->value
    ]) ?>
</div>