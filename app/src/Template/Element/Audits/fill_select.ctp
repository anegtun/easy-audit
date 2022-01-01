<?php
$fields_cloned = [];
foreach($audit->audit_field_optionset_values as $fv) {
    if(!empty($fv->observations_cloned)) {
        $fields_cloned[] = $fv;
    }
}
?>

<?php if(!empty($fields_cloned)) : ?>
    <div class="alert alert-warning alert-dismissible fade in audit-alert-obervations-cloned">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong><?= __('Warning!') ?></strong> <?= __('Observations for the following items are the same as the last audit: ') ?>
        <ul>
            <?php foreach($fields_cloned as $f) : ?>
                <li><?= $this->EasyAuditTemplate->fieldLabel($f->form_template_fields_optionset->form_template_section, $f->form_template_fields_optionset, false) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>



<div class="audit-open-all">
    <?= $this->Html->link(__('Open all'), '#', ['class' => 'open-button']) ?>
    <?= $this->Html->link(__('Close all'), '#', ['class' => 'close-button']) ?>
</div>

<?php foreach($template->form_template_sections as $s) : ?>

    <?php $collapseId = "section{$s->id}" ?>

    <fieldset>

        <legend>
            <a href="#<?= $collapseId ?>" data-toggle="collapse">
                <?= $this->EasyAuditTemplate->section($s) . " ({$audit->score_section[$s->id]})" ?> 
            </a>
        </legend>

        <div id="<?= $collapseId ?>" class="collapse">

            <?php foreach($s->form_template_fields_optionset as $f) : ?>

                <?php
                    $value = $audit->getFieldOptionsetValue($f);
                    $hasObservations = !empty($value) && !empty($value->observations);
                    $imgs = empty($field_images[$template->id][$f->id]) ? [] : $field_images[$template->id][$f->id];
                    $hasImgs = !empty($imgs);
                    $hasImgsOrObs = $hasObservations || $hasImgs;
                ?>

                <div class="form-group audit-field">

                    <label for="<?= "field-values-{$f->id}" ?>">
                        <?= $hasImgsOrObs ? $this->EasyAuditHtml->gliphicon('warning-sign', ['classes'=>['text-warning']]) : '' ?>
                        <span class="audit-field-position"><?= $this->EasyAuditTemplate->fieldLabel($s, $f, false) ?>.</span>
                        <?= $f->text ?>
                    </label>

                    <?= $this->EasyAuditForm->cleanControl("field_values[{$template->id}][{$f->id}]", [
                        'id' => "field-values-{$f->id}",
                        'classes' => "audit-field-select",
                        'data-optionset-id' => $f->optionset_id,
                        'options' => $this->EasyAuditForm->objectToKeyValue($optionset_values[$f->optionset_id], 'id', 'label'),
                        'value' => empty($value) ? '' : $value->optionset_value_id
                    ]) ?>

                    <div class="audit-observations" data-has-observations="<?= $hasImgsOrObs ? true : false ?>">
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