<?php
$exploded = explode("\n", $observations);
?>

<div>
    <p><?= __('Esteemed client') ?></p>
    <p><?= __('Please find attached the result of the audit carried out on <strong>{0}</strong>.', $audit->date) ?></p>
    <?php foreach ($exploded as $line) : ?>
        <p><?= $line ?></p>
    <?php endforeach ?>
    <p><?= __('If you have any inquiry, please do not hesitate to contact us.') ?></p>
    <p><?= __('Regards.') ?></p>
</div>
