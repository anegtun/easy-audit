<?php
$title = $audit->customer->name." ({$audit->date})";
$this->extend('template');
$this->set('headerTitle', $title);
$this->set('headerBreadcrumbs', [
    ['label'=>__('Audits'), 'url'=>['action'=>'index']],
    ['label'=>$audit->customer->name],
    ['label'=>__('History')]
]);
$this->Html->script('audit-history', ['block' => 'script']);
?>

<?php foreach($audit->templates as $t) : ?>

    <?php if($t->form->type === 'select') : ?>

        <?php
        $form_audits = [];
        foreach($audits as $a)  {
            if(in_array($t->form->id, $a->getFormIds())) {
                $form_audits[] = $a;
            }
        }
        ?>

        <div class="row history-container" data-template-id="<?= $t->id ?>">
            <fieldset>
                <legend><?= $t->name ?></legend>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="celda-titulo"><?= __('Section') ?></th>
                                <?php foreach($form_audits as $a) : ?>
                                    <th class="celda-titulo"><?= strtoupper($a->date->i18nFormat('MMM yy')) ?></th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($t->form->sections as $s) : ?>
                                <tr>
                                    <td>
                                        <?= $this->EasyAuditTemplate->section($s) ?>
                                        (<?= $s->weigth ?>)
                                    </td>
                                    <?php foreach($form_audits as $a) : ?>
                                        <td><?= $a->score_section[$s->id] ?></td>
                                    <?php endforeach ?>
                                </tr>
                            <?php endforeach ?>
                                <tr>
                                    <td><strong><?= __('TOTAL') ?></strong></td>
                                    <?php foreach($form_audits as $a) : ?>
                                        <td class="audit-history-total" data-audit-date="<?= strtoupper($a->date->i18nFormat('MMM yy')) ?>">
                                            <strong><?= $a->score_form[$t->form->id] ?></strong>
                                        </td>
                                    <?php endforeach ?>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>

            <div>
                <canvas id="audit-history-chart-<?= $t->id ?>" style="max-width: 700px;"></canvas>
            </div>
        </div>

    <?php endif ?>

<?php endforeach ?>

<div class="button-group">
    <div></div>
    <div>
        <?= $this->EasyAuditHtml->linkButton(['action' => 'fill', $audit->id], 'edit', __('Fill audit')) ?>
        <?= $this->EasyAuditHtml->linkButton(['action' => 'data', $audit->id], 'cog', __('Audit data')) ?>
        <?= $this->EasyAuditHtml->linkButton(['action' => 'print', $audit->id], 'list-alt', __('View report'), ['target'=>'_blank']) ?>
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-send-report"><?= $this->EasyAuditHtml->gliphiconText('envelope', __('Send report')) ?></button>
        <?= $this->element('Audits/modals/send', ['audit' => $audit]) ?>
    </div>
</div>