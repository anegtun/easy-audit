<?php
namespace App\View\Helper;

use Cake\View\Helper;

class EasyAuditTemplateHelper extends Helper {

    public function section($section) {
        return ($section->position-1) . ". ". $section->name;
    }

    public function fieldLabel($section, $field, $showText = true) {
        $result = ($section->position-1) . ".". $field->position;
        if($showText) {
            $result .= ". {$field->text}";
        }
        return $result;
    }
 
}