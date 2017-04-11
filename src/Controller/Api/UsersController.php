<?php
namespace Dwdm\Users\Controller\Api;

use Cake\Database\Expression\IdentifierExpression;
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

    public function confirm()
    {
        $this->request->allowMethod(['POST', 'PUT', 'PATCH']);

        $success = (bool)$this->Users->Contacts->updateAll(
            ['contact' => new IdentifierExpression('replace'), 'code' => null, 'replace' => null],
            ['replace' => $this->request->getData('contact'), 'code' => $this->request->getData('code')]
        );

        $message = $success ? __('Contact confirmed.', null) : __('Invalid contact.', null);

        $this->set(compact('success', 'message'));
    }

    public function login()
    {

    }
}
