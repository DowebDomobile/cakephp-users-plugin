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
 * @since         0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Migrations\AbstractMigration;

/**
 * Class CreateUsers
 */
class CreateUsers extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $this->table('users')
                ->addColumn('username', 'string', ['length' => 255, 'null' => true, 'default' => null])
                ->addColumn('password', 'string', ['length' => 60, 'null' => true, 'default' => null])
                ->addColumn('code', 'string', ['length' => 60, 'null' => true, 'default' => null])
                ->addColumn('is_active', 'boolean', ['null' => true, 'default' => null])
                ->addIndex(['username'], ['unique' => true])
                ->create();
    }
}
