<?php

    $lowerplural   = elgg_get_plugin_setting('lowerplural', 'elggx_userpoints');
    $lowersingular = elgg_get_plugin_setting('lowersingular', 'elggx_userpoints');
    $limit         = elgg_get_plugin_setting('numtoppoints', 'elggx_userpoints');

    $limit = $limit ? $limit : 5;

    $meta_array = array(array('name' => 'userpoints_points', 'operand' => '>', 'value' => 0));
    $entities = userpoints_get_entities_from_metadata_by_value($meta_array, 'user', '', false, 0, 0, $limit, 0, 'v1.string + 0 DESC', 0);

    $html = '';

    foreach ($entities as $entity) {

        $icon = elgg_view_entity_icon($entity, 'small');

        $branding = (abs($entity->userpoints_points) > 1) ? $lowerplural : $lowersingular;

        $info = "<a href=\"{$entity->getURL()}\">{$entity->name}</a><br><b>{$entity->userpoints_points} $branding</b>";

        $html .= elgg_view('page/components/image_block', array('image' => $icon, 'body' => $info));
    }

    echo $html;
