<?php

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      (Student::create([
        'username' => 'EX012019-ST000001',
        'code' => 'ST000001',
        'is_actived' => 1,
        'first_name' => 'Sinh',
        'last_name' => 'Vien',
        'password' => bcrypt('123@abc'), // secret
      ]))->assignRole(config('access.roles_list.student'));
      (Student::create([
        'username' => 'EX012019-ST000002',
        'code' => 'ST000002',
        'is_actived' => 1,
        'first_name' => 'Sinh',
        'last_name' => 'Vien',
        'password' => bcrypt('123@abc'), // secret
      ]))->assignRole(config('access.roles_list.student'));
      (Student::create([
        'username' => 'EX012019-ST000003',
        'code' => 'ST000003',
        'is_actived' => 1,
        'first_name' => 'Sinh',
        'last_name' => 'Vien',
        'password' => bcrypt('123@abc'), // secret
      ]))->assignRole(config('access.roles_list.student'));

      factory(Student::class, 54)->create()->each(function($student) {
        $student->assignRole(config('access.roles_list.student'));
      });
    }
}
