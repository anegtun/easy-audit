<?php
namespace App\Model;

class Roles {
    
    public function getAll() {
        return array(
            'admin' => __('Admin'),
            'auditor' => __('Auditor')
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