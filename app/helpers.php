<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

if (!function_exists('get_all_nodes')) {
    function get_all_nodes()
    {
        $bids = DB::table('bids')->get();
        $components = DB::table('bid_components')->get();
        $line_items = DB::table('bid_line_items')->get();
        return Arr::collapse([$bids, $components, $line_items]);
    }
}

if (!function_exists('nodes_structure')) {
    function nodes_structure($nodes)
    {
        $all_nodes = nodes_refresh($nodes);
        $components = items_to_components($all_nodes);
        $cool_components = components_to_components($components);
        $bid = get_bid($all_nodes);
        return components_to_bid($bid, $cool_components);
    }
}

if (!function_exists('nodes_refresh')) {
    function nodes_refresh($nodes)
    {
        $result = [];
        foreach ($nodes as $node) {
            $config = json_decode($node->config);
            array_push($result, [
                'id' => $node->id,
                'ref_id' => $node->ref_id,
                'type' => property_exists($config, 'parent_component_id')
                    ? 'component'
                    : (property_exists($config, 'order_index')
                        ? 'line_item'
                        : 'bid'),
                'title' => $node->title,
                'cost' => $node->cost,
                'components' => isset($config->components)
                    ? $config->components
                    : null,
                'line_items' => isset($config->line_items)
                    ? $config->line_items
                    : null,
                'parent_component_id' => isset($config->parent_component_id)
                    ? $config->parent_component_id
                    : null,
                'real_cost' => isset($node->real_cost)
                    ? $node->real_cost
                    : null,
                'given' => isset($node->given) ? $node->given : null,
                'factor' => isset($node->factor) ? $node->factor : null,
                'children' => [],
            ]);
        }
        return $result;
    }
}

if (!function_exists('items_to_components')) {
    function items_to_components($nodes)
    {
        $result = [];
        foreach ($nodes as $node) {
            if ($node['type'] == 'component') {
                foreach ($node['line_items'] as $line_item) {
                    foreach ($nodes as $node2) {
                        if (
                            $node2['type'] == 'line_item' &&
                            $node2['id'] == $line_item
                        ) {
                            array_push($node['children'], $node2);
                        }
                    }
                }
                array_push($result, $node);
            }
        }
        return $result;
    }
}

if (!function_exists('components_to_components')) {
    function components_to_components($data)
    {
        $itemsByReference = [];

        // Build array of item references:
        foreach ($data as $key => &$item) {
            $itemsByReference[$item['id']] = &$item;
            // Empty data class (so that json_encode adds "data: {}" )
            // $itemsByReference[$item['id']]['data'] = new StdClass();
        }

        // Set items as children of the relevant parent item.
        foreach ($data as $key => &$item) {
            if (
                $item['parent_component_id'] &&
                isset($itemsByReference[$item['parent_component_id']])
            ) {
                $itemsByReference[$item['parent_component_id']][
                    'children'
                ][] = &$item;
            }
        }

        // Remove items that were added to parents elsewhere:
        foreach ($data as $key => &$item) {
            if (
                $item['parent_component_id'] &&
                isset($itemsByReference[$item['parent_component_id']])
            ) {
                unset($data[$key]);
            }
        }
        return array_values($data);
    }
}

if (!function_exists('get_bid')) {
    function get_bid($nodes)
    {
        foreach ($nodes as $node) {
            if ($node['type'] == 'bid') {
                return $node;
            }
        }
    }
}

if (!function_exists('components_to_bid')) {
    function components_to_bid($bid, $components)
    {
        $bid['children'] = $components;
        return $bid;
    }
}

if (!function_exists('calc_given_nodes')) {
    function calc_given_nodes($request_data)
    {
        $result = [];
        foreach ($request_data as $req) {
            $bid_node = DB::table('bids')
                ->where('ref_id', $req->component_ref_id)
                ->get();
            $com_node = DB::table('bid_components')
                ->where('ref_id', $req->component_ref_id)
                ->get();
            $line_node = DB::table('bid_line_items')
                ->where('ref_id', $req->component_ref_id)
                ->get();
            $node =
                count($bid_node) != 0
                    ? $bid_node[0]
                    : (count($com_node) != 0
                        ? $com_node[0]
                        : $line_node[0]);
            $node->given = $req->value;
            $node->real_cost = $req->value;
            $node->factor = 1;
            $result[] = $node;
        }
        return $result;
    }
}

if (!function_exists('calc_neigh_nodes')) {
    function calc_neigh_nodes($given_nodes)
    {
        $result = $given_nodes;
        foreach ($given_nodes as $node) {
            $neigh_nodes = get_neighbors($node);
            $parent_node = get_parent($node);
            if (!isset($parent_node)) {
                continue;
            } else {
                if (is_already_calc($result, $parent_node) !== null) {
                    $p_cost = is_already_calc($result, $parent_node)->real_cost;
                    $done_sum = 0;
                    $weight_sum = 0;
                    foreach ($neigh_nodes as $n_node) {
                        if (is_already_calc($result, $n_node) != null) {
                            $done_sum += is_already_calc($result, $n_node)
                                ->real_cost;
                        } else {
                            $weight_sum += $n_node->cost;
                        }
                    }
                    $un_sum = $p_cost - $done_sum;
                    foreach ($neigh_nodes as $n_node) {
                        if (is_already_calc($result, $n_node) != null) {
                            continue;
                        } else {
                            $n_node->real_cost =
                                ($un_sum * $n_node->cost) / $weight_sum;
                            $n_node->factor = 2;
                            $result[] = $n_node;
                        }
                    }
                } else {
                    foreach ($neigh_nodes as $n_node) {
                        if (is_already_calc($result, $n_node) !== null) {
                            continue;
                        } else {
                            $n_node->real_cost = $n_node->cost;
                            $n_node->factor = 0;
                            $result[] = $n_node;
                        }
                    }
                }
            }
        }
        return $result;
    }
}

