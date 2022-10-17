<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Bid;

class BidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bid::truncate();
        Bid::create([
            'ref_id' => 'huk4s55z',
            'title' => 'Bid Name',
            'cost' => 11700,
            'price' => 14625,
            'config' => '{}',
            'actual_cost' => null,
            'actual_cost_confidence_factor' => null,
        ]);
        $this->command->info('bids table seeded.');
    }
}
