<?php
/**
 * @copyright     Copyright (c) DowebDomobile (http://dowebdomobile.ru)
 */

namespace Dwdm\Users\Controller;

use Cake\Controller\Component\AuthComponent;
use Cake\Controller\Component\FlashComponent;
use Cake\Http\ServerRequest;
use Cake\Http\Response;
use Cake\Mailer\Email;
use Dwdm\Users\Model\Entity\User;
use Dwdm\Users\Model\Table\UsersTable;

/**
 * Class UsersActionsTrait
 * @package Dwdm\Users\Controller\Component
 *
 * @property ServerRequest $request
 * @property array $viewVars
 *
 * @property AuthComponent $Auth
 * @property FlashComponent $Flash
 *
 * @property UsersTable $Users
 *
 * @method Response redirect($url = null)
 * @method string referer($default = null, $local = false)
 */
trait UsersActionsTrait
{
    /**
     * Logging in user.
     *
     * @return Response|null
     */
    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error(__d('users', 'Unknown user'), ['key' => 'auth']);
            }
        }
    }

    /**
     * Logging out user.
     *
     * @return Response|null
     */
    public function logout()
    {
        return $this->redirect($this->request->is('post') ? $this->Auth->logout() : $this->referer('/', true));
    }

    /**
     * Restore user password.
     *
     * @param string|null $email
     * @param string|null $code
     * @return Response|null
     */
    public function restore($email = null, $code = null)
    {
        if ($this->request->is('post')) {
            /** @var User $user */
            $user = $this->Users->find()
                ->where(['email' => $this->request->getData('email'), 'is_active' => true])
                ->first();

            if (!($user instanceof User)) {
                $this->Flash->error(__d('users', 'Unknown user'));
                return;
            }

            $user->code = true;

            if ($this->Users->save($user)) {
                (new Email())->addTo($user->email)
                    ->setEmailFormat('html')
                    ->setSubject(__d('users', 'Restore password for {0}', [$this->viewVars['description']]))
                    ->setTemplate('Users.restore')
                    ->setViewVars($user->toArray() + ['code' => $user->code])
                    ->send();
                $this->Flash->success(__d('users', 'Restore code sent to email.'));
                return $this->redirect(['action' => 'login']);
            }
        } elseif (isset($email, $code)) {
            $user = $this->Users->find()->where(['email' => $email, 'code' => $code, 'is_active' => true])->first();

            if (!($user instanceof User)) {
                $this->Flash->error(__d('users', 'Unknown user'));
            }

            $user->code = false;
            $user->password = $password = rand(10000000, 99999999);
            if ($this->Users->save($user)) {
                $this->Flash->success(__d('users', 'Password changed to {0}', [$password]));
                return $this->redirect(['action' => 'login']);
            }
        }
    }
}