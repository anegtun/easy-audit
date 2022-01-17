<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Mailer\Email;

class EmailParserComponent extends Component {

    public function parse($input) {
        if(empty($input)) {
            return '';
        }
        $result = preg_replace("/\r\n|\r|\n/", ',', $input);
        $result = preg_replace("/[,\s]+/", ',', $result);
        $result = preg_replace("/,\$/", '', $result);
        return explode(',', $result);
    }
    
}
