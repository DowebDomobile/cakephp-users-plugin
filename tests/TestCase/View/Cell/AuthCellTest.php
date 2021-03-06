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
namespace Dwdm\Users\Test\TestCase\View\Cell;

use Cake\TestSuite\TestCase;
use Dwdm\Users\View\Cell\AuthCell;

/**
 * User\View\Cell\LogoutCell Test Case
 */
class AuthCellTest extends TestCase
{

    /**
     * Request mock
     *
     * @var \Cake\Network\Request|\PHPUnit_Framework_MockObject_MockObject
     */
    public $request;

    /**
     * Response mock
     *
     * @var \Cake\Network\Response|\PHPUnit_Framework_MockObject_MockObject
     */
    public $response;

    /**
     * Test subject
     *
     * @var \Users\View\Cell\AuthCell
     */
    public $Logout;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->request = $this->createMock('Cake\Network\Request');
        $this->response = $this->createMock('Cake\Network\Response');
        $this->Logout = new AuthCell($this->request, $this->response);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Logout);

        parent::tearDown();
    }

    /**
     * Test display method
     *
     * @return void
     */
    public function testDisplayLoggedInUser()
    {
        $Auth = $this->createMock('Cake\Controller\Component\AuthComponent');
        $Auth->expects($this->once())->method('user')->will($this->returnValue(true));

        /** @var Cake\Controller\Component\AuthComponent $Auth */
        $this->Logout->display($Auth, ['class' => 'passed']);

        $this->assertEquals('logout', $this->Logout->template, 'When user is logged in template must be logout');
        $this->assertEquals($Auth, $this->Logout->viewVars['Auth']);
        $this->assertEquals('passed', $this->Logout->viewVars['class']);
        $this->assertCount(2, $this->Logout->viewVars);
    }

    public function testDisplayLoggedOutUser()
    {
        $Auth = $this->createMock('Cake\Controller\Component\AuthComponent');
        $Auth->expects($this->once())->method('user')->will($this->returnValue(false));

        /** @var Cake\Controller\Component\AuthComponent $Auth */
        $this->Logout->display($Auth);

        $this->assertEquals('login', $this->Logout->template, 'When user is logged in template must be login');
        $this->assertEquals($Auth, $this->Logout->viewVars['Auth']);
        $this->assertCount(1, $this->Logout->viewVars);
    }
}
