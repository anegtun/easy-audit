<?php
namespace App\Model;

class FormTemplateFieldTypes {
    
    public function getAll() {
        $values = [
            'date' => __('Date'),
            'photos' => __('Photos'),
            'select' => __('Select'),
            'text' => __('Text'),
        ];
        asort($values);
        return $values;
    }
    
    public function getAllWithEmpty() {
        return array_merge([''=>''], $this->getAll());
    }

    public function get($key) {
        $values = $this->getAll();
        return $values[$key];
    }

}