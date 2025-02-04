<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddTaskAction extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menu_actions')->insert([
            [
                'menuId' => '17',
                'actionName' => 'Followup',
                'route' => 'task.index'
            ]
        ]);
    }
}
