<?php
namespace Dwdm\Users\Controller\Api;

use Dwdm\Users\Controller\AppController;

/**
 * Users Controller
 *
 * @property \Users\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    public function registration()
    {
        $this->set('_serialize', true);
    }

    public function login()
    {

    }
}
