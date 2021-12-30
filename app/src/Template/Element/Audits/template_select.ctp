<?php
$fields_cloned = [];
foreach($audit->audit_field_optionset_values as $fv) {
    if(!empty($fv->observations_cloned)) {
        $fields_cloned[] = $fv;
    }
}
?>

<?php if(!empty($fields_cloned)) : ?>
    <div class="alert alert-warning audit-alert-obervations-cloned">
        <strong><?= __('Warning!') ?></strong> <?= __('Observations for the following items are the same as the last audit: ') ?>
        <ul>
        <?php foreach($fields_cloned as $f) : ?>
            <li><?= "{$f->form_template_fields_optionset->form_template_section->position}.{$f->form_template_fields_optionset->position}" ?></li>
        <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>

<?php foreach($template->form_template_sections as $s) : ?>

    <?php $collapseId = "section{$s->id}" ?>

    <fieldset>

        <legend>
            <a href="#<?= $collapseId ?>" data-toggle="collapse">
                <?= "{$s->position}. {$s->name} ({$s->score})" ?> 
            </a>
        </legend>

        <div id="<?= $collapseId ?>" class="collapse">

            <?php foreach($s->form_template_fields_optionset as $f) : ?>

                <?php
                    $value = null;
                    foreach($audit->audit_field_optionset_values as $fv) {
                        if($fv->form_template_field_id === $f->id) {
                            $value = $fv;
                            break;
                        }
                    }
                    $hasObservations = !empty($value) && !empty($value->observations);
                    $imgs = empty($field_images[$template->id][$f->id]) ? [] : $field_images[$template->id][$f->id];
                    $hasImgs = !empty($imgs);
                    $hasImgsOrObs = $hasObservations || $hasImgs;
                ?>

                <div class="form-group audit-field">
                    <label for="<?= "field-values-{$f->id}" ?>">
                        <?= $hasImgsOrObs ? $this->EasyAuditHtml->gliphicon('warning-sign', ['classes'=>['text-warning']]) : '' ?>
                        <?= "{$s->position}.{$f->position}. {$f->text}" ?>
                    </label>
                    <?= $this->EasyAuditForm->cleanControl("field_values[{$template->id}][{$f->id}]", [
                        'id' => "field-values-{$f->id}",
                        'options' => $this->EasyAuditForm->objectToKeyValue($optionset_values[$f->optionset_id], 'id', 'label'),
                        'value' => empty($value) ? '' : $value->optionset_value_id
                    ]) ?>
                    <div class="audit-observations">
                        <a class="audit-observations-open" href="#"><?= __('+ add observations & photos') ?></a>
                        <a class="audit-observations-close" href="#" style="display:none;"><?= __('- close observations & photos') ?></a>
                        <div class="audit-observations-input" style="display:none">
                            <textarea name="<?="field_observations[{$template->id}][{$f->id}]"?>" class="form-control"><?= $hasObservations ? $value->observations : '' ?></textarea>
                            <div class="audit-img-current">
                                <?php if($hasImgs) : ?>
                                    <p><strong><?= __('Current images') ?></strong></p>
                                    <?php foreach($imgs as $img) : ?>
                                        <?= $this->Html->image("/$img", ['data-template-id'=>$template->id, 'data-field-id'=>$f->id]) ?>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </div>
                            <div class="audit-img-preview">
                                <p style="display:none"><strong><?= __('New images') ?></strong></p>
                            </div>
                            <div class="audit-img-inputs">
                                <div class="audit-img-input">
                                    <?php $fieldId = "field_photo_{$f->id}"; ?>
                                    <input type="file" name="<?="field_photo[{$template->id}][{$f->id}][]"?>" id="<?=$fieldId?>" accept="image/*" capture="capture" />
                                    <label for="<?=$fieldId?>"><?= $this->EasyAuditHtml->gliphiconText('camera', __('Add photo')) ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach ?>

        </div>

    </fieldset>

<?php endforeach ?>