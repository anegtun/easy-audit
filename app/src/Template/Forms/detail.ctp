<?php
$title = empty($form->name) ? __('Template') : $form->name;
$this->extend('template');
$this->set('headerTitle', $title . (empty($form->public_name) ? '' : " ({$form->public_name})"));
$this->set('headerBreadcrumbs', [
    ['label'=>__('Config')],
    ['label'=>__('Forms'), 'url'=>['action'=>'index']],
    ['label'=>$title]
]);
$this->Html->script('form-templates', ['block' => 'script']);

$has_audits = !empty($form->audits);
$is_disabled = !empty($form->disabled);
$is_editable = !$has_audits && !$is_disabled;
?>

<div class="row">

    <div class="button-group">
        <div>
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-rename"><?= __('Rename') ?></button>
            <button type="button" class="btn btn-default modal-clone-button" data-form-id="<?=$form->id?>" data-form-name="<?=$form->name?>" data-form-public_name="<?=$form->public_name?>"><?= __('Clone') ?></button>
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

    <?php if($is_disabled) : ?>
        <div class="alert alert-info form-template-audit-info">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?= $this->EasyAuditHtml->gliphiconText('info-sign', __('This form is disabled, so it can\'t be edited anymore. To edit please re-enable.')) ?>
        </div>
    <?php endif ?>


    <fieldset>
        <legend><?= __('Sections') ?></legend>
        <?php if(!empty($form->sections)) : ?>
            <?php foreach($form->sections as $s) : ?>
                <div class="form-section">
                    <div>
                        <?= $this->EasyAuditForm->editModalLink($s, 'data-section', ['id', 'position', 'name', 'weigth']) ?>
                        <?php if($is_editable) : ?>
                            <?= $this->EasyAuditHtml->gliphiconLink('arrow-up', '', ['action'=>'moveSectionUp', $form->id, $s->id]) ?>
                            <?= $this->EasyAuditHtml->gliphiconLink('arrow-down', '', ['action'=>'moveSectionDown', $form->id, $s->id]) ?>
                            <?php if(empty($s->form_template_fields)) : ?>
                                <?= $this->EasyAuditHtml->gliphiconLink('remove', '', ['action'=>'deleteSection', $s->id]) ?>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                    <div>
                        <?= $this->EasyAuditTemplate->section($s) ?>
                        (<?= $s->weigth ?>)
                    </div>
                </div>
            <?php endforeach ?>
        <?php endif ?>

        <div class="button-group">
            <?php if($is_editable && $form->type !== 'measure') : ?>
                <button type="button" id="modal-section-button" class="btn btn-primary" data-target="#modal-section"><?= __('Add section') ?></button>
            <?php endif ?>
        </div>
    </fieldset>
</div>



<?= $this->element('Forms/modals/audits', ['modal_id' => 'modal-audits', 'audits' => $form->audits]) ?>
<?= $this->element('Forms/modals/customers', ['modal_id' => 'modal-customers', 'customers' => $form->customers]) ?>

<?= $this->element('Forms/modals/section', ['modal_id' => 'modal-section']) ?>
<?= $this->element('Forms/modals/rename', ['modal_id' => 'modal-rename', 'form' => $form]) ?>
<?= $this->element('Forms/modals/clone', ['modal_id' => 'modal-clone-template']) ?>