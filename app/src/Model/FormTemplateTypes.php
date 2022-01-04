<?php
namespace App\Model;

class FormTemplateTypes {
    
    public function getAll() {
        $values = [
            'measure' => __('Measure'),
            'simple' => __('Simple'),
            'select' => __('Checklist'),
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