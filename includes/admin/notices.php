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
use Norcross\ManageInactiveAuthors\Utilities as Utilities;
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

	// $confirm_action = filter_input( INPUT_POST, 'miauthors-admin-criteria-submit', FILTER_SANITIZE_SPECIAL_CHARS );

	// Make sure we have the completed flags.
	if ( empty( $_GET['miauthors-action-complete'] ) || empty( $_GET['miauthors-action-result'] ) ) {
		return;
	}

	// Determine the message type.
	$result_type    = ! empty( $_GET['miauthors-success'] ) ? 'success' : 'error';

	// Handle dealing with an error return.
	if ( 'error' === $result_type ) {

		// Figure out my error code.
		$error_code = ! empty( $_GET['miauthors-error-code'] ) ? $_GET['miauthors-error-code'] : 'unknown';

		// Handle my error text retrieval.
		$error_text = Helpers\get_error_notice_text( $error_code );

		// Make sure the error type is correct, since one is more informational.
		$error_type = 'NO-INACTIVE-USERS' === $error_code ? 'info' : 'error';

		// And handle the display.
		AdminMarkup\display_admin_notice_markup( $error_text, $error_type );

		// And be done.
		return;
	}

	// Handle my success message based on the clear flag.
	if ( 'cleared' === sanitize_text_field( $_GET['miauthors-action-result'] ) ) {
		$alert_text = __( 'Success! The pending data has been cleared.', 'manage-inactive-authors' );
	} else {
		$alert_text = __( 'Success! The selected users have been updated to Subscriber status.', 'manage-inactive-authors' );
	}

	// And handle the display.
	AdminMarkup\display_admin_notice_markup( $alert_text, 'success' );

	// And be done.
	return;
}
