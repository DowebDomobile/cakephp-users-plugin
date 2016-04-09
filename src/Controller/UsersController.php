<?php
/**
 *
 */
namespace User\Controller;

use App\Controller\AppController;
use Cake\Mailer\Email;
use User\Exception\NotInitializedComponentException;
use User\Model\Entity\User;

/**
 * Users Controller
 *
 * @property \User\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    /**
     * @throws \User\Exception\NotInitializedComponentException
     */
    public function initialize()
    {
        parent::initialize();

        if (!isset($this->Auth)) {
            throw new NotInitializedComponentException(['AuthComponent']);
        }

        $this->Auth->allow(['login', 'restore']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, ['contain' => []]);

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__d('users', 'The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('users', 'The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, ['contain' => []]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__d('users', 'The user has been saved.'));
                return $this->redirect($this->referer(['action' => 'index'], true));
            } else {
                $this->Flash->error(__d('users', 'The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__d('users', 'The user has been deleted.'));
        } else {
            $this->Flash->error(__d('users', 'The user could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Logging in user.
     *
     * @return \Cake\Network\Response|null
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
     * @return \Cake\Network\Response|null
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
     * @return \Cake\Network\Response|null
     */
    public function restore($email = null, $code = null)
    {
        if ($this->request->is('post')) {
            /** @var User $user */
            $user = $this->Users->find()->where(['email' => $this->request->data['email'], 'is_active' => true])->first();

            if (!($user instanceof User)) {
                $this->Flash->error(__d('users', 'Unknown user'));
                return;
            }

            $user->code = true;

            if ($this->Users->save($user)) {
                (new Email())->addTo($user->email)
                        ->emailFormat('html')
                        ->subject(__d('users', 'Restore password for {0}', [$this->viewVars['description']]))
                        ->template('User.restore')
                        ->viewVars($user->toArray() + ['code' => $user->code])
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