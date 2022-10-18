<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use App\Http\Controllers\APIController;
use App\Bid;
use App\BidComponent;
use App\BidLineItem;

class BidController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bids = Bid::get();
        $components = BidComponent::get();
        $line_items = BidLineItem::get();
        $all_nodes = Arr::collapse([$bids, $components, $line_items]);

        return nodes_organize($all_nodes);

        return response()->json(
            [
                'status' => 201,
                'message' => 'Resource created.',
                'id' => 123,
            ],
            201
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response()->json(
            [
                'status' => 201,
                'message' => 'Resource created.',
                'id' => 1239,
            ],
            201
        );
    }
}
