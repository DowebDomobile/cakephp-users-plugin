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

namespace Dwdm\Users\Test\TestCase\Auth;

use Cake\Controller\ComponentRegistry;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\IntegrationTestCase;
use Dwdm\Users\Auth\ContactsAuthenticate;

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
            'plugin.dwdm/users.users',
            'plugin.dwdm/users.contacts'
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
        $request = $this->createMock(ServerRequest::class);

        $request->expects($this->any())
                ->method('getData')
                ->will($this->returnValueMap([['contact', null, 'email@example.com'], ['password', null, 'password']]));

        $response = $this->createMock(Response::class);

        /** @var ServerRequest $request */
        /** @var Response $response */
        $user = $this->Auth->authenticate($request, $response);

        $this->assertInternalType('array', $user, 'User should be authenticated');
    }
}
 