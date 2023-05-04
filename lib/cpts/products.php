<?php

function cptui_register_my_cpts_product() {

	/**
	 * Post Type: Cases.
	 */

	$labels = [
		"name" => esc_html__( "Products", "lagerblad" ),
		"singular_name" => esc_html__( "product", "lagerblad" ),
		"menu_name" => esc_html__( "Products", "lagerblad" ),
		"all_items" => esc_html__( "All Products", "lagerblad" ),
		"add_new" => esc_html__( "Add new", "lagerblad" ),
		"add_new_item" => esc_html__( "Add new product", "lagerblad" ),
		"edit_item" => esc_html__( "Edit product", "lagerblad" ),
		"new_item" => esc_html__( "New product", "lagerblad" ),
		"view_item" => esc_html__( "View product", "lagerblad" ),
		"view_items" => esc_html__( "View Products", "lagerblad" ),
		"search_items" => esc_html__( "Search Products", "lagerblad" ),
		"not_found" => esc_html__( "No Products found", "lagerblad" ),
		"not_found_in_trash" => esc_html__( "No Products found in trash", "lagerblad" ),
		"parent" => esc_html__( "Parent product:", "lagerblad" ),
		"featured_image" => esc_html__( "Featured image for this product", "lagerblad" ),
		"set_featured_image" => esc_html__( "Set featured image for this product", "lagerblad" ),
		"remove_featured_image" => esc_html__( "Remove featured image for this product", "lagerblad" ),
		"use_featured_image" => esc_html__( "Use as featured image for this product", "lagerblad" ),
		"archives" => esc_html__( "product archives", "lagerblad" ),
		"insert_into_item" => esc_html__( "Insert into product", "lagerblad" ),
		"uploaded_to_this_item" => esc_html__( "Upload to this product", "lagerblad" ),
		"filter_items_list" => esc_html__( "Filter Products list", "lagerblad" ),
		"items_list_navigation" => esc_html__( "Products list navigation", "lagerblad" ),
		"items_list" => esc_html__( "Products list", "lagerblad" ),
		"attributes" => esc_html__( "Products attributes", "lagerblad" ),
		"name_admin_bar" => esc_html__( "product", "lagerblad" ),
		"item_published" => esc_html__( "product published", "lagerblad" ),
		"item_published_privately" => esc_html__( "product published privately.", "lagerblad" ),
		"item_reverted_to_draft" => esc_html__( "product reverted to draft.", "lagerblad" ),
		"item_scheduled" => esc_html__( "product scheduled", "lagerblad" ),
		"item_updated" => esc_html__( "product updated.", "lagerblad" ),
		"parent_item_colon" => esc_html__( "Parent product:", "lagerblad" ),
	];

	$args = [
		"label" => esc_html__( "Products", "lagerblad" ),
		"labels" => $labels,
		"description" => "Work product",
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
		"rewrite" => [ "slug" => "products", "with_front" => true ],
		"query_var" => true,
		"menu_position" => 15,
		"menu_icon" => "dashicons-products",
		"supports" => [ "title", "editor", "thumbnail", "revisions", "author" ],
		"show_in_graphql" => false,
	];

	register_post_type( "product", $args );
}
cptui_register_my_cpts_product();
// add_action( 'init', 'cptui_register_my_cpts_product' );

