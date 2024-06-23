<?= $this->element("form_templates/detail/field_editor", [
    'is_editable' => $is_editable,
    'allowed_field_types' => ['select']
]) ?>