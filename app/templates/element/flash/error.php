<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>

<div class="alert alert-danger">
    <span>
        <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
        <span><?= $message ?></span>
    </span>
</div>