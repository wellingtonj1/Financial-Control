<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EssentialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->comment("Inserindo as sql essenciais do sistema.");

        //Executa as query no banco.
        DB::unprepared(file_get_contents('database/sql/essentials.sql'));
    }
}
