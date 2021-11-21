<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class FormTemplatesController extends AppController {

    public function index() {
        $this->set('templates', $this->FormTemplates->find());
    }

    public function save() {
        $template = $this->FormTemplates->newEntity();
        if ($this->request->is('post') || $this->request->is('put')) {
            $clube = $this->FormTemplates->patchEntity($template, $this->request->getData());
            if ($this->FormTemplates->save($template)) {
                $this->Flash->success(__('Template created.'));
                return $this->redirect(['action'=>'index']);
            }
            $this->Flash->error(__('Error saving template.'));
        }
        // $this->set(compact('clube'));
        // $this->render('detalle');
        return $this->redirect(['action'=>'index']);
    }

    public function delete($id) {
        $template = $this->FormTemplates->get($id);
        if($this->FormTemplates->delete($template)) {
            $this->Flash->success(__('Template deleted successfully.'));
        } else {
            $this->Flash->error(__('Error deleting template.'));
        }
        return $this->redirect(['action'=>'index']);
    }

}