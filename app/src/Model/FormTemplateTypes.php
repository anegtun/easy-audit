<?php
namespace App\Model;

class FormTemplateTypes {
    
    public function getAll() {
        return array(
            'measure' => __('Measure'),
            'select' => __('Select form')
        );
    }
    
    public function getAllWithEmpty() {
        return array_merge([''=>''], $this->getAll());
    }

    public function get($key) {
        $values = $this->getAll();
        return $values[$key];
    }

}