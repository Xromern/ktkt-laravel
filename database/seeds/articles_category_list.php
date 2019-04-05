<?php

use Illuminate\Database\Seeder;

class articles_category_list extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category_list = [];

        for($i = 1;$i<99;$i++){
            $categories_amount = rand(1,3);
            for($j = 0;$j<$categories_amount;$j++){
                $category_list[] = [
                    'article_id'    => $i,
                    'category_id'   => rand(1,10),
                ];
            }

        }
        \Illuminate\Support\Facades\DB::table('articles_category_list')->insert($category_list);
    }
}
