<?php
use Migrations\AbstractMigration;

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
                ->addColumn('email', 'string', ['length' => 255, 'null' => false, 'default' => null])
                ->addColumn('password', 'string', ['length' => 60, 'null' => false, 'default' => null])
                ->addColumn('code', 'string', ['length' => 60, 'null' => true, 'default' => null])
                ->addColumn('is_active', 'boolean', ['null' => false, 'default' => true])
                ->addIndex(['email'], ['unique' => true])
                ->create();
    }
}
