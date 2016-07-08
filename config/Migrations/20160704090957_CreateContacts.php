<?php
use Migrations\AbstractMigration;

class CreateContacts extends AbstractMigration
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
        $this->table('contacts')
                ->addColumn('user_id', 'integer', ['length' => 11, 'default' => null, 'null' => false])
                ->addColumn('type', 'string', ['length' => 20, 'default' => null, 'null' => false])
                ->addColumn('contact', 'string', ['length' => 255, 'default' => null, 'null' => true])
                ->addColumn('replace', 'string', ['length' => 255, 'default' => null, 'null' => true])
                ->addColumn('code', 'string', ['length' => 60, 'default' => null, 'null' => true])
                ->addColumn('is_login', 'boolean', ['default' => false, 'null' => false])
                ->addIndex(['type', 'contact'], ['unique' => true])
                ->addForeignKey('user_id', 'users', 'id', ['delete' => 'cascade'])
                ->create();
    }
}
