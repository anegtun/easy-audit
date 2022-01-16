<div id="modal-send-report" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <?= $this->Form->create(null, ['type'=>'post', 'url'=>['controller'=>'audits', 'action'=>'send', $audit->id]]) ?>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= __('Send audit') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><?= __('Are you sure you want to send the report to the customer?') ?></p>
                    <p><strong><?= __('Customer') ?>:</strong> <?= $audit->customer->name ?></p>
                    <p><strong><?= __('Email addresses') ?>:</strong> <?= $audit->customer->emails ?></p>

                    <?= $this->EasyAuditForm->checkbox('send_auditor', ['label'=>__('Send copy to auditor?'), 'checked'=>true]) ?>

                    <div class="form-group">
                        <label><?= __('BCC') ?></label>
                        <?= $this->Form->textarea('bcc') ?>
                    </div>
                    <div class="form-group">
                        <label><?= __('Observations') ?></label>
                        <?= $this->Form->textarea('observations') ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <?= $this->EasyAuditForm->saveButton(__('Send')) ?>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= __('Close') ?></button>
                </div>
            </div>
        </div>
    <?= $this->Form->end() ?>
</div>