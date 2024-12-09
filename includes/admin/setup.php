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
use Norcross\ManageInactiveAuthors\Utilities as Utilities;

/**
 * Start our engines.
 */
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\load_admin_core_assets' );
add_filter( 'removable_query_args', __NAMESPACE__ . '\add_removable_args' );

/**
 * Load any admin CSS or JS as needed.
 *
 * @return void
 */
function load_admin_core_assets() {

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
		'miu-admin-status',
		'miu-admin-success',
		'miu-admin-action-complete',
		'miu-admin-action-result',
		'miu-admin-error-code',
	];

	// Include my new args and return.
	return wp_parse_args( $setup_custom_args, $args );
}
