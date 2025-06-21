<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CleaningServicePricingSeeder extends Seeder
{
   public function run()
    {
        $data = [
            ['name' => '1 Bathroom', 'rate' => 40],
            ['name' => '2 Bathrooms', 'rate' => 60],
            ['name' => '3 Bathrooms', 'rate' => 80],
            ['name' => '4 Bathrooms', 'rate' => 100],
            ['name' => '5 Bathrooms', 'rate' => 120],
        ];

        DB::table('bathrooms')->insert($data);
    }
}
