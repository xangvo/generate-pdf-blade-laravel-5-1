<?php
use Illuminate\Database\Seeder;

class UserSingleRowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        factory(App\User::class)->create([
            'name' => 'Aleks',
            'email' => 'testmysendmail@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('123456'),
        ]);
    }
}
