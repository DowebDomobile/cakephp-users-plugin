<?php
/**
 *
 */
namespace Dwdm\Users\Controller\Api;

use Cake\Database\Connection;
use Cake\Database\Expression\IdentifierExpression;
use Cake\ORM\Query;
use Dwdm\Users\Controller\AppController;
use Dwdm\Users\Model\Entity\Contact;
use Dwdm\Users\Model\Table\UsersTable;

/**
 * Users Controller
 *
 * @property UsersTable $Users
 */
class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->Auth->allow(['register', 'confirm', 'login', 'restore', 'update']);
    }

    public function register()
    {
        $this->request->allowMethod('POST');

        /* @todo configure next variables */
        $passwordGenerator = $codeGenerator = function() {
            return rand(100000, 999999);
        };
        $contactType = 'phone';
        $isActive = false;

        $this->dispatchEvent('Controller.Users.beforeRegister', null, $this);

        $user = $this->Users->newEntity(
            [
                'password' => $password = $isActive ? $passwordGenerator() : null,
                'is_active' => $isActive,
                'contacts' => [
                    [
                        'type' => $contactType,
                        'replace' => $this->request->getData('contact'),
                        'code' => $code = $codeGenerator(),
                        'is_login' => true,
                    ]
                ],
            ]
        );

        $success = (bool)$this->Users->save($user);

        if ($success) {
            $this->dispatchEvent(
                'Controller.Users.afterRegister',
                ['user' => $user, 'password' => $password, 'code' => $code],
                $this
            );
        }

        $errors = $user->getErrors();
        $message = $success ? __('User successfully registered.') : __('Please fix registration info.');

        $this->set(compact('success', 'message', 'errors'));
    }

    public function confirm()
    {
        $this->request->allowMethod(['POST', 'PUT', 'PATCH']);

        $contactType = 'phone';

        $this->dispatchEvent('Controller.Users.beforeConfirm', null, $this);

        $success = $this->Users->getConnection()->transactional(function (Connection $connection) use ($contactType) {
                $conditions = [
                    'type' => $contactType,
                    'replace' => $this->request->getData('contact'),
                    'code' => $this->request->getData('code')
                ];
                /** @var Contact $contact */
                $contact = $this->Users->Contacts->find()->where($conditions)->first();

                $fail = !$this->Users->Contacts->updateAll(
                    ['contact' => new IdentifierExpression('replace'), 'code' => null, 'replace' => null], $conditions
                );

                $fail = $fail ? : !$this->Users->updateAll(['is_active' => true], ['id' => $contact->user_id]);

                return !$fail;
            });

        if ($success) {
            $this->dispatchEvent('Controller.Users.afterConfirm', null, $this);
        }

        $message = $success ? __('Contact confirmed.') : __('Invalid contact.');

        $this->set(compact('success', 'message'));
    }

    public function login()
    {
        $this->request->allowMethod('POST');

        $this->dispatchEvent('Controller.Users.beforeLogin', null, $this);

        $user = $this->Auth->identify();
        $success = (bool)$user;
        $message = $success ? __('User logged in.') : __('Invalid contact or password.');

        if ($success) {
            $this->dispatchEvent('Controller.Users.afterLogin', ['user' => $user], $this);
        }

        $this->set(compact('success', 'message', 'user'));
    }

    public function restore()
    {
        $this->request->allowMethod(['POST', 'PUT', 'PATCH']);

        /* @todo get generator from config */
        $codeGenerator = function() {
            return rand(100000, 999999);
        };
        $contactType = 'phone';

        $this->dispatchEvent('Controller.Users.beforeRestore', null, $this);

        /** @var Contact $contact */
        $contact = $this->Users->Contacts->find()
            ->contain(
                [
                    'Users' => function (Query $q) {
                        return $q->where(['Users.is_active' => true]);
                    }
                ]
            )
            ->where(['type' => $contactType, 'contact' => $this->request->getData('contact')])
            ->first();

        $success = (bool) $contact;

        if ($success) {
            $contact->user->code = $codeGenerator();
            $success = (bool) $this->Users->save($contact->user);
        }

        if ($success) {
            $this->dispatchEvent('Controller.Users.afterRestore', ['contact' => $contact], $this);
        }

        $message = $success ? __('Confirmation code was sent.') : __('Invalid contact.');

        $errors = $contact->user->getErrors();

        $this->set(compact('success', 'message', 'errors'));
    }

    public function update()
    {
        $this->request->allowMethod(['POST', 'PUT', 'PATCH']);

        /* @todo get generator from config */
        $passwordGenerator = function() {
            return rand(100000, 999999);
        };
        $contactType = 'phone';

        $this->dispatchEvent('Controller.Users.beforeUpdate', null, $this);

        $code = $this->request->getData('code');

        /** @var Contact $contact */
        $contact = $this->Users->Contacts->find()
            ->contain(
                [
                    'Users' => function (Query $q) use ($code) {
                        return $q->where(['Users.is_active' => true, 'Users.code' => $code]);
                    }
                ]
            )
            ->where(['type' => $contactType, 'contact' => $this->request->getData('contact')])
            ->first();

        $success = (bool) $contact;

        if ($success) {
            $contact->user->password = $passwordGenerator();
            $contact->user->code = null;
            $success = (bool) $this->Users->save($contact->user);
        }

        if ($success) {
            $this->dispatchEvent('Controller.Users.afterUpdate', ['contact' => $contact], $this);
        }

        $message = $success ? __('New password was sent.') : __('Invalid contact or code.');

        $errors = $contact->user->getErrors();

        $this->set(compact('success', 'message', 'errors'));
    }

    public function logout()
    {
        $this->dispatchEvent('Controller.Users.beforeLogout', ['user' => $this->Auth->user()], $this);

        $this->Auth->logout();

        $this->dispatchEvent('Controller.Users.afterLogout', ['user' => $this->Auth->user()], $this);

        $this->set('message', __('Logged out.'));
    }
}
