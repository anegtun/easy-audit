<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>

<div class="alert alert-success">
    <span>
        <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
        <span><?= $message ?></span>
    </span>
</div>