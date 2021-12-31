<?php
$this->extend('template');
$this->set('headerTitle', __('Customers'));
$this->set('headerBreadcrumbs', [
    ['label'=>__('Config')],
    ['label'=>__('Customers')]
]);
?>

<div class="row">
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="cell-small"></th>
                    <th class="celda-titulo"><?= __('Name') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($customers as $c) : ?>
                    <tr>
                        <td><?= $this->EasyAuditHtml->deleteLink(['action'=>'delete', $c->id]) ?></td>
                        <td><?= $this->Html->link($c->name, ['action'=>'detail', $c->id]) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <?= $this->Html->link(__('Create'), ['action'=>'detail'], ['class'=>'btn btn-primary']) ?>
</div>