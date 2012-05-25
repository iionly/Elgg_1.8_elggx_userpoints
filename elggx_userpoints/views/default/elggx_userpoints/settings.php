<?php

	/**
	* Userpoints settings form
	*/

    $plugin = elgg_get_plugin_from_id('elggx_userpoints');

    $upgrade = false;
    if ($upgrade) {
        echo "<a href=\"<?php echo $upgrade_url; ?>\">Upgrade</a><br />";
    }

    $ts = time ();
    $token = generate_action_token ( $ts );

    echo "<br><b>";
    echo elgg_view("output/confirmlink", array(
        'href' => $vars['url'] . "action/elggx_userpoints/reset?user_guid=0&__elgg_token=$token&__elgg_ts=$ts",
        'text' => elgg_echo('elggx_userpoints:reset:all'),
        'confirm' => elgg_echo('elggx_userpoints:reset:all:confirm')
    ));
    echo "</b><br><br>";

    $action = $vars['url'] . 'action/elggx_userpoints/settings';

    // Set default values
    $upperplural     = $plugin->upperplural    ? $plugin->upperplural      : elgg_echo('elggx_userpoints:upperplural');
    $lowerplural     = $plugin->lowerplural    ? $plugin->lowerplural      : elgg_echo('elggx_userpoints:lowerplural');
    $lowersingular   = $plugin->lowersingular  ? $plugin->lowersingular    : elgg_echo('elggx_userpoints:lowersingular');
    $moderate        = $plugin->moderate       ? $plugin->moderate         : '0';
    $subtract        = $plugin->subtract       ? $plugin->subtract         : '0';
    $displaymessage  = $plugin->displaymessage ? $plugin->displaymessage   : '0';
    $mypoints_name   = $plugin->mypoints_name  ? $plugin->mypoints_name    : elgg_echo('elggx_userpoints:mypoints');
    $toppoints_name  = $plugin->toppoints_name ? $plugin->toppoints_name   : elgg_echo('elggx_userpoints:toppoints');
    $numtoppoints    = $plugin->numtoppoints   ? $plugin->numtoppoints     : '5';

    $form .= elgg_echo('elggx_userpoints:settings:transaction_status_info');
    $form .= "<br><b>" . elgg_echo('elggx_userpoints:settings:transaction_status') . "</b>";
    $form .= elgg_view('input/dropdown', array(
                    'name' => 'params[moderate]',
                    'options_values' => array('1' => elgg_echo('elggx_userpoints:settings:moderated'), '0' => elgg_echo('elggx_userpoints:settings:approved')),
                    'value' => $plugin->moderate
    ));

    $form .= "<br><br><b>" . elgg_echo('elggx_userpoints:settings:profile_display') . "</b>";
    $form .= elgg_view('input/dropdown', array(
                    'name' => 'params[profile_display]',
                    'options_values' => array('1' => elgg_echo('elggx_userpoints:settings:yes'), '0' => elgg_echo('elggx_userpoints:settings:no')),
                    'value' => $plugin->profile_display
    ));

    $form .= "<br><br>";
    $form .= "<b>" . elgg_echo('elggx_userpoints:settings:upperplural') . "</b>";
    $form .= elgg_view('input/text', array('name' => "params[upperplural]", 'value' => $upperplural));
    $form .= "<br><br>";

    $form .= "<b>" . elgg_echo('elggx_userpoints:settings:lowerplural') . "</b>";
    $form .= elgg_view('input/text', array('name' => "params[lowerplural]", 'value' => $lowerplural));
    $form .= "<br><br>";

    $form .= "<b>" . elgg_echo('elggx_userpoints:settings:lowersingular') . "</b>";
    $form .= elgg_view('input/text', array('name' => "params[lowersingular]", 'value' => $lowersingular));
    $form .= "<br><br>";

    $form .= "<b>" . elgg_echo('elggx_userpoints:settings:toppoints:name') . "</b>";
    $form .= elgg_view('input/text', array('name' => "params[toppoints_name]", 'value' => $toppoints_name));
    $form .= "<br><br>";

    $form .= "<b>" . elgg_echo('elggx_userpoints:settings:toppoints:num') . "</b>";
    $form .= elgg_view('input/text', array('name' => "params[numtoppoints]", 'value' => $numtoppoints));
    $form .= "<br><br>";

    $form .= "<b>" . elgg_echo('elggx_userpoints:settings:displaymessage') . "</b>";
    $form .= elgg_view('input/dropdown', array(
                    'name' => 'params[displaymessage]',
                    'options_values' => array('1' => elgg_echo('elggx_userpoints:settings:yes'), '0' => elgg_echo('elggx_userpoints:settings:no')),
                    'value' => $plugin->displaymessage
    ));

    $form .= "<br><br><b>" . elgg_echo('elggx_userpoints:settings:subtract') . "</b>";
    $form .= elgg_view('input/dropdown', array(
                    'name' => 'params[subtract]',
                    'options_values' => array('1' => elgg_echo('elggx_userpoints:settings:yes'), '0' => elgg_echo('elggx_userpoints:settings:no')),
                    'value' => $plugin->subtract
    ));

    $form .= "<br><br><b>" . elgg_echo('elggx_userpoints:settings:delete') . "</b>";
    $form .= elgg_view('input/dropdown', array(
                    'name' => 'params[delete]',
                    'options_values' => array(
                        '1' => elgg_echo('elggx_userpoints:settings:yes'),
                        '0' => elgg_echo('elggx_userpoints:settings:no')
                    ),
                    'value' => $plugin->delete
    ));
    $form .= "<br>" . elgg_echo('elggx_userpoints:settings:delete:note');

    $form .= "<br><br><b>" . elgg_echo('elggx_userpoints:settings:expire_after') . "</b>";
    $form .= elgg_view('input/dropdown', array(
                    'name' => 'params[expire_after]',
                    'options_values' => array(
                        '0'        => elgg_echo('elggx_userpoints:settings:never'),
                        '3600'     => elgg_echo('elggx_userpoints:settings:1_hour'),
                        '86400'    => elgg_echo('elggx_userpoints:settings:1_day'),
                        '604800'   => elgg_echo('elggx_userpoints:settings:1_week'),
                        '1209600'  => elgg_echo('elggx_userpoints:settings:2_weeks'),
                        '2419200'  => elgg_echo('elggx_userpoints:settings:4_weeks'),
                        '31536000' => elgg_echo('elggx_userpoints:settings:365_days')
                    ),
                    'value' => $plugin->expire_after
    ));
    $form .= "<br>" . elgg_echo('elggx_userpoints:settings:expire_after:help');

	$form .= elgg_view("input/securitytoken");

	$form .= "<br><br>" . elgg_view('input/submit', array('value' => elgg_echo("save")));
        echo elgg_view('input/form', array('action' => $action, 'body' => $form));