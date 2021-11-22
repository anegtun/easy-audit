<?php
namespace App\View\Helper;

use Cake\View\Helper;

class EasyAuditFormHelper extends Helper {
    
    public function objectToKeyValue($array, $key, $value, $allowEmpty=true, $order=true) {
        $tmp = $allowEmpty ? [''=>''] : [];
        foreach($array as $e) {
            $v = '';
            if(isset($e->$value)) {
                $v = $e->$value;
            } else {
                eval("\$v = \"$value\";");
            }
            $tmp[$e->$key] = $v;
        }
        if($order) {
            asort($tmp);
        }
        return $tmp;
    }
    
}