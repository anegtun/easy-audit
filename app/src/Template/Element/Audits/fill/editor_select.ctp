<?php
$fields_cloned = [];
foreach($audit->field_values as $fv) {
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
                <li><?= $this->EasyAuditTemplate->fieldLabel($f->field->section, $f->field, false) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>



<div class="audit-open-all">
    <?= $this->Html->link(__('Open all'), '#', ['class' => 'open-button']) ?>
    <?= $this->Html->link(__('Close all'), '#', ['class' => 'close-button']) ?>
</div>

<?php foreach($template->form->sections as $s) : ?>

    <?php $collapseId = "section{$s->id}" ?>

    <fieldset>

        <legend>
            <a href="#<?= $collapseId ?>" data-toggle="collapse-unique">
                <?= $this->EasyAuditTemplate->section($s) . " ({$audit->score_section[$s->id]})" ?> 
            </a>
        </legend>

        <div id="<?= $collapseId ?>" class="collapse">

            <?php foreach($template->fields as $f) : ?>

                <?php if($f->form_section_id === $s->id) : ?>

                    <?php
                        $value = $audit->getFieldValue($f);
                        $hasObservations = !empty($value) && !empty($value->observations);
                        $imgs = empty($field_images[$template->id][$f->id]) ? [] : $field_images[$template->id][$f->id];
                        $hasImgs = !empty($imgs);
                        $hasImgsOrObs = $hasObservations || $hasImgs;
                    ?>

                    <div class="form-group audit-field">

                        <label>
                            <span class="audit-field-position"><?= $this->EasyAuditTemplate->fieldLabel($s, $f, false) ?>.</span>
                            <?= $f->text ?>
                        </label>

                        <?= $this->element("Audits/field/type_{$f->type}", ['template'=>$template, 'field'=>$f, 'value'=>$value]) ?>

                        <div class="audit-observations" data-has-observations="<?= $hasImgsOrObs ? true : false ?>">
                            <a class="audit-observations-open" href="#"><?= __('+ add observations') ?></a>
                            <a class="audit-observations-close" href="#" style="display:none;"><?= __('- close observations') ?></a>
                            <?= $hasObservations ? $this->EasyAuditHtml->gliphicon('comment', ['classes'=>['text-warning']]) : '' ?>
                            <?= $hasImgs ? $this->EasyAuditHtml->gliphicon('camera', ['classes'=>['text-warning']]) : '' ?>
                            
                            <div class="audit-observations-input" style="display:none">
                                
                                <textarea name="<?="field_observations[{$template->id}][{$f->id}]"?>" class="form-control"><?= $hasObservations ? $value->observations : '' ?></textarea>
                                
                                <div class="audit-img-display" data-template-id="<?= $template->id ?>" data-field-id="<?= $f->id ?>" <?= $hasImgs ? '': 'style="display:none"' ?>>
                                    <p><strong><?= __('Photos') ?></strong></p>
                                    <?php foreach($imgs as $img) : ?>
                                        <?= $this->Html->image("/$img") ?>
                                    <?php endforeach ?>
                                </div>
                                <div class="audit-img-input">
                                    <?php $fieldId = "field_photo_{$f->id}"; ?>
                                    <input
                                        id="<?=$fieldId?>"
                                        type="file"
                                        name="field_photo"
                                        data-post-url="<?= $this->Url->build(['action'=>'addPhoto', $audit->id, $template->id, $f->id]) ?>"
                                        data-target-name="<?="field_photo[{$template->id}][{$f->id}][]"?>"
                                        accept="image/*"
                                        capture="capture"
                                    />
                                    <label for="<?=$fieldId?>">
                                        <?= $this->EasyAuditHtml->gliphiconText('camera', __('Take photo')) ?>
                                    </label>
                                </div>
                                <div class="audit-img-aux-container" style="display:none">
                                    <span class="audit-img-loader">
                                        <?= $this->Html->image('/images/components/loading-dots.gif') ?>
                                    </span>
                                    <span class="audit-img-photo-error">
                                        <?= $this->Html->image('/images/components/photo-error.png', [
                                            'data-post-url' => $this->Url->build(['action'=>'addPhoto', $audit->id, $template->id, $f->id])
                                        ]) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif ?>

            <?php endforeach ?>

        </div>

    </fieldset>

<?php endforeach ?>