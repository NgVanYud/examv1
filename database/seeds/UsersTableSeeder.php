<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'GV000001',
            'first_name' => 'Quan Tri',
            'last_name' => 'He Thong',
            'email' => 'quantrihethong@test.com',
            'password' => bcrypt('123@abc'), // secret
        ])->assignRole(config('access.roles_list.admin'));
        User::create([
            'username' => 'GV000002',
            'first_name' => 'Giao Vien',
            'last_name' => 'Ra De',
            'email' => 'giaovienrade@test.com',
            'password' => bcrypt('123@abc'), // secret
        ])->assignRole(config('access.roles_list.exams_maker'));
        (User::create([
            'username' => 'GV000003',
            'first_name' => 'Giao Vien',
            'last_name' => 'Coi Thi',
            'email' => 'giaoviencoithi@test.com',
            'password' => bcrypt('123@abc'), // secret
        ]))->assignRole(config('access.roles_list.protor'));
        (User::create([
            'username' => 'GV000004',
            'first_name' => 'Can Bo',
            'last_name' => 'Khao Thi',
            'email' => 'canbokhaothi@test.com',
            'password' => bcrypt('123@abc'), // secret
        ]))->assignRole(config('access.roles_list.curator'));
        (User::create([
            'username' => 'SVAT000005',
            'first_name' => 'Sinh',
            'last_name' => 'Vien',
            'email' => 'sinhvien01@test.com',
            'password' => bcrypt('123@abc'), // secret
        ]))->assignRole(config('access.roles_list.student'));
        (User::create([
            'username' => 'SVAT000006',
            'first_name' => 'Sinh',
            'last_name' => 'Vien',
            'email' => 'sinhvien02@test.com',
            'password' => bcrypt('123@abc'), // secret
        ]))->assignRole(config('access.roles_list.student'));
        (User::create([
            'username' => 'SVMM000007',
            'first_name' => 'Sinh',
            'last_name' => 'Vien',
            'email' => 'sinhvien03@test.com',
            'password' => bcrypt('123@abc'), // secret
        ]))->assignRole(config('access.roles_list.student'));

        factory(App\Models\User::class, 54)->create()->each(function($student) {
            $student->assignRole(config('access.roles_list.student'));
        });
    }
}
