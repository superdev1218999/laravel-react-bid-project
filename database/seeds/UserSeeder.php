<?php
use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Generate Users.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        // Create primary user account for testing.
        User::create([
            'name' => 'User Test',
            'email' => 'user@test.dev',
            'password' => bcrypt('password'),
        ]);

        $this->command->info('users table seeded.');
    }
}
