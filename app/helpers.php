<?php

if (!function_exists('nodes_organize')) {
    function nodes_organize($nodes)
    {
        return nodes_refresh($nodes);
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
            ]);
        }
        return $result;
    }
}
