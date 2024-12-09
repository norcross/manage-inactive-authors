<?php
/**
 * Our uninstall call.
 *
 * @package ManageInactiveAuthors
 */

// Declare our namespace.
namespace Norcross\ManageInactiveAuthors\Uninstall;

// Set our aliases.
use Norcross\ManageInactiveAuthors as Core;
use Norcross\ManageInactiveAuthors\Helpers as Helpers;

/**
 * Delete various options when uninstalling the plugin.
 *
 * @return void
 */
function uninstall() {

	// Delete the data.
	Helpers\clear_pending_data();
}
register_uninstall_hook( Core\FILE, __NAMESPACE__ . '\uninstall' );
