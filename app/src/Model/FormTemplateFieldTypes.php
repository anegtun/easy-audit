<?php
namespace App\Model;

class FormTemplateFieldTypes {
    
    public function getAll() {
        return array(
            'select' => __('Option select')
        );
    }
    
    public function getAllWithEmpty() {
        return array_merge([''=>''], $this->getCategorias());
    }

    public function get($key) {
        $values = $this->getAll();
        return $values[$key];
    }

}