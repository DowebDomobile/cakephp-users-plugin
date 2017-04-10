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

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ContactsFixture
 *
 */
class ContactsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'user_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'type' => ['type' => 'string', 'length' => 20, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'fixed' => null],
        'contact' => ['type' => 'string', 'length' => 255, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'replace' => ['type' => 'string', 'length' => 255, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'code' => ['type' => 'string', 'length' => 60, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'is_login' => ['type' => 'boolean', 'length' => null, 'default' => 0, 'null' => false, 'comment' => null, 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'contacts_user_id_type_contact' => ['type' => 'unique', 'columns' => ['user_id', 'type', 'contact'], 'length' => []],
            'contacts_user_id' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'id'], 'update' => 'noAction', 'delete' => 'cascade', 'length' => []],
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1000,
            'user_id' => 1000,
            'type' => 'email',
            'contact' => 'email@example.com',
            'replace' => null,
            'code' => null,
            'is_login' => true
        ],
    ];
}
