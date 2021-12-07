<?php
$title = empty($user->name) ? __('New user') : $user->name;
$this->extend('template');
$this->set('headerTitle', $title);
$this->set('headerBreadcrumbs', [
    ['label'=>__('Config')],
    ['label'=>__('Users'), 'url'=>['action'=>'index']],
    ['label'=>$title]
]);
$authUser = $this->request->getSession()->read('Auth.User');
?>

<div class="row">
    <?= $this->Form->create($user, ['type'=>'post', 'url'=>['action'=>'save']]) ?>
        <?= $this->Form->hidden('id') ?>
        <fieldset>
            <legend><?= __('User details') ?></legend>
            <?= $this->Form->control('username', ['label'=>__('Username')]) ?>
            <?php if(empty($user->id) || $authUser['id'] === $user->id || $authUser['role'] === 'admin') : ?>
                <?= $this->Form->control('password', ['value'=>'', 'label'=>__('Password'), 'placeholder'=>__('Fill to set new password')]) ?>
            <?php endif ?>
            <?= $this->Form->control('name', ['label'=>__('Name')]) ?>
            <?= $this->Form->control('role', ['options'=>$roles, 'label'=>__('Role')]) ?>
            <?= $this->EasyAuditForm->saveButton(__('Save')) ?>
        </fieldset>
    <?= $this->Form->end() ?>
</div>




<?php if(!empty($user->id)) : ?>

    <div class="row" style="margin-top: 10px">
        <h3><?= __('Audits') ?></h3>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="celda-titulo"><?= __('Name') ?></th>
                        <th class="celda-titulo"><?= __('Template') ?></th>
                        <th class="celda-titulo"><?= __('Date') ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($user->audits as $a) : ?>
                        <tr>
                            <td><?= $a->customer->name ?></td>
                            <td><?= $a->form_template->name ?></td>
                            <td><?= $a->date ?></td>
                            <td class="text-center"><?= $this->EasyAuditHtml->gliphiconLink('edit', '', ['controller'=>'Audits', 'action'=>'detail', $a->id]) ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

<?php endif ?>