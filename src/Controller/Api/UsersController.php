<?php
namespace Dwdm\Users\Controller\Api;

use Dwdm\Users\Controller\AppController;
use Dwdm\Users\Model\Table\UsersTable;

/**
 * Users Controller
 *
 * @property UsersTable $Users
 */
class UsersController extends AppController
{
    public function registration()
    {
        $this->request->allowMethod('POST');

        /* @todo configure next variables */
        $passwordGenerator = $codeGenerator = function() {
            return rand(100000, 999999);
        };
        $contactType = 'phone';
        $isActive = false;

        $user = $this->Users->newEntity(
            [
                'password' => $passwordGenerator(),
                'is_active' => $isActive,
                'contacts' => [
                    [
                        'type' => $contactType,
                        'replace' => $this->request->getData('contact'),
                        'code' => $codeGenerator(),
                        'is_login' => true,
                    ]
                ],
            ]
        );

        $success = (bool)$this->Users->save($user);

        $errors = $user->getErrors();
        $message = $success ? __('User successfully registered.', null) : __('Please fix registration info.', null);

        $this->set(compact('success', 'message', 'errors'));
    }

    public function login()
    {

    }
}
