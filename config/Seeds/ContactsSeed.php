<?php
use Migrations\AbstractSeed;

/**
 * Contacts seed.
 */
class ContactsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [[
            'user_id' => 1,
            'type' => 'email',
            'contact' => 'admin@example.com',
            'is_login' => true,
        ]];

        $table = $this->table('contacts');
        $table->insert($data)->save();
    }
}
