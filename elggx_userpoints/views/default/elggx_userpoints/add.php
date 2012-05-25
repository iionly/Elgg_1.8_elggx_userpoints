<?php

    /**
    * Userpoints for for manually adding points
    */

    $action = $vars['url'] . 'action/elggx_userpoints/add';

    // Set default values
    $upperplural    = $plugin->upperplural    ? $plugin->upperplural    : elgg_echo('elggx_userpoints:upperplural');
    $lowerplural    = $plugin->lowerplural    ? $plugin->lowerplural    : elgg_echo('elggx_userpoints:lowerplural');
    $lowersingular  = $plugin->lowersingular  ? $plugin->lowersingular  : elgg_echo('elggx_userpoints:lowersingular');


    $form = "<h2>" . elgg_echo('elggx_userpoints:add') . " $upperplural</h2><br>";

    $form .= "<b>" . elgg_echo('elggx_userpoints:add:user') . "</b>";
    $form .= elgg_view('input/text', array('name' => "params[username]", 'value' => ''));
    $form .= "<br><br>";

    $form .= "<b>$upperplural:</b>";
    $form .= elgg_view('input/text', array('name' => "params[points]", 'value' => ''));
    $form .= "<br><br>";

    $form .= "<b>" . elgg_echo('elggx_userpoints:add:description') . "</b>";
    $form .= elgg_view('input/text', array('name' => "params[description]", 'value' => ''));
    $form .= "<br><br>";

    $form .= elgg_view("input/securitytoken");

    $form .= elgg_view('input/submit', array('value' => elgg_echo("save")));
    echo elgg_view('input/form', array('action' => $action, 'body' => $form));
