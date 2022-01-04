<?php
$optionsetOptions = iterator_to_array($optionsets);
?>

<?php foreach($sections as $s) : ?>
    <fieldset>
        <legend>
            <?= $this->EasyAuditForm->editModalLink($s, 'data-section', ['id', 'position', 'name', 'weigth']) ?>
            <?php if($is_editable) : ?>
                <?= $this->EasyAuditHtml->gliphiconLink('arrow-up', '', ['action'=>'moveSectionUp', $s->id]) ?>
                <?= $this->EasyAuditHtml->gliphiconLink('arrow-down', '', ['action'=>'moveSectionDown', $s->id]) ?>
                <?php if(empty($s->form_template_fields)) : ?>
                    <?= $this->EasyAuditHtml->gliphiconLink('remove', '', ['action'=>'deleteSection', $s->id]) ?>
                <?php endif ?>
            <?php endif ?>
            <?= $this->EasyAuditTemplate->section($s) ?>
            (<?= $s->weigth ?>)
        </legend>
        <?php foreach($s->form_template_fields_optionset as $f) : ?>
            <div class="row form-template-field">
                <?php if($is_editable) : ?>
                    <div class="col-sm-1">
                        <?= $this->EasyAuditForm->editModalLink($f, 'data-field', ['id', 'form_template_section_id', 'position', 'text']) ?>
                        <?= $this->EasyAuditHtml->gliphiconLink('arrow-up', '', ['action'=>'moveFieldUp', $f->id]) ?>
                        <?= $this->EasyAuditHtml->gliphiconLink('arrow-down', '', ['action'=>'moveFieldDown', $f->id]) ?>
                        <?= $this->EasyAuditHtml->gliphiconLink('remove', '', ['action'=>'deleteField', $f->id], ['confirm' => __('Are you sure you want to remove this field? This can\'t be undone')]) ?>
                    </div>
                <?php endif ?>
                <div class="col-sm-1">
                    <strong><?= $optionsetOptions[$f->optionset_id] ?></strong>
                </div>
                <div class="col-sm-10 form-group">
                    <label><?= $this->EasyAuditTemplate->fieldLabel($s, $f) ?></label>
                </div>
            </div>
        <?php endforeach ?>
    </fieldset>
<?php endforeach ?>



<?= $this->element("FormTemplates/detail_modal_section") ?>
<?= $this->element("FormTemplates/detail_modal_field") ?>