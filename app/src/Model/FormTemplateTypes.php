<?php
namespace App\Model;

class FormTemplateTypes {
    
    public function getAll() {
        $values = [
            'measure' => __('Measure'),
            'select' => __('Select form')
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