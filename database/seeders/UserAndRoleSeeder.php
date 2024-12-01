<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class UserAndRoleSeeder extends Seeder
{
    
    public function __construct()
    {
        $this->run();
    }

    public function run()
    {
        
        $role = Role::create(['name' => 'admin']);
        
        // user
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $user->assignRole($role);
    }
}
