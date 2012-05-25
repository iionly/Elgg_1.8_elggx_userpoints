<?php

    global $CONFIG;

    admin_gatekeeper();
    action_gatekeeper();

    $user_guid = (int)get_input('user_guid');

    // Delete all the userpoint objects for the selected user
    // or for all users if user_guid = 0
    $entities = elgg_get_entities(array('type' => 'object', 'subtype' => 'userpoint', 'owner_guid' => $user_guid,));
    foreach ($entities as $entity)
        delete_entity($entity->guid);

    if ($user_guid > 0) {
        // Remove the userpoints_points metadata for the selected user
        $options = array(
                        'guid' => $user_guid,
                        'metadata_name' => 'userpoints_points'
                );
        elgg_delete_metadata($options);
    } else {
        // Remove all userpoints_points metadata
        delete_data("DELETE from {$CONFIG->dbprefix}metadata where name_id=" . add_metastring('userpoints_points'));
    }

    system_message(sprintf(elgg_echo("elggx_userpoints:reset:success"), elgg_get_plugin_setting('lowerplural', 'elggx_userpoints')));
    forward($_SERVER['HTTP_REFERER']);
