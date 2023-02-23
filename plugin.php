<?php
/*
Plugin Name: Emoji Reactions
Plugin URI: https://example.com
Description: Add emoji reactions to your posts and track the counts in postmeta.
Version: 1.0.0
Author: Suman Wagle
Author URI: https://example.com
License: GPL2
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




// Add the emoji reaction buttons to the post content
function emoji_reactions_buttons($content)
{
  global $post;

  
  $post_id = $post->ID;
  // update_post_meta( $post_id, 'ip_address', $ip_add );
  
  /* Checking if the user is logged in. */
  if (is_user_logged_in()) {
    /* Getting the current user ID and then getting the user meta for each emoji. */
    $user_id = get_current_user_id();
    $reactions = get_user_meta($user_id, 'emoji_reactions', true);
    // if ($reactions) { // Checks if the serialized data is not empty
      $reactions = unserialize($reactions); // Unserializes the data
  
      // Retrieves the count of reactions from the unserialized data
      // $like_count = isset($reactions['like']) ? $reactions['like'] : 0;
      // $haha_count = isset($reactions['haha']) ? $reactions['haha'] : 0;
      // $love_count = isset($reactions['love']) ? $reactions['love'] : 0;
      // $angry_count = isset($reactions['angry']) ? $reactions['angry'] : 0;
    
    $like_count = get_user_meta($user_id, 'emoji_reaction_' . $post_id . '_like', true);
    $haha_count = get_user_meta($user_id, 'emoji_reaction_' . $post_id . '_haha', true);
    $love_count = get_user_meta($user_id, 'emoji_reaction_' . $post_id . '_love', true);
    $angry_count = get_user_meta($user_id, 'emoji_reaction_' . $post_id . '_angry', true);

    //Like Reaction Button
    $content .= '<span class="cpm-reaction-icon nullClass like-High ' . ($like_count == 1 ? 'highlighted' : '') . ' hover-text-like" like-data-text="Like" >
        <img class="emoji-reaction-button likeReaction" data-post-id="' . $post_id . '" data-reaction="like" src="' . CPM_PLUGIN_DIR . 'assets/img/emoji_like_1.png" alt="Like Reaction"></span>';
    $content .= '<span id="cpmR1" class="cpm-reaction-count ">' . $like_count . '</span>';

    //Love Reaction Button
    $content .= '<span class="cpm-reaction-icon nullClass love-High ' . ($love_count == 1 ? 'highlighted' : '') . ' hover-text-love" love-data-text="Love" >
        <img class="emoji-reaction-button loveReaction" data-post-id="' . $post_id . '" data-reaction="love"  src="' . CPM_PLUGIN_DIR . '/assets/img/emoji_love_1.png" alt="Like Reaction"></span>';
    $content .= '<span id="cpmR2" class="cpm-reaction-count ">' . $love_count . '</span>';

    //Laugh Reaction Button
    $content .= '<span class="cpm-reaction-icon nullClass haha-High ' . ($haha_count == 1 ? 'highlighted' : '') . ' hover-text-laugh" laugh-data-text="haha">
        <img class="emoji-reaction-button laughReaction" data-post-id="' . $post_id . '" data-reaction="haha" src="' . CPM_PLUGIN_DIR . '/assets/img/emoji_laugh_1.png" alt="Like Reaction"></span>';
    $content .= '<span id="cpmR3" class="cpm-reaction-count ">' . $haha_count . '</span>';

    //Angry Reaction Button
    $content .= '<span class="cpm-reaction-icon nullClass angry-High' . ($angry_count == 1 ? 'highlighted' : '') . ' hover-text-angry" angry-data-text="angry">
  <img class="emoji-reaction-button angryReaction" data-post-id="' . $post_id . '" data-reaction="angry"  src="' . CPM_PLUGIN_DIR . '/assets/img/emoji_angry_1.png" alt="Like Reaction"></span>';
    $content .= '<span id="cpmR4" class="cpm-reaction-count ">' . $angry_count . '</span>';

    return $content;
    // }
  } else {
    $ip_add = '';
  // whether ip is from share internet
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip_add = $_SERVER['HTTP_CLIENT_IP'];
  }
  //whether ip is from proxy
  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_add = $_SERVER['HTTP_X_FORWARDED_FOR'];
  }
  //whether ip is from remote address
  else {
    $ip_add = $_SERVER['REMOTE_ADDR'];
  }
    /* Getting the count of each emoji reaction. */
    $like_count = get_post_meta($post_id, 'emoji_reaction_' . $post_id . '_(' . $ip_add . ')_' . 'like', true);
    $haha_count = get_post_meta($post_id, 'emoji_reaction_' . $post_id . '_(' . $ip_add . ')_' . 'haha', true);
    $love_count = get_post_meta($post_id, 'emoji_reaction_' . $post_id . '_(' . $ip_add . ')_' . 'love', true);
    $angry_count = get_post_meta($post_id,'emoji_reaction_' . $post_id . '_(' . $ip_add . ')_' . 'angry', true);

    //Like Reaction Button
    $content .= '<span class="cpm-reaction-icon nullClass like-High ' . ($like_count == 1 ? 'highlighted' : '') . ' hover-text-like" like-data-text="Like" >
        <img class="emoji-reaction-button likeReaction" data-post-id="' . $post_id . '" data-reaction="like" data-ip-add="' . $ip_add . ' " src="' . CPM_PLUGIN_DIR . 'assets/img/emoji_like_1.png" alt="Like Reaction"></span>';
    $content .= '<span id="cpmR1" class="cpm-reaction-count">' . $like_count . '</span>';

    //Love Reaction Button
    $content .= '<span class="cpm-reaction-icon nullClass love-High ' . ($love_count == 1 ? 'highlighted' : '') . ' hover-text-love" love-data-text="Love" >
        <img class="emoji-reaction-button loveReaction" data-post-id="' . $post_id . '" data-reaction="love" data-ip-add="' . $ip_add . '" src="' . CPM_PLUGIN_DIR . '/assets/img/emoji_love_1.png" alt="Like Reaction"></span>';
    $content .= '<span id="cpmR2" class="cpm-reaction-count">' . $love_count . '</span>';


    //Laugh Reaction Button
    $content .= '<span class="cpm-reaction-icon nullClass haha-High ' . ($haha_count == 1 ? 'highlighted' : '') . ' hover-text-laugh" laugh-data-text="haha">
        <img class="emoji-reaction-button laughReaction" data-post-id="' . $post_id . '" data-reaction="haha" data-ip-add="' . $ip_add . '" src="' . CPM_PLUGIN_DIR . '/assets/img/emoji_laugh_1.png" alt="Like Reaction"></span>';
    $content .= '<span id="cpmR3" class="cpm-reaction-count">' . $haha_count . '</span>';

    //Angry Reaction Button
    $content .= '<span class="cpm-reaction-icon nullClass angry-High' . ($angry_count == 1 ? 'highlighted' : '') . ' hover-text-angry" angry-data-text="angry">
  <img class="emoji-reaction-button angryReaction" data-post-id="' . $post_id . '" data-reaction="angry"  data-ip-add="' . $ip_add . '" src="' . CPM_PLUGIN_DIR . '/assets/img/emoji_angry_1.png" alt="Like Reaction"></span>';
    $content .= '<span id="cpmR4" class="cpm-reaction-count">' . $angry_count . '</span>';

    return $content;
  }
}
add_filter('the_content', 'emoji_reactions_buttons');
add_action('wp_ajax_emoji_reactions', 'emoji_reactions');
add_action('wp_ajax_nopriv_emoji_reactions', 'emoji_reactions');


