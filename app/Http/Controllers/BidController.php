<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\APIController;

class BidController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $all_nodes = get_all_nodes();
        return nodes_structure($all_nodes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request_data = json_decode($request->get('data'));
        $given_nodes = calc_given_nodes($request_data);
        $result = $given_nodes;
        for ($i = 0; $i < count($given_nodes); $i ++) {
            $result = calc_neigh_nodes($result, $given_nodes[$i]);
        }
        $total_count = get_total_count();
        $all_nodes = get_all_nodes();
        do {
            for ($i = 0; $i < $total_count; $i++) {
                if (is_already_calc($result, $all_nodes[$i]) == null) {
                    continue;
                }
                $result = calc_parent_node($result, $all_nodes[$i]);
                $result = calc_child_nodes($result, $all_nodes[$i]);
            }
        } while (count($result) < $total_count);
        return nodes_structure($result);
    }
}
