<?php
/**
 * Created by PhpStorm.
 * User: udit
 * Date: 05/01/16
 * Time: 11:33
 */

/**
 * Plugin Name: Giant Lamp WP Plugin
 * Plugin URI: http://blog.incognitech.in/
 * Description: A WordPress plugin for Giant Lamp To-Do App
 * Version: 0.7.1
 * Author: desaiuditd
 * Author URI: http://blog.incognitech.in
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gl_init() {
	$labels = array(
		'name'                  => _x( 'ToDos', 'Post Type General Name', 'todo' ),
		'singular_name'         => _x( 'ToDo', 'Post Type Singular Name', 'todo' ),
		'menu_name'             => __( 'ToDo', 'todo' ),
		'name_admin_bar'        => __( 'ToDo', 'todo' ),
		'archives'              => __( 'ToDo Archives', 'todo' ),
		'parent_item_colon'     => __( 'Parent ToDo:', 'todo' ),
		'all_items'             => __( 'All ToDos', 'todo' ),
		'add_new_item'          => __( 'Add New ToDo', 'todo' ),
		'add_new'               => __( 'Add New', 'todo' ),
		'new_item'              => __( 'New ToDo', 'todo' ),
		'edit_item'             => __( 'Edit ToDo', 'todo' ),
		'update_item'           => __( 'Update ToDo', 'todo' ),
		'view_item'             => __( 'View ToDo', 'todo' ),
		'search_items'          => __( 'Search ToDo', 'todo' ),
		'not_found'             => __( 'Not found', 'todo' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'todo' ),
		'featured_image'        => __( 'Featured Image', 'todo' ),
		'set_featured_image'    => __( 'Set featured image', 'todo' ),
		'remove_featured_image' => __( 'Remove featured image', 'todo' ),
		'use_featured_image'    => __( 'Use as featured image', 'todo' ),
		'insert_into_item'      => __( 'Insert into To-Do', 'todo' ),
		'uploaded_to_this_item' => __( 'Uploaded to this ToDo', 'todo' ),
		'items_list'            => __( 'ToDos list', 'todo' ),
		'items_list_navigation' => __( 'ToDos list navigation', 'todo' ),
		'filter_items_list'     => __( 'Filter ToDos list', 'todo' ),
	);
	$args = array(
		'label'                 => __( 'ToDo', 'todo' ),
		'description'           => __( 'ToDo', 'todo' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		'taxonomies'            => array( 'category' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'show_in_rest'          => true,
		'rest_base'             => 'todos',
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type( 'todo', $args );
}
add_action( 'init', 'gl_init' );
