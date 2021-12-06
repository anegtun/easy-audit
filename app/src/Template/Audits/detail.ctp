<?php
$this->extend('template');
$this->set('headerTitle', __('Audit'));
$this->set('headerBreadcrumbs', [
    ['label'=>__('Audits'), 'url'=>['action'=>'index']],
    ['label'=>__('Audit')]
]);
?>

<?= $this->Form->create(null, ['url'=>['action'=>'update']]) ?>

    <?= $this->Form->hidden('id', ['value' => $audit->id]) ?>

    <fieldset>
        <div class="row">
            <div class="form-group col-lg-3">
                <label><?= __('Customer') ?></label>
                <div>
                    <?= $audit->customer->name ?>
                    &nbsp;
                    <?= $this->EasyAuditHtml->gliphiconLink('edit', '', ['controller' => 'Customers', 'action' => 'detail', $audit->customer->id]) ?>
                </div>
            </div>
            <div class="form-group col-lg-3">
                <label><?= __('Template') ?></label>
                <div>
                    <?= $audit->form_template->name ?>
                    &nbsp;
                    <?= $this->EasyAuditHtml->gliphiconLink('edit', '', ['controller' => 'FormTemplates', 'action' => 'detail', $audit->form_template->id]) ?>
                </div>
            </div>
            <div class="form-group col-lg-3">
                <?= $this->EasyAuditForm->dateControl('date', ['value'=>$audit->date, 'label'=>__('Date')]) ?>
            </div>
            <div class="form-group col-lg-3">
                <?= $this->Form->control('auditor_user_id', ['options' => $this->EasyAuditForm->objectToKeyValue($users, 'id', 'name'), 'value'=>$audit->auditor->id, 'label'=>__('Auditor')]) ?>
            </div>
        </div>
    </fieldset>

    <?php foreach($template_sections as $s) : ?>
        <?php $collapseId = "section{$s->id}" ?>
        <fieldset>
            <legend>
                <a href="#<?= $collapseId ?>" data-toggle="collapse">
                    <?= $s->name ?> (<?= $s->score ?>)
                </a>
            </legend>
            <div id="<?= $collapseId ?>" class="collapse">
                <?php foreach($template_fields as $f) : ?>
                    <?php if($f->form_template_section_id === $s->id) : ?>
                        <?= $this->Form->control("field_values[{$f->id}]", [
                            'options' => $this->EasyAuditForm->objectToKeyValue($optionset_values[$f->optionset_id], 'id', 'label'),
                            'value' => empty($field_values[$f->id]) ? '' : $field_values[$f->id]->optionset_value_id,
                            'label' => __($f->text)]) ?>
                    <?php endif ?>
                <?php endforeach ?>
            </div>
        </fieldset>
    <?php endforeach ?>

    <?= $this->EasyAuditForm->saveButton(__('Save')) ?>

<?= $this->Form->end() ?>