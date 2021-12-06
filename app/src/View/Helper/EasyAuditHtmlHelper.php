<?php
namespace App\View\Helper;

use Cake\View\Helper;

class EasyAuditHtmlHelper extends Helper {

    public $helpers = ['Html'];

    public function editButton($url) {
        return $this->gliphiconLink('pencil', '', $url);
    }

    public function deleteButton($url) {
        return $this->gliphiconLink('trash', '', $url, ['confirm'=>__('This operation can\'t be undone, are you sure?')]);
    }

    public function gliphiconText($glyphicon, $text) {
        return "<span class='glyphicon glyphicon-$glyphicon'><span class='sr-only'>$text</span></span> $text";
    }

    public function gliphiconLink($glyphicon, $text, $url, $options = []) {
        if(empty($text)) {
            $classes = array_merge(["glyphicon glyphicon-$glyphicon"], empty($options['classes']) ? [] : $options['classes']);
            return $this->Html->link(
                $this->gliphiconText($glyphicon, $text),
                $url,
                array_merge($options, ['classes' => $classes], ['escape' => false])
            );
        }

        return $this->Html->link(
            $this->gliphiconText($glyphicon, $text),
            $url,
            array_merge($options, ['escape'=>false])
        );
    }
 
}