<?php

$offset = get_input('offset') ? (int)get_input('offset') : 0;
$limit = 10;

$ts = time ();
$token = generate_action_token ( $ts );

$options_count = array('type' => 'user', 'limit' => false, 'count' => true, 'order_by_metadata' =>  array('name' => 'userpoints_points', 'direction' => DESC, 'as' => integer));
$options_count['metadata_name_value_pairs'] = array(array('name' => 'userpoints_points', 'value' => 0,  'operand' => '>'));
$count = elgg_get_entities_from_metadata($options_count);
$options = array('type' => 'user', 'limit' => $limit, 'offset' => $offset, 'order_by_metadata' =>  array('name' => 'userpoints_points', 'direction' => DESC, 'as' => integer));
$options['metadata_name_value_pairs'] = array(array('name' => 'userpoints_points', 'value' => 0,  'operand' => '>'));
$entities = elgg_get_entities_from_metadata($options);

$nav = elgg_view('navigation/pagination',array(
    'base_url' => $_SERVER['REQUEST_URI'],
    'offset' => $offset,
    'count' => $count,
    'limit' => 5
));

$html = $nav;

$html .= "<div><br><table><tr><th width=\"50%\"><b>".elgg_echo('elggx_userpoints:user')."</b></th>";
$html .= "<th width=\"20%\"><b>".elgg_echo('elggx_userpoints:upperplural')."</b></th>";
$html .= "<th width=\"10%\"><b>".elgg_echo('elggx_userpoints:action')."</b></tr>";
$html .= "<tr><td colspan=3><hr></td></tr>";

foreach ($entities as $entity) {

    $html .= "<tr><td><a href=\"" . elgg_get_site_url() . "admin/administer_utilities/elggx_userpoints?tab=detail&user_guid={$entity->guid}\">{$entity->name}</a></td>";
    $html .= "<td><a href=\"" . elgg_get_site_url() . "admin/administer_utilities/elggx_userpoints?tab=detail&user_guid={$entity->guid}\">{$entity->userpoints_points}</a></td>";
    $html .= "<td>" . elgg_view("output/confirmlink", array(
                          'href' => elgg_get_site_url() . "action/elggx_userpoints/reset?user_guid={$entity->guid}&__elgg_token=$token&__elgg_ts=$ts",
                          'text' => elgg_echo('elggx_userpoints:reset'),
                          'confirm' => sprintf(elgg_echo('elggx_userpoints:reset:confirm'), $entity->name)
                      ));
    $html .= "</td></tr>";

}

$html .= "</table></div>";

echo $html;
