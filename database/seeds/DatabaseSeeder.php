<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(UsersTableSeeder::class);
        $this->call(users::class);
        $this->call(articles_category::class);
     //   $this->call(articles::class);
       // $this->call(articles_category_list::class);
      //  $this->call(articles_comment::class);
    }
}
