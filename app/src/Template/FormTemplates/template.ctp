<?php
$this->set('menu_option', 'config');
$this->set('submenu_option', 'config-forms');
$this->set('headerTitle', empty($headerTitle) ? null : $headerTitle);
$this->set('headerBreadcrumbs', empty($headerBreadcrumbs) ? null : $headerBreadcrumbs);

echo $this->fetch('content');
?>