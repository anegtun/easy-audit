<?php
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>

<div class="alert alert-info <?= h($class) ?>">
    <span>
        <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
        <span><?= $message ?></span>
    </span>
</div>