if (!function_exists('get_neighbors')) {
    function get_neighbors($node)
    {
        $config = json_decode($node->config);
        $type = property_exists($config, 'parent_component_id')
            ? 'component'
            : (property_exists($config, 'order_index')
                ? 'line_item'
                : 'bid');
        if ($type == 'bid') {
            return [$node];
        } elseif ($type == 'component') {
            if ($config->parent_component_id == null) {
                return DB::table('bid_components')
                    ->where('config', 'like', '{"is_nested": false%')
                    ->get();
            } else {
                $parent_component = get_parent($node);
                return get_child_nodes($parent_component);
            }
        } else {
            $parent_component = get_parent($node);
            return get_child_nodes($parent_component);
        }
    }
}

if (!function_exists('get_parent')) {
    function get_parent($node)
    {
        $config = json_decode($node->config);
        $type = property_exists($config, 'parent_component_id')
            ? 'component'
            : (property_exists($config, 'order_index')
                ? 'line_item'
                : 'bid');
        if ($type == 'bid') {
            return null;
        } elseif ($type == 'component') {
            if ($config->parent_component_id == null) {
                return DB::table('bids')->get()[0];
            } else {
                return DB::table('bid_components')
                    ->where('id', $config->parent_component_id)
                    ->first();
            }
        } else {
            $components = DB::table('bid_components')->get();
            foreach ($components as $component) {
                $c_config = json_decode($component->config);
                if (in_array($node->id, $c_config->line_items)) {
                    return $component;
                }
            }
        }
    }
}

if (!function_exists('is_already_calc')) {
    function is_already_calc($arr, $node)
    {
        foreach ($arr as $item) {
            if ($item->ref_id == $node->ref_id) {
                return $item;
            }
        }
        return null;
    }
}

if (!function_exists('get_total_count')) {
    function get_total_count()
    {
        return DB::table('bids')->count() +
            DB::table('bid_components')->count() +
            DB::table('bid_line_items')->count();
    }
}

if (!function_exists('calc_parent_node')) {
    function calc_parent_node($arr, $node, $deep)
    {
        $result_arr = $arr;
        $result = 0;
        if (is_already_calc($arr, $node) == null) {
            return $result_arr;
        } else {
            $parent_node = get_parent($node);
            if ($parent_node == null) {
                return $result_arr;
            }
            if (is_already_calc($arr, $parent_node) !== null) {
                return $result_arr;
            } else {
                $n_nodes = get_neighbors($node);
                foreach ($n_nodes as $n_node) {
                    foreach ($arr as $d_node) {
                        if ($d_node->ref_id == $n_node->ref_id) {
                            $result += $d_node->real_cost;
                        }
                    }
                }
                $parent_node->real_cost = $result;
                $parent_node->factor = $deep;
                $result_arr[] = $parent_node;
                return $result_arr;
            }
        }
    }
}

if (!function_exists('get_child_nodes')) {
    function get_child_nodes($node)
    {
        $config = json_decode($node->config);
        $type = property_exists($config, 'parent_component_id')
            ? 'component'
            : (property_exists($config, 'order_index')
                ? 'line_item'
                : 'bid');
        if ($type == 'line_item') {
            return [];
        }
        if ($type == 'bid') {
            return DB::table('bid_components')
                ->where('config', 'like', '{"is_nested": false%')
                ->get();
        } else {
            $n_components = DB::table('bid_components')
                ->whereIn('id', $config->components)
                ->get();
            $n_line_items = DB::table('bid_line_items')
                ->whereIn('id', $config->line_items)
                ->get();
            return Arr::collapse([$n_components, $n_line_items]);
        }
    }
}

if (!function_exists('calc_child_nodes')) {
    function calc_child_nodes($arr, $node, $deep)
    {
        $result_arr = $arr;
        if (is_already_calc($arr, $node) == null) {
            return $result_arr;
        } else {
            $child_nodes = get_child_nodes($node);
            if (count($child_nodes) == 0) {
                return $result_arr;
            }
            if (is_already_calc($arr, $child_nodes[0]) !== null) {
                return $result_arr;
            } else {
                $sum = 0;
                foreach ($child_nodes as $c_node) {
                    $sum += $c_node->cost;
                }
                foreach ($child_nodes as $c_node) {
                    // try {
                    //     $c_node->real_cost =
                    //         ($node->real_cost * $c_node->cost) / $sum;
                    // } catch (Exception $e) {
                    //     print_r([$deep, $node, $c_node, $result_arr]);
                    //     exit();
                    // }
                    $node = is_already_calc($arr, $node);

                    $c_node->real_cost =
                        ($node->real_cost * $c_node->cost) / $sum;
                    $c_node->factor = $deep;
                    $result_arr[] = $c_node;
                }
                return $result_arr;
            }
        }
    }
}
