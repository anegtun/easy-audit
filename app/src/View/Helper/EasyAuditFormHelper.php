<?php
namespace App\View\Helper;

use Cake\View\Helper;

class EasyAuditFormHelper extends Helper {

    public $helpers = ['EasyAuditHtml', 'Form'];

    public function checkbox($key, $attrs=[]) {
        $label = empty($attrs['label']) ? "" : "<span>{$attrs['label']}</span>";
        $checked = empty($attrs['value']) ? "" : "checked=\"checked\"";
        return "<label class=\"checkbox-container\"><input type=\"checkbox\" name=\"$key\" $checked>$label</label>";
    }

    public function dateControl($key, $attrs=[]) {
        $formAttrs = array_merge($attrs, ['templateVars' => ['classes' => 'fld-date']]);
        return $this->Form->control($key, $formAttrs);
    }
    
    public function saveButton($label, $opts=[]) {
        $attrs = array_merge($opts, ['class'=>'btn btn-primary glyphicon glyphicon-saved']);
        return $this->Form->button($label, $attrs);
    }
    
    public function editModalLink($entity, $prefix, $fields = []) {
        $attrs = [];
        foreach($fields as $f) {
            $attrs["${prefix}-${f}"] = $entity->$f;
        }
        return $this->EasyAuditHtml->gliphiconLink('edit', '', '#', $attrs);
    }
    
    public function objectToKeyValue($array, $key, $value, $allowEmpty=true, $order=false) {
        $tmp = $allowEmpty ? [''=>''] : [];
        if(!empty($array)) {
            foreach($array as $e) {
                $v = '';
                if(isset($e->$value)) {
                    $v = $e->$value;
                } else {
                    eval("\$v = \"$value\";");
                }
                $tmp[$e->$key] = $v;
            }
        }
        if($order) {
            asort($tmp);
        }
        return $tmp;
    }
    
}