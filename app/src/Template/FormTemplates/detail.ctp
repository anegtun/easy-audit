<?php
$title = empty($template->name) ? __('Template') : $template->name;
$this->extend('template');
$this->set('headerTitle', $title);
$this->set('headerBreadcrumbs', [
    ['label'=>__('Config')],
    ['label'=>__('Form templates'), 'url'=>['action'=>'index']],
    ['label'=>$title]
]);
$this->Html->script('form-templates', ['block' => 'script']);

$has_audits = !empty($template->audits);
$is_disabled = !empty($template->disabled);
$is_editable = !$has_audits && !$is_disabled;
?>

<div class="row">

    <div class="button-group">
        <div>
            <?php if($is_editable && $template->type === 'select') : ?>
                <button type="button" id="modal-section-button" class="btn btn-primary" data-target="#modal-section"><?= __('Add section') ?></button>
                <button type="button" id="modal-field-button" class="btn btn-primary" data-target="#modal-field"><?= __('Add field') ?></button>
            <?php endif ?>
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-rename"><?= __('Rename') ?></button>
            <button type="button" class="btn btn-default modal-clone-button" data-template-id="<?=$template->id?>" data-template-name="<?=$template->name?>"><?= __('Clone') ?></button>
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

    <?= $this->element("FormTemplates/detail_{$template->type}", ['is_editable'=>$is_editable]) ?>
</div>



<div id="modal-customers" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= __('Customers') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="celda-titulo"><?= __('Name') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($template->customers as $c) : ?>
                            <tr>
                                <td><?= $this->Html->link($c->name, ['controller'=>'customers', 'action'=>'detail', $c->id]) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= __('Close') ?></button>
            </div>
        </div>
    </div>
</div>



<div id="modal-audits" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= __('Audits') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= $this->element('Audits/list', ['audits' => $template->audits]) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= __('Close') ?></button>
            </div>
        </div>
    </div>
</div>



<div id="modal-rename" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <?= $this->Form->create(null, ['id'=>'field-form', 'url'=>['action'=>'rename']]) ?>
        <?= $this->Form->hidden('id', ['value' => $template->id]) ?>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= __('Rename') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <?= $this->Form->control('name', ['label'=>__('New name'), 'value'=>$template->name]) ?>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <?= $this->EasyAuditForm->saveButton(__('Save')) ?>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= __('Close') ?></button>
                </div>
            </div>
        </div>
    <?= $this->Form->end() ?>
</div>


<?= $this->element('FormTemplates/modal_clone') ?>