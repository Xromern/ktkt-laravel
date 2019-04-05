<?php

use Illuminate\Database\Seeder;

class articles_comment extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comments = [];

        for($i = 1; $i<100; $i++){
            $comments_amount = rand(1,12);
            for($j = 0; $j < $comments_amount; $j++){
                $comments[] = [
                    'article_id'   => $i,
                    'users_id'      => rand(1,5),
                    'content'       => str_random(200),

                ];
            }
        }
        \Illuminate\Support\Facades\DB::table('articles_comment')->insert($comments);

    }
}
