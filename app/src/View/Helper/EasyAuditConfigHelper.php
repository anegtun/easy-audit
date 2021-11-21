<?php
namespace App\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;

class EasyAuditConfigHelper extends Helper {

    public function mainUrl() {
        return Configure::read('easy-audit.main-url');
    }

    public function siteTitle() {
        return Configure::read('easy-audit.site.title');
    }

    public function version() {
        return Configure::read('easy-audit.version');
    }

}