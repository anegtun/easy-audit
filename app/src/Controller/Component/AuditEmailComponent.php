<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Mailer\Email;

class AuditEmailComponent extends Component {

    public function send($audit) {
        $email = new Email('default');
        $email->viewBuilder()->setTemplate('audit_report', 'default');
        $email
            ->setEmailFormat('both')
            ->setTo('nitta18@gmail.com')
            ->setSubject(__('Audit report'))
            ->setViewVars(compact('audit'))
            ->send();
    }

}
