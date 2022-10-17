<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\BidComponent;

class BidComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BidComponent::truncate();
        BidComponent::create([
            'ref_id' => 'ajkcs47e',
            'title' => 'Component 1',
            'cost' => 10700,
            'price' => 13375,
            'config' =>
                '{"is_nested": false, "components": [2,3], "line_items": [1], "order_index": 0, "parent_component_id": null}',
            'actual_cost' => null,
            'actual_cost_confidence_factor' => null,
        ]);
        BidComponent::create([
            'ref_id' => 'ajvfl84j',
            'title' => 'Component 2',
            'cost' => 1000,
            'price' => 1250,
            'config' =>
                '{"is_nested": true, "components": [], "line_items": [2,3,4], "order_index": 0, "parent_component_id": 1}',
            'actual_cost' => null,
            'actual_cost_confidence_factor' => null,
        ]);
        BidComponent::create([
            'ref_id' => 'ajgie21o',
            'title' => 'Component 3',
            'cost' => 9600,
            'price' => 12000,
            'config' =>
                '{"is_nested": true, "components": [4,5], "line_items": [5,6], "order_index": 1, "parent_component_id": 1}',
            'actual_cost' => null,
            'actual_cost_confidence_factor' => null,
        ]);
        BidComponent::create([
            'ref_id' => 'ajrlx68t',
            'title' => 'Component 4',
            'cost' => 5500,
            'price' => 6875,
            'config' =>
                '{"is_nested": true, "components": [], "line_items": [7,8], "order_index": 0, "parent_component_id": 3}',
            'actual_cost' => null,
            'actual_cost_confidence_factor' => null,
        ]);
        BidComponent::create([
            'ref_id' => 'ajcoq05y',
            'title' => 'Component 5',
            'cost' => 2000,
            'price' => 2500,
            'config' =>
                '{"is_nested": true, "components": [], "line_items": [9], "order_index": 2, "parent_component_id": 3}',
            'actual_cost' => null,
            'actual_cost_confidence_factor' => null,
        ]);
        BidComponent::create([
            'ref_id' => 'ajnrj42d',
            'title' => 'Component 6',
            'cost' => 1000,
            'price' => 1250,
            'config' =>
                '{"is_nested": false, "components": [], "line_items": [10], "order_index": 2, "parent_component_id": null}',
            'actual_cost' => null,
            'actual_cost_confidence_factor' => null,
        ]);
        $this->command->info('bid_components table seeded.');
    }
}
