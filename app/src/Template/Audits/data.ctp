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

<?= $this->Form->create(null, ['id'=>'auditForm', 'type'=>'file', 'url'=>['action'=>'update_data']]) ?>
    <?= $this->Form->hidden('id', ['value' => $audit->id]) ?>

    <fieldset>
        <legend><?= __('Audit data') ?></legend>
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
    </fieldset>

    <?= $this->EasyAuditForm->saveButton(__('Save')) ?>
    <?= $this->EasyAuditHtml->deleteButton(['action'=>'delete', $audit->id]) ?>

<?= $this->Form->end() ?>