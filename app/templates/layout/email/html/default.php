<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
?>

<?php
$companyInfo = $this->EasyAuditConfig->company();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
    <title><?= $this->fetch('title') ?></title>
    <style type="text/css">
        .disclaimer {
            color: #777;
            font-size: smaller;
        }
    </style>
</head>
<body>
    <?= $this->fetch('content') ?>

    <footer>
        <div class="company-info">
            <?= $this->Html->image("/images/logo/email-footer.png", ['fullBase' => true, 'alt' => $companyInfo->name]) ?><br/>
            <?= $companyInfo->phone ?><br/>
            <?= $this->Html->link($companyInfo->website, $this->EasyAuditConfig->mainUrl()) ?><br/>
            <?= $companyInfo->address ?>
        </div>
        <div class="disclaimer">
            <?= $this->element('Layout/email_disclaimer') ?>
        </div>
    </footer>
</body>
</html>
