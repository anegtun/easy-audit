<?php
$this->extend('template');
$this->set('headerTitle', __('Audits'));
$this->set('headerBreadcrumbs', [
    ['label'=>__('Audits')]
]);
$this->Html->script('audit-list', ['block' => 'script']);
?>

<div class="row">
    <div class="button-group">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-new-audit"><?= __('New audit') ?></button>
    </div>
    <?= $this->element('Audits/list', ['audits' => $audits]) ?>
</div>

<div id="modal-new-audit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <?= $this->Form->create(null, ['type'=>'post', 'url'=>['action'=>'create']]) ?>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= __('New audit') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <?= $this->Form->control('customer_id', ['options' => $this->EasyAuditForm->objectToKeyValue($customers, 'id', 'name'), 'label'=>__('Customer')]) ?>
                        <div class="form-group">
                            <label><?= __('Template') ?></label>
                            <div id="template-check-container"></div>
                        </div>
                        <?= $this->EasyAuditForm->dateControl('date', ['label'=>__('Date')]) ?>
                        <?= $this->EasyAuditForm->checkbox('clone', ['label'=>__('Copy last audit values & observations (no photos)?'), 'checked'=>true]) ?>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <?= $this->Form->button(__('Create'), ['class'=>'btn btn-primary']); ?>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= __('Close') ?></button>
                </div>
            </div>
        </div>
    <?= $this->Form->end() ?>
</div>