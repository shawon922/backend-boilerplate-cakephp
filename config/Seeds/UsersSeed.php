<?php
use Migrations\AbstractSeed;
use Cake\Auth\DefaultPasswordHasher;

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
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'username' => 'testuser',
            'password' => (new DefaultPasswordHasher)->hash('123456'),
            'name' => 'Test User',
        ];

        $table = $this->table('users');
        $table->insert($data)->save();
    }
}
