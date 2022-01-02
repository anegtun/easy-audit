<?php
$title = empty($customer) ? __('Customer') : $customer->name;
$this->extend('template');
$this->set('headerTitle', $title);
$this->set('headerBreadcrumbs', [
    ['label'=>__('Config')],
    ['label'=>__('Customers'), 'url'=>['action'=>'index']],
    ['label'=>$title]
]);
$authUser = $this->request->getSession()->read('Auth.User');
$isAdmin = $authUser['role'] === 'admin';
?>

<div class="row">
    <?= $this->Form->create($customer, ['type'=>'post', 'url'=>['action'=>'save']]) ?>
        <?= $this->Form->hidden('id') ?>
        <fieldset>
            <legend><?= __('Customer details') ?></legend>
            <div class="form-row">
                <?= $this->Form->control('name', ['label'=>__('Name'), 'disabled' => !$isAdmin]) ?>
                </div>
            <div class="form-row">
                <div class="form-group">
                    <label><?= __('Emails') ?></label>
                    <?= $this->Form->textarea('emails', ['disabled' => !$isAdmin]) ?>
                </div>
            </div>

            <?php if($authUser['role'] === 'admin') : ?>
                <div class="button-group">
                    <div><?= $this->EasyAuditForm->saveButton(__('Save')) ?></div>
                    <?php if(!empty($customer->id)) : ?>
                        <div><?= $this->EasyAuditHtml->deleteButton(['action'=>'delete', $customer->id]) ?></div>
                    <?php endif ?>
                </div>
            <?php endif ?>
        </fieldset>
    <?= $this->Form->end() ?>
</div>


<?php if(!empty($customer->id)) : ?>

    <div class="row">
        <fieldset>
            <legend><?= __('Associated templates') ?></legend>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="cell-small"></th>
                            <th class="celda-titulo"><?= __('Name') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($customer->form_templates as $t) : ?>
                            <tr class="<?= $t->disabled ? 'disabled' : '' ?>">
                                <td><?= $this->EasyAuditHtml->deleteLink(['action'=>'deleteTemplate', $customer->id, $t->id], 'remove') ?></td>
                                <td><?= $this->Html->link($t->name, ['controller' => 'FormTemplates', 'action' => 'detail', $t->id]) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <button type="button" id="modal-templates-button" class="btn btn-secondary" data-toggle="modal" data-target="#modal-templates"><?= __('Add template') ?></button>
        </fieldset>
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
                        <?= $this->EasyAuditForm->saveButton(__('Save')) ?>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= __('Close') ?></button>
                    </div>
                </div>
            </div>
        <?= $this->Form->end() ?>
    </div>



    <div class="row">
        <fieldset>
            <legend><?= __('Audits') ?></legend>
            <?= $this->element('Audits/list', ['audits' => $customer->audits]) ?>
        </fieldset>
    </div>

<?php endif ?>