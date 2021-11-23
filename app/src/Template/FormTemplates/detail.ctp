<?php
$title = empty($template->name) ? __('Template') : $template->name;
$this->extend('template');
$this->set('headerTitle', $title);
$this->set('headerBreadcrumbs', [
    ['label'=>__('Config')],
    ['label'=>__('Form templates'), 'url'=>['action'=>'index']],
    ['label'=>$title]
]);
$this->Html->script('modal-utils', ['block' => 'script']);
$this->Html->script('form-templates', ['block' => 'script']);
$optionsetOptions = iterator_to_array($optionsets);
?>

<div class="row">
    <?php foreach($sections as $s) : ?>
        <fieldset>
            <legend>
                <?= $this->EasyAuditForm->editModalLink($s, 'data-section', ['id', 'position', 'name']) ?>
                <?= $this->EasyAuditHtml->gliphiconLink('arrow-up', '', ['action'=>'moveSectionUp', $s->id]) ?>
                <?= $this->EasyAuditHtml->gliphiconLink('arrow-down', '', ['action'=>'moveSectionDown', $s->id]) ?>
                <?php if(empty($s->form_template_fields)) : ?>
                    <?= $this->EasyAuditHtml->gliphiconLink('remove', '', ['action'=>'deleteSection', $s->id]) ?>
                <?php endif ?>
                <?= $s->name ?>
            </legend>
            <?php foreach($s->form_template_fields as $f) : ?>
                <div class="col-sm-1">
                    <?= $this->EasyAuditForm->editModalLink($f, 'data-field', ['id', 'form_template_section_id', 'position', 'text']) ?>
                    <?= $this->EasyAuditHtml->gliphiconLink('arrow-up', '', ['action'=>'moveFieldUp', $f->id]) ?>
                    <?= $this->EasyAuditHtml->gliphiconLink('arrow-down', '', ['action'=>'moveFieldDown', $f->id]) ?>
                    <?= $this->EasyAuditHtml->gliphiconLink('remove', '', ['action'=>'deleteField', $f->id]) ?>
                </div>
                <div class="col-sm-11">
                    <div class="form-group">
                        <?= $this->Form->control('dummy', ['label'=>$f->text, 'value'=>$optionsetOptions[$f->optionset_id], 'disabled'=>true]) ?>
                    </div>
                </div>
            <?php endforeach ?>
        </fieldset>
    <?php endforeach ?>
    <button type="button" id="modal-section-button" class="btn btn-secondary" data-target="#modal-section"><?= __('Add section') ?></button>
    <button type="button" id="modal-field-button" class="btn btn-secondary" data-target="#modal-field"><?= __('Add field') ?></button>
</div>



<div id="modal-section" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <?= $this->Form->create(null, ['url'=>['action'=>'saveSection']]) ?>
        <?= $this->Form->hidden('id') ?>
        <?= $this->Form->hidden('form_template_id', ['value' => $template->id]) ?>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= __('Section') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <?= $this->Form->control('position', ['options' => $this->EasyAuditForm->objectToKeyValue($sections, 'position', 'name'), 'label'=>__('Before')]) ?>
                        <?= $this->Form->control('name', ['label'=>__('Name')]) ?>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <?= $this->Form->button(__('Save'), ['class'=>'btn btn-primary']); ?>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= __('Close') ?></button>
                </div>
            </div>
        </div>
    <?= $this->Form->end() ?>
</div>



<div id="modal-field" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <?= $this->Form->create(null, ['id'=>'field-form', 'url'=>['action'=>'saveField']]) ?>
        <?= $this->Form->hidden('id') ?>
        <?= $this->Form->hidden('form_template_id', ['value' => $template->id]) ?>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= __('Field') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <?= $this->Form->control('form_template_section_id', ['options' => $this->EasyAuditForm->objectToKeyValue($sections, 'id', 'name'), 'label'=>__('Section')]) ?>
                        <?= $this->Form->control('position', ['options' => [], 'label'=>__('Before')]) ?>
                        <?= $this->Form->control('text', ['label'=>__('Text')]) ?>
                        <?= $this->Form->control('type', ['options'=>$fieldTypes, 'label'=>__('Type')]) ?>
                        <?= $this->Form->control('optionset_id', ['options'=>$optionsets, 'label'=>__('Optionset')]) ?>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <?= $this->Form->button(__('Save'), ['class'=>'btn btn-primary']); ?>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= __('Close') ?></button>
                </div>
            </div>
        </div>
    <?= $this->Form->end() ?>
</div>


<div id='all-field-options'>
    <?php foreach($allFields as $f) : ?>
        <?= $this->Form->hidden("field-{$f->id}", ['data-id' => $f->id, 'data-position' => $f->position, 'data-section' => $f->form_template_section_id, 'value'=>$f->text]) ?>
    <?php endforeach ?>
</div>