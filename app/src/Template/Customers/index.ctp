<?php
$this->extend('template');
$this->set('headerTitle', __('Customers'));
$this->set('headerBreadcrumbs', [
    ['label'=>__('Config')],
    ['label'=>__('Customers')]
]);
$this->Html->script('customers', ['block' => 'script']);
?>

<div class="row">
    <div class="col-xs-12 table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="celda-titulo"><?= __('Name') ?></th>
                    <th class="celda-titulo"><?= __('Email') ?></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($customers as $c) : ?>
                    <tr>
                        <td><?= $c->name ?></td>
                        <td><?= $c->email ?></td>
                        <td class="text-center">
                            <?php $attrs = ['data-customer-id' => $c->id, 'data-customer-name' => $c->name, 'data-customer-email' => $c->email] ?>
                            <?= $this->EasyAuditHtml->gliphiconLink('edit', '', '#', $attrs) ?>
                        </td>
                        <td class="text-center"><?= $this->EasyAuditHtml->deleteButton(['action'=>'delete', $c->id]) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <button type="button" id="modal-customer-button" class="btn btn-secondary" data-target="#modal-customer"><?= __('Create') ?></button>
    </div>
</div>

<div id="modal-customer" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <?= $this->Form->create(null, ['type'=>'post', 'url'=>['action'=>'save']]) ?>
        <?= $this->Form->hidden('id') ?>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= __('Customer') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <?= $this->Form->control('name', ['label'=>__('Name')]) ?>
                        <?= $this->Form->control('email', ['label'=>__('Email')]) ?>
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