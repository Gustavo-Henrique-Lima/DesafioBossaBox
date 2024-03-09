<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('tb_tags')->insert([
            ['name' => 'Organization', 'created_at' => Carbon::now()],
            ['name' => 'Planning', 'created_at' => Carbon::now()],
            ['name' => 'Collaboration', 'created_at' => Carbon::now()],
            ['name' => 'Writing', 'created_at' => Carbon::now()],
            ['name' => 'Api', 'created_at' => Carbon::now()],
            ['name' => 'Json', 'created_at' => Carbon::now()],
            ['name' => 'Schema', 'created_at' => Carbon::now()],
            ['name' => 'Node', 'created_at' => Carbon::now()],
            ['name' => 'Github', 'created_at' => Carbon::now()],
            ['name' => 'Rest', 'created_at' => Carbon::now()],
            ['name' => 'Web', 'created_at' => Carbon::now()],
            ['name' => 'Frameword', 'created_at' => Carbon::now()],
            ['name' => 'Http2', 'created_at' => Carbon::now()],
            ['name' => 'Https', 'created_at' => Carbon::now()],
            ['name' => 'Localhost', 'created_at' => Carbon::now()],
        ]);
    }
}
