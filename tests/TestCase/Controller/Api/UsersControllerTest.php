<?php
/**
 * @copyright     Copyright (c) DowebDomobile (http://dowebdomobile.ru)
 */

namespace Dwdm\Users\Test\TestCase\Controller\Api;

use Cake\Controller\Controller;
use Cake\Event\EventList;
use Cake\Event\EventManager;
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
    /** @var array  */
    public $fixtures = ['plugin.dwdm/users.users', 'plugin.dwdm/users.contacts'];

    /** @var EventManager */
    public $eventManager;

    public function setUp()
    {
        parent::setUp();
        $this->useHttpServer(false);
    }


    public function controllerSpy($event, $controller = null)
    {
        /** @var Controller|null $controller */
        if (!$controller) {
            $controller = $event->getSubject();
        }

        $this->eventManager = $controller->eventManager();
        $this->eventManager->setEventList(new EventList());

        parent::controllerSpy($event, $controller);
    }

    public function testRegisterSuccess()
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
        $Users = TableRegistry::get('Dwdm/Users.Users');

        /** @var User $user */
        $user = $Users->find('all', ['contain' => ['Contacts']])->where(['id' => 1])->first();

        $this->assertInstanceOf(User::class, $user);
        $this->assertCount(1, $user->contacts);
        $this->assertInstanceOf(Contact::class, $user->contacts[0]);
        $this->assertEquals($phone, $user->contacts[0]->replace);
        $this->assertNull($user->contacts[0]->contact);
        $this->assertNull($user->is_active);

        $this->assertEventFired('Controller.Users.beforeRegister', $this->eventManager);
        $this->assertEventFired('Controller.Users.afterRegister', $this->eventManager);
    }

    public function testRegisterShortPhone()
    {
        $this->post(
            '/users/api/users/register.json',
            ['contact' => $phone = '+7913123121']
        );

        $this->assertResponseOk();

        $this->assertResponseEquals(
            json_encode(
                [
                    'success' => false,
                    'message' => 'Please fix registration info.',
                    'errors' => ['contacts' => [['replace' => ['length' => 'The provided value is invalid']]]]
                ],
                JSON_PRETTY_PRINT
            )
        );
    }

    public function testRegisterDuplicate()
    {
        $this->post(
            '/users/api/users/register.json',
            ['contact' => $phone = '+70000000002']
        );

        $this->assertResponseOk();

        $this->assertResponseEquals(
            json_encode(
                [
                    'success' => false,
                    'message' => 'Please fix registration info.',
                    'errors' => ['contacts' => [['replace' => ['unique' => 'Contact already registered']]]]
                ],
                JSON_PRETTY_PRINT
            )
        );
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
        $Contacts = TableRegistry::get('Dwdm/Users.Contacts');

        $contact = $Contacts->get(1001, ['contain' => ['Users']]);

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertNull($contact->replace);
        $this->assertNull($contact->code);
        $this->assertEquals($phone, $contact->contact);
        $this->assertEquals('phone', $contact->type);

        $this->assertInstanceOf(User::class, $contact->user);
        $this->assertTrue($contact->user->is_active);
        $this->assertNotEquals(8, strlen($contact->user->password));

        $this->assertEventFired('Controller.Users.beforeConfirm', $this->eventManager);
        $this->assertEventFired('Controller.Users.afterConfirm', $this->eventManager);
    }

    public function testLogin()
    {
        $this->post('/users/api/users/login.json', ['contact' => 'email@example.com', 'password' => 'password']);

        $this->assertResponseOk();
        $this->assertSession(1000, 'Auth.User.id');
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

        $this->assertEventFired('Controller.Users.beforeLogin', $this->eventManager);
        $this->assertEventFired('Controller.Users.afterLogin', $this->eventManager);
    }

    public function testLoginInvalidContact()
    {
        $this->post('/users/api/users/login.json', ['contact' => 'invalid', 'password' => 'password']);

        $this->assertResponseOk();
        $this->assertSession(null, 'Auth.User.id');
        $this->assertResponseEquals(
            json_encode(
                [
                    'success' => false,
                    'message' => 'Contact not registered in system.',
                    'errors' => [],
                    'user' => false,
                ],
                JSON_PRETTY_PRINT
            )
        );

        $this->assertEventFired('Controller.Users.beforeLogin', $this->eventManager);
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
        $Users = TableRegistry::get('Dwdm/Users.Users');

        $user = $Users->get(1002);

        $this->assertNotNull($user->code);

        $this->assertEventFired('Controller.Users.beforeRestore', $this->eventManager);
        $this->assertEventFired('Controller.Users.afterRestore', $this->eventManager);
    }

    public function testRestoreEmpty()
    {
        $this->post('/users/api/users/restore.json');

        $this->assertResponseOk();
        $this->assertResponseContains('"success": false,');
        $this->assertResponseContains('"message": "Invalid contact.",');
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
        $Users = TableRegistry::get('Dwdm/Users.Users');

        $user = $Users->get(1002);

        $this->assertNull($user->code);
        $this->assertNotEquals($oldHash, $user->password);

        $this->assertEventFired('Controller.Users.beforeUpdate', $this->eventManager);
        $this->assertEventFired('Controller.Users.afterUpdate', $this->eventManager);
    }

    public function testUpdateEmpty()
    {
        $this->post('/users/api/users/update.json');

        $this->assertResponseOk();
        $this->assertResponseContains('"success": false,');
        $this->assertResponseContains('"message": "Invalid contact or code.",');
    }

    public function testLogout()
    {
        $this->session(['Auth' => ['User' => ['id' => 1000]]]);

        $this->post('/users/api/users/logout.json');

        $this->assertResponseOk();

        $this->assertEventFired('Controller.Users.beforeLogout', $this->eventManager);
        $this->assertEventFired('Controller.Users.afterLogout', $this->eventManager);
    }
}