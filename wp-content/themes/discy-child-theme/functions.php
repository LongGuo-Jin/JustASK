<?php add_action('wp_enqueue_scripts','discy_enqueue_parent_theme_style',20);
function discy_enqueue_parent_theme_style() {
	wp_enqueue_style('discy-child-theme',get_stylesheet_uri(),'',null,'all');
}
?>