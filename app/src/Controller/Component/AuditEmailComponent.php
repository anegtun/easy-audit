<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Mailer\Email;

class AuditEmailComponent extends Component {

    public function sendReport($audit, $content) {
        $filename = $audit->getReportFilename();

        $email = new Email('default');
        $email->viewBuilder()->setTemplate('audit_report', 'default');
        $email
            ->setEmailFormat('both')
            ->setTo('nitta18@gmail.com')
            ->setSubject(__('Audit report'))
            ->setViewVars(compact('audit'))
            ->setAttachments([
                $filename => [
                    'data' => $content,
                    'mimetype' => 'application/pdf'
                ]
            ])
            ->send();
    }

}
