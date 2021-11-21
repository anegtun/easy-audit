<?php
$this->extend('template');
$this->set('headerTitle', __('Form templates'));
$this->set('headerBreadcrumbs', [
    ['label'=>__('Config')],
    ['label'=>__('Form templates')]
]);
?>

<div class="row">
    <div class="col-xs-12 table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="celda-titulo">Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($templates as $t) : ?>
                    <tr>
                        <td><?= $t->name ?></td>
                        <td class="text-center"><?= $this->EasyAuditHtml->deleteButton(['action'=>'delete', $t->id]) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-new-template"><?= __('Create') ?></button>
    </div>
</div>

<div id="modal-new-template" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <?= $this->Form->create(null, ['type'=>'post', 'url'=>['action'=>'save']]) ?>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= __('New template') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <?= $this->Form->control('name', ['label'=>'Name']) ?>
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