<?php
$this->extend('template');
$this->set('headerTitle', __('Form templates'));
$this->set('headerBreadcrumbs', [
    ['label'=>__('Config')],
    ['label'=>__('Forms')]
]);
$this->Html->script('form', ['block' => 'script']);
$this->Html->script('form-clone', ['block' => 'script']);
?>

<div class="row">
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="cell-small"></th>
                    <th class="celda-titulo"><?= __('Name') ?></th>
                    <th class="celda-titulo"><?= __('Public name') ?></th>
                    <th class="celda-titulo"><?= __('Type') ?></th>
                    <th class="celda-titulo"><?= __('Sections') ?></th>
                    <th class="celda-titulo"><?= __('Templates') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($forms as $f) : ?>
                    <tr>
                        <td><?= $this->EasyAuditHtml->deleteLink(['action'=>'delete', $f->id]) ?></td>
                        <td><?= $this->Html->link($f->name, ['action'=>'detail', $f->id]) ?></td>
                        <td><?= $f->public_name ?></td>
                        <td><?= $form_types[$f->type] ?></td>
                        <td><?= count($f->sections) ?></td>
                        <td><?= count($f->templates) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <div class="button-group">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-new-form"><?= __('Create') ?></button>
    </div>
</div>



<?= $this->element('forms/modals/new_form', ['modal_id' => 'modal-new-form']) ?>