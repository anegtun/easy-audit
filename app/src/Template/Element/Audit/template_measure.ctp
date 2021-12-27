<div class="row">
    <div class="audit-measures">

        <?php if(!empty($field_measure_values[$template->id])) : ?>

        <?php foreach($field_measure_values[$template->id] as $i => $v) : ?>
            <?php $index = $i + 1 ?>
            <div class="audit-measure">
                <div><?= $this->EasyAuditHtml->gliphiconLink('remove', '', '', ['class' => 'remove-measure']) ?></div>
                <div><input type="text" class="form-control" name="audit_measure[<?= $template->id ?>][<?= $index ?>][item]" value="<?= $v['item'] ?>" /></div>
                <div><input type="number" class="form-control" step="any" name="audit_measure[<?= $template->id ?>][<?= $index ?>][expected]" value="<?= $v['expected'] ?>" /></div>
                <div><input type="number" class="form-control" step="any" name="audit_measure[<?= $template->id ?>][<?= $index ?>][actual]" value="<?= $v['actual'] ?>" /></div>
                <div><input type="number" class="form-control" step="any" name="audit_measure[<?= $template->id ?>][<?= $index ?>][threshold]" value="<?= $v['threshold'] ?>" /></div>
            </div>
        <?php endforeach ?>

        <?php endif ?>

        <div class="audit-measure audit-measure-template" style="display: none">
            <div><input type="text" class="form-control" name="audit_measure[<?= $template->id ?>][0][item]" /></div>
            <div><input type="number" class="form-control" step="any" name="audit_measure[<?= $template->id ?>][0][expected]" /></div>
            <div><input type="number" class="form-control" step="any" name="audit_measure[<?= $template->id ?>][0][actual]" /></div>
            <div><input type="number" class="form-control" step="any" name="audit_measure[<?= $template->id ?>][0][threshold]" /></div>
        </div>
    </div>

    <button type="button" class="btn btn-secondary add-measure"><?= __('+ add measure') ?></button>
</div>


<div id="modal-measure" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= __('Measure') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <fieldset>
                    <?= $this->Form->control('item', ['label'=>__('Item')]) ?>
                    <?= $this->Form->control('expected', ['type'=>'number', 'label'=>__('Expected')]) ?>
                    <?= $this->Form->control('actual', ['type'=>'number', 'label'=>__('Actual')]) ?>
                    <?= $this->Form->control('threshold', ['type'=>'number', 'label'=>__('Threshold')]) ?>
                </fieldset>
            </div>
            <div class="modal-footer">
                <?= $this->EasyAuditForm->saveButton(__('Save'), ['id'=>'submit-measure']) ?>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= __('Close') ?></button>
            </div>
        </div>
    </div>
</div>