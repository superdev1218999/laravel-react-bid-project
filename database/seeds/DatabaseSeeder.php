<?php

use Database\Seeders\BidComponentSeeder;
use Database\Seeders\BidLineItemSeeder;
use Database\Seeders\BidSeeder;
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
        $this->call(UserSeeder::class);
        $this->call(BidSeeder::class);
        $this->call(BidComponentSeeder::class);
        $this->call(BidLineItemSeeder::class);
    }
}
