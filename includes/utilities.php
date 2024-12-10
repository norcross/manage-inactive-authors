<?php
/**
 * Our utility functions to use across the plugin.
 *
 * @package ManageInactiveAuthors
 */

// Call our namepsace.
namespace Norcross\ManageInactiveAuthors\Utilities;

// Set our aliases.
use Norcross\ManageInactiveAuthors as Core;

/**
 * Fetch the admin menu link on the tools menu.
 *
 * @return string
 */
function get_admin_menu_link() {

	// Bail if we aren't on the admin side.
	if ( ! is_admin() ) {
		return false;
	}

	// Set the root menu page and the admin base.
	$set_menu_root  = trim( Core\MENU_ROOT );

	// If we're doing Ajax, build it manually.
	if ( wp_doing_ajax() ) {
		return add_query_arg( [ 'page' => $set_menu_root ], admin_url( 'users.php' ) );
	}

	// Use the `menu_page_url` function if we have it.
	if ( function_exists( 'menu_page_url' ) ) {

		// Return using the function.
		return menu_page_url( $set_menu_root, false );
	}

	// Build out the link if we don't have our function.
	return add_query_arg( [ 'page' => $set_menu_root ], admin_url( 'users.php' ) );
}

/**
 * Redirect based on an edit action result.
 *
 * @param  string  $error    Optional error code.
 * @param  string  $result   What the result of the action was.
 * @param  boolean $success  Whether it was successful.
 *
 * @return void
 */
function redirect_admin_action_result( $error = '', $result = 'failed', $success = false ) {

	// Set our base redirect link.
	$base_redirect  = get_admin_menu_link();

	// Set up my redirect args.
	$redirect_args  = [
		'miauthors-success'         => $success,
		'miauthors-action-complete' => 'yes',
		'miauthors-action-result'   => esc_attr( $result ),
	];

	// Add the error code if we have one.
	$redirect_args  = ! empty( $error ) ? wp_parse_args( $redirect_args, [ 'miauthors-error-code' => esc_attr( $error ) ] ) : $redirect_args;

	// Now set my redirect link.
	$redirect_link  = add_query_arg( $redirect_args, $base_redirect );

	// Do the redirect.
	wp_safe_redirect( $redirect_link );
	exit;
}

/**
 * Redirect just for the 2nd step of changing.
 *
 * @return void
 */
function redirect_admin_pending_status() {

	// Set our base redirect link.
	$base_redirect  = get_admin_menu_link();

	// Now set my redirect link.
	$redirect_link  = add_query_arg( ['miauthors-status' => 'pending'], $base_redirect );

	// Do the redirect.
	wp_safe_redirect( $redirect_link );
	exit;
}
