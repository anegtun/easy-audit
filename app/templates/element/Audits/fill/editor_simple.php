<?php
$fields_cloned = [];
foreach($audit->field_values as $fv) {
    if(!empty($fv->observations_cloned)) {
        $fields_cloned[] = $fv;
    }
}
?>

<?php foreach($template->form->sections as $s) : ?>

    <fieldset>
        <legend><?= $s->name ?></legend>
        <div>
            <?php foreach($template->fields as $f) : ?>
                <?php if($f->form_section_id === $s->id) : ?>
                    <div class="form-group audit-field-simple">
                        <label><?= $f->text ?></label>
                        <?= $this->element("Audits/field/type_{$f->type}", [
                            'template' => $template,
                            'field' => $f,
                            'value' => $audit->getFieldValue($f)
                        ]) ?>
                    </div>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    </fieldset>

<?php endforeach ?>