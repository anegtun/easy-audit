<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Mailer\Email;

class AuditEmailComponent extends Component {

    public function sendReport($audit, $content, $observations) {
        $filename = $audit->getReportFilename();
        $to = explode(',', $audit->customer->emails);
        array_walk($to, function (&$e) { $e = trim($e); });

        $email = new Email('default');
        $email->viewBuilder()->setTemplate('audit_report', 'default');
        $email
            ->setEmailFormat('both')
            ->setFrom($audit->auditor->email, $audit->auditor->name)
            ->setTo($to)
            ->setSubject(__('Audit report'))
            ->setViewVars(compact('audit', 'observations'))
            ->setAttachments([
                $filename => [
                    'data' => $content,
                    'mimetype' => 'application/pdf'
                ]
            ])
            ->send();
    }
    
}
