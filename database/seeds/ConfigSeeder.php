<?php

use App\Config;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Config::truncate();
        Config::create([
            'name'  =>  'app_name',
            'value' =>  'Horte'
        ]);
    }
}
