<div id="<?= $modal_id ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <?= $this->Form->create(null, ['type'=>'post', 'url'=>['controller'=>'Forms', 'action'=>'clone']]) ?>
        <?= $this->Form->hidden('id') ?>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= __('Clone form') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <?= $this->Form->control('name', ['label'=>__('New form name')]) ?>
                        <?= $this->Form->control('public_name', ['label'=>__('New form public name')]) ?>
                        <div class="form-group">
                            <label><?= __('Copy the selected templates:') ?></label>
                            <?php foreach($form->templates as $t) : ?>
                                <?= $this->EasyAuditForm->checkbox('templates[]', ['label'=>$t->name, 'value'=>$t->id]) ?>
                            <?php endforeach ?>
                        </div>
                        <?= $this->EasyAuditForm->checkbox('rename_old', ['label'=>__('Rename old form?')]) ?>
                        <div class="modal-clone-form-rename" style="display:none">
                            <?= $this->Form->control('name_old', ['label'=>__('Name')]) ?>
                            <?= $this->Form->control('public_name_old', ['label'=>__('Public name')]) ?>
                        </div>
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