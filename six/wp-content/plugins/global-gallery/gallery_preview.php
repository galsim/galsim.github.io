<?php
// overwrite the page content to display the gallery

add_filter('the_content', 'gg_manage_preview' );
function gg_manage_preview($the_content) {
	$target_page = (int)get_option('gg_preview_pag');
	$curr_page_id = (int)get_the_ID();
	
	if($target_page == $curr_page_id && is_user_logged_in() && isset($_REQUEST['gg_gid'])) {
				
		$content = do_shortcode('[g-gallery gid="'.(int)$_REQUEST['gg_gid'].'" random="0"]');
		return $content;
	}	
	
	else {return $the_content;}
}

?>