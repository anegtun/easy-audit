<?php
$title = empty($template->name) ? __('Template') : $template->name;
$this->extend('template');
$this->set('headerTitle', $title);
$this->set('headerBreadcrumbs', [
    ['label'=>__('Config')],
    ['label'=>__('Forms'), 'url'=>['controller'=>'Forms', 'action'=>'index']],
    ['label'=>$template->form->name, 'url'=>['controller'=>'Forms', 'action'=>'detail', $template->form->id]],
    ['label'=>$title]
]);
$this->Html->script('form-templates', ['block' => 'script']);
$this->Html->script('form-clone', ['block' => 'script']);

$has_audits = !empty($template->audits);
$is_disabled = !empty($template->disabled);
$is_editable = !$has_audits && !$is_disabled;
?>

<div class="row">

    <div class="button-group">
        <div>
            <?php if($is_editable && $template->form->type !== 'measure') : ?>
                <button type="button" id="modal-field-button" class="btn btn-primary" data-target="#modal-field"><?= __('Add field') ?></button>
            <?php endif ?>
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-rename"><?= __('Rename') ?></button>
            <button type="button" class="btn btn-default modal-clone-template-button" data-template-id="<?=$template->id?>" data-template-name="<?=$template->name?>"><?= __('Clone') ?></button>
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-customers"><?= __('See customers') ?></button>
            <?php if($has_audits) : ?>
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-audits"><?= __('See audits') ?></button>
            <?php endif ?>
        </div>
        <?php if($is_editable) : ?>
            <div>
                <?= $this->EasyAuditHtml->deleteButton(['action'=>'delete', $template->id]) ?>
            </div>
        <?php endif ?>
    </div>

    <?php if($has_audits) : ?>
        <div class="alert alert-info form-template-audit-info">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?= $this->EasyAuditHtml->gliphiconText('info-sign', __('This template is associated with at least one audit, so the editing options are limited.')) ?>
        </div>
    <?php endif ?>

    <?php if($is_disabled) : ?>
        <div class="alert alert-info form-template-audit-info">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?= $this->EasyAuditHtml->gliphiconText('info-sign', __('This template is disabled, so it can\'t be edited anymore. To edit please re-enable.')) ?>
        </div>
    <?php endif ?>

    <?= $this->element("form_templates/detail/editor_{$template->form->type}", ['is_editable'=>$is_editable]) ?>
</div>



<div id='all-field-options'>
    <?php foreach($template->fields as $f) : ?>
        <?= $this->Form->hidden("field-{$f->id}", [
            'data-id' => $f->id,
            'data-position' => $f->position,
            'data-section' => $f->form_section_id,
            'value'=>"{$f->position}. ".strip_tags($f->text)]) ?>
    <?php endforeach ?>
</div>



<?= $this->element('form_templates/modals/rename', ['modal_id' => 'modal-rename']) ?>
<?= $this->element('form_templates/modals/clone', ['modal_id' => 'modal-clone-template']) ?>

<?= $this->element('audits/modals/list', ['modal_id' => 'modal-audits', 'audits' => $template->audits]) ?>
<?= $this->element('customers/modals/list', ['modal_id' => 'modal-customers', 'customers' => $template->customers]) ?>
