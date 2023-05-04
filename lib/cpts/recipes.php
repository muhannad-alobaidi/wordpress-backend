<?php

function cptui_register_my_cpts_recipe() {

	/**
	 * Post Type: Cases.
	 */

	$labels = [
		"name" => esc_html__( "Recipes", "lagerblad" ),
		"singular_name" => esc_html__( "recipe", "lagerblad" ),
		"menu_name" => esc_html__( "Recipes", "lagerblad" ),
		"all_items" => esc_html__( "All Recipes", "lagerblad" ),
		"add_new" => esc_html__( "Add new", "lagerblad" ),
		"add_new_item" => esc_html__( "Add new recipe", "lagerblad" ),
		"edit_item" => esc_html__( "Edit recipe", "lagerblad" ),
		"new_item" => esc_html__( "New recipe", "lagerblad" ),
		"view_item" => esc_html__( "View recipe", "lagerblad" ),
		"view_items" => esc_html__( "View Recipes", "lagerblad" ),
		"search_items" => esc_html__( "Search Recipes", "lagerblad" ),
		"not_found" => esc_html__( "No Recipes found", "lagerblad" ),
		"not_found_in_trash" => esc_html__( "No Recipes found in trash", "lagerblad" ),
		"parent" => esc_html__( "Parent recipe:", "lagerblad" ),
		"featured_image" => esc_html__( "Featured image for this recipe", "lagerblad" ),
		"set_featured_image" => esc_html__( "Set featured image for this recipe", "lagerblad" ),
		"remove_featured_image" => esc_html__( "Remove featured image for this recipe", "lagerblad" ),
		"use_featured_image" => esc_html__( "Use as featured image for this recipe", "lagerblad" ),
		"archives" => esc_html__( "recipe archives", "lagerblad" ),
		"insert_into_item" => esc_html__( "Insert into recipe", "lagerblad" ),
		"uploaded_to_this_item" => esc_html__( "Upload to this recipe", "lagerblad" ),
		"filter_items_list" => esc_html__( "Filter Recipes list", "lagerblad" ),
		"items_list_navigation" => esc_html__( "Recipes list navigation", "lagerblad" ),
		"items_list" => esc_html__( "Recipes list", "lagerblad" ),
		"attributes" => esc_html__( "Recipes attributes", "lagerblad" ),
		"name_admin_bar" => esc_html__( "recipe", "lagerblad" ),
		"item_published" => esc_html__( "recipe published", "lagerblad" ),
		"item_published_privately" => esc_html__( "recipe published privately.", "lagerblad" ),
		"item_reverted_to_draft" => esc_html__( "recipe reverted to draft.", "lagerblad" ),
		"item_scheduled" => esc_html__( "recipe scheduled", "lagerblad" ),
		"item_updated" => esc_html__( "recipe updated.", "lagerblad" ),
		"parent_item_colon" => esc_html__( "Parent recipe:", "lagerblad" ),
	];

	$args = [
		"label" => esc_html__( "Recipes", "lagerblad" ),
		"labels" => $labels,
		"description" => "Work recipe",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => [ "slug" => "recipes", "with_front" => true ],
		"query_var" => true,
		"menu_position" => 15,
		"menu_icon" => "dashicons-food",
		"supports" => [ "title", "editor", "thumbnail", "revisions", "author" ],
		"show_in_graphql" => false,
	];

	register_post_type( "recipe", $args );
}
cptui_register_my_cpts_recipe();
// add_action( 'init', 'cptui_register_my_cpts_recipe' );

