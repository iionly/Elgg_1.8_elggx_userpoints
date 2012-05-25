<?php

	/**
	 * Save Userpoints settings
	 *
	 */

	global $CONFIG;

	gatekeeper();
	action_gatekeeper();


	// Params array (text boxes and drop downs)
	$params = get_input('params');
	$result = false;
	foreach ($params as $k => $v) {
		if (!elgg_set_plugin_setting($k, $v, 'elggx_userpoints')) {
			register_error(sprintf(elgg_echo('plugins:settings:save:fail'), 'elggx_userpoints'));
			forward($_SERVER['HTTP_REFERER']);
		}
	}

	system_message(elgg_echo('elggx_userpoints:settings:save:ok'));

	forward($_SERVER['HTTP_REFERER']);
