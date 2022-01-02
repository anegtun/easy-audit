<?php
namespace App\View\Helper;

use Cake\View\Helper;

class EasyAuditHtmlHelper extends Helper {

    public $helpers = ['Html'];

    public function deleteLink($url, $icon = 'trash') {
        return $this->gliphiconLink($icon, '', $url, ['confirm'=>__('This operation can\'t be undone, are you sure?')]);
    }

    public function deleteButton($url, $icon = 'trash') {
        return $this->gliphiconLink($icon, __('Delete'), $url, ['class'=>['btn','btn-danger'], 'role'=>'button', 'confirm'=>__('This operation can\'t be undone, are you sure?')]);
    }

    public function linkButton($url, $icon, $label, $options = []) {
        $opts = array_merge($options, ['class'=>['btn','btn-default'], 'role'=>'button']);
        return $this->gliphiconLink($icon, $label, $url, $opts);
    }

    public function gliphiconText($glyphicon, $text) {
        return "<span class='glyphicon glyphicon-$glyphicon'><span class='sr-only'>$text</span></span> $text";
    }

    public function gliphicon($glyphicon, $options = []) {
        $classes = array_merge(["glyphicon glyphicon-$glyphicon"], empty($options['classes']) ? [] : $options['classes']);
        $classes_str = join(" ", $classes);
        return "<span class='$classes_str'></span>";
    }

    public function gliphiconLink($glyphicon, $text, $url, $options = []) {
        if(empty($text)) {
            return $this->Html->link(
                $this->gliphiconText($glyphicon, $text),
                $url,
                array_merge($options, ['escape' => false])
            );
        }

        return $this->Html->link(
            $this->gliphiconText($glyphicon, $text),
            $url,
            array_merge($options, ['escape'=>false])
        );
    }
 
}