<?php
$this->extend('template');
$this->set('headerTitle', __('Customers'));
$this->set('headerBreadcrumbs', [
    ['label'=>__('Config')],
    ['label'=>__('Customers')]
]);
$authUser = $this->request->getAttribute('identity');
$isAdmin = $authUser['role'] === 'admin';
?>

<div class="row">
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <?php if($isAdmin) : ?>
                        <th class="cell-small"></th>
                    <?php endif ?>
                    <th class="celda-titulo"><?= __('Name') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($customers as $c) : ?>
                    <tr>
                        <?php if($isAdmin) : ?>
                            <td><?= $this->EasyAuditHtml->deleteLink(['action'=>'delete', $c->id]) ?></td>
                        <?php endif ?>
                        <td><?= $this->Html->link($c->name, ['action'=>'detail', $c->id]) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <?php if($isAdmin) : ?>
        <div class="button-group">
            <?= $this->Html->link(__('Create'), ['action'=>'detail'], ['class'=>'btn btn-primary']) ?>
        </div>
    <?php endif ?>
</div>