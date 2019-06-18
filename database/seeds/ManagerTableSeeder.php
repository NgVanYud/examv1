<?php

use Illuminate\Database\Seeder;
use App\Models\Manager;

class ManagerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Manager::create([
        'username' => 'MN000001',
        'code' => 'MN000001',
        'is_actived' => 1,
        'first_name' => 'Quan Tri',
        'last_name' => 'He Thong',
        'email' => 'quantrihethong@test.com',
        'password' => bcrypt('123@abc'), // secret
      ])->assignRole(config('access.roles_list.admin'));
      Manager::create([
        'username' => 'MN000002',
        'code' => 'MN000002',
        'is_actived' => 1,
        'first_name' => 'Giao Vien',
        'last_name' => 'Ra De',
        'email' => 'giaovienrade@test.com',
        'password' => bcrypt('123@abc'), // secret
      ])->assignRole(config('access.roles_list.exams_maker'));
      (Manager::create([
        'username' => 'MN000003',
        'code' => 'MN000003',
        'is_actived' => 1,
        'first_name' => 'Giao Vien',
        'last_name' => 'Coi Thi',
        'email' => 'giaoviencoithi@test.com',
        'password' => bcrypt('123@abc'), // secret
      ]))->assignRole(config('access.roles_list.protor'));
      (Manager::create([
        'username' => 'MN000004',
        'code' => 'MN000004',
        'is_actived' => 1,
        'first_name' => 'Can Bo',
        'last_name' => 'Khao Thi',
        'email' => 'canbokhaothi@test.com',
        'password' => bcrypt('123@abc'), // secret
      ]))->assignRole(config('access.roles_list.curator'));
      Manager::create([
        'username' => 'MN000005',
        'code' => 'MN000005',
        'is_actived' => 1,
        'first_name' => 'Giao Vien',
        'last_name' => 'Ra De',
        'email' => 'MN000005@test.com',
        'password' => bcrypt('123@abc'), // secret
      ])->assignRole(config('access.roles_list.exams_maker'));
    }
}
