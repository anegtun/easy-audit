<?php
$companyInfo = $this->EasyAuditConfig->company();
?>

<p>
    <?= __('This message and all of its attachments are meant to be sent exclusively to its recipient and contain confidential information.') ?>
    <?= __('In the event of accidantally receiving this message, please delete all of its content and inform us at {0}.', $companyInfo->email) ?>
    <?= __('We also comunicate that the distribution, copy or any use of this message or any of its attachments is not permitted.') ?>
</p>
<p>
    <?= __('Please think in the environment before printing this email.') ?>
</p>
<p>
    <?= __('{0} has implemented all the needed technical and organizational measures to protect all your personal data required by law.', $companyInfo->legal) ?>
    <?= __('In any case, the owner of this data can exercise its right to access, rectify, eliminate or limitate its exposure by writing to the postal address previously indicated or through the following email address: {0}.', $companyInfo->email) ?>
</p>