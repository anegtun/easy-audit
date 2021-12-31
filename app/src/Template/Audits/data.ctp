<?php
$title = __('Audit') . " ". $audit->customer->name;
$this->extend('template');
$this->set('headerTitle', $title);
$this->set('headerBreadcrumbs', [
    ['label'=>__('Audits'), 'url'=>['action'=>'index']],
    ['label'=>$title],
    ['label'=>__('Audit data')]
]);
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
    <?= $this->EasyAuditHtml->linkButton(['action' => 'fill', $audit->id], 'edit', _('Fill')) ?>
    <?= $this->EasyAuditHtml->linkButton(['action' => 'history', $audit->id], 'stats', _('History')) ?>
    <?= $this->EasyAuditHtml->linkButton(['action' => 'print', $audit->id], 'list-alt', _('View report'), ['target'=>'_blank']) ?>
    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-send-report"><?= $this->EasyAuditHtml->gliphiconText('envelope', __('Send report')) ?></button>

<?= $this->Form->end() ?>



<div class="row">
    <fieldset>
        <legend><?= __('Associated templates') ?></legend>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="cell-small"></th>
                        <th class="celda-titulo"><?= __('Name') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($audit->form_templates as $t) : ?>
                        <tr class="<?= $t->disabled ? 'disabled' : '' ?>">
                            <td><?= $this->EasyAuditHtml->deleteLink(['action'=>'deleteTemplate', $audit->id, $t->id], 'remove') ?></td>
                            <td><?= $this->Html->link($t->name, ['controller' => 'FormTemplates', 'action' => 'detail', $t->id]) ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <button type="button" id="modal-templates-button" class="btn btn-secondary" data-toggle="modal" data-target="#modal-templates"><?= __('Add template') ?></button>
    </fieldset>
</div>


<div id="modal-templates" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <?= $this->Form->create(null, ['url'=>['action'=>'addTemplate']]) ?>
        <?= $this->Form->hidden('audit_id', ['value' => $audit->id]) ?>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= __('Add template') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <?= $this->Form->control('form_template_id', ['options' => $this->EasyAuditForm->objectToKeyValue($audit->customer->form_templates, 'id', 'name'), 'label'=>__('Template')]) ?>
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

<?= $this->element('Audits/modal_send', ['audit' => $audit]) ?>