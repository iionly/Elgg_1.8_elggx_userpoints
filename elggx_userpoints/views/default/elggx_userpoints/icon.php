<?php

    if ($vars['size'] == 'large') {
	    if (elgg_get_plugin_setting('profile_display', 'elggx_userpoints')) {
            $upperplural = elgg_get_plugin_setting('upperplural', 'elggx_userpoints');
?>

	        <div class="userpoints_profile">
		        <div><span><?php echo $upperplural . ': ' . $vars['entity']->userpoints_points;?></span></div>
	        </div>

        <?php } ?>
    <?php } ?>
