<?php

    // Include the Userpoint class
    require_once dirname(__FILE__) . "/lib/userpoint.php";

    function userpoints_init() {

        // Register the userpoint entity
        elgg_register_entity_type('object', 'userpoint', 'Userpoint');

        elgg_register_plugin_hook_handler('expirationdate:expire_entity', 'all', 'elggx_userpoints_expire');

        elgg_extend_view('css/elgg', 'elggx_userpoints/css');
        elgg_extend_view('icon/user/default','elggx_userpoints/icon');

        $toppoints_name  =  elgg_get_plugin_setting('toppoints_name') ? elgg_get_plugin_setting('toppoints_name') : 'Top Points';

        elgg_register_widget_type('toppoints', $toppoints_name, elgg_echo('elggx_userpoints:widget:toppoints:info'));

        // Hooks for awarding points
        elgg_register_plugin_hook_handler('permissions_check', 'all', 'elggx_userpoints_permissions_check');
        elgg_register_plugin_hook_handler('action', 'invitefriends/invite', 'elggx_userpoints_invite');
        elgg_register_plugin_hook_handler('action', 'register', 'elggx_userpoints_register');
        elgg_register_plugin_hook_handler('action', 'uservalidationbyemail/validate', 'elggx_userpoints_validate');
        elgg_register_plugin_hook_handler('action', 'siteaccess/confirm', 'elggx_userpoints_validate');
        elgg_register_plugin_hook_handler('action', 'recommendations/new', 'elggx_userpoints_recommendations');
        elgg_register_plugin_hook_handler('action', 'recommendations/approve', 'elggx_userpoints_recommendations');
        elgg_register_plugin_hook_handler('action', 'friends/add', 'elggx_userpoints_friend');
        elgg_register_event_handler('login','user','elggx_userpoints_login');
        elgg_register_event_handler('create','object', 'elggx_userpoints_object');
        elgg_register_event_handler('delete','object', 'elggx_userpoints_object');
        elgg_register_event_handler('delete','entity', 'elggx_userpoints_object');
        elgg_register_event_handler('create','annotation','elggx_userpoints_annotate_create');
        elgg_register_event_handler('create','group','elggx_userpoints_group');
        elgg_register_event_handler('delete','group','elggx_userpoints_group');
        elgg_register_event_handler('upload','tp_album','elggx_userpoints_tidypics');
        elgg_register_event_handler('profileupdate','user','elggx_userpoints_profile');

        elgg_register_admin_menu_item('administer', 'elggx_userpoints', 'administer_utilities');

        // Upgrage settings
        $oldversion = elgg_get_plugin_setting('version', 'userpoints');
        $newversion = elgg_get_plugin_setting('version', 'elggx_userpoints');

        // Check if we need to run an upgrade
        if ($oldversion && !$newversion) {

            // Update plugin settings
            $plugin = elgg_get_plugin_from_id('userpoints');

            elgg_set_plugin_setting('upperplural', $plugin->upperplural, 'elggx_userpoints');
            elgg_set_plugin_setting('lowerplural', $plugin->lowerplural, 'elggx_userpoints');
            elgg_set_plugin_setting('lowersingular', $plugin->lowersingular, 'elggx_userpoints');
            elgg_set_plugin_setting('moderate', $plugin->moderate, 'elggx_userpoints');
            elgg_set_plugin_setting('subtract', $plugin->subtract, 'elggx_userpoints');
            elgg_set_plugin_setting('displaymessage', $plugin->displaymessage, 'elggx_userpoints');
            elgg_set_plugin_setting('mypoints_name', $plugin->mypoints_name, 'elggx_userpoints');
            elgg_set_plugin_setting('toppoints_name', $plugin->toppoints_name, 'elggx_userpoints');
            elgg_set_plugin_setting('numtoppoint', $plugin->numtoppoint, 'elggx_userpoints');
            elgg_set_plugin_setting('profile_display', $plugin->profile_display, 'elggx_userpoints');
            elgg_set_plugin_setting('delete', $plugin->delete, 'elggx_userpoints');
            elgg_set_plugin_setting('expire_after', $plugin->expire_after, 'elggx_userpoints');


            // Update point settings
            $pointssettings = elgg_get_plugin_from_id('userpoints_standard');

            elgg_set_plugin_setting('friend', $pointssettings->friend, 'elggx_userpoints');
            elgg_set_plugin_setting('blog', $pointssettings->blog, 'elggx_userpoints');
            elgg_set_plugin_setting('file', $pointssettings->file, 'elggx_userpoints');
            elgg_set_plugin_setting('bookmarks', $pointssettings->bookmarks, 'elggx_userpoints');
            elgg_set_plugin_setting('group', $pointssettings->group, 'elggx_userpoints');
            elgg_set_plugin_setting('profileupdate', $pointssettings->profileupdate, 'elggx_userpoints');
            elgg_set_plugin_setting('messageboard', $pointssettings->messageboard, 'elggx_userpoints');
            elgg_set_plugin_setting('page_top', $pointssettings->page_top, 'elggx_userpoints');
            elgg_set_plugin_setting('likes', $pointssettings->likes, 'elggx_userpoints');
            elgg_set_plugin_setting('generic_comment', $pointssettings->generic_comment, 'elggx_userpoints');
            elgg_set_plugin_setting('riverpost', $pointssettings->riverpost, 'elggx_userpoints');
            elgg_set_plugin_setting('thewire', $pointssettings->thewire, 'elggx_userpoints');
            elgg_set_plugin_setting('upload_photo', $pointssettings->upload_photo, 'elggx_userpoints');
            elgg_set_plugin_setting('poll', $pointssettings->poll, 'elggx_userpoints');
            elgg_set_plugin_setting('pollvote', $pointssettings->pollvote, 'elggx_userpoints');
            elgg_set_plugin_setting('phototag', $pointssettings->phototag, 'elggx_userpoints');
            elgg_set_plugin_setting('group_topic_post', $pointssettings->group_topic_post, 'elggx_userpoints');
            elgg_set_plugin_setting('login', $pointssettings->login, 'elggx_userpoints');
            elgg_set_plugin_setting('delete', $pointssettings->delete, 'elggx_userpoints');
            elgg_set_plugin_setting('invite', $pointssettings->invite, 'elggx_userpoints');
            elgg_set_plugin_setting('verify_email', $pointssettings->verify_email, 'elggx_userpoints');
            elgg_set_plugin_setting('require_registration', $pointssettings->require_registration, 'elggx_userpoints');
            elgg_set_plugin_setting('expire_invite', $pointssettings->expire_invite, 'elggx_userpoints');

            // Set new version
            elgg_set_plugin_setting('version', '1.8.0', 'elggx_userpoints');
        }
    }

    /**
     * Add pending points to a user
     *
     * This method is intended to be called by other plugins
     * that need to add points pending some future action.
     *
     * An example would be inviting friends but the points are
     * awarded pending registration. The plugin calling this
     * method is responsible for calling userpoints_moderate()
     * when the points should be awarded.
     *
     * @param integer  $guid User Guid
     * @param integer  $points The number of ppoints to add
     * @param string   $description Description for these points
     * @param string   $type The entity type that the points are being awarded for
     * @param integer  $guid The entity guid
     * @return object  The userpoint object
     */
    function userpoints_add_pending($user_guid, $points, $description, $type=null, $guid=null) {

        $points = (int)$points;

        // Create and save our new Userpoint object
        $userpoint = new Userpoint(null, $user_guid, $description);
        $userpoint->save();

        // Add the points, type, and guid as metadata to the user object
        $userpoint->meta_points = $points;
        $userpoint->meta_type = $type;
        $userpoint->meta_guid = $guid;
        $userpoint->meta_moderate = 'pending';

        return($userpoint);
    }

    /**
     * Add points to a user
     *
     * @param integer  $guid User Guid
     * @param integer  $points The number of ppoints to add
     * @param string   $description Description for these points
     * @param string   $type The entity type that the points are being awarded for
     * @param integer  $guid The entity guid
     * @return Bool    Return true/false on success/failure
     */
    function userpoints_add($user_guid, $points, $description, $type=null, $guid=null) {

        $points = (int)$points;

        // Create and save our new Userpoint object
        $userpoint = new Userpoint(null, $user_guid, $description);
        $userpoint->save();

        // Just in case the save fails
        if (!$userpoint->guid) {
            return(false);
        }

        // Add the points, type, and guid as metadata to the user object
        $userpoint->meta_points = $points;
        $userpoint->meta_type = $type;
        $userpoint->meta_guid = $guid;

        if (!elgg_trigger_plugin_hook('userpoints:add', $userpoint->type, array('entity' => $userpoint), true)) {
            $userpoint->delete();
            return(false);
        }

        // If moderation is enabled set points to pending else they are auto approved
        if (elgg_get_plugin_setting('moderate') && $type != 'admin') {
            $userpoint->meta_moderate = 'pending';
        } else {
            $userpoint->meta_moderate = 'approved';
            userpoints_update_user($user_guid, $points);
        }

        // Setup point expiration if enabled
        if (elgg_get_plugin_setting('expire_after')) {
            if (function_exists('expirationdate_set')) {
                $ts = time() + elgg_get_plugin_setting('expire_after');
                expirationdate_set($userpoint->guid, date('Y-m-d H:i:s', $ts), false);
            }
        }

        // Display a system message to the user if configured to do so
        $branding = ($points == 1) ? elgg_get_plugin_setting('lowersingular') : elgg_get_plugin_setting('lowerplural');
        if (elgg_get_plugin_setting('displaymessage') && $type != 'admin' && $user_guid == $_SESSION['user']->guid) {
            $message = elgg_get_plugin_setting('moderate') ? 'elggx_userpoints:pending_message' : 'elggx_userpoints:awarded_message';
            system_message(sprintf(elgg_echo($message), $points, $branding));
        }

        return($userpoint);
    }

    /**
     * Subtract points from a user. This is just a wrapper around
     * userpoints_add as we are really just adding negataive x points.
     *
     * @param integer  $guid User Guid
     * @param integer  $points The number of points to subtract
     * @param string   $description Description for these points
     * @param string   $type The entity type that the points are being awarded for
     * @param integer  $guid The entity guid
     * @return Bool    Return true/false on success/failure
     */
    function userpoints_subtract($user_guid, $points, $description, $type=null, $guid=null) {
        if ($points > 0) {
            $points = -$points;
        }

        return(userpoints_add($user_guid, $points, $description, $type=null, $guid=null));
    }

    /**
     * Called when the expirationdate:expire_entity hook is triggered.
     * When a userpoint record is expired we have to decrement the users
     * total points.
     *
     * @param integer  $hook The hook being called.
     * @param integer  $type The type of entity you're being called on.
     * @param string   $return The return value.
     * @param string   $params An array of parameters including the userpoint entity
     * @return Bool    Return true
     */
    function elggx_userpoints_expire($hook, $type, $return, $params) {

        if (!$params['entity']->subtype == 'userpoint') {
            return(true);
        }

        $user = get_user($params['entity']->owner_guid);

        // Decrement the users total points
        userpoints_update_user($params['entity']->owner_guid, -$params['entity']->meta_points);

        return(true);
    }

    /**
     * Given a user id, type, and entity id check to see if points have
     * already been awarded.
     *
     * @param  integer  $user_guid User Guid
     * @param  string   $type The entity type that the points are being awarded for
     * @param  integer  $guid The entity guid
     * @return Bool
     */
    function userpoints_exists($user_guid, $type, $guid) {
        $entities = elgg_get_entities_from_metadata(array(
                  'metadata_names' => 'meta_type',
                  'types' => 'object',
                  'subtypes' => 'userpoint',
                  'owner_guid' => $owner_guid,
                  ));


        foreach($entities as $obj) {
            if ($obj->meta_type == $type && $obj->meta_guid == $guid) {
                return(true);
            }
        }
        return(false);
    }

    /**
     * Returns a count of approved and pending points for the given user.
     *
     * @param  integer  $user_guid The user Guid
     * @return array    An array including the count of approved/pending points
     */
    function userpoints_get($user_guid) {

        $points = array('approved' => 0, 'pending' => 0);

        if ($entities = elgg_get_entities_from_metadata(array('metadata_names' => 'meta_points', 'types' => 'object', 'subtypes' => 'userpoint', 'owner_guid' => $user_guid, 'limit' => 100000,))) {
            foreach($entities as $obj) {
                if (isset($obj->meta_moderate)) {
                    if ($obj->meta_moderate == 'approved') {
                        $points['approved'] = $points['approved'] + $obj->meta_points;
                    } else if ($obj->meta_moderate == 'pending') {
                        $points['pending'] = $points['pending'] + $obj->meta_points;
                    }
                } else {
                    $points['approved'] = $points['approved'] + $obj->meta_points;
                }
            }
        }
        return($points);
    }

    /**
     * Deletes a userpoint record based on the meta_guid. This method
     * should be called by plugins that want to delete points if the
     * content/object that awarded the points is deleted.
     *
     * @param  integer  $user_guid The user Guid
     * @param  integer  $guid The guid of the object being deleted
     */
    function userpoints_delete($user_guid, $guid) {

        if (!elgg_get_plugin_setting('delete')) {
            return(false);
        }

        $points = 0;

        $entities = elgg_get_entities_from_metadata(array('metadata_names' => 'meta_guid', 'metadata_values' => $guid, 'types' => 'object', 'subtypes' => 'userpoint', 'owner_guid' => $user_guid,));
        foreach ($entities as $entity) {
            $points = $points + $entity->meta_points;
            delete_entity($entity->guid);
        }

        $user = get_user($user_guid);

        // Decrement the users total points
        userpoints_update_user($user_guid, -$points);
    }

    /**
     * Deletes userpoints by the guid of the userpoint entity.
     * This method is called when administratively deleting points
     * or when points expire.
     *
     * @param  integer  $guid The guid of the userpoint entity
     */
    function userpoints_delete_by_userpoint($guid) {

        $entity = get_entity($guid);

        // Decerement the users total points
        userpoints_update_user($entity->owner_guid, -$entity->meta_points);

        // Delete the userpoint entity
        $entity->delete();
        delete_entity($guid);
    }

    // Update the users running points total
    function userpoints_update_user($guid, $points) {
        $user = get_user($guid);

        $user->userpoints_points = $user->userpoints_points + $points;

        if (!elgg_trigger_plugin_hook('userpoints:update', 'object', array('entity' => $user), true)) {
            $user->userpoints_points = $user->userpoints_points - $points;
            return(false);
        }
    }

    /**
     * Deletes userpoints by the guid of the userpoint entity.
     * This method is called when administratively deleting points
     * or when points expire.
     *
     * @param  integer  $guid The guid of the userpoint entity
     */
    function userpoints_moderate($guid, $status) {

        $entity = get_entity($guid);

        $entity->meta_moderate = $status;

        // increment the users total points if approved
        if ($status == 'approved') {
            userpoints_update_user($entity->owner_guid, $entity->meta_points);
        }
    }

    /**
     * This very cool method was contributed by Alivin79 to the Goolge Elgg Development group
     * http://groups.google.com/group/elgg-development/browse_thread/thread/30259601808493f1/b66ce5aa2f48b921
     *
     * @global Array $CONFIG
     * @param Array $meta_array Is a multidimensional array with the list of metadata to filter.
     * For each metadata you have to provide 3 values:
     * - name of metadata
     * - value of metadata
     * - operand ( <, >, <=, >=, =, like)
     * For example
     *      $meta_array = array(
     *              array(
     *                  'name'=>'my_metadatum',
     *                  'operand'=>'>=',
     *                  'value'=>'my value'
     *              )
     *      )
     * @param String $entity_type
     * @param String $entity_subtype
     * @param Boolean $count
     * @param Integer $owner_guid
     * @param Integer $container_guid
     * @param Integer $limit
     * @param Integer $offset
     * @param String $order_by "Order by" SQL string. If you want to sort by metadata string,
     * possible values are vN.string, where N is the first index of $meta_array,
     * hence our example is $order by = 'v1.string ASC'
     * @param Integer $site_guid
     * @return Mixed Array of entities or false
     *
     */
    function userpoints_get_entities_from_metadata_by_value($meta_array, $entity_type="", $entity_subtype="", $count=false, $owner_guid=0, $container_guid=0, $limit=10, $offset=0, $order_by="", $site_guid=0) {

        global $CONFIG;

        // ORDER BY
        if ($order_by == "") $order_by = "e.time_created desc";
        $order_by = sanitise_string($order_by);

        $where = array();

        // Filetr by metadata
        $mindex = 1; // Starting index of joined metadata/metastring tables
        $join_meta = "";
        $query_access = "";
        foreach($meta_array as $meta) {
            $join_meta .= "JOIN {$CONFIG->dbprefix}metadata m{$mindex} on e.guid = m{$mindex}.entity_guid ";
            $join_meta .= "JOIN {$CONFIG->dbprefix}metastrings v{$mindex} on v{$mindex}.id = m{$mindex}.value_id ";

            $meta_n = get_metastring_id($meta['name']);
            $where[] = "m{$mindex}.name_id='$meta_n'";

            if (strtolower($meta['operand']) == "like"){
                // "LIKE" search
                $where[] = "v{$mindex}.string LIKE ('".$meta['value']."') ";
            }elseif(strtolower($meta['operand']) == "in"){
                // TO DO - "IN" search
            }else{
                // Simple operand search
                $where[] = "v{$mindex}.string".$meta['operand']."'".$meta['value']."'";
            }

            $query_access .= ' and ' . get_access_sql_suffix("m{$mindex}"); // Add access controls

            $mindex++;
        }

        $limit = (int)$limit;
        $offset = (int)$offset;

        if ((is_array($owner_guid) && (count($owner_guid)))) {
            foreach($owner_guid as $key => $guid) {
                $owner_guid[$key] = (int) $guid;
            }
        } else {
            $owner_guid = (int) $owner_guid;
        }

        if ((is_array($container_guid) && (count($container_guid)))) {
            foreach($container_guid as $key => $guid) {
                $container_guid[$key] = (int) $guid;
            }
        } else {
            $container_guid = (int) $container_guid;
        }

        $site_guid = (int) $site_guid;
        if ($site_guid == 0)
            $site_guid = $CONFIG->site_guid;

        $entity_type = sanitise_string($entity_type);
        if ($entity_type!="")
            $where[] = "e.type='$entity_type'";

        $entity_subtype = get_subtype_id($entity_type, $entity_subtype);
        if ($entity_subtype)
            $where[] = "e.subtype=$entity_subtype";

        if ($site_guid > 0)
            $where[] = "e.site_guid = {$site_guid}";

        if (is_array($owner_guid)) {
            $where[] = "e.owner_guid in (".implode(",",$owner_guid).")";
        } else if ($owner_guid > 0) {
            $where[] = "e.owner_guid = {$owner_guid}";
        }

        if (is_array($container_guid)) {
            $where[] = "e.container_guid in (".implode(",",$container_guid).")";
        } else if ($container_guid > 0)
            $where[] = "e.container_guid = {$container_guid}";
        if (!$count) {
            $query = "SELECT distinct e.* ";
        } else {
            $query = "SELECT count(distinct e.guid) as total ";
        }

        $query .= "FROM {$CONFIG->dbprefix}entities e ";
        $query .= $join_meta;

        $query .= "  WHERE ";
        foreach ($where as $w)
            $query .= " $w and ";
            $query .= get_access_sql_suffix("e"); // Add access controls
            $query .= $query_access;

        if (!$count) {
            $query .= " order by $order_by limit $offset, $limit"; // Add order and limit
            return get_data($query, "entity_row_to_elggstar");
        } else {
            $row = get_data_row($query);
            if ($row)
                return $row->total;
        }
        return false;
    }


    // Methods for awarding points
    function elggx_userpoints_permissions_check($hook_name, $entity_type, $return_value, $parameters) {
        if (elgg_get_context() == 'userpoints_access') {
            return true;
        }
    }

    /**
     * Elevate user to admin.
     *
     * @param  bool $unsu  Return to original permissions
     * @return bool  is_admin true/false
     */
    function elggx_userpoints_su($unsu=false) {
        global $is_admin;
        static $is_admin_orig = null;

        if (is_null($is_admin_orig)) {
            $is_admin_orig = $is_admin;
        }

        if ($unsu) {
            return $is_admin = $is_admin_orig;
        } else {
            return $is_admin = true;
        }
    }



    // Add points for various actions

    function elggx_userpoints_object($event, $object_type, $object) {
        if (function_exists('userpoints_add')) {
            if ($event == 'create') {
                $subtype = get_subtype_from_id($object->subtype);
                if ($points = elgg_get_plugin_setting($subtype)) {
                    userpoints_add($_SESSION['user']->guid, $points, $subtype, $subtype, $object->guid);
                }
            } else if ($event == 'delete') {
                userpoints_delete($_SESSION['user']->guid, $object->guid);
            }
        }

        return(true);
    }

    function elggx_userpoints_annotate_create($event, $object_type, $object) {
        if ($points = elgg_get_plugin_setting($object->name)) {
            if (function_exists('userpoints_add')) {
                $description = $object->name;
                userpoints_add($_SESSION['user']->guid, $points, $description, $object_type, $object->entity_guid);
            }
        }

        return(true);
    }

    function elggx_userpoints_friend($hook, $action) {

        if (function_exists('userpoints_add')) {
            if ($action == 'friends/add') {
                $user = get_user(get_input('friend'));
                if ($points = elgg_get_plugin_setting('friend')) {
                    userpoints_add($_SESSION['user']->guid, $points, 'Making '.$user->name.' a friend');
                    return(true);
                }
            }
        }
    }

    function elggx_userpoints_recommendations($hook, $action) {

        $approval = (int) elgg_get_plugin_setting('recommendations_approve');
        $points = (int) elgg_get_plugin_setting('recommendation');

        if ($action == 'recommendations/new' && !$approval) {
            $user = get_user(get_input('recommendation_to'));
            userpoints_add($_SESSION['user']->guid, $points, 'Recommending '.$user->name, 'recommendation');
            return(true);
        }

        if ($action == 'recommendations/approve') {

            $entity_guid = (int) get_input('entity_guid');
            $entity = get_entity($entity_guid);
            $user = get_user($entity->recommendation_to);

            $description = '<a href='.$entity->getUrl().'>'.$entity->title.'</a>';

            $context = elgg_get_context();
            elgg_set_context('userpoints_access');
            elggx_userpoints_su();

            userpoints_add($entity->owner_guid, $points, $description, 'recommendation');

            elggx_userpoints_su(true);
            elgg_set_context($context);

            return(true);
        }
    }

    function elggx_userpoints_profile($event, $type, $object) {
        if ($points = elgg_get_plugin_setting('profileupdate')) {
            if (function_exists('userpoints_add')) {
                userpoints_add($_SESSION['user']->guid, $points, $event, $type, $object->entity_guid);
            }
        }

        return(true);
    }

    function elggx_userpoints_tidypics($event, $type, $object) {
        if ($points = elgg_get_plugin_setting('upload_photo')) {
            foreach($_FILES as $key => $sent_file) {
                if (!empty($sent_file['name'])) {
                    $description = elgg_echo('elggx_userpoints:uploaded_photo') . ' ' . $object->title;
                    userpoints_add($_SESSION['user']->guid, $points, $description, $type, $object->guid);
                }
            }
       }

        return(true);
    }

    function elggx_userpoints_group($event, $object_type, $object) {
        if (function_exists('userpoints_add')) {
            if ($event == 'create') {
                if ($points = elgg_get_plugin_setting($object_type)) {
                    userpoints_add($_SESSION['user']->guid, $points, $object_type, $object_type, $object->guid);
                }
            } else if ($event == 'delete') {
                userpoints_delete($_SESSION['user']->guid, $object->guid);
            }
        }

        return(true);
    }

    function elggx_userpoints_login() {

        // Check to see if the configured amount of time
        // has passed before awarding more login points
        $user = get_user_by_username($_SESSION['user']->username);
        $diff = time() - $user->userpoints_login;

        if ($diff > elgg_get_plugin_setting('login_threshold')) {

            // Check to see if the user has logged in frequently enough
            $s = (int) elgg_get_plugin_setting('login_interval') * 86400;
            $diff = time() - $user->prev_last_login;

            if (($diff < $s) || !$user->prev_last_login) {

                // The login threshold has been met so now add the points
                userpoints_add($_SESSION['user']->guid, elgg_get_plugin_setting('login'), 'Login');
                $user->userpoints_login = time();
            }
        }

        return(true);
    }

    /**
     * Hooks on the register action and checks to see if the inviting
     * user has a pending userpoints record the invited user. If
     */
    function elggx_userpoints_validate($hook, $action) {

        $access_status = access_get_show_hidden_status();
        access_show_hidden_entities(true);

        $guid = (int)get_input('u');
        $user = get_entity($guid);
        $code = sanitise_string(get_input('c'));

        elggx_userpoints_su();

        // This is a siteaccess validation.
        if ($action == 'siteaccess/confirm') {
            if ($code && $user) {
                if (siteaccess_validate_email($guid, $code)) {
                    elggx_userpoints_registration_award($user->email);
                }
            }
        }

        if ($action == 'uservalidationbyemail/validate') {
            if (uservalidationbyemail_validate_email($guid, $code)) {
                elggx_userpoints_registration_award($user->email);
            }
        }

        access_show_hidden_entities($access_status);

        elggx_userpoints_su(true);
    }

    /**
     * Hooks on the register action and checks to see if the inviting
     * user has a pending userpoints record for the invited user. If
     * the uservalidationbyemail plugin is enabled then points will
     * not be awarded until the invited user verifies their email
     * address. The same is true for the siteaccess module with
     * auto activation disabled.
     */
    function elggx_userpoints_register() {

        $friend_guid = (int) get_input('friend_guid');
        $email = get_input('email');

        // // register.php has to be overridden to pass m has a hidden input
        if (get_input('m')) {
            elggx_userpoints_contact_importer($friend_guid, $email);
            return(true);
        }

        if (elgg_is_active_plugin('uservalidationbyemail') || elgg_is_active_plugin('siteaccess')) {
            return(true);
        }

        if (elgg_is_active_plugin('siteaccess') && elgg_get_plugin_setting('autoactivate', 'siteaccess') != 'yes') {
            return(true);
        }

        // No email validation configured so award the points
        elggx_userpoints_registration_award($email);

        return(true);
    }

    /**
     * Award points to unvalidated users on register. This
     * is to support users that were invited using openinviter. Requires
     * a modification to contact_importer plugin to pass friend_guid,
     * invite code, and a parameter that specifies an openinvite.
     */
    function elggx_userpoints_contact_importer($friend_guid, $email) {

        if (!$points = elgg_get_plugin_setting('invite')) {
            return true;
        }

        $access_status = access_get_show_hidden_status();

        access_show_hidden_entities(true);
        elgg_set_context('userpoints_access');
        elggx_userpoints_su();

        userpoints_add($friend_guid, $points, $email, 'openinviter');

        elggx_userpoints_su(true);
        access_show_hidden_entities($access_status);
    }

    /**
     * Hooks on the invitefriends/invite action and either awards
     * points for the invite or sets up a pending userpoint record
     * where points can be awarded when the invited user registers.
     */
    function elggx_userpoints_invite() {

        if (!$points = elgg_get_plugin_setting('invite')) {
            return;
        }

        $emails = get_input('emails');
        $emails = explode("\n",$emails);

        if (sizeof($emails)) {
            foreach($emails as $email) {

                $email = trim($email);

                if (elgg_get_plugin_setting('verify_email') && !elggx_userpoints_validEmail($email)) {
                    continue;
                }

                if ((int)elgg_get_plugin_setting('require_registration')) {
                    if (!elggx_userpoints_invite_status($_SESSION['user']->guid, $email)) {
                        $user = get_user($_SESSION['user']->guid);
                        $userpoint = userpoints_add_pending($_SESSION['user']->guid, $points, $email, 'invite');
                        if (elgg_is_active_plugin('expirationdate') && $expire = (int)elgg_get_plugin_setting('expire_invite')) {
                            $ts = time() + $expire;
                            expirationdate_set($userpoint->guid, date('Y-m-d H:i:s', $ts), false);
                        }
                    }
                } else {
                    userpoints_add($_SESSION['user']->guid, $points, $email, 'invite');
                }
            }
        }
    }

    /**
     * Check for an existing pending invite for the given email address.
     *
     * @param integer  $guid The inviting users guid
     * @param string   $email The amail address of the invited user
     * @return Bool    Return true/false on pending record found or not
     */
    function elggx_userpoints_registration_award($email) {

        $context = elgg_get_context();
        elgg_set_context('userpoints_access');
        elggx_userpoints_su();

        $guids = elggx_userpoints_invite_status($email);

        if (!empty($guids)) {
            foreach ($guids as $guid) {
                userpoints_moderate($guid, 'approved');
            }
        }

        elggx_userpoints_su(true);
        elgg_set_context($context);

        return;
    }


    /**
     * Check for an existing pending invite for the given email address.
     *
     * @param integer  $guid The inviting users guid
     * @param string   $email The amail address of the invited user
     * @return mixed   Return userpoint guid on pending otherwise return moderation status or false if no record
     */
    function elggx_userpoints_invite_status($email) {

        $status = false;

        $meta_array = array(
            array(
                'name' => 'meta_type',
                 'operand' => '=',
                'value' => 'invite'
            ),
            array(
                'name' => 'meta_moderate',
                'operand' => '=',
                'value' => 'pending'
            )
        );
        $entities = userpoints_get_entities_from_metadata_by_value($meta_array, 'object', 'userpoint', false, 0, 0, 10000, 0);

        foreach ($entities as $entity) {
            if ($entity->description == $email) {
                $status[] = $entity->guid;
            }
        }

        return($status);
    }

    /**
     * Validate an email address
     * Source: http://www.linuxjournal.com/article/9585
     *
     * Returns true if the email has the proper email address
     * has the proper format and the domain exists.
     *
     * @param string   $email The amail address to verify
     * @return Bool    Return true/false on success/failure
     */
    function elggx_userpoints_validEmail($email) {
       $isValid = true;
       $atIndex = strrpos($email, "@");
       if (is_bool($atIndex) && !$atIndex)
       {
          $isValid = false;
       }
       else
       {
          $domain = substr($email, $atIndex+1);
          $local = substr($email, 0, $atIndex);
          $localLen = strlen($local);
          $domainLen = strlen($domain);
          if ($localLen < 1 || $localLen > 64)
          {
             // local part length exceeded
             $isValid = false;
          }
          else if ($domainLen < 1 || $domainLen > 255)
          {
             // domain part length exceeded
             $isValid = false;
          }
          else if ($local[0] == '.' || $local[$localLen-1] == '.')
          {
             // local part starts or ends with '.'
             $isValid = false;
          }
          else if (preg_match('/\\.\\./', $local))
          {
             // local part has two consecutive dots
             $isValid = false;
          }
          else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
          {
             // character not valid in domain part
             $isValid = false;
          }
          else if (preg_match('/\\.\\./', $domain))
          {
             // domain part has two consecutive dots
             $isValid = false;
          }
          else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local)))
          {
             // character not valid in local part unless
             // local part is quoted
             if (!preg_match('/^"(\\\\"|[^"])+"$/',
                 str_replace("\\\\","",$local)))
             {
                $isValid = false;
             }
          }
          if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
          {
             // domain not found in DNS
             $isValid = false;
          }
       }
       return $isValid;
    }


    elgg_register_event_handler('init','system','userpoints_init');

    elgg_register_action("elggx_userpoints/settings", $CONFIG->pluginspath . "elggx_userpoints/actions/settings.php", 'admin');
    elgg_register_action("elggx_userpoints/delete", $CONFIG->pluginspath . "elggx_userpoints/actions/delete.php", 'admin');
    elgg_register_action("elggx_userpoints/moderate", $CONFIG->pluginspath . "elggx_userpoints/actions/moderate.php", 'admin');
    elgg_register_action("elggx_userpoints/add", $CONFIG->pluginspath . "elggx_userpoints/actions/add.php", 'admin');
    elgg_register_action("elggx_userpoints/reset", $CONFIG->pluginspath . "elggx_userpoints/actions/reset.php", 'admin');
