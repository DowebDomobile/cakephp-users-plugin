<?php
/**
 * @copyright     Copyright (c) DowebDomobile (http://dowebdomobile.ru)
 */

namespace Dwdm\Users\Test\TestCase\Controller\Api;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;
use Dwdm\Users\Model\Entity\Contact;
use Dwdm\Users\Model\Entity\User;
use Dwdm\Users\Model\Table\ContactsTable;
use Dwdm\Users\Model\Table\UsersTable;

/**
 * Class UsersControllerTest
 * @package Users\Test\TestCase\Controller\Api
 */
class UsersControllerTest extends IntegrationTestCase
{
    public $fixtures = ['plugin.dwdm/users.users', 'plugin.dwdm/users.contacts'];

    public function testSuccessRegister()
    {
        $this->post(
            '/users/api/users/register.json',
            ['contact' => $phone = '+79131231212']
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

    public function testRegisterGet()
    {
        $this->get('/users/api/users/register.json');

        $this->assertResponseCode(405);
    }

    public function testConfirm()
    {
        $this->post(
            '/users/api/users/confirm.json',
            ['contact' => $phone = '+70000000001', 'code' => 123456]
        );

        $this->assertResponseOk();

        $this->assertResponseEquals(
            json_encode(
                ['success' => true, 'message' => 'Contact confirmed.', 'errors' => []],
                JSON_PRETTY_PRINT
            )
        );

        /** @var ContactsTable $Contacts */
        $Contacts = TableRegistry::get('Contacts');

        $contact = $Contacts->get(1001, ['contain' => ['Users']]);

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertNull($contact->replace);
        $this->assertNull($contact->code);
        $this->assertEquals($phone, $contact->contact);
        $this->assertEquals('phone', $contact->type);

        $this->assertInstanceOf(User::class, $contact->user);
        $this->assertTrue($contact->user->is_active);
    }

    public function testLogin()
    {
        $this->post('/users/api/users/login.json', ['contact' => 'email@example.com', 'password' => 'password']);

        $this->assertResponseOk();

        $this->assertResponseEquals(
            json_encode(
                [
                    'success' => true,
                    'message' => 'User logged in.',
                    'errors' => [],
                    'user' => [
                        'id' => 1000,
                        'username' => 'username0',
                        'is_active' => true,
                        'contacts' => [[
                            'id' => 1000,
                            'type' => 'email',
                            'contact' => 'email@example.com',
                            'replace' => null,
                            'is_login' => true
                        ]]
                    ]
                ],
                JSON_PRETTY_PRINT
            )
        );
    }

    public function testRestore()
    {
        $this->post('/users/api/users/restore.json', ['contact' => '+70000000002']);

        $this->assertResponseOk();
        $this->assertResponseEquals(
            json_encode(
                ['success' => true, 'message' => 'Confirmation code was sent.', 'errors' => []],
                JSON_PRETTY_PRINT
            )
        );

        /** @var UsersTable $Users */
        $Users = TableRegistry::get('Users');

        $user = $Users->get(1002);

        $this->assertNotNull($user->code);
    }

    public function testUpdate()
    {
        $oldHash = $this->fixtureManager->loaded()['plugin.dwdm/users.users']->records[3]['password'];

        $this->post('/users/api/users/update.json', ['contact' => '+70000000003', 'code' => 123456]);

        $this->assertResponseOk();
        $this->assertResponseEquals(
            json_encode(
                ['success' => true, 'message' => 'New password was sent.', 'errors' => []],
                JSON_PRETTY_PRINT
            )
        );

        /** @var UsersTable $Users */
        $Users = TableRegistry::get('Users');

        $user = $Users->get(1002);

        $this->assertNull($user->code);
        $this->assertNotEquals($oldHash, $user->password);
    }
}