<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserTableSeeder::class);
        for($i=1; $i<=3; $i++){
            DB::table('warehouses')->insert([
                'name'      =>  'Warehouse_'.$i,
                'location'  =>  '',
                'owner'     =>  ''
            ]);
        }

    }
}
