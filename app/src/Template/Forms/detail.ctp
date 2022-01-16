<?php
$title = empty($form->name) ? __('Template') : $form->name;
$this->extend('template');
$this->set('headerTitle', $title . (empty($form->public_name) ? '' : " ({$form->public_name})"));
$this->set('headerBreadcrumbs', [
    ['label'=>__('Config')],
    ['label'=>__('Forms'), 'url'=>['action'=>'index']],
    ['label'=>$title]
]);
$this->Html->script('form', ['block' => 'script']);
$this->Html->script('form-clone', ['block' => 'script']);

$has_audits = !empty($audits);
$is_editable = !$has_audits;
?>

<div class="row">

    <div class="button-group">
        <div>
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-rename"><?= __('Rename') ?></button>
            <button type="button" class="btn btn-default modal-clone-form-button" data-form-id="<?=$form->id?>" data-form-name="<?=$form->name?>" data-form-public_name="<?=$form->public_name?>"><?= __('Clone') ?></button>
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-customers"><?= __('See customers') ?></button>
            <?php if($has_audits) : ?>
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-audits"><?= __('See audits') ?></button>
            <?php endif ?>
        </div>
        <?php if($is_editable) : ?>
            <div>
                <?= $this->EasyAuditHtml->deleteButton(['action'=>'delete', $form->id]) ?>
            </div>
        <?php endif ?>
    </div>

    <?php if($has_audits) : ?>
        <div class="alert alert-info form-template-audit-info">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?= $this->EasyAuditHtml->gliphiconText('info-sign', __('This form is associated with at least one audit, so the editing options are limited.')) ?>
        </div>
    <?php endif ?>


    <?php if($form->type === 'checklist') : ?>
        <?= $this->Form->create(null, ['url'=>['action'=>'save']]) ?>
            <?= $this->Form->hidden('id', ['value' => $form->id]) ?>
            <fieldset>
                <legend><?= __('Form config') ?></legend>

                <div class="form-row">
                    <?= $this->EasyAuditForm->checkbox('scores', ['label'=>__('Calculate scores?'), 'checked'=>!empty($form->scores)]) ?>
                </div>
                <div class="button-group">
                    <div><?= $this->EasyAuditForm->saveButton(__('Save')) ?></div>
                </div>
            </fieldset>
        <?= $this->Form->end() ?>
    <?php endif ?>


    <?php if($form->type !== 'measure') : ?>

        <fieldset>
            <legend><?= __('Sections') ?></legend>
            <?php if(!empty($form->sections)) : ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="cell-small"></th>
                                <th class="cell-small"></th>
                                <th class="cell-small"></th>
                                <th class="cell-small"></th>
                                <th class="celda-titulo"><?= __('Name') ?></th>
                                <th class="celda-titulo"><?= __('Weigth') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($form->sections as $s) : ?>
                                <tr>
                                    <td><?= $this->EasyAuditForm->editModalLink($s, 'data-section', ['id', 'position', 'name', 'weigth']) ?></td>
                                    <td><?= $this->EasyAuditHtml->gliphiconLink('arrow-up', '', ['action'=>'moveSectionUp', $form->id, $s->id]) ?></td>
                                    <td><?= $this->EasyAuditHtml->gliphiconLink('arrow-down', '', ['action'=>'moveSectionDown', $form->id, $s->id]) ?></td>
                                    <td>
                                        <?php if($is_editable) : ?>
                                            <?= $this->EasyAuditHtml->gliphiconLink('remove', '', ['action'=>'deleteSection', $s->id]) ?>
                                        <?php endif ?>
                                    </td>
                                    <td><?= $this->EasyAuditTemplate->section($s) ?></td>
                                    <td><?= $s->weigth ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>

            <div class="button-group">
                <button type="button" id="modal-section-button" class="btn btn-primary" data-target="#modal-section"><?= __('Add section') ?></button>
            </div>
        </fieldset>

    <?php endif ?>

    <fieldset>
        <legend><?= __('Templates') ?></legend>
        <?php if(!empty($form->templates)) : ?>
            <div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="cell-small"></th>
                                <th class="cell-small"></th>
                                <th class="cell-small"></th>
                                <th class="celda-titulo"><?= __('Name') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($form->templates as $t) : ?>
                                <tr class="<?= $t->disabled ? 'disabled' : '' ?>">
                                    <td><?= $this->EasyAuditHtml->deleteLink(['controller'=>'FormTemplates', 'action'=>'delete', $t->id]) ?></td>
                                    <td><?= $this->Html->link('', '#', ['class'=>'glyphicon glyphicon-duplicate modal-clone-template-button', 'data-template-id'=>$t->id, 'data-template-name'=>$t->name]) ?></td>
                                    <td><?= $this->Html->link('', ['controller'=>'FormTemplates', 'action'=>'toggleEnabled', $t->id], ['class'=>'glyphicon glyphicon-'.($t->disabled?'thumbs-up':'thumbs-down')]) ?></td>
                                    <td><?= $this->Html->link($t->name, ['controller'=>'FormTemplates', 'action'=>'detail', $t->id]) ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif ?>

        <div class="button-group">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-new-template"><?= __('Add template') ?></button>
        </div>
    </fieldset>

</div>



<?= $this->element('Forms/modals/section', ['modal_id' => 'modal-section']) ?>
<?= $this->element('Forms/modals/rename', ['modal_id' => 'modal-rename', 'form' => $form]) ?>
<?= $this->element('Forms/modals/clone', ['modal_id' => 'modal-clone-form']) ?>

<?= $this->element('FormTemplates/modals/new_template', ['modal_id' => 'modal-new-template']) ?>
<?= $this->element('FormTemplates/modals/clone', ['modal_id' => 'modal-clone-template']) ?>

<?= $this->element('Audits/modals/list', ['modal_id' => 'modal-audits', 'audits' => $audits]) ?>
<?= $this->element('Customers/modals/list', ['modal_id' => 'modal-customers', 'customers' => $customers]) ?>