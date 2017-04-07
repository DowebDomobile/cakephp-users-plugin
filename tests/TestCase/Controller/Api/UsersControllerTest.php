<?php
/**
 * @copyright     Copyright (c) DowebDomobile (http://dowebdomobile.ru)
 */

namespace Dwdm\Users\Test\TestCase\Controller\Api;

use Cake\TestSuite\IntegrationTestCase;

/**
 * Class UsersControllerTest
 * @package Users\Test\TestCase\Controller\Api
 */
class UsersControllerTest extends IntegrationTestCase
{
    public function testRegistration()
    {
        $this->configRequest(['headers' => ['Content-type' => 'application/json']]);

        $this->post(
            '/users/api/users/registration.json',
            ['phone' => '+79131231212', 'firstname' => 'Vasil', 'lastname' => 'Pupkin']
        );

        $this->assertResponseOk();
    }
}