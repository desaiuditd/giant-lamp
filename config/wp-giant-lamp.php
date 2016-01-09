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
		'supports'              => array( 'title', 'author' ),
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

/**
 *
 * Basic Auth
 */

function json_basic_auth_handler( $user ) {
	global $wp_json_basic_auth_error;
	$wp_json_basic_auth_error = null;
	// Don't authenticate twice
	if ( ! empty( $user ) ) {
		return $user;
	}
	// Check that we're trying to authenticate
	if ( !isset( $_SERVER['PHP_AUTH_USER'] ) ) {
		return $user;
	}
	$username = $_SERVER['PHP_AUTH_USER'];
	$password = $_SERVER['PHP_AUTH_PW'];
	/**
	 * In multi-site, wp_authenticate_spam_check filter is run on authentication. This filter calls
	 * get_currentuserinfo which in turn calls the determine_current_user filter. This leads to infinite
	 * recursion and a stack overflow unless the current function is removed from the determine_current_user
	 * filter during authentication.
	 */
	remove_filter( 'determine_current_user', 'json_basic_auth_handler', 20 );
	$user = wp_authenticate( $username, $password );
	add_filter( 'determine_current_user', 'json_basic_auth_handler', 20 );
	if ( is_wp_error( $user ) ) {
		$wp_json_basic_auth_error = $user;
		return null;
	}
	$wp_json_basic_auth_error = true;
	return $user->ID;
}
add_filter( 'determine_current_user', 'json_basic_auth_handler', 20 );
function json_basic_auth_error( $error ) {
	// Passthrough other errors
	if ( ! empty( $error ) ) {
		return $error;
	}
	global $wp_json_basic_auth_error;
	return $wp_json_basic_auth_error;
}
add_filter( 'json_authentication_errors', 'json_basic_auth_error' );
