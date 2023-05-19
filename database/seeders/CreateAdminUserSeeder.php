<?php
namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{

public function run()
{

         $user = User::create([
        'name' => 'alfatih',
        'email' => 'alfatihalmokashfi47@yahoo.com',
        'password' => bcrypt('123456'),
        'roles_name' => ["user"],
        'Status' => 'مفعل',
        ]);


        $role = Role::create(['name' => 'owner']);


        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);


}
}
