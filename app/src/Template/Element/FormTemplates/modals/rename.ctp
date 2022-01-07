<div id="<?= $modal_id ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <?= $this->Form->create(null, ['id'=>'field-form', 'url'=>['controller'=>'FormTemplates', 'action'=>'rename']]) ?>
        <?= $this->Form->hidden('id', ['value' => $template->id]) ?>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= __('Rename') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <?= $this->Form->control('name', ['label'=>__('Name'), 'value'=>$template->name]) ?>
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