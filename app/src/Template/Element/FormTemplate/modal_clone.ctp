<div id="modal-clone-template" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <?= $this->Form->create(null, ['type'=>'post', 'url'=>['action'=>'clone']]) ?>
        <?= $this->Form->hidden('id') ?>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= __('Clone template') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <?= $this->Form->control('name', ['label'=>__('New template name')]) ?>
                        <?= $this->Form->control('name_old', ['label'=>__('Rename old template?')]) ?>
                        <label class="checkbox-container">
                            <?= __('Disable old template?') ?>
                            <input type="checkbox" name="disable">
                        </label>
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