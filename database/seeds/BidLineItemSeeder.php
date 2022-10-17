<?php

namespace Database\Seeders;

use App\BidLineItem;
use Illuminate\Database\Seeder;

class BidLineItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BidLineItem::truncate();
        BidLineItem::create([
            'ref_id' => 'd13pen25f',
            'title' => 'Line item 1',
            'cost' => 100,
            'price' => 125,
            'config' => '{"order_index": 0}',
            'actual_cost' => null,
            'actual_cost_confidence_factor' => null,
        ]);
        BidLineItem::create([
            'ref_id' => 'd13fib40k',
            'title' => 'Line item 2',
            'cost' => 200,
            'price' => 250,
            'config' => '{"order_index": 0}',
            'actual_cost' => null,
            'actual_cost_confidence_factor' => null,
        ]);
        BidLineItem::create([
            'ref_id' => 'd13umo65p',
            'title' => 'Line item 3',
            'cost' => 300,
            'price' => 375,
            'config' => '{"order_index": 1}',
            'actual_cost' => null,
            'actual_cost_confidence_factor' => null,
        ]);
        BidLineItem::create([
            'ref_id' => 'd13kqc80u',
            'title' => 'Line item 4',
            'cost' => 500,
            'price' => 625,
            'config' => '{"order_index": 2}',
            'actual_cost' => null,
            'actual_cost_confidence_factor' => null,
        ]);
        BidLineItem::create([
            'ref_id' => 'd13aup05a',
            'title' => 'Line item 5',
            'cost' => 800,
            'price' => 1000,
            'config' => '{"order_index": 0}',
            'actual_cost' => null,
            'actual_cost_confidence_factor' => null,
        ]);
        BidLineItem::create([
            'ref_id' => 'd13pyd20f',
            'title' => 'Line item 6',
            'cost' => 1300,
            'price' => 1625,
            'config' => '{"order_index": 1}',
            'actual_cost' => null,
            'actual_cost_confidence_factor' => null,
        ]);
        BidLineItem::create([
            'ref_id' => 'd13fdq45k',
            'title' => 'Line item 7',
            'cost' => 2100,
            'price' => 2625,
            'config' => '{"order_index": 0}',
            'actual_cost' => null,
            'actual_cost_confidence_factor' => null,
        ]);
        BidLineItem::create([
            'ref_id' => 'd13uhe60p',
            'title' => 'Line item 8',
            'cost' => 3400,
            'price' => 4250,
            'config' => '{"order_index": 1}',
            'actual_cost' => null,
            'actual_cost_confidence_factor' => null,
        ]);
        BidLineItem::create([
            'ref_id' => 'd13klr85u',
            'title' => 'Line item 9',
            'cost' => 2000,
            'price' => 2500,
            'config' => '{"order_index": 0}',
            'actual_cost' => null,
            'actual_cost_confidence_factor' => null,
        ]);
        BidLineItem::create([
            'ref_id' => 'd13apf00a',
            'title' => 'Line item 10',
            'cost' => 1000,
            'price' => 1250,
            'config' => '{"order_index": 0}',
            'actual_cost' => null,
            'actual_cost_confidence_factor' => null,
        ]);
        $this->command->info('bid_line_items table seeded.');
    }
}
