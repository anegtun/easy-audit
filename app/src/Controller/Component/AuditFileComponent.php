<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;

class AuditFileComponent extends Component {

    public function readImgs($auditId) {
        $result = [];
        $dir = new Folder(WWW_ROOT . "uploads/audits/$auditId", true, 0755);
        $subdirs = $dir->read()[0];
        foreach($subdirs as $fieldId) {
            $result[$fieldId] = [];
            $dirField = new Folder($dir->path . DS . $fieldId);
            if(file_exists($dirField->path)) {
                foreach($dirField->read()[1] as $f) {
                    $result[$fieldId][] = "uploads/audits/$auditId/$fieldId/$f";
                }
            }
        }
        return $result;
    }

    public function moveAllImgs($auditId, $imgs) {
        $dir = new Folder(WWW_ROOT . "uploads/audits/$auditId", true, 0755);
        foreach($imgs as $fieldId => $fieldImgs) {
            if(!empty($fieldImgs && !empty($fieldImgs[0]['name']))) {
                $dirField = new Folder($dir->path . DS . $fieldId, true, 0755);
                foreach($fieldImgs as $img) {
                    move_uploaded_file($img['tmp_name'], $dirField->path . DS . "{$img['name']}");
                }
            }
        }
    }

    public function deleteAllImgs($auditId, $imgs) {
        $dir = new Folder(WWW_ROOT . "uploads/audits/$auditId");
        foreach($imgs as $fieldId => $filenames) {
            foreach($filenames as $f) {
                $file = new File($dir->path . DS . $fieldId. DS . $f);
                $file->delete();
            }
        }
    }

}
