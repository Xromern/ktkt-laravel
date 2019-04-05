<?php

use Illuminate\Database\Seeder;

class articles_category extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $categories[] = [
        'title'=> 'Без категории',
        'description'=> 'Без категории',
        ];

        for($i=1; $i<14; $i++) {
            $categories[] = [
                'title'=> str_random(12),
                'description'=>str_random(12),
            ];
        }

        \Illuminate\Support\Facades\DB::table('articles_category')->insert($categories);
    }
}
