<?php
$title = __('Audit') . " ". $audit->customer->name;
$this->extend('template');
$this->set('headerTitle', $title);
$this->set('headerBreadcrumbs', [
    ['label'=>__('Audits'), 'url'=>['action'=>'index']],
    ['label'=>$title]
]);
$this->Html->script('modal-utils', ['block' => 'script']);
$this->Html->script('audits', ['block' => 'script']);
?>

<?= $this->Form->create(null, ['id'=>'auditForm', 'type'=>'file', 'url'=>['action'=>'update']]) ?>

    <?= $this->Form->hidden('id', ['value' => $audit->id]) ?>

    <div id="dirtyFormMsg" style="display:none"><?=__('There are unsaved changed.')?></div>

    <ul class="nav nav-tabs">
        <?php foreach($audit->form_templates as $i => $t) : ?>
            <li class="<?= $i==0 ? "active" : "" ?>">
                <a data-toggle="tab" href="#form<?= $t->id ?>"><?= $t->name ?></a>
            </li>
        <?php endforeach ?>
    </ul>

    <div class="tab-content">

        <?php foreach($audit->form_templates as $i => $t) : ?>

            <div id="form<?= $t->id ?>" class="tab-pane fade <?= $i==0 ? "in active" : "" ?>">
                <?= $this->element("Audit/template_{$t->type}", ['template'=>$t]) ?>
            </div>

        <?php endforeach ?>

    </div>

    <div class="row">
        <fieldset>
            <div class="form-group col-lg-1">
                <?= $this->EasyAuditForm->dateControl('date', ['value'=>$audit->date, 'label'=>__('Date')]) ?>
            </div>
            <div class="form-group col-lg-3">
                <?= $this->Form->control('auditor_user_id', ['options' => $this->EasyAuditForm->objectToKeyValue($users, 'id', 'name'), 'value'=>$audit->auditor->id, 'label'=>__('Auditor')]) ?>
            </div>
        </fieldset>
    </div>

    <?= $this->EasyAuditForm->saveButton(__('Save')) ?>

<?= $this->Form->end() ?>