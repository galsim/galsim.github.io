<?php
// custom taxonomy used to store external connections data
//// if missing - creates main terms (connections will be sub-terms)


// register taxonomy
add_action('init', 'gg_conn_taxonomy');
function gg_conn_taxonomy() {
		
	$labels = array( 
        'name' => __('Connections', 'gg_ml'),
        'singular_name' => __( 'Connection', 'gg_ml' ),
        'search_items' => __( 'Search Connections', 'gg_ml' ),
        'popular_items' => NULL,
        'all_items' => __( 'All Connections', 'gg_ml' ),
        'parent_item' => __( 'Parent Connection', 'gg_ml' ),
        'parent_item_colon' => __( 'Parent Connection:', 'gg_ml' ),
        'edit_item' => __( 'Edit Connection', 'gg_ml' ),
        'update_item' => __( 'Update Connection', 'gg_ml' ),
        'add_new_item' => __( 'Add New Connection', 'gg_ml' ),
        'new_item_name' => __( 'New Connection', 'gg_ml' ),
        'separate_items_with_commas' => __( 'Separate item categories with commas', 'gg_ml' ),
        'add_or_remove_items' => __( 'Add or remove Connections', 'gg_ml' ),
        'choose_from_most_used' => __( 'Choose from most used Connections', 'gg_ml' ),
        'menu_name' => __( 'Connections', 'gg_ml' ),
    );

    $args = array( 
        'labels' => $labels,
        'public' => false,
        'show_in_nav_menus' => false,
        'show_ui' => false,
        'show_tagcloud' => false,
        'hierarchical' => true,
        'rewrite' => false,
        'query_var' => false
    );
    register_taxonomy('gg_connect_hub', null, $args);
}


