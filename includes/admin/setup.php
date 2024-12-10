<?php
/**
 * Handle any admin-related setup.
 *
 * @package ManageInactiveAuthors
 */

// Declare our namespace.
namespace Norcross\ManageInactiveAuthors\Admin\Setup;

// Set our aliases.
use Norcross\ManageInactiveAuthors as Core;

/**
 * Start our engines.
 */
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\load_admin_core_assets', 10 );
add_filter( 'removable_query_args', __NAMESPACE__ . '\add_removable_args' );

/**
 * Load any admin CSS or JS as needed.
 *
 * @param  string $admin_hook  The hook of the page we're on.
 *
 * @return void
 */
function load_admin_core_assets( $admin_hook ) {

	// Only run this on our page.
	if ( empty( $admin_hook ) || 'users_page_' . Core\MENU_ROOT !== $admin_hook ) {
		return;
	}

	// Set my handle.
	$handle = 'manage-inactive-authors';

	// Set a file suffix structure based on whether or not we want a minified version.
	$file   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? $handle : $handle . '.min';

	// Set a version for whether or not we're debugging.
	$vers   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? time() : Core\VERS;

	// Load our primary CSS file.
	wp_enqueue_style( $handle, Core\ASSETS_URL . '/css/' . $file . '.css', false, $vers, 'all' );
}

/**
 * Add our custom strings to the vars.
 *
 * @param  array $args  The existing array of args.
 *
 * @return array $args  The modified array of args.
 */
function add_removable_args( $args ) {

	// Set the array of new args.
	$setup_custom_args  = [
		'miauthors-status',
		'miauthors-success',
		'miauthors-action-complete',
		'miauthors-action-result',
		'miauthors-error-code',
	];

	// Include my new args and return.
	return wp_parse_args( $setup_custom_args, $args );
}
