<?php
namespace App\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;

class EasyAuditConfigHelper extends Helper {

    public function company() {
        return (object) [
            'name' => Configure::read('easy-audit.company.name'),
            'nameFull' => Configure::read('easy-audit.company.name-full'),
            'legal' => Configure::read('easy-audit.company.legal'),
            'email' => Configure::read('easy-audit.company.email'),
            'phone' => Configure::read('easy-audit.company.phone'),
            'website' => Configure::read('easy-audit.company.website'),
            'address' => Configure::read('easy-audit.company.address'),
            'motto' => Configure::read('easy-audit.company.motto'),
        ];
    }

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