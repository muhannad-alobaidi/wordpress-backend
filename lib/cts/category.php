<?php

function cptui_register_my_taxes_category() {

	/**
	 * Taxonomy: Categories.
	 */

	$labels = [
		"name" => esc_html__( "Categories", "nextsek" ),
		"singular_name" => esc_html__( "category", "nextsek" ),
		"menu_name" => esc_html__( "Categories", "nextsek" ),
		"all_items" => esc_html__( "All Categories", "nextsek" ),
		"edit_item" => esc_html__( "Edit category", "nextsek" ),
		"view_item" => esc_html__( "View category", "nextsek" ),
		"update_item" => esc_html__( "Update category name", "nextsek" ),
		"add_new_item" => esc_html__( "Add new category", "nextsek" ),
		"new_item_name" => esc_html__( "New category name", "nextsek" ),
		"parent_item" => esc_html__( "Parent category", "nextsek" ),
		"parent_item_colon" => esc_html__( "Parent category:", "nextsek" ),
		"search_items" => esc_html__( "Search Categories", "nextsek" ),
		"popular_items" => esc_html__( "Popular Categories", "nextsek" ),
		"separate_items_with_commas" => esc_html__( "Separate Categories with commas", "nextsek" ),
		"add_or_remove_items" => esc_html__( "Add or remove Categories", "nextsek" ),
		"choose_from_most_used" => esc_html__( "Choose from the most used Categories", "nextsek" ),
		"not_found" => esc_html__( "No Categories found", "nextsek" ),
		"no_terms" => esc_html__( "No Categories", "nextsek" ),
		"items_list_navigation" => esc_html__( "Categories list navigation", "nextsek" ),
		"items_list" => esc_html__( "Categories list", "nextsek" ),
		"back_to_items" => esc_html__( "Back to Categories", "nextsek" ),
		"name_field_description" => esc_html__( "The name is how it appears on your site.", "nextsek" ),
		"parent_field_description" => esc_html__( "Assign a parent term to create a hierarchy. The term Jazz, for example, would be the parent of Bebop and Big Band.", "nextsek" ),
		"slug_field_description" => esc_html__( "The slug is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.", "nextsek" ),
		"desc_field_description" => esc_html__( "The description is not prominent by default; however, some themes may show it.", "nextsek" ),
	];

	
	$args = [
		"label" => esc_html__( "Categories", "nextsek" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'category', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "category",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];
	register_taxonomy( "category", [ "product" ], $args );
}
cptui_register_my_taxes_category();
// add_action( 'init', 'cptui_register_my_taxes_category' );
