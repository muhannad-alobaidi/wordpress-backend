<?php

function cptui_register_my_taxes_producttype() {

	/**
	 * Taxonomy: Product types.
	 */

	$labels = [
		"name" => esc_html__( "Product types", "nextsek" ),
		"singular_name" => esc_html__( "Product type", "nextsek" ),
		"menu_name" => esc_html__( "Product types", "nextsek" ),
		"all_items" => esc_html__( "All Product types", "nextsek" ),
		"edit_item" => esc_html__( "Edit Product type", "nextsek" ),
		"view_item" => esc_html__( "View Product type", "nextsek" ),
		"update_item" => esc_html__( "Update Product type name", "nextsek" ),
		"add_new_item" => esc_html__( "Add new Product type", "nextsek" ),
		"new_item_name" => esc_html__( "New Product type name", "nextsek" ),
		"parent_item" => esc_html__( "Parent Product type", "nextsek" ),
		"parent_item_colon" => esc_html__( "Parent Product type:", "nextsek" ),
		"search_items" => esc_html__( "Search Product types", "nextsek" ),
		"popular_items" => esc_html__( "Popular Product types", "nextsek" ),
		"separate_items_with_commas" => esc_html__( "Separate Product types with commas", "nextsek" ),
		"add_or_remove_items" => esc_html__( "Add or remove Product types", "nextsek" ),
		"choose_from_most_used" => esc_html__( "Choose from the most used Product types", "nextsek" ),
		"not_found" => esc_html__( "No Product types found", "nextsek" ),
		"no_terms" => esc_html__( "No Product types", "nextsek" ),
		"items_list_navigation" => esc_html__( "Product types list navigation", "nextsek" ),
		"items_list" => esc_html__( "Product types list", "nextsek" ),
		"back_to_items" => esc_html__( "Back to Product types", "nextsek" ),
		"name_field_description" => esc_html__( "The name is how it appears on your site.", "nextsek" ),
		"parent_field_description" => esc_html__( "Assign a parent term to create a hierarchy. The term Jazz, for example, would be the parent of Bebop and Big Band.", "nextsek" ),
		"slug_field_description" => esc_html__( "The slug is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.", "nextsek" ),
		"desc_field_description" => esc_html__( "The description is not prominent by default; however, some themes may show it.", "nextsek" ),
	];

	
	$args = [
		"label" => esc_html__( "Product types", "nextsek" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'producttype', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "producttype",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];
	register_taxonomy( "producttype", [ "product" ], $args );
}
cptui_register_my_taxes_producttype();
// add_action( 'init', 'cptui_register_my_taxes_producttype' );
