<?php

    global $CONFIG;

    admin_gatekeeper();
    action_gatekeeper();

    $guid = (int)get_input('guid');

    userpoints_delete_by_userpoint($guid);

    system_message(elgg_echo("elggx_userpoints:delete_success"));
    forward($_SERVER['HTTP_REFERER']);
