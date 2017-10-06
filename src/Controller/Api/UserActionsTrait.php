<?php
/**
 * @copyright     Copyright (c) DowebDomobile (http://dowebdomobile.ru)
 */

namespace Dwdm\Users\Controller\Api;

use Cake\Controller\Component\AuthComponent;
use Cake\Database\Connection;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Http\ServerRequest;
use Cake\Network\Exception\ForbiddenException;
use Cake\ORM\Query;
use Dwdm\Users\Controller\Component\NumberGeneratorComponent;
use Dwdm\Users\Model\Entity\Contact;
use Dwdm\Users\Model\Table\UsersTable;

/**
 * Class UserActionsTrait
 * @package Dwdm\Users\Controller\Api
 *
 * @property UsersTable $Users
 * @property NumberGeneratorComponent $PasswordGenerator
 * @property NumberGeneratorComponent $CodeGenerator
 * @property AuthComponent $Auth
 * @property ServerRequest $request
 *
 * @method dispatchEvent($name, $data, $subject)
 */
trait UserActionsTrait
{

    /**
     * Register new user.
     */
    public function register()
    {
        $this->request->allowMethod('POST');

        /**
         * @var array|string $contactType
         * @todo move to config. Can be string if need single contact type or array for limit contact types from request
         */
        $contactType = 'phone';

        /**
         * @var bool $isActive
         * @todo move to config. True for create active user without confirmation. False for use confirmation step
         */
        $isActive = false;

        /**
         * @var bool $isGeneratePassword
         * @todo move to config. False for allow user custom password. True for generate password.
         */
        $isGeneratePassword = true;

        $this->dispatchEvent('Controller.Users.beforeRegister', null, $this);

        $data = [
            'contacts' => [
                ['type' => $contactType, 'replace' => $isActive ? null : $this->request->getData('contact')]
            ],
        ];

        if ($isGeneratePassword) {
            $data['password'] = $isActive ? $this->PasswordGenerator->run() : null;
        }

        $data += $this->request->getData();
        unset($data['contact']);

        $user = $this->Users->newEntity($data);

        $user->is_active = $isActive ? : null;
        $user->contacts[0]->contact = $isActive ? $this->request->getData('contact') : null;
        $user->contacts[0]->code = $code = $isActive ? null : $this->CodeGenerator->run();
        $user->contacts[0]->is_login = true;

        $success = (bool)$this->Users->save($user);

        if ($success) {
            $this->dispatchEvent(
                'Controller.Users.afterRegister',
                ['user' => $user, 'contact' => $user->contacts[0], 'password' => $data['password'], 'code' => $code],
                $this
            );
        }

        $errors = $user->getErrors();
        $message = $success ? __d('users', 'User successfully registered.') : __d('users','Please fix registration info.');

        $this->set(compact('success', 'message', 'errors'));
    }

    /**
     * Confirm contact.
     *
     * @todo Works fine for new registration only. For update contact or change contact needs fix.
     */
    public function confirm()
    {
        $this->request->allowMethod(['POST', 'PUT', 'PATCH']);

        /**
         * @var array|string $contactType
         * @todo move to config. Can be string if need single contact type or array for limit contact types from request
         */
        $contactType = 'phone';
        $password = null;

        $this->dispatchEvent('Controller.Users.beforeConfirm', null, $this);

        $conditions = [
            'Contacts.type' => $contactType,
            'Contacts.replace' => $this->request->getData('contact'),
            'Contacts.code' => $this->request->getData('code')
        ];
        /** @var Contact $contact */
        $contact = $this->Users->Contacts->find()->contain('Users')->where($conditions)->first();

        $success = false;
        if ($contact) {
            $contact->set('contact', $contact->get('replace'));
            $contact->set('code', null);
            $contact->set('replace', null);
            $success = (bool) $this->Users->Contacts->save($contact);
        }

        if ($success && empty($contact->user->password)) {
            $contact->user->set('password', $password = $this->PasswordGenerator->run());
            $contact->user->set('is_active', true);
            $success = (bool)$this->Users->save($contact->user);
        }

        if ($success) {
            $this->dispatchEvent(
                'Controller.Users.afterConfirm',
                ['contact' => $contact, 'user' => $contact->user, 'password' => $password],
                $this
            );
        }

        $message = $success ? __d('users', 'Contact confirmed.') : __d('users', 'Invalid contact.');

        $this->set(compact('success', 'message'));
    }

    /**
     * Login user.
     */
    public function login()
    {
        $this->request->allowMethod('POST');

        $this->dispatchEvent('Controller.Users.beforeLogin', null, $this);

        $user = false;
        try {
            $user = $this->Auth->identify();
        } catch (ForbiddenException $e) {
            $message = $e->getMessage();
        }

        if ($success = (bool)$user) {
            $message = __d('users', 'User logged in.');
            $this->Auth->setUser($user);
            $this->dispatchEvent('Controller.Users.afterLogin', ['user' => $user], $this);
        }

        $this->set(compact('success', 'message', 'user'));
    }

    /**
     * Request restore password.
     */
    public function restore()
    {
        $this->request->allowMethod(['POST', 'PUT', 'PATCH']);

        /**
         * @var array|string $contactType
         * @todo move to config. Can be string if need single contact type or array for limit contact types from request
         */
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
            $contact->user->code = $this->CodeGenerator->run();
            $success = (bool) $this->Users->save($contact->user);
        }

        if ($success) {
            $this->dispatchEvent('Controller.Users.afterRestore', ['contact' => $contact], $this);
        }

        $message = $success ? __d('users', 'Confirmation code was sent.') : __d('users', 'Invalid contact.');

        $errors = isset($contact->user) ? $contact->user->getErrors() : [];

        $this->set(compact('success', 'message', 'errors'));
    }

    /**
     * Update password requested by restore action.
     */
    public function update()
    {
        $this->request->allowMethod(['POST', 'PUT', 'PATCH']);

        /**
         * @var array|string $contactType
         * @todo move to config. Can be string if need single contact type or array for limit contact types from request
         */
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
            $contact->user->password = $password = $this->PasswordGenerator->run();
            $contact->user->code = null;
            $success = (bool) $this->Users->save($contact->user);
        }

        if ($success) {
            $this->dispatchEvent('Controller.Users.afterUpdate', compact('contact', 'password'), $this);
        }

        $message = $success ? __d('users', 'New password was sent.') : __d('users', 'Invalid contact or code.');

        $errors = isset($contact->user) ? $contact->user->getErrors() : [];

        $this->set(compact('success', 'message', 'errors'));
    }

    /**
     * Log out authorized user.
     */
    public function logout()
    {
        $this->dispatchEvent('Controller.Users.beforeLogout', ['user' => $this->Auth->user()], $this);

        $this->Auth->logout();

        $this->dispatchEvent('Controller.Users.afterLogout', ['user' => $this->Auth->user()], $this);

        $this->set('message', __d('users', 'Logged out.'));
    }
}