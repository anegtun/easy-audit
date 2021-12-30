<?php
$show_user = empty($hide_user);
?>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th class="cell-small"></th>
                <th class="cell-small"></th>
                <th class="cell-small"></th>
                <th class="cell-small celda-titulo"><?= __('Date') ?></th>
                <th class="celda-titulo"><?= __('Name') ?></th>
                <?php if($show_user) : ?>
                    <th class="celda-titulo"><?= __('Auditor') ?></th>
                <?php endif ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($audits as $a) : ?>
                <tr>
                    <td><?= $this->EasyAuditHtml->gliphiconLink('edit', '', ['controller'=>'Audits', 'action'=>'fill', $a->id]) ?></td>
                    <td><?= $this->EasyAuditHtml->gliphiconLink('cog', '', ['controller'=>'Audits', 'action'=>'data', $a->id]) ?></td>
                    <td><?= $this->EasyAuditHtml->gliphiconLink('stats', '', ['controller'=>'Audits', 'action'=>'history', $a->id]) ?></td>
                    <td><?= $a->date ?></td>
                    <td><?= $a->customer->name ?></td>
                    <?php if($show_user) : ?>
                        <td><?= $a->auditor->name ?></td>
                    <?php endif ?>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>