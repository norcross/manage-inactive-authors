<?php
/**
 * Handle any admin notices.
 *
 * @package ManageInactiveAuthors
 */

// Declare our namespace.
namespace Norcross\ManageInactiveAuthors\Admin\Notices;

// Set our aliases.
use Norcross\ManageInactiveAuthors as Core;
use Norcross\ManageInactiveAuthors\Helpers as Helpers;
use Norcross\ManageInactiveAuthors\Admin\Markup as AdminMarkup;

/**
 * Start our engines.
 */
add_action( 'admin_notices', __NAMESPACE__ . '\display_admin_notices' );

/**
 * Display our admin notices.
 *
 * @return void
 */
function display_admin_notices() {

	// Make sure this is the correct admin page.
	$confirm_admin  = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS );

	// Make sure it is what we want.
	if ( empty( $confirm_admin ) || Core\MENU_ROOT !== $confirm_admin ) {
		return;
	}

	// Check for our complete flag.
	$confirm_action = filter_input( INPUT_GET, 'miauthors-action-complete', FILTER_SANITIZE_SPECIAL_CHARS );

	// Make sure it is what we want.
	if ( empty( $confirm_action ) || 'yes' !== $confirm_action ) {
		return;
	}

	// Now check for the result.
	$confirm_result = filter_input( INPUT_GET, 'miauthors-action-result', FILTER_SANITIZE_SPECIAL_CHARS );

	// Make sure we have a result to show.
	if ( empty( $confirm_result ) ) {
		return;
	}

	// Determine the message type.
	$maybe_failed   = filter_input( INPUT_GET, 'miauthors-success', FILTER_SANITIZE_SPECIAL_CHARS );
	$confirm_type   = ! empty( $maybe_failed ) ? 'success' : 'error';

	// Handle dealing with an error return.
	if ( 'error' === $confirm_type ) {

		// Figure out my error code.
		$maybe_code = filter_input( INPUT_GET, 'miauthors-error-code', FILTER_SANITIZE_SPECIAL_CHARS );
		$error_code = ! empty( $maybe_code ) ? $maybe_code : 'unknown';

		// Handle my error text retrieval.
		$error_text = Helpers\get_admin_notice_text( $error_code );

		// Make sure the error type is correct, since one is more informational.
		$error_type = 'no-inactive-users' === $error_code ? 'info' : 'error';

		// And handle the display.
		AdminMarkup\display_admin_notice_markup( $error_text, $error_type );

		// And be done.
		return;
	}

	// Handle my success message based on the clear flag.
	$alert_text = Helpers\get_admin_notice_text( $confirm_result );

	// And handle the display.
	AdminMarkup\display_admin_notice_markup( $alert_text, 'success' );

	// And be done.
	return;
}
