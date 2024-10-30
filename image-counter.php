<?php
/*
Plugin Name: Image Counter
Plugin URI: http://wordpress.org/extend/plugins/image-counter/
Description: This plugin adds a small counter to each image in your posts.
Author: Ajay Verma
Version: 0.4.1
Author URI: http://traveliving.org/
License: GPL2
*/
/*  Copyright 2011  Verma Ajay  (email : ajayverma1986@gmail.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

 function image_counter_css() {
		$myStyleUrl = WP_PLUGIN_URL . '/image-counter/style.css';
        $myStyleFile = WP_PLUGIN_DIR . '/image-counter/style.css';
        if ( file_exists($myStyleFile) ) {
            wp_register_style('image-counter-css', $myStyleUrl);
            wp_enqueue_style( 'image-counter-css');
        }
    }
$ic_options = image_counter_options();

if ($ic_options['css'] == 'on') add_action( 'wp_print_styles', 'image_counter_css' );

function image_counter_options(){

$image_counter_options = get_option('image_counter');
if (empty($image_counter_options)) {
  $image_counter_options = array('blacklist' => array('1' => '.gif'), 'css' => 'on', 'limit' => 3, 'before' => __('Image # ', 'image-counter'), 'after' => '. ' ); 
update_option('image_counter', $image_counter_options);
} 

return $image_counter_options; 

			}

function insert_counter($image)
{
  global $icount, $post, $ic_options;
  $string = $image[0];
  preg_match ('/<img [^>]*src=["|\']([^"|\']+)/i', $string, $src);
  preg_match ('/<img [^>]*class=["|\']([^"|\']+)/i', $string, $class);
  $no_counter = $ic_options['blacklist'];
  $banned = false;
if (strpos($class[1], 'no-counter') !== false ) {$banned = true;} else 
{foreach ($no_counter as $banned_file_name) {
		if (strpos($src[1], $banned_file_name) !== false ) $banned = true;
} }
	
  if ($banned == false) {
	  $icount++;
	  
	  $new_string = '<span class="image">';
	  $new_string .= '<span class="image-count">';
	  $new_string .= $ic_options['before'];
	  $new_string .= $icount;
	  $new_string .= $ic_options['after'];
	  $new_string .= '</span>';
	  $new_string .= $string;
	  $new_string .= '</span>';  
	  return $new_string;
	} 
  else { 
	  return $image[0];
	  }

  
  
  
}        

function image_counter($content) {
global $post, $ic_options;

if (!is_single() && !is_page() || get_post_meta($post->ID, 'image-counter', true) == 'off'){
	return $content;
	} 
else {
	preg_match_all( '/<img.*>/i', $content, $imgs );
	foreach ($ic_options['blacklist'] as $banned_file_name) {
		foreach ($imgs[0] as $img){
			if (strpos($img, $banned_file_name) !== false ) $banned_count++;
			if (strpos($img, 'no_counter') !== false ) $banned_count++;
		}
	}
	$img_count = count($imgs[0]) - $banned_count;

	if ( $img_count >= $ic_options['limit'] ) {	 
		$new_content = preg_replace_callback('/<img.*>/i', "insert_counter", $content);
		return $new_content;} else {return $content;}
	} 
	
}
          


  


function image_counter_admin() {
	add_options_page(__('Image Counter options', 'image-counter'), __('Image Counter', 'image-counter'), 'manage_options', 'image_counter', 'image_counter_options_page');
}

function image_counter_options_page() {
include('image-counter-admin.php');

}
if (function_exists('load_plugin_textdomain'))
	{
	load_plugin_textdomain('image-counter', '/' .PLUGINDIR. '/' .dirname(plugin_basename(__FILE__)) . '/languages/' );
	}
	
add_action('admin_menu', 'image_counter_admin');	
add_filter('the_content','image_counter', 100); 
?>