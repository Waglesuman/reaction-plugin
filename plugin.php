<?php
/*
Plugin Name: Emoji Reactions
Plugin URI: https://example.com
Description: Add emoji reactions to your posts and track the counts in postmeta.
Version: 1.0.0
Author: Suman Wagle
Author URI: https://example.com
License: GPL2
Text Domain: cpmreaction
*/

// If this file is called directly, abort.
if (!defined('WPINC')) {
  die;
}

//Define Constants
if (!defined('CPM_PLUGIN_DIR')) {
  define('CPM_PLUGIN_DIR', plugin_dir_url(__FILE__));
}
if (!defined('CPM_PLUGIN_DIR_PATH')) {
  define('CPM_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
}

// Enqueue the JavaScript file for handling the emoji reactions
function emoji_reactions_enqueue_scripts()
{
  wp_enqueue_style('cpm-plugin-style', CPM_PLUGIN_DIR . 'assets/css/reactioncpm.css');
  wp_enqueue_script('cpm-ajax', CPM_PLUGIN_DIR . 'assets/js/emoji-reactions.js', 'jQuery');


  wp_enqueue_script('emoji-reactions-script', plugins_url('assets/js/emoji-reactions.js', __FILE__), array('jquery'), '1.0.0', true);


}
add_action('wp_enqueue_scripts', 'emoji_reactions_enqueue_scripts');

// Define ajaxurl for use in the JavaScript file
add_action('wp_enqueue_scripts', function () {
  wp_localize_script(
    'emoji-reactions-script',
    'emoji_reactions',
    array(
      'ajaxurl' => admin_url('admin-ajax.php')
    )
  );
});

/* Including the file `main.php` from the `inc` folder. */
require CPM_PLUGIN_DIR_PATH. 'inc/main.php';