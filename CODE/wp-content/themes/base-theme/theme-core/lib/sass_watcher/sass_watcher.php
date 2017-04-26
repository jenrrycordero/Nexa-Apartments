<?php
include_once theme_get_core_directory() . "/lib/sass_watcher/SassWatcher.php";

$sw = new SassWatcher;


// Number '20' it's mean after save and '1' for get data before save
add_action('acf/save_post', 'watcher_acf_save_post', 20);
function watcher_acf_save_post($post_id)
{
	$sw = new SassWatcher;
	$sw->evaluateSavedPost($post_id, $_REQUEST['page']);
}