<?php

    $offset = get_input('offset') ? (int)get_input('offset') : 0;
    $limit = 10;

    $ts = time ();
    $token = generate_action_token ( $ts );

    $meta_array = array(array('name' => 'userpoints_points', 'operand' => '>', 'value' => 0));
    $count = userpoints_get_entities_from_metadata_by_value($meta_array, 'user', '', true, 0, 0, 0, 0);
    $entities = userpoints_get_entities_from_metadata_by_value($meta_array, 'user', '', false, 0, 0, $limit, $offset, 'v1.string + 0 DESC');

    $nav = elgg_view('navigation/pagination',array(
        'base_url' => $_SERVER['REQUEST_URI'],
        'offset' => $offset,
        'count' => $count,
        'limit' => 5,
    ));

    $html = $nav;

    $html .= "<div><br><table><tr><th width=\"50%\"><b>".elgg_echo('elggx_userpoints:user')."</b></th>";
    $html .= "<th width=\"20%\"><b>".elgg_get_plugin_setting('upperplural', 'elggx_userpoints')."</b></th>";
    $html .= "<th width=\"10%\"><b>".elgg_echo('elggx_userpoints:action')."</b></tr>";
    $html .= "<tr><td colspan=3><hr></td></tr>";

    foreach ($entities as $entity) {

        $html .= "<tr><td><a href=\"{$vars['url']}admin/administer_utilities/elggx_userpoints?tab=detail&user_guid={$entity->guid}\">{$entity->name}</a></td>";
        $html .= "<td><a href=\"{$vars['url']}admin/administer_utilities/elggx_userpoints?tab=detail&user_guid={$entity->guid}\">{$entity->userpoints_points}</a></td>";
        $html .= "<td>" . elgg_view("output/confirmlink", array(
                              'href' => $vars['url'] . "action/elggx_userpoints/reset?user_guid={$entity->guid}&__elgg_token=$token&__elgg_ts=$ts",
                              'text' => elgg_echo('elggx_userpoints:reset'),
                              'confirm' => sprintf(elgg_echo('elggx_userpoints:reset:confirm'), elgg_get_plugin_setting('lowerplural', 'elggx_userpoints'), $entity->name)
                          ));
        $html .= "</td></tr>";

    }

    $html .= "</table></div>";

    echo $html;
