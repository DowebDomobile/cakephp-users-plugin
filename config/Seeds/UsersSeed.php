<?php
use Phinx\Seed\AbstractSeed;

/**
 * Users seed.
 */
class UsersSeed extends AbstractSeed
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
                'email' => 'admin@example.com',
                'password' => (new \Cake\Auth\DefaultPasswordHasher())->hash('DefaultAdminPassword'),
        ]];

        $table = $this->table('users');
        $table->insert($data)->save();
    }
}
