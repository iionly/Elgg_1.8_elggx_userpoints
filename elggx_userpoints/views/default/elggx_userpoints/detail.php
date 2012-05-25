<?php

    $offset = (int)get_input('offset');
    $user_guid = get_input('user_guid');

    $ts = time();
    $token = generate_action_token($ts);

    $count    = elgg_get_entities_from_metadata(array(
              'metadata_names' => 'meta_moderate',
              'metadata_values' => 'approved',
              'types' => 'object',
              'subtypes' => 'userpoint',
              'owner_guid' => $user_guid,
              'limit' => 0,
              'offset' => 0,
              'site_guids',
              'count' => true,
              ));
    $entities = elgg_get_entities_from_metadata(array(
              'metadata_names' => 'meta_moderate',
              'metadata_values' => 'approved',
              'types' => 'object',
              'subtypes' => 'userpoint',
              'owner_guids' => $user_guid,
              'limit' => 10,
              'offset' => $offset,
              ));

    $nav = elgg_view('navigation/pagination',array(
        'base_url' => $_SERVER['REQUEST_URI'],
        'offset' => $offset,
        'count' => $count,
        'limit' => 5,
    ));

    $html = $nav;

    if ($count=='0') {
        $html .= "<br>" . elgg_echo('elggx_userpoints:approved_empty');
    }

    foreach ($entities as $entity) {

        $owner = $entity->getOwnerEntity();
        $friendlytime = elgg_view_friendly_time($entity->time_created);
        $points = $entity->meta_points;
        $v = ($points == 1) ? elgg_get_plugin_setting('lowersingular', 'elggx_userpoints') : elgg_get_plugin_setting('lowerplural', 'elggx_userpoints');

        $icon = elgg_view_entity_icon($owner, 'small');

        // Initialize link to the description on the Userpoint
        $link = $entity->description;

        // If we have the parent ID and its a valid object,
        // build a link to it.
        if (isset($entity->meta_guid)) {
            $parent = get_entity($entity->meta_guid);
            if (is_object($parent)) {
                $item = $parent->title ? $parent->title : $parent->description;
                $link = "<a href=\"{$parent->getURL()}\">$item</a>";
            }
        }

        $info = "<p><a href=\"{$entity->getURL()}\">{$points} $v</a> awarded for {$entity->meta_type}: $link</p>";
        $info .= "<p class=\"owner_timestamp\"><a href=\"{$owner->getURL()}\">{$owner->name}</a> {$friendlytime} ";

        $info .= "(<a href=\"" . $vars['url'] . "action/elggx_userpoints/delete?guid={$entity->guid}&__elgg_token=$token&__elgg_ts=$ts\">".elgg_echo('elggx_userpoints:delete')."</a>)";

        $html .= elgg_view('page/components/image_block', array('image' => $icon, 'body' => $info));
    }

    echo $html;
