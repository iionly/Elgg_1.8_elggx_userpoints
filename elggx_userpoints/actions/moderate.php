<?php

    global $CONFIG;

    admin_gatekeeper();
    action_gatekeeper();

    $guid = (int)get_input('guid');
    $status = get_input('status');

    userpoints_moderate($guid, $status);

    system_message(sprintf(elgg_echo("elggx_userpoints:".$status."_message"), elgg_get_plugin_setting('lowerplural', 'elggx_userpoints')));
    forward($_SERVER['HTTP_REFERER']);
