<?php
namespace App\Model;

class FormTypes {
    
    public function getAll() {
        $values = [
            'checklist' => __('Checklist'),
            'measure' => __('Measure'),
            'simple' => __('Simple'),
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