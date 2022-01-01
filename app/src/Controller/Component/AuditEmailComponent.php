<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Mailer\Email;

class AuditEmailComponent extends Component {

    public function send() {
        $email = new Email('default');
        $email->setFrom(['cona@example.com' => 'My Site'])
            ->setTo('nitta18@gmail.com')
            ->setSubject('About')
            ->send('My message');
    }

}
