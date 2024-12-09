<?php
/**
 * Plugin Name: Manage Inactive Authors
 * Plugin URI:  https://github.com/norcross/manage-inactive-authors
 * Description: Set inactive users down to subscriber status based on latest published post.
 * Version:     0.0.1
 * Author:      Andrew Norcross
 * Author URI:  https://andrewnorcross.com
 * Text Domain: manage-inactive-authors
 * Domain Path: /languages
 * License:     MIT
 * License URI: https://opensource.org/licenses/MIT
 *
 * @package ManageInactiveAuthors
 */

// Declare our namespace.
namespace Norcross\ManageInactiveAuthors;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

// Define our plugin version.
define( __NAMESPACE__ . '\VERS', '0.0.1' );

// Plugin root file.
define( __NAMESPACE__ . '\FILE', __FILE__ );

// Define our file base.
define( __NAMESPACE__ . '\BASE', plugin_basename( __FILE__ ) );

// Plugin Folder URL.
define( __NAMESPACE__ . '\URL', plugin_dir_url( __FILE__ ) );

// Set our assets URL constant.
define( __NAMESPACE__ . '\ASSETS_URL', URL . 'assets' );

// Set our includes and template path constants.
define( __NAMESPACE__ . '\INCLUDES_PATH', __DIR__ . '/includes' );

// Set the various prefixes for our actions and filters.
define( __NAMESPACE__ . '\HOOK_PREFIX', 'manage_inactive_authors_' );
define( __NAMESPACE__ . '\NONCE_PREFIX', 'miauth_nonce_' );
define( __NAMESPACE__ . '\TRANSIENT_PREFIX', 'miu_tr_' );
define( __NAMESPACE__ . '\OPTION_PREFIX', 'miauthor_setting_' );

// Set our menu root.
define( __NAMESPACE__ . '\MENU_ROOT', 'manage-inactive-authors' );

// Now we handle all the various file loading.
manage_inactive_authors_file_load();

/**
 * Actually load our files.
 *
 * @return void
 */
function manage_inactive_authors_file_load() {

	// Load the multi-use files first.
	require_once __DIR__ . '/includes/utilities.php';
	require_once __DIR__ . '/includes/helpers.php';

	// Handle our admin items.
	require_once __DIR__ . '/includes/admin/setup.php';
	require_once __DIR__ . '/includes/admin/markup.php';
	require_once __DIR__ . '/includes/admin/menu-items.php';
	require_once __DIR__ . '/includes/admin/notices.php';
	require_once __DIR__ . '/includes/admin/process.php';

	// Load the triggered file loads.
	require_once __DIR__ . '/includes/deactivate.php';
	require_once __DIR__ . '/includes/uninstall.php';
}
