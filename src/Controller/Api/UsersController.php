<?php
/**
 *
 */
namespace Dwdm\Users\Controller\Api;

use Dwdm\Users\Controller\AppController;
use Dwdm\Users\Controller\Component\NumberGeneratorComponent;
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
    use UserActionsTrait;

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
}
