<?php

use Illuminate\Database\Seeder;

class articles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $articles = [];
        for($i = 1;$i<122;$i++){
            $user_id = rand(1,5);
            $articles[] = [
                'user_id'       => $user_id,
                'title'         => str_random(20),
                'excerpt'       => str_random(40),
                'content_html'  => str_random(400),
                'created_at'    =>now(),
                'updated_at'    =>now(),

            ];
        }
        \Illuminate\Support\Facades\DB::table('articles')->insert($articles);


    }

}
