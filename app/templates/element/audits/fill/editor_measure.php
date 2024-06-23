<fieldset>
    <div class="audit-measures">

        <?php if(!empty($audit->measure_values)) : ?>
            <?php foreach($audit->measure_values as $i => $v) : ?>

                <?php $index = $i + 1 ?>
                <div class="audit-measure">
                    <div class="audit-measure-button">
                        <?= $this->EasyAuditHtml->gliphiconLink('remove', '', '', ['class' => 'remove-measure']) ?>
                    </div>
                    <div class="audit-measure-input">
                        <div class="audit-measure-item">
                            <input type="text" class="form-control" name="audit_measure[<?= $template->id ?>][<?= $index ?>][item]" value="<?= $v['item'] ?>" placeholder="<?= __('Item') ?>" />
                            <input type="text" class="form-control" name="audit_measure[<?= $template->id ?>][<?= $index ?>][unit]" value="<?= $v['unit'] ?>" placeholder="<?= __('Unit') ?>" />
                        </div>
                        <div class="audit-measure-numbers">
                            <div class="audit-measure-expected"><input type="number" class="form-control" step="any" name="audit_measure[<?= $template->id ?>][<?= $index ?>][expected]" value="<?= $v['expected'] ?>" placeholder="<?= __('Expected') ?>" /></div>
                            <div class="audit-measure-actual"><input type="number" class="form-control" step="any" name="audit_measure[<?= $template->id ?>][<?= $index ?>][actual]" value="<?= $v['actual'] ?>" placeholder="<?= __('Actual') ?>" /></div>
                            <div class="audit-measure-difference"><input type="text" class="form-control" disabled="disabled" /></div>
                        </div>
                        <div class="audit-measure-thredshold">
                            <div><input type="number" class="form-control" step="any" name="audit_measure[<?= $template->id ?>][<?= $index ?>][threshold]" value="<?= $v['threshold'] ?>" placeholder="<?= __('Thredshold') ?>" /></div>
                            <div class="audit-measure-result">
                                <span class='glyphicon glyphicon-ok-sign'></span>
                                <span class='glyphicon glyphicon-remove-sign'></span>
                                <span class='glyphicon glyphicon-question-sign'></span>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach ?>
        <?php endif ?>

        <div class="audit-measure audit-measure-template" style="display:none;">
            <div class="audit-measure-button">
                <?= $this->EasyAuditHtml->gliphiconLink('remove', '', '', ['class' => 'remove-measure']) ?>
            </div>
            <div class="audit-measure-input">
                <div class="audit-measure-item">
                    <input type="text" class="form-control" name="audit_measure[<?= $template->id ?>][0][item]" placeholder="<?= __('Item') ?>" />
                    <input type="text" class="form-control" name="audit_measure[<?= $template->id ?>][0][unit]" placeholder="<?= __('Unit') ?>" />
                </div>
                <div class="audit-measure-numbers">
                    <div class="audit-measure-expected"><input type="number" class="form-control" step="any" name="audit_measure[<?= $template->id ?>][0][expected]" placeholder="<?= __('Expected') ?>" /></div>
                    <div class="audit-measure-actual"><input type="number" class="form-control" step="any" name="audit_measure[<?= $template->id ?>][0][actual]" placeholder="<?= __('Actual') ?>" /></div>
                    <div class="audit-measure-difference"><input type="number" class="form-control" step="any" disabled="disabled" /></div>
                </div>
                <div class="audit-measure-thredshold">
                    <div><input type="number" class="form-control" step="any" name="audit_measure[<?= $template->id ?>][0][threshold]" placeholder="<?= __('Thredshold') ?>" /></div>
                    <div class="audit-measure-result">
                        <span class='glyphicon glyphicon-ok-sign'></span>
                        <span class='glyphicon glyphicon-remove-sign'></span>
                        <span class='glyphicon glyphicon-question-sign'></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-secondary add-measure"><?= __('+ add measure') ?></button>
</fieldset>