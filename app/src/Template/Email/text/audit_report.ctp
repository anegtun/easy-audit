<?= strip_tags($this->element('Email/audit_report', [
    'audit' => $audit,
    'content' => $observations,
])) ?>