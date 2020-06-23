<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employees')->insert([
            'id' => 'test1234',
            'login' => 'testuser1234',
            'name' => 'Test User',
            'salary' => 12999.99
        ]);
        factory(App\Employee::class, 50)->create();
    }
}
