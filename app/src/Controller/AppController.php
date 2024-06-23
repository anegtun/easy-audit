<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Authentication.Authentication');

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');

        // Locale para comparacións
        setlocale(LC_COLLATE, 'es_ES.utf8');
    }



    /*public function beforeFilter(\Cake\Event\EventInterface $event) {
        // $this->Auth->allow(['index', 'view', 'display']);
    }*/
    
    /*public function isAuthorized($user) {
        return true;
    }*/

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    /*public function beforeRender(\Cake\Event\EventInterface $event) {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->getType(), array('application/json', 'application/xml'))
        ) {
            $this->set('_serialize', true);
        } else {
            Date::setToStringFormat('dd/MM/yyyy');
            FrozenDate::setToStringFormat('dd/MM/yyyy');
            $this->set('isMobile', $this->request->is('mobile'));
        }
    }*/
}
