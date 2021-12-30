<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;

class AuditFileComponent extends Component {

    public function readPhotos($auditId) {
        $result = [];
        $dir = new Folder($this->pathToAuditFolder($auditId));
        $templateDirs = $dir->read()[0];
        foreach($templateDirs as $templateFolderName) {
            $templateId = $this->getTemplateId($templateFolderName);
            $result[$templateId] = [];
            $templateFolder = new Folder($dir->path . DS . $templateFolderName);
            $fieldDirs = $templateFolder->read()[0];
            foreach($fieldDirs as $fieldFolderName) {
                $fieldId = $this->getFieldId($fieldFolderName);
                $result[$templateId][$fieldId] = [];
                $fieldFolder = new Folder($templateFolder->path . DS . $fieldFolderName);
                foreach($fieldFolder->read()[1] as $f) {
                    $result[$templateId][$fieldId][] = $this->urlToImage($auditId, $templateId, $fieldId, $f);
                }
            }
        }
        return $result;
    }

    public function addPhotos($auditId, $templateId, $imgs) {
        $dir = new Folder($this->pathToTemplateFolder($auditId, $templateId), true, 0755);
        foreach($imgs as $fieldId => $fieldImgs) {
            if(!empty($fieldImgs && !empty($fieldImgs[0]['name']))) {
                $dirField = new Folder($this->pathToFieldFolder($auditId, $templateId, $fieldId), true, 0755);
                foreach($fieldImgs as $img) {
                    move_uploaded_file($img['tmp_name'], $dirField->path . DS . "{$img['name']}");
                }
            }
        }
    }

    public function removePhotos($auditId, $templateId, $imgs) {
        $dir = new Folder($this->pathToTemplateFolder($auditId, $templateId));
        foreach($imgs as $fieldId => $filenames) {
            $fieldPath = $this->pathToFieldFolder($auditId, $templateId, $fieldId);
            foreach($filenames as $f) {
                $file = new File($fieldPath . DS . $f);
                $file->delete();
            }
        }
    }

    public function removeAllPhotos($auditId) {
        $dir = new Folder($this->pathToAuditFolder($auditId));
        $dir->delete();
    }

    private function readSubdirectories($parentFolder, $name) {
        $subFolder = new Folder($parentFolder->path . DS . $name);
        return file_exists($subFolder->path) ? $subFolder->read()[0] : [];
    }

    private function readFiles($parentFolder, $name) {
        $subFolder = new Folder($parentFolder->path . DS . $name);
        return file_exists($subFolder->path) ? $subFolder->read()[1] : [];
    }

    private function pathToAuditFolder($auditId) {
        return WWW_ROOT . "uploads/audits/audit_$auditId";
    }

    private function pathToTemplateFolder($auditId, $templateId) {
        return WWW_ROOT . "uploads/audits/audit_$auditId/template_$templateId";
    }

    private function pathToFieldFolder($auditId, $templateId, $fieldId) {
        return WWW_ROOT . "uploads/audits/audit_$auditId/template_$templateId/field_$fieldId";
    }

    private function urlToImage($auditId, $templateId, $fieldId, $filename) {
        return "uploads/audits/audit_$auditId/template_$templateId/field_$fieldId/$filename";
    }

    private function getTemplateId($dir_name) {
        return str_replace('template_', '', $dir_name);
    }

    private function getFieldId($dir_name) {
        return str_replace('field_', '', $dir_name);
    }

}
