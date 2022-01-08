<?= $this->element("FormTemplates/detail/field_editor", [
    'is_editable' => $is_editable,
    'allowed_field_types' => ['select']
]) ?>