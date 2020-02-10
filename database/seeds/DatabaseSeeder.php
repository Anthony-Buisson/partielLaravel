<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $userIds = [];
        for($i = 0; $i < 10; $i++){
            array_push($userIds,DB::table('users')->insertGetId([
                'name' => Str::random(10),
                'email' => Str::random(10).'@gmail.com',
                'password' => Hash::make('password'),
            ]));
        }
        $roleIds = [];
        foreach (['administrateur','utilisateur','modérateur','rédacteur'] as $role){
            array_push($roleIds,DB::table('roles')->insertGetId([
                'name' => $role,
            ]));
        }
        $permissionIds = [];
        foreach (['user.read','user.update','user.delete','user.create','article_read','article_update','article_delete','article_create'] as $permission){
            array_push($permissionIds,DB::table('permissions')->insertGetId([
                'name' => $permission,
            ]));
        }

        shuffle($userIds);
        shuffle($roleIds);
        shuffle($permissionIds);

        for($i = 0; $i < 6; $i++){
            DB::table('role_user')->insert([
                'role_id' => $roleIds[rand(0,count($roleIds)-1)],
                'user_id' => $userIds[rand(0,count($userIds)-1)],
            ]);
            DB::table('permission_role')->insert([
                'role_id' => $roleIds[rand(0,count($roleIds)-1)],
                'permission_id' => $permissionIds[rand(0,count($permissionIds)-1)],
            ]);
        }

    }
}
