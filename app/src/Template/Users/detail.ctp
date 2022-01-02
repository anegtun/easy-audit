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
            <div class="form-row">
                <?= $this->Form->control('username', ['label'=>__('Username')]) ?>
                <?php if(empty($user->id) || $authUser['id'] === $user->id || $authUser['role'] === 'admin') : ?>
                    <?= $this->Form->control('password', ['value'=>'', 'label'=>__('Password'), 'placeholder'=>__('Fill to set new password')]) ?>
                <?php endif ?>
            </div>
            <div class="form-row">
                <?= $this->Form->control('name', ['label'=>__('Name')]) ?>
                <?= $this->Form->control('email', ['label'=>__('Email')]) ?>
                <?= $this->Form->control('position', ['label'=>__('Company position')]) ?>
                <?= $this->Form->control('role', ['options'=>$roles, 'label'=>__('Role')]) ?>
            </div>
            <div class="button-group">
                <div><?= $this->EasyAuditForm->saveButton(__('Save')) ?></div>
                <?php if(!empty($user->id)) : ?>
                    <div><?= $this->EasyAuditHtml->deleteButton(['action'=>'delete', $user->id]) ?></div>
                <?php endif ?>
            </div>
        </fieldset>
    <?= $this->Form->end() ?>
</div>




<?php if(!empty($user->id)) : ?>

    <div class="row">
        <fieldset>
            <legend><?= __('Audits') ?></legend>
            <?= $this->element('Audits/list', ['audits' => $user->audits, 'hide_user' => true]) ?>
        </fieldset>
    </div>

<?php endif ?>