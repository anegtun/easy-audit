<div id="modal-section" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <?= $this->Form->create(null, ['url'=>['action'=>'saveSection']]) ?>
        <?= $this->Form->hidden('id') ?>
        <?= $this->Form->hidden('form_id', ['value' => $form->id]) ?>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= __('Section') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <?= $this->Form->control('name', ['label'=>__('Name')]) ?>
                        <?= $this->Form->control('weigth', ['label'=>__('Weigth')]) ?>
                        <?= $this->Form->control('position', ['options' => $this->EasyAuditForm->objectToKeyValue($form->sections, 'position', 'name'), 'label'=>__('Place before...')]) ?>
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