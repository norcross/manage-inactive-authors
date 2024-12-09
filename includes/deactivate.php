<?php
/**
 * Our deactivation call.
 *
 * @package ManageInactiveAuthors
 */

// Declare our namespace.
namespace Norcross\ManageInactiveAuthors\Deactivate;

// Set our aliases.
use Norcross\ManageInactiveAuthors as Core;
use Norcross\ManageInactiveAuthors\Helpers as Helpers;

/**
 * Delete various options when deactivating the plugin.
 *
 * @return void
 */
function deactivate() {

	// Delete the data.
	Helpers\clear_pending_data();
}
register_deactivation_hook( Core\FILE, __NAMESPACE__ . '\deactivate' );
