<?php

$guid = (int)get_input('guid');
$status = get_input('status');

userpoints_moderate($guid, $status);

system_message(sprintf(elgg_echo("elggx_userpoints:".$status."_message"), elgg_echo('elggx_userpoints:lowerplural')));
forward(REFERER);
