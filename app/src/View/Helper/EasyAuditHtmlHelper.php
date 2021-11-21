<?php
namespace App\View\Helper;

use Cake\View\Helper;

class EasyAuditHtmlHelper extends Helper {

    public $helpers = ['Html'];

    public function gliphiconLink($glyphicon, $text, $url) {
        return $this->Html->link(
            "<span class='glyphicon glyphicon-$glyphicon'><span class='sr-only'>$text</span></span> $text",
            $url,
            ['escape'=>false]
        );
    }
 
}