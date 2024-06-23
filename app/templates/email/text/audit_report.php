<?= strip_tags($this->element('email/audit_report', [
    'audit' => $audit,
    'content' => $observations,
])) ?>