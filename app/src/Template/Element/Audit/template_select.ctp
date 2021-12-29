<?php foreach($template->form_template_sections as $s) : ?>

    <?php $collapseId = "section{$s->id}" ?>

    <fieldset>

        <legend>
            <a href="#<?= $collapseId ?>" data-toggle="collapse">
                <?= $s->name ?> (<?= $s->score ?>)
            </a>
        </legend>

        <div id="<?= $collapseId ?>" class="collapse">

            <?php foreach($template->form_template_fields_optionset as $f) : ?>

                <?php if($f->form_template_section_id === $s->id) : ?>

                    <?php 
                        $value = empty($field_values[$f->id]) ? null : $field_values[$f->id];
                        $hasObservations = !empty($value) && !empty($value->observations);
                        $imgs = empty($field_images[$f->id]) ? [] : $field_images[$f->id];
                        $hasImgs = !empty($imgs);
                        $hasImgsOrObs = $hasObservations || $hasImgs;
                    ?>

                    <div class="form-group audit-field">
                        <label for="<?= "field-values-{$f->id}" ?>">
                            <?= $hasImgsOrObs ? $this->EasyAuditHtml->gliphicon('warning-sign', ['classes'=>['text-warning']]) : '' ?>
                            <?= $f->text ?>
                        </label>
                        <?= $this->EasyAuditForm->cleanControl("field_values[{$f->id}]", [
                            'id' => "field-values-{$f->id}",
                            'options' => $this->EasyAuditForm->objectToKeyValue($optionset_values[$f->optionset_id], 'id', 'label'),
                            'value' => empty($value) ? '' : $value->optionset_value_id
                        ]) ?>
                        <div class="audit-observations">
                            <a class="audit-observations-open" href="#"><?= __('+ add observations & photos') ?></a>
                            <a class="audit-observations-close" href="#" style="display:none;"><?= __('- close observations & photos') ?></a>
                            <div class="audit-observations-input" style="display:none">
                                <textarea name="<?="field_observations[{$f->id}]"?>" class="form-control"><?= $hasObservations ? $value->observations : '' ?></textarea>
                                <div class="audit-img-current">
                                    <?php if($hasImgs) : ?>
                                        <p><strong><?= __('Current images') ?></strong></p>
                                        <?php foreach($imgs as $img) : ?>
                                            <?= $this->Html->image("/$img", ['data-field-id'=>$f->id]) ?>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </div>
                                <div class="audit-img-preview">
                                    <p style="display:none"><strong><?= __('New images') ?></strong></p>
                                </div>
                                <div class="audit-img-inputs">
                                    <div class="audit-img-input">
                                        <?php $fieldId = "field_photo_{$f->id}"; ?>
                                        <input type="file" name="<?="field_photo[{$f->id}][]"?>" id="<?=$fieldId?>" accept="image/*" capture="capture" />
                                        <label for="<?=$fieldId?>"><?= $this->EasyAuditHtml->gliphiconText('camera', __('Add photo')) ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif ?>

            <?php endforeach ?>

        </div>

    </fieldset>

<?php endforeach ?>