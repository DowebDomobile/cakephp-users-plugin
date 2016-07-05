<?php
/**
 * This file is part of the CakePHP(tm) Users plugin package.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) DowebDomobile (http://dowebdomobile.ru)
 * @link          https://github.com/DowebDomobile/cakephp-users-plugin CakePHP(tm) Users plugin project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace Users\Test\TestCase\Auth;

use Cake\Controller\ComponentRegistry;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\TestSuite\IntegrationTestCase;
use Users\Auth\ContactsAuthenticate;

/**
 * Class ContactsAuthenticateTest
 * @package Users\Test\TestCase\Auth
 */
class ContactsAuthenticateTest extends IntegrationTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
            'plugin.users.users',
            'plugin.users.contacts'
    ];

    /**
     * @var ContactsAuthenticate
     */
    public $Auth;

    public function setUp()
    {
        parent::setUp();
        $this->Auth = new ContactsAuthenticate(new ComponentRegistry());
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->Auth = null;
    }

    public function testAuthenticate()
    {
        $request = $this->createMock('\Cake\Network\Request');

        $request->expects($this->exactly(2))
                ->method('data')
                ->withConsecutive($this->equalTo('contact'), $this->equalTo('password'))
                ->will($this->returnValueMap([['contact', 'email@example.com'], ['password', 'password']]));

        $request->data['contact'] = 'email@example.com';
        $request->data['password'] = 'password';

        $response = $this->createMock('\Cake\Network\Response');

        /** @var Response $response */
        /** @var Request $request */
        $user = $this->Auth->authenticate($request, $response);

        $this->assertInternalType('array', $user, 'User should be authenticated');
    }
}
 