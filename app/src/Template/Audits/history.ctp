<?php
$title = __('Audit') . " ". $audit->customer->name;
$this->extend('template');
$this->set('headerTitle', $title);
$this->set('headerBreadcrumbs', [
    ['label'=>__('Audits'), 'url'=>['action'=>'index']],
    ['label'=>$title],
    ['label'=>__('History')]
]);
$this->Html->script('audit-history', ['block' => 'script']);
?>

<?php foreach($audit->form_templates as $t) : ?>

    <?php if($t->type === 'select') : ?>

        <?php
        $template_audits = [];
        foreach($audits as $a)  {
            if(in_array($t->id, $a->getTemplateIds())) {
                $template_audits[] = $a;
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
                                <?php foreach($template_audits as $a) : ?>
                                    <th class="celda-titulo"><?= strtoupper($a->date->i18nFormat('MMM yy')) ?></th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($t->form_template_sections as $s) : ?>
                                <tr>
                                    <td>
                                        <?= $this->EasyAuditTemplate->section($s) ?>
                                        (<?= $s->weigth ?>)
                                    </td>
                                    <?php foreach($template_audits as $a) : ?>
                                        <td><?= $a->score_section[$s->id] ?></td>
                                    <?php endforeach ?>
                                </tr>
                            <?php endforeach ?>
                                <tr>
                                    <td><strong><?= __('TOTAL') ?></strong></td>
                                    <?php foreach($template_audits as $a) : ?>
                                        <td class="audit-history-total" data-audit-date="<?= strtoupper($a->date->i18nFormat('MMM yy')) ?>">
                                            <strong><?= $a->score_templates[$t->id] ?></strong>
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
        <?= $this->element('Audits/modal_send', ['audit' => $audit]) ?>
    </div>
</div>