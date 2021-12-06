<?php
$this->extend('template');
$this->set('headerTitle', __('Audits'));
$this->set('headerBreadcrumbs', [
    ['label'=>__('Audits')]
]);
$this->Html->script('modal-utils', ['block' => 'script']);
$this->Html->script('audits', ['block' => 'script']);
?>

<div class="row">
    <div class="col-xs-12 table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="celda-titulo"><?= __('Name') ?></th>
                    <th class="celda-titulo"><?= __('Template') ?></th>
                    <th class="celda-titulo"><?= __('Date') ?></th>
                    <th class="celda-titulo"><?= __('Auditor') ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($audits as $a) : ?>
                    <tr>
                        <td><?= $a->customer->name ?></td>
                        <td><?= $a->form_template->name ?></td>
                        <td><?= $a->date ?></td>
                        <td><?= $a->auditor->name ?></td>
                        <td class="text-center"><?= $this->EasyAuditHtml->gliphiconLink('edit', '', ['action'=>'detail', $a->id]) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-new-audit"><?= __('New audit') ?></button>
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
                        <?= $this->Form->control('form_template_id', ['options' => [], 'label'=>__('Template')]) ?>
                        <?= $this->EasyAuditForm->dateControl('date', ['label'=>__('Date')]) ?>
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