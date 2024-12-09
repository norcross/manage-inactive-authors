<?php
/**
 * Handle the processing involves.
 *
 * @package ManageInactiveAuthors
 */

// Declare our namespace.
namespace Norcross\ManageInactiveAuthors\Admin\Process;

// Set our aliases.
use Norcross\ManageInactiveAuthors as Core;
use Norcross\ManageInactiveAuthors\Utilities as Utilities;
use Norcross\ManageInactiveAuthors\Helpers as Helpers;

/**
 * Start our engines.
 */
add_action( 'admin_init', __NAMESPACE__ . '\run_criteria_lookup' );
add_action( 'admin_init', __NAMESPACE__ . '\run_pending_data_clear' );
add_action( 'admin_init', __NAMESPACE__ . '\run_pending_user_updates' );

/**
 * Load any admin CSS or JS as needed.
 *
 * @return void
 */
function run_criteria_lookup() {

	// Confirm we requested this action.
	$confirm_action = filter_input( INPUT_POST, 'miauthors-admin-criteria-submit', FILTER_SANITIZE_SPECIAL_CHARS ); // phpcs:ignore -- the nonce check is happening after this.

	// Make sure it is what we want.
	if ( empty( $confirm_action ) || 'go' !== $confirm_action ) {
		return;
	}

	// Make sure we have a nonce.
	$confirm_nonce  = filter_input( INPUT_POST, 'miauthors-nonce-criteria', FILTER_SANITIZE_SPECIAL_CHARS ); // phpcs:ignore -- the nonce check is happening after this.

	// Handle the nonce check.
	if ( empty( $confirm_nonce ) || ! wp_verify_nonce( $confirm_nonce, Core\NONCE_PREFIX . 'criteria_submit' ) ) {

		// Let them know they had a failure.
		wp_die( esc_html__( 'There was an error validating the nonce.', 'manage-inactive-users' ), esc_html__( 'Manage Inactive Authors', 'manage-inactive-users' ), [ 'back_link' => true ] );
	}

	// Get the passed critera entries.
	$user_criteria  = filter_input( INPUT_POST, 'miauthors-criteria-settings', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY );

	// Error out with nothing.
	if ( empty( $user_criteria ) ) {
		Utilities\redirect_admin_action_result( 'NO-CRITERIA' );
	}

	// Error out with the date items missing.
	if ( empty( $user_criteria['roles'] ) ) {
		Utilities\redirect_admin_action_result( 'MISSING-USER-ROLES' );
	}

	// Error out with the date items missing.
	if ( empty( absint( $user_criteria['number'] ) ) || empty( absint( $user_criteria['range'] ) ) ) {
		Utilities\redirect_admin_action_result( 'MISSING-DATE-INFO' );
	}

	// Make sure the array is clean.
	$set_role_array = array_map( 'sanitize_text_field', $user_criteria['roles'] );

	// Get the timestamp we need based on the range.
	$inactive_stamp = Helpers\calculate_date_for_query( absint( $user_criteria['number'] ), absint( $user_criteria['range'] ) );

	// Build the arguments for the users.
	$user_lookup_args   = [
		'fields'   => 'ids',
		'role__in' => $set_role_array,
		'number'   => -1
	];

	// Pull my contributors list.
	$build_user_array   = get_users( $user_lookup_args );

	// Now do the lookup.
	$maybe_has_inactive = Helpers\get_inactive_user_ids( $build_user_array, $inactive_stamp );

	// Error out with no users to convert.
	if ( empty( $maybe_has_inactive ) ) {
		Utilities\redirect_admin_action_result( 'NO-INACTIVE-USERS' );
	}

	// Store the relevant data.
	Helpers\set_pending_user_ids( $maybe_has_inactive, $inactive_stamp );

	// And redirect with our good one.
	Utilities\redirect_admin_pending_status();
}

/**
 * Clear the pending data if we requested it.
 *
 * @return void
 */
function run_pending_data_clear() {

	// Confirm we requested this action.
	$confirm_action = filter_input( INPUT_POST, 'miauthors-admin-pending-clear', FILTER_SANITIZE_SPECIAL_CHARS ); // phpcs:ignore -- the nonce check is happening after this.

	// Make sure it is what we want.
	if ( empty( $confirm_action ) || 'go' !== $confirm_action ) {
		return;
	}

	// Make sure we have a nonce.
	$confirm_nonce  = filter_input( INPUT_POST, 'miauthors-nonce-pending', FILTER_SANITIZE_SPECIAL_CHARS ); // phpcs:ignore -- the nonce check is happening after this.

	// Handle the nonce check.
	if ( empty( $confirm_nonce ) || ! wp_verify_nonce( $confirm_nonce, Core\NONCE_PREFIX . 'pending_submit' ) ) {

		// Let them know they had a failure.
		wp_die( esc_html__( 'There was an error validating the nonce.', 'manage-inactive-users' ), esc_html__( 'Manage Inactive Authors', 'manage-inactive-users' ), [ 'back_link' => true ] );
	}

	// Delete the data.
	Helpers\clear_pending_data();

	// And redirect with the success flag.
	Utilities\redirect_admin_action_result( '', 'cleared', true );
}

/**
 * Handle the actual pending user actions.
 *
 * @return void
 */
function run_pending_user_updates() {

	// Confirm we requested this action.
	$confirm_action = filter_input( INPUT_POST, 'miauthors-admin-pending-submit', FILTER_SANITIZE_SPECIAL_CHARS ); // phpcs:ignore -- the nonce check is happening after this.

	// Make sure it is what we want.
	if ( empty( $confirm_action ) || 'go' !== $confirm_action ) {
		return;
	}

	// Make sure we have a nonce.
	$confirm_nonce  = filter_input( INPUT_POST, 'miauthors-nonce-pending', FILTER_SANITIZE_SPECIAL_CHARS ); // phpcs:ignore -- the nonce check is happening after this.

	// Handle the nonce check.
	if ( empty( $confirm_nonce ) || ! wp_verify_nonce( $confirm_nonce, Core\NONCE_PREFIX . 'pending_submit' ) ) {

		// Let them know they had a failure.
		wp_die( esc_html__( 'There was an error validating the nonce.', 'manage-inactive-users' ), esc_html__( 'Manage Inactive Authors', 'manage-inactive-users' ), [ 'back_link' => true ] );
	}

	// Fetch the user IDs we have stored.
	$get_pending_users  = Helpers\maybe_has_pending( true );

	// Error out with no users to convert.
	if ( empty( $get_pending_users ) ) {
		Utilities\redirect_admin_action_result( 'NO-INACTIVE-USERS' );
	}

	// Loop our user IDs and update them.
	foreach ( $get_pending_users as $user_id ) {

		// Fetch the WP_User object of our user.
		$get_user_obj   = new \WP_User( absint( $user_id ) );

		// Replace the current role with 'subscriber' role.
		$get_user_obj->set_role( 'subscriber' );
	}

	// Delete the data.
	Helpers\clear_pending_data( false );

	// Set our last run timestamp.
	update_option( Core\OPTION_PREFIX . 'last_run', time(), 'no' );

	// And redirect with the success flag.
	Utilities\redirect_admin_action_result( '', 'updated', true );
}
