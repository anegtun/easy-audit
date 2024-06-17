<?php
$this->set('menu_option', 'customers');
$this->set('headerTitle', empty($headerTitle) ? null : $headerTitle);
$this->set('headerBreadcrumbs', empty($headerBreadcrumbs) ? null : $headerBreadcrumbs);

echo $this->fetch('content');
?>