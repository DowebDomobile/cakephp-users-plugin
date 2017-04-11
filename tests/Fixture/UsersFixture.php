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
namespace Dwdm\Users\Test\Fixture;

use Cake\Auth\DefaultPasswordHasher;
use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 *
 */
class UsersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'username' => ['type' => 'string', 'length' => 255, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'password' => ['type' => 'string', 'length' => 60, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'fixed' => null],
        'code' => ['type' => 'string', 'length' => 60, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'is_active' => ['type' => 'boolean', 'length' => null, 'default' => 1, 'null' => false, 'comment' => null, 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'users_username' => ['type' => 'unique', 'columns' => ['username'], 'length' => []],
        ],
    ];
    // @codingStandardsIgnoreEnd

    public function init()
    {
        $this->records = [
                [
                        'id' => 1000,
                        'username' => 'username0',
                        'password' => (new DefaultPasswordHasher())->hash('password'),
                        'code' => null,
                        'is_active' => 1
                ],
                [
                        'id' => 1001,
                        'username' => 'username1',
                        'password' => (new DefaultPasswordHasher())->hash('password'),
                        'code' => null,
                        'is_active' => 1
                ],
        ];;

        parent::init();
    }
}
