<?php
namespace App\View\Helper;

use Cake\View\Helper;

class EasyAuditTemplateHelper extends Helper {

    public function section($section) {
        return $section->getPositionNumber() . ". ". $section->name;
    }

    public function fieldLabel($section, $field, $showText = true) {
        $result = $section->getPositionNumber() . ".". $field->position;
        if($showText) {
            $result .= ". {$field->text}";
        }
        return $result;
    }
 
}