function emoji_reactions()
{

  $post_id = $_POST['post_id'];
  $reaction = $_POST['reaction'];
  $current_count = '';
 

    // Check if user is logged in
    if (is_user_logged_in()) {
      $user_id = get_current_user_id();
      $current_count = get_user_meta($user_id, 'emoji_reaction_' . $post_id . '_' . $reaction, true);
      if ($current_count == 1) {
        // If the user has already reacted to the post with this emoji, remove the reaction
        update_user_meta($user_id, 'emoji_reaction_' . $post_id . '_' . $reaction, 0);
      } else {
        // Remove all previous reactions by the user and set the new reaction
        $arr_emo = ["like", "haha", "love", "angry"];
      
        foreach ($arr_emo as $emo) {
          if ($emo != $reaction) {
            update_user_meta($user_id, 'emoji_reaction_' . $post_id . '_' . $emo, 0);
          }
        }
        // Set the new reaction by the user
        update_user_meta($user_id, 'emoji_reaction_' . $post_id . '_' . $reaction, 1);
        
        // Save the reaction as a custom field for the post
        $reactions = get_post_meta($post_id, 'emoji_reactions', true);
        if (!$reactions) {
          $reactions = array();
        }
        if (!isset($reactions[$reaction])) {
          $reactions[$reaction] = 1;
        } else {
          $reactions[$reaction]++;
        }
        update_post_meta($post_id, 'emoji_reactions', $reactions);
      }
      

      // $user_id = get_current_user_id();
      // $current_count = get_user_meta($user_id, 'emoji_reaction_' . $post_id . '_' . $reaction, true);
      // if ($current_count == 1) {
      //   update_user_meta($user_id, 'emoji_reaction_' . $post_id . '_' . $reaction, 0);
      // } else {
      //   // If the user has reacted to the post before, remove previous reaction
      //   $arr_emo = ["like", "haha", "love", "angry"];

      //   foreach ($arr_emo as $emo) {
      //     if ($emo != $reaction) {
      //       update_user_meta($user_id, 'emoji_reaction_' . $post_id . '_' . $emo, 0);
      //     }
      //   }
      //   update_user_meta($user_id, 'emoji_reaction_' . $post_id . '_' . $reaction, 1);
      // }

    }
     else {
      $ip_add = '';
      // whether ip is from share internet
      if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_add = $_SERVER['HTTP_CLIENT_IP'];
      }
      //whether ip is from proxy
      elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_add = $_SERVER['HTTP_X_FORWARDED_FOR'];
      }
      //whether ip is from remote address
      else {
        $ip_add = $_SERVER['REMOTE_ADDR'];
      }

      $current_count = get_post_meta($post_id, 'emoji_reaction_' . $post_id . '_(' . $ip_add . ')_' . $reaction, true);
      if ($current_count == 1) {
        update_post_meta($post_id, 'emoji_reaction_' . $post_id . '_(' . $ip_add . ')_' . $reaction, 0);
      } else {
        // If the post does not have a reaction of this type, increase the count
        $arr_emo = ["like", "haha", "love", "angry"];
        foreach ($arr_emo as $emo) {
          if ($emo != $reaction) {
            $current_emo_count = get_post_meta($post_id, 'emoji_reaction_' . $post_id . '_(' . $ip_add . ')_' . $emo, true);
            if (empty($current_emo_count)) {
              update_post_meta($post_id, 'emoji_reaction_' . $post_id . '_(' . $ip_add . ')_' . $emo, 0);
            }
          }
        }
        update_post_meta($post_id, 'emoji_reaction_' . $post_id . '_(' . $ip_add . ')_' . $reaction, 1);
      }
  
    }
  
}