<?php
$this->extend('template');
$this->set('headerTitle', __('Users'));
$this->set('headerBreadcrumbs', [
    ['label'=>__('Config')],
    ['label'=>__('Users')]
]);
?>

<div class="row">
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="cell-small"></th>
                    <th class="celda-titulo"><?= __('Username') ?></th>
                    <th class="celda-titulo"><?= __('Name') ?></th>
                    <th class="celda-titulo"><?= __('Company position') ?></th>
                    <th class="celda-titulo"><?= __('Role') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $u) : ?>
                    <tr>
                        <td><?= $this->EasyAuditHtml->deleteLink(['action'=>'delete', $u->id]) ?></td>
                        <td><?= $this->Html->link($u->username, ['action'=>'detail', $u->id]) ?></td>
                        <td><?= $u->name ?></td>
                        <td><?= $u->position ?></td>
                        <td><?= $roles[$u->role] ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <?= $this->Html->link(__('Create'), ['action'=>'detail'], ['class'=>'btn btn-primary']) ?>
</div>