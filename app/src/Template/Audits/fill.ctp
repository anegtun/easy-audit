<?php
$title = __('Audit') . " ". $audit->customer->name;
$this->extend('template');
$this->set('headerTitle', $title);
$this->set('headerBreadcrumbs', [
    ['label'=>__('Audits'), 'url'=>['action'=>'index']],
    ['label'=>$title],
    ['label'=>__('Fill')]
]);
$this->Html->script('audit-fill', ['block' => 'script']);
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
                <?= $this->element("Audits/fill_{$t->type}", ['audit'=>$audit, 'template'=>$t]) ?>
            </div>
        <?php endforeach ?>
    </div>

    <div class="button-group">
        <div>
            <button type="button" class="btn btn-warning audit-retry-photos" style="display:none"><?= $this->EasyAuditHtml->gliphiconText('repeat', __('Retry failed photos')) ?></button>
            <?= $this->EasyAuditForm->saveButton(__('Save')) ?>
        </div>
        <div>
            <?= $this->EasyAuditHtml->linkButton(['action' => 'data', $audit->id], 'cog', __('Audit data')) ?>
            <?= $this->EasyAuditHtml->linkButton(['action' => 'history', $audit->id], 'stats', __('Audit history')) ?>
            <?= $this->EasyAuditHtml->linkButton(['action' => 'print', $audit->id], 'list-alt', __('View report'), ['target'=>'_blank']) ?>
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-send-report"><?= $this->EasyAuditHtml->gliphiconText('envelope', __('Send report')) ?></button>
        </div>
    </div>

<?= $this->Form->end() ?>

<?php foreach($optionset_values as $id => $opset) : ?>
    <div id="optionset-<?= $id ?>" style="display:none;">
        <?php foreach($opset as $opt) : ?>
            <div data-opt-id="<?= $opt->id ?>" data-opt-color="<?= $opt->color ?>"></div>
        <?php endforeach ?>
    </div>
<?php endforeach ?>

<?= $this->element('Audits/modal_send', ['audit' => $audit]) ?>