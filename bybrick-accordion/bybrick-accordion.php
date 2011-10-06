<?php
/*
Plugin Name: byBrick Accordion
Plugin URI: https://github.com/byBrick/byBrick-Accordion-Plugin
Description: A plugin that enables in-post open and close menus.
Version: 1.0
Author: byBrick
Author URI: http://www.bybrick.se/
License: GNU General Public License (GPLv3)
*/

/*
byBrick Accordion Plugin
Written by Emil Tullstedt (@sakjur) & David Pausson (@davidpaulsson)
*/

// Defines some variables that are needed in the future.
$bbcodes = array('collapsible_item');
$set_id = 0;

add_filter('the_posts', 'bb_include_accordion_if_needed');

function bb_include_accordion_if_needed($posts){

	if (empty($posts)) return $posts;

	$bb_accordion_required = false;
	foreach ($posts as $post) {
		foreach ($GLOBALS["bbcodes"] as $bbcode) {
			if (stripos($post->post_content, $bbcode)) {
				$bb_accordion_required = true;
				break;
			}
		}
	}

	if ($bb_accordion_required) {		
		// Adding logic for Wordpress integration.
		wp_enqueue_script("jquery");
		$style = plugins_url('/style.css', __FILE__);
	    wp_enqueue_style("accordion-style", $style);
	} 

	return $posts;
	
}

foreach ($bbcodes AS $bbcode){ // The loop. Checks for every possible collapsible_item
	add_shortcode($bbcode, 'bb_accordion_' . $bbcode); // Yay for magics!
} 

function bb_accordion_collapsible_item ( $atts, $content = null ) {
 
	extract( shortcode_atts(array (
		'title' => NULL,
		'id' => 'bb_accordion_' . $GLOBALS['set_id']
	), $atts));
	
	$GLOBALS['set_id']++; // Adds one to the ID for the next item.

	if ($title != NULL) { // Checks for a defined title.
 		
		/* This is the part where the content is printed into the post */
		$html = "<div id=\"". $id ."_title\" class=\"accordion-title\">". $title ."</div><div style=\"display:none;\" id=\"" . $id . "\" class=\"accordion-content\">" . do_shortcode($content) . "</div><script type=\"text/javascript\">jQuery('#". $id ."_title').click(function() {
					jQuery('#" . $id . "').slideToggle('fast', function() {
						// What to do after the function() is done.
					});
				});</script>";
		return $html;
	} else { // If no title
		return("<!-- Please define the title for the accordion -->" . do_shortcode($content));
	}
}
