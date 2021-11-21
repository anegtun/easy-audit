<?php
namespace App\View\Helper;

use Cake\View\Helper;

class EasyAuditHtmlHelper extends Helper {

    public $helpers = ['Html'];
    
    public function editButton($url) {
        return $this->Html->link('', $url, ['class'=>'glyphicon glyphicon-pencil']);
    }
    
    public function deleteButton($url) {
        return $this->Html->link('', $url, ['class'=>'glyphicon glyphicon-trash', 'confirm'=>__('This operation can\'t be undone, are you sure?')]);
    }

    public function gliphiconText($glyphicon, $text) {
        return "<span class='glyphicon glyphicon-$glyphicon'><span class='sr-only'>$text</span></span> $text";
    }

    public function gliphiconLink($glyphicon, $text, $url) {
        return $this->Html->link(
            $this->gliphiconText($glyphicon, $text),
            $url,
            ['escape'=>false]
        );
    }
 
}