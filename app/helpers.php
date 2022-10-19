<?php

if (!function_exists('nodes_organize')) {
    function nodes_organize($nodes)
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
            $itemsByReference[$item['id']]['data'] = new StdClass();
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
