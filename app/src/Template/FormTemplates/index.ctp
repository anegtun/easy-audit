<?php
$this->extend('template');
$this->set('headerTitle', __('Form templates'));
$this->set('headerBreadcrumbs', [
    ['label'=>__('Config')],
    ['label'=>__('Form templates')]
]);
$this->Html->script('modal-utils', ['block' => 'script']);
$this->Html->script('form-templates', ['block' => 'script']);
?>

<div class="row">
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="cell-small"></th>
                    <th class="cell-small"></th>
                    <th class="cell-small"></th>
                    <th class="celda-titulo"><?= __('Name') ?></th>
                    <th class="celda-titulo"><?= __('Type') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($templates as $t) : ?>
                    <tr class="<?= $t->disabled ? 'disabled' : '' ?>">
                        <td><?= $this->EasyAuditHtml->deleteButton(['action'=>'delete', $t->id]) ?></td>
                        <td><?= $this->Html->link('', '', ['class'=>'glyphicon glyphicon-duplicate modal-clone-button', 'data-template-id'=>$t->id, 'data-template-name'=>$t->name]) ?></td>
                        <td><?= $this->Html->link('', ['action'=>'toggleEnabled', $t->id], ['class'=>'glyphicon glyphicon-'.($t->disabled?'thumbs-up':'thumbs-down')]) ?></td>
                        <td><?= $this->Html->link($t->name, ['action'=>'detail', $t->id]) ?></td>
                        <td><?= $template_types[$t->type] ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-new-template"><?= __('Create') ?></button>
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
                        <?= $this->Form->control('name', ['label'=>__('Name')]) ?>
                        <?= $this->Form->control('type', ['options'=>$template_types, 'label'=>__('Type')]) ?>
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

<?= $this->element('FormTemplate/modal_clone') ?>