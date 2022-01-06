<?php
$this->extend('template');
$this->set('headerTitle', __('Form templates'));
$this->set('headerBreadcrumbs', [
    ['label'=>__('Config')],
    ['label'=>__('Forms')]
]);
$this->Html->script('form', ['block' => 'script']);
?>

<div class="row">
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="cell-small"></th>
                    <th class="cell-small"></th>
                    <th class="celda-titulo"><?= __('Name') ?></th>
                    <th class="celda-titulo"><?= __('Public name') ?></th>
                    <th class="celda-titulo"><?= __('Type') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($forms as $f) : ?>
                    <tr class="<?= $f->disabled ? 'disabled' : '' ?>">
                        <td><?= $this->EasyAuditHtml->deleteLink(['action'=>'delete', $f->id]) ?></td>
                        <td><?= $this->Html->link('', '', ['class'=>'glyphicon glyphicon-duplicate modal-clone-button', 'data-form-id'=>$f->id, 'data-form-name'=>$f->name, 'data-form-public_name'=>$f->public_name]) ?></td>
                        <td><?= $this->Html->link($f->name, ['action'=>'detail', $f->id]) ?></td>
                        <td><?= $f->public_name ?></td>
                        <td><?= $form_types[$f->type] ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <div class="button-group">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-new-form"><?= __('Create') ?></button>
    </div>
</div>



<?= $this->element('Forms/modals/new_form', ['modal_id' => 'modal-new-form']) ?>

<?= $this->element('Forms/modals/clone', ['modal_id' => 'modal-clone-template']) ?>