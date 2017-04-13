<?php
/**
 *
 */
namespace Dwdm\Users\Controller\Api;

use Cake\Database\Connection;
use Cake\Database\Expression\IdentifierExpression;
use Cake\ORM\Query;
use Dwdm\Users\Controller\AppController;
use Dwdm\Users\Controller\Component\NumberGeneratorComponent;
use Dwdm\Users\Model\Entity\Contact;
use Dwdm\Users\Model\Table\UsersTable;

/**
 * Users Controller
 *
 * @property UsersTable $Users
 * @property NumberGeneratorComponent $PasswordGenerator
 * @property NumberGeneratorComponent $CodeGenerator
 */
class UsersController extends AppController
{
    /**
     * Initialize controller for generators and action access.
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('CodeGenerator', ['className' => 'Dwdm/Users.NumberGenerator', 'length' => 3]);
        $this->loadComponent('PasswordGenerator', ['className' => 'Dwdm/Users.NumberGenerator', 'length' => 8]);

        $this->Auth->allow(['register', 'confirm', 'login', 'restore', 'update']);
    }

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

        $this->dispatchEvent('Controller.Users.beforeRegister', null, $this);

        $user = $this->Users->newEntity(
            [
                'password' => $password = $isActive ? $this->PasswordGenerator->run() : null,
                'is_active' => $isActive ? : null,
                'contacts' => [
                    [
                        'type' => $contactType,
                        'contact' => $isActive ? $this->request->getData('contact') : null,
                        'replace' => $isActive ? null : $this->request->getData('contact'),
                        'code' => $code = $isActive ? null : $this->CodeGenerator->run(),
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

        $this->dispatchEvent('Controller.Users.beforeConfirm', null, $this);

        $success = $this->Users->getConnection()->transactional(
            function (Connection $connection) use ($contactType) {
                $conditions = [
                    'type' => $contactType,
                    'replace' => $this->request->getData('contact'),
                    'code' => $this->request->getData('code')
                ];
                /** @var Contact $contact */
                $contact = $this->Users->Contacts->find()->where($conditions)->first();

                $fail = !$this->Users->Contacts->updateAll(
                    ['contact' => new IdentifierExpression('replace'), 'code' => null, 'replace' => null],
                    $conditions
                );

                $fail = $fail ?: !$this->Users->updateAll(
                    ['password' => $this->PasswordGenerator->run(), 'is_active' => true],
                    ['id' => $contact->user_id, 'password IS' => null]
                );

                return !$fail;
            }
        );

        if ($success) {
            $this->dispatchEvent('Controller.Users.afterConfirm', null, $this);
        }

        $message = $success ? __('Contact confirmed.') : __('Invalid contact.');

        $this->set(compact('success', 'message'));
    }

    /**
     * Login user.
     */
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

        $message = $success ? __('Confirmation code was sent.') : __('Invalid contact.');

        $errors = $contact->user->getErrors();

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
            $contact->user->password = $this->PasswordGenerator->run();
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

    /**
     * Log out authorized user.
     */
    public function logout()
    {
        $this->dispatchEvent('Controller.Users.beforeLogout', ['user' => $this->Auth->user()], $this);

        $this->Auth->logout();

        $this->dispatchEvent('Controller.Users.afterLogout', ['user' => $this->Auth->user()], $this);

        $this->set('message', __('Logged out.'));
    }
}
