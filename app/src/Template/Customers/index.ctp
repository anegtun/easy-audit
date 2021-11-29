<?php
$this->extend('template');
$this->set('headerTitle', __('Customers'));
$this->set('headerBreadcrumbs', [
    ['label'=>__('Config')],
    ['label'=>__('Customers')]
]);
?>

<div class="row">
    <div class="col-xs-12 table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="celda-titulo"><?= __('Name') ?></th>
                    <th class="celda-titulo"><?= __('Email') ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($customers as $c) : ?>
                    <tr>
                        <td><?= $this->Html->link($c->name, ['action'=>'detail', $c->id]) ?></td>
                        <td><?= $c->email ?></td>
                        <td class="text-center"><?= $this->EasyAuditHtml->deleteButton(['action'=>'delete', $c->id]) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <?= $this->Html->link(__('Create'), ['action'=>'detail'], ['class'=>'btn btn-primary']) ?>
    </div>
</div>