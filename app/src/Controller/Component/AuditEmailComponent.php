<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Mailer\Email;

class AuditEmailComponent extends Component {

    public $components = ['EmailParser'];

    public function sendReport($audit, $content, $send_to_auditor = true, $bcc = null, $observations = null) {
        $filename = $audit->getReportFilename();
        $to = explode(',', $audit->customer->emails);
        array_walk($to, function (&$e) { $e = trim($e); });
        $bccEmails = $this->EmailParser->parse($bcc);

        $email = new Email('default');
        $email->viewBuilder()->setTemplate('audit_report', 'default');
        if(!empty($send_to_auditor)) {
            $email->setCc($audit->auditor->email);
        }
        if(!empty($bccEmails)) {
            $email->setBcc($bccEmails);
        }
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
