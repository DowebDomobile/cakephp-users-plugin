<?php
/**
 * @copyright     Copyright (c) DowebDomobile (http://dowebdomobile.ru)
 */

namespace Dwdm\Users\Test\TestCase\Controller\Api;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;
use Dwdm\Users\Model\Entity\Contact;
use Dwdm\Users\Model\Entity\User;
use Dwdm\Users\Model\Table\UsersTable;

/**
 * Class UsersControllerTest
 * @package Users\Test\TestCase\Controller\Api
 */
class UsersControllerTest extends IntegrationTestCase
{
    public $fixtures = ['plugin.dwdm/users.users', 'plugin.dwdm/users.contacts'];

    public function testSuccessRegistration()
    {
        $this->configRequest(['headers' => ['Content-type' => 'application/json']]);

        $this->post(
            '/users/api/users/registration.json',
            json_encode(['contact' => $phone = '+79131231212'])
        );

        $this->assertResponseOk();

        $this->assertResponseEquals(
            json_encode(
                ['success' => true, 'message' => 'User successfully registered.', 'errors' => []],
                JSON_PRETTY_PRINT
            )
        );

        /** @var UsersTable $Users */
        $Users = TableRegistry::get('Users');

        $user = $Users->find('all', ['contain' => ['Contacts']])->where(['id' => 1])->first();

        $this->assertInstanceOf(User::class, $user);
        $this->assertCount(1, $user->contacts);
        $this->assertInstanceOf(Contact::class, $user->contacts[0]);
        $this->assertEquals($phone, $user->contacts[0]->replace);
    }

    public function testRegistrationGet()
    {
        $this->get('/users/api/users/registration.json');

        $this->assertResponseCode(405);
    }
}