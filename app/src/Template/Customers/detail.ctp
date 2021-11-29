<?php
$title = empty($customer) ? __('Customer') : $customer->name;
$this->extend('template');
$this->set('headerTitle', $title);
$this->set('headerBreadcrumbs', [
    ['label'=>__('Config')],
    ['label'=>__('Customers'), 'url'=>['action'=>'index']],
    ['label'=>$title]
]);
?>

<div class="row">
    <?= $this->Form->create($customer, ['type'=>'post', 'url'=>['action'=>'save']]) ?>
        <?= $this->Form->hidden('id') ?>
        <fieldset>
            <legend><?= __('Customer details') ?></legend>
            <?= $this->Form->control('name', ['label'=>__('Name')]) ?>
            <?= $this->Form->control('email', ['label'=>__('Email')]) ?>
            <?= $this->Form->button('Gardar', ['class'=>'btn btn-primary']); ?>
        </fieldset>
    <?= $this->Form->end() ?>
</div>