<?php
/**
 * @copyright     Copyright (c) DowebDomobile (http://dowebdomobile.ru)
 */

namespace Dwdm\Users\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Utility\Hash;

/**
 * Class AppController
 * @package Users\Controller
 */
class AppController extends Controller
{
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent(
            'Auth',
            [
                'authenticate' => [
                    'Dwdm/Users.Contacts' => [
                        'contactTypes' => ['phone'],
                        'scope' => ['is_active' => true]
                    ]
                ],
                'authorize' => 'Controller',
                'unauthorizedRedirect' => !$this->request->is(['ajax', 'json'], null),
                'authError' => __('Forbidden'),
                'loginAction' => false,
            ]
        );
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        $this->set('success', Hash::get($this->viewVars, 'success', empty($this->viewVars['errors'])));
        $this->set('message', Hash::get($this->viewVars, 'message', ''));
        $this->set('errors', Hash::get($this->viewVars, 'errors', []));

        if (in_array($this->response->type(), ['application/json', 'application/xml'])) {
            $serialize = ['success', 'message', 'errors'];
            $this->set(
                '_serialize',
                Hash::merge($serialize, Hash::get($this->viewVars, '_serialize', array_keys($this->viewVars)))
            );
        }
    }

    public function isAuthorized($user)
    {
        return true;
    }
} 