<?php

$params = get_input('params');

$user = get_user_by_username($params['username']);

userpoints_add($user->guid, $params['points'], $params['description'], 'admin');

system_message(sprintf(elgg_echo("elggx_userpoints:add:success"), $params['points'], elgg_echo('elggx_userpoints:lowerplural'), $params['username']));
forward(REFERER);
