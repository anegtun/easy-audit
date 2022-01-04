<div id="modal-field" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <?= $this->Form->create(null, ['id'=>'field-form', 'url'=>['action'=>'saveField']]) ?>
        <?= $this->Form->hidden('id') ?>
        <?= $this->Form->hidden('form_template_id', ['value' => $template->id]) ?>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= __('Field') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <?= $this->Form->control('form_template_section_id', ['options' => $this->EasyAuditForm->objectToKeyValue($sections, 'id', '{$e->name}'), 'label'=>__('Section')]) ?>
                        <?= $this->Form->control('text', ['id'=>'field-text', 'type'=>'textarea', 'label'=>__('Text')]) ?>
                        <?= $this->Form->control('optionset_id', ['options'=>$optionsets, 'label'=>__('Option Set')]) ?>
                        <?= $this->Form->control('position', ['options' => [], 'label'=>__('Place before...')]) ?>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <?= $this->EasyAuditForm->saveButton(__('Save')) ?>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= __('Close') ?></button>
                </div>
            </div>
        </div>
    <?= $this->Form->end() ?>
</div>