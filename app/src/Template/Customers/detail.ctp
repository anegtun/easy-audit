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


<?php if(!empty($customer)) : ?>

    <div class="row table-responsive" style="margin-top: 10px">

        <h3><?= __('Associated templates') ?></h3>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="celda-titulo"><?= __('Name') ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($customer->form_templates as $t) : ?>
                    <tr>
                        <td><?= $t->name ?></td>
                        <td class="text-center"><?= $this->EasyAuditHtml->deleteButton(['action'=>'deleteTemplate', $customer->id, $t->id]) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <button type="button" id="modal-templates-button" class="btn btn-secondary" data-toggle="modal" data-target="#modal-templates"><?= __('Add template') ?></button>
    </div>

    <div id="modal-templates" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <?= $this->Form->create(null, ['url'=>['action'=>'saveTemplate']]) ?>
            <?= $this->Form->hidden('customer_id', ['value' => $customer->id]) ?>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?= __('Add template') ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <fieldset>
                            <?= $this->Form->control('form_template_id', ['options' => $this->EasyAuditForm->objectToKeyValue($templates, 'id', 'name'), 'label'=>__('Template')]) ?>
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

<?php endif ?>