<?php foreach($template->form->sections as $s) : ?>
    <fieldset>
        <legend><?= $this->EasyAuditTemplate->section($s) ?></legend>
        <?php foreach($template->fields as $f) : ?>
            <?php if($f->form_section_id === $s->id) : ?>
                <div class="row form-template-field">
                        <div class="col-sm-1">
                            <?php if($is_editable) : ?>
                                <?= $this->EasyAuditForm->editModalLink($f, 'data-field', ['id', 'form_section_id', 'position', 'text', 'type']) ?>
                            <?php endif ?>
                            <?= $this->EasyAuditHtml->gliphiconLink('arrow-up', '', ['action'=>'moveFieldUp', $f->form_template_id, $f->id]) ?>
                            <?= $this->EasyAuditHtml->gliphiconLink('arrow-down', '', ['action'=>'moveFieldDown', $f->form_template_id, $f->id]) ?>
                            <?php if($is_editable) : ?>
                                <?= $this->EasyAuditHtml->gliphiconLink('remove', '', ['action'=>'deleteField', $f->id], ['confirm' => __('Are you sure you want to remove this field? This can\'t be undone')]) ?>
                            <?php endif ?>
                        </div>
                    <div class="col-sm-1">
                        <strong>
                            <?= $field_types[$f->type] ?>
                            <?= empty($f->optionset) ? '' : "<br/>({$f->optionset->name})" ?>
                        </strong>
                    </div>
                    <div class="col-sm-10 form-group">
                        <label><?= $this->EasyAuditTemplate->fieldLabel($s, $f) ?></label>
                    </div>
                </div>
            <?php endif ?>
        <?php endforeach ?>
    </fieldset>
<?php endforeach ?>



<?= $this->element("form_templates/modals/field", [
    'allowed_field_types' => empty($allowed_field_types) ? null : $allowed_field_types
]) ?>