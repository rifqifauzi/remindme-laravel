<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Traits\Seeder\ImportDatasets;

class UserSeeder extends Seeder
{
    use ImportDatasets;

    protected $model = User::class;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        User::create([
//            'nama' => 'Admin',
//            'email' => 'admin@admin.com',
//            'password' => bcrypt('password'),
//            'remember_token' => null,
//            'current_role_id' => ADMIN
//        ]);
    }
}
