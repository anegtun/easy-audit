<?php

$result = [];
foreach($customer->templates as $t) {
    if(!$t->disabled) {
        $result[] = ['id' => $t->id, 'name' => $t->name, 'form_name' => $t->form->name];
    }
}

echo json_encode($result);