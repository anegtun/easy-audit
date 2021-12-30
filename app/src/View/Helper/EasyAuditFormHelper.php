<?php
namespace App\View\Helper;

use Cake\View\Helper;

class EasyAuditFormHelper extends Helper {

    public $helpers = ['EasyAuditHtml', 'Form'];

    private $clean_templates = [
        'inputContainer' => '{{content}}',
        'input' => '<input type="{{type}}" name="{{name}}" {{attrs}}/>',
        'select' => '<select name="{{name}}" class="form-control {{classes}}"{{attrs}}>{{content}}</select>',
    ];

    public function checkbox($key, $attrs=[]) {
        $label = empty($attrs['label']) ? "" : "<span>{$attrs['label']}</span>";
        $checked = empty($attrs['value']) ? "" : "checked";
        return "<label class=\"checkbox-container\"><input type=\"checkbox\" name=\"$key\" checked=\"$checked\">$label</label>";
    }

    public function dateControl($key, $attrs=[]) {
        $formAttrs = array_merge($attrs, ['templateVars' => ['classes' => 'fld-date']]);
        return $this->Form->control($key, $formAttrs);
    }

    public function cleanControl($name, $attrs=[]) {
        $input_attrs = array_merge($attrs, ['label' => false, 'templates' => $this->clean_templates]);
        return $this->Form->control($name, $input_attrs);
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