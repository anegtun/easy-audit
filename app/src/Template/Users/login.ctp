<div class="col-xs-12 login-form">
    <h2><?= __('Login') ?></h2>
    <?= $this->Form->create() ?>
        <div class="form-group">
            <?= $this->Form->control('username', array('class'=>'form-control','label'=>false)) ?>
        </div>
        <div class="form-group">
            <?= $this->Form->control('password', array('class'=>'form-control','type'=>'password','label'=>false)) ?>
        </div>
        <?= $this->Form->button('Login', array('type'=>'submit', 'class'=>'btn btn-primary')) ?>
    <?= $this->Form->end() ?>

    <?= $this->Flash->render('auth') ?>
</div>