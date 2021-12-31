<div id="modal-send-report" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <?= $this->Form->create(null, ['type'=>'post', 'url'=>['controller'=>'audits', 'action'=>'send']]) ?>
        <?= $this->Form->hidden('id') ?>
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
                </div>
                <div class="modal-footer">
                    <?= $this->EasyAuditForm->saveButton(__('Send')) ?>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= __('Close') ?></button>
                </div>
            </div>
        </div>
    <?= $this->Form->end() ?>
</div>