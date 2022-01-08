<div class="audit-field-date">
    <?= $this->EasyAuditForm->dateControl("field_values[{$template->id}][{$field->id}]", [
        'label' => false,
        'value' => empty($value) ? '' : $value->value
    ]) ?>
</div>