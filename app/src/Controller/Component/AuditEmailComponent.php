<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Mailer\Email;

class AuditEmailComponent extends Component {

    public function send() {
        $email = new Email('default');
        $email->viewBuilder()->setTemplate('default', 'default');
        $email
            ->setEmailFormat('both')
            ->setTo('nitta18@gmail.com')
            ->setSubject('QUE PASA')
            ->send('TEST');
    }

}
