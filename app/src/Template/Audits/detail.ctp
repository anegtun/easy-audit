<?php
$title = __('Audit') . " ". $audit->customer->name;
$this->extend('template');
$this->set('headerTitle', $title);
$this->set('headerBreadcrumbs', [
    ['label'=>__('Audits'), 'url'=>['action'=>'index']],
    ['label'=>$title]
]);
$this->Html->script('modal-utils', ['block' => 'script']);
$this->Html->script('audits', ['block' => 'script']);
?>

<?= $this->Form->create(null, ['type'=>'file', 'url'=>['action'=>'update']]) ?>

    <?= $this->Form->hidden('id', ['value' => $audit->id]) ?>

    <ul class="nav nav-tabs">
        <?php foreach($audit->form_templates as $i => $t) : ?>
            <li class="<?= $i==0 ? "active" : "" ?>">
                <a data-toggle="tab" href="#form<?= $t->id ?>"><?= $t->name ?></a>
            </li>
        <?php endforeach ?>
    </ul>

    <div class="tab-content">

        <?php foreach($audit->form_templates as $i => $t) : ?>

            <div id="form<?= $t->id ?>" class="tab-pane fade <?= $i==0 ? "in active" : "" ?>">
                <?php foreach($t->form_template_sections as $s) : ?>
                    <?php $collapseId = "section{$s->id}" ?>
                    <fieldset>
                        <legend>
                            <a href="#<?= $collapseId ?>" data-toggle="collapse">
                                <?= $s->name ?> (<?= $s->score ?>)
                            </a>
                        </legend>
                        <div id="<?= $collapseId ?>" class="collapse">
                            <?php foreach($t->form_template_fields as $f) : ?>
                                <?php if($f->form_template_section_id === $s->id) : ?>
                                    <?php 
                                        $value = empty($field_values[$f->id]) ? null : $field_values[$f->id];
                                        $hasObservations = !empty($value) && !empty($value->observations);
                                    ?>
                                    <div class="form-group audit-field">
                                        <label for="<?= "field-values-{$f->id}" ?>">
                                            <?= $f->text ?>
                                        </label>
                                        <?= $this->EasyAuditForm->cleanControl("field_values[{$f->id}]", [
                                            'id' => "field-values-{$f->id}",
                                            'options' => $this->EasyAuditForm->objectToKeyValue($optionset_values[$f->optionset_id], 'id', 'label'),
                                            'value' => empty($value) ? '' : $value->optionset_value_id
                                        ]) ?>
                                        <div class="audit-observations">
                                            <?php if(!$hasObservations) : ?>
                                                <a href="#"><?= __('+ add observations & photos') ?></a>
                                            <?php endif ?>
                                            <div class="audit-observations-input" <?= $hasObservations ? '' : 'style="display:none"' ?>>
                                                <textarea name="<?="field_observations[{$f->id}]"?>" class="form-control"><?= $hasObservations ? $value->observations : '' ?></textarea>
                                                <div class="audit-img-current">
                                                    <?php $imgs = empty($field_images[$f->id]) ? [] : $field_images[$f->id] ?>
                                                    <?php foreach($imgs as $img) : ?>
                                                        <?= $this->Html->image("/$img") ?>
                                                    <?php endforeach ?>
                                                </div>
                                                <div class="audit-img-preview">
                                                    <p style="display:none"><strong><?= __('New images') ?></strong></p>
                                                </div>
                                                <div class="audit-img-input">
                                                    <div>
                                                        <?php $fieldId = "field_img_{$f->id}"; ?>
                                                        <input type="file" name="<?="field_img[{$f->id}][]"?>" id="<?=$fieldId?>" accept="image/*" multiple />
                                                        <label for="<?=$fieldId?>"><?= $this->EasyAuditHtml->gliphiconText('picture', __('Choose images')) ?></label>
                                                    </div>
                                                    <div>
                                                        <?php $fieldId = "field_photo_{$f->id}"; ?>
                                                        <input type="file" name="<?="field_photo[{$f->id}][]"?>" id="<?=$fieldId?>" accept="image/*" capture="capture" multiple />
                                                        <label for="<?=$fieldId?>"><?= $this->EasyAuditHtml->gliphiconText('camera', __('Take photo')) ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php endforeach ?>
                        </div>
                    </fieldset>
                <?php endforeach ?>
            </div>

        <?php endforeach ?>

    </div>

    <div class="row">
        <fieldset>
            <div class="form-group col-lg-1">
                <?= $this->EasyAuditForm->dateControl('date', ['value'=>$audit->date, 'label'=>__('Date')]) ?>
            </div>
            <div class="form-group col-lg-3">
                <?= $this->Form->control('auditor_user_id', ['options' => $this->EasyAuditForm->objectToKeyValue($users, 'id', 'name'), 'value'=>$audit->auditor->id, 'label'=>__('Auditor')]) ?>
            </div>
        </fieldset>
    </div>

    <?= $this->EasyAuditForm->saveButton(__('Save')) ?>

<?= $this->Form->end() ?>