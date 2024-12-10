<?php
/**
 * Set up and render the markup pieces.
 *
 * @package ManageInactiveAuthors
 */

// Call our namepsace.
namespace Norcross\ManageInactiveAuthors\Admin\Markup;

// Set our alias items.
use Norcross\ManageInactiveAuthors as Core;
use Norcross\ManageInactiveAuthors\Helpers as Helpers;

/**
 * Handle fetching and using the introduction data.
 *
 * @param  boolean $echo  Whether to echo or just return it.
 *
 * @return HTML
 */
function display_admin_page_intro( $echo = true ) {

	// Check to see if we have a last run.
	$maybe_last_run = get_option( Core\OPTION_PREFIX . 'last_run' );

	// Set an empty.
	$build  = '';

	// Start with a div.
	$build .= '<div class="miauthors-settings-section-wrap miauthors-settings-intro-wrap">';

		// Display the headline if we have one.
		$build .= '<h1 class="miauthors-settings-intro-headline">' . esc_html( get_admin_page_title() ) . '</h1>';

		// And some intro content.
		if ( ! empty( $maybe_last_run ) ) {

			// Set the date we wanna show.
			$set_date_show  = gmdate( get_option( 'date_format', 'F j, Y' ), $maybe_last_run );
			$set_time_show  = gmdate( get_option( 'time_format', 'g:i a' ), $maybe_last_run );

			// And add it.
			$build .= '<p class="miauthors-settings-intro-subtitle">' . sprintf( __( 'This process was last run on %s at %s.', 'manage-inactive-authors' ), '<strong>' . esc_attr( $set_date_show ) . '</strong>', '<strong>' . esc_attr( $set_time_show ) . '</strong>' ) . '</p>';
		}

	// Close out my div.
	$build .= '</div>';

	// Return if requested.
	if ( ! $echo ) {
		return $build;
	}

	// Echo it out.
	echo $build;
}

/**
 * Set up the options for displaying the search parameters.
 *
 * @param  boolean $echo  Whether to echo or just return it.
 *
 * @return HTML
 */
function display_user_criteria_fields( $echo = true ) {

	// Set an empty.
	$build  = '';

	// Start with a div.
	$build .= '<div class="miauthors-settings-section-wrap miauthors-settings-fields-wrap">';

		// Wrap this in an actual table.
		$build .= '<table class="form-table miauthors-settings-table" role="presentation"><tbody>';

			// Set the row for the date setup.
			$build .= '<tr>';
				$build .= '<th scope="row">' . esc_html__( 'Inactive Range', 'manage-inactive-authors' ) . '</th>';
				$build .= '<td>';

					// Output the range number input field.
					$build .= '<input name="miauthors-criteria-settings[number]" step="1" min="1" id="miauthors-criteria-settings-number" value="2" class="miauthors-settings-input small-text" type="number">';

					// Output the range number select field.
					$build .= '<select name="miauthors-criteria-settings[range]" id="miauthors-criteria-settings-range" class="miauthors-settings-input">';

					// Loop my range types to make the select field.
					foreach ( Helpers\get_range_types() as $array_type => $array_label ) {
						$build .= '<option value="' . absint( $array_type ) . '" ' . selected( YEAR_IN_SECONDS, absint( $array_type ), false ) . '>' . esc_html( $array_label ) . '</option>';
					}

					// Close the select.
					$build .= '</select>';

					// And explain what it is.
					$build .= '<span class="miauthors-settings-description description">' . esc_html__( 'Set the time since the last published post.', 'manage-inactive-authors' ) . '</span>';

				$build .= '</td>';
			$build .= '</tr>';

			// Set the table row for the use role types.
			$build .= '<tr>';

				// Handle my label.
				$build .= '<th scope="row">' . esc_html__( 'User Roles', 'manage-inactive-authors' ) . '</th>';

				// Do the actual checkbox.
				$build .= '<td>';
					$build .= '<fieldset>';

						// Display the legend.
						$build .= '<legend class="screen-reader-text"><span>' . esc_html__( 'User Roles', 'manage-inactive-authors' ) . '</span></legend>';

						// Loop my user roles to make the input fields.
						foreach ( Helpers\get_user_roles() as $role_type => $role_label ) {

							// Set the field ID.
							$set_field_id   = 'miauthors-criteria-settings-role-' . sanitize_text_field( $role_type );

							// Wrap it in a span so we can do an inline.
							$build .= '<span class="miauthors-settings-checkbox-wrap">';

								// Wrap the input inside the label.
								$build .= '<label for="' . esc_attr( $set_field_id ) . '">';

									// Construct the checkbox field.
									$build .= '<input name="miauthors-criteria-settings[roles][]" type="checkbox" id="' . esc_attr( $set_field_id ) . '" value="' . esc_attr( $role_type ) . '" checked>' . esc_html( $role_label );

								// Close the label.
								$build .= '</label>';

							// And close the span.
							$build .= '</span>';
						}

					// Close the fieldset and block.
					$build .= '</fieldset>';
				$build .= '</td>';

			// Close up the row.
			$build .= '</tr>';

		// Close up the table.
		$build .= '</tbody></table>';

	// Close out my div.
	$build .= '</div>';

	// Return if requested.
	if ( ! $echo ) {
		return $build;
	}

	// Echo it out.
	echo $build;
}

/**
 * Handle rendering the submit button along with nonces.
 *
 * @param  boolean $echo  Whether to echo or return them.
 *
 * @return HTML
 */
function display_user_criteria_submit_fields( $echo = true ) {

	// Set an empty.
	$build  = '';

	// Start with a div.
	$build .= '<div class="miauthors-settings-section-wrap miauthors-settings-submit-fields-wrap">';

		// Render the hidden nonce field.
		$build .= wp_nonce_field( Core\NONCE_PREFIX . 'criteria_submit', 'miauthors-nonce-criteria', false, false );

		// Handle our submit button.
		$build .= '<button type="submit" class="miauthors-settings-button button button-primary" name="miauthors-criteria-submit" value="go">' . esc_html__( 'Search Users', 'manage-inactive-authors' ) . '</button>';

	// Close out my div.
	$build .= '</div>';

	// Return if requested.
	if ( ! $echo ) {
		return $build;
	}

	// Echo it out.
	echo $build;
}

/**
 * Set up the options for displaying the list of pending users.
 *
 * @param  array   $pending_data  The data related to the pending user changes.
 * @param  boolean $echo          Whether to echo or just return it.
 *
 * @return HTML
 */
function display_pending_users_list_fields( $pending_data = [], $echo = true ) {

	// Bail without data.
	if ( empty( $pending_data ) || empty( $pending_data['users'] ) ) {
		return;
	}

	// Now set my args for the author list itself.
	$set_user_list_args = [
		'optioncount' => 1,
		'include'     => $pending_data['users'],
		'echo'        => false,
		'style'       => 'plain',
		'hide_empty'  => false,
		'html'        => false,
	];

	// Get my plain list.
	$get_user_list_raw  = wp_list_authors( $set_user_list_args );

	// Make it an array.
	$get_user_list_arr  = explode( ',', $get_user_list_raw );

	// And handle the class.
	$setup_list_class   = 'miauthors-settings-pending-users-list';
	$setup_list_class  .= count( $pending_data['users'] ) > 30 ? ' miauthors-settings-pending-users-list-columns' : '';

	// Set an empty.
	$build  = '';

	// Start with a div.
	$build .= '<div class="miauthors-settings-section-wrap miauthors-settings-pending-users-wrap">';

		// List the count and timestamp that was used.
		$build .= '<p class="miauthors-settings-pending-users-intro">' . sprintf( __( 'You are about to change %d users to Subscriber status who have not published content since %s.', 'manage-inactive-authors' ), absint( $pending_data['count'] ), gmdate( get_option( 'date_format', 'F j, Y' ), $pending_data['stamp'] ) ) . '</p>';

		// Wrap the whole thing in a div.
		$build .= '<div class="miauthors-settings-pending-users-block">';

			// Put a list wrapper on it.
			$build .= '<ul class="' . esc_attr( $setup_list_class ) . '">';

			// Loop and display the usernames.
			foreach ( $get_user_list_arr as $username ) {
				$build .= '<li>' . esc_html( $username ) . '</li>';
			}

			// Close the list wrapper.
			$build .= '</ul>';

		// Close the div around it.
		$build .= '</div>';

	// Close out my div.
	$build .= '</div>';

	// Return if requested.
	if ( ! $echo ) {
		return $build;
	}

	// Echo it out.
	echo $build;
}

/**
 * Handle rendering the submit button along with nonces.
 *
 * @param  boolean $echo  Whether to echo or return them.
 *
 * @return HTML
 */
function display_pending_users_submit_fields( $echo = true ) {

	// Set an empty.
	$build  = '';

	// Start with a div.
	$build .= '<div class="miauthors-settings-section-wrap miauthors-settings-submit-fields-wrap">';

		// Render the hidden nonce field.
		$build .= wp_nonce_field( Core\NONCE_PREFIX . 'pending_submit', 'miauthors-nonce-pending', false, false );

		// Handle our submit button.
		$build .= '<button type="submit" class="miauthors-settings-button button button-primary" name="miauthors-pending-submit" value="go">' . esc_html__( 'Update Users', 'manage-inactive-authors' ) . '</button>';

		// And our clear / delete.
		$build .= '<button type="submit" class="miauthors-settings-button miauthors-settings-button-alt button button-secondary" name="miauthors-pending-clear" value="go">' . esc_html__( 'Clear Pending Data', 'manage-inactive-authors' ) . '</button>';

	// Close out my div.
	$build .= '</div>';

	// Return if requested.
	if ( ! $echo ) {
		return $build;
	}

	// Echo it out.
	echo $build;
}

/**
 * Build the markup for an admin notice.
 *
 * @param  string  $notice       The actual message to display.
 * @param  string  $result       Which type of message it is.
 * @param  boolean $dismiss      Whether it should be dismissable.
 * @param  boolean $show_button  Show the dismiss button (for Ajax calls).
 * @param  boolean $echo         Whether to echo out the markup or return it.
 *
 * @return HTML
 */
function display_admin_notice_markup( $notice = '', $result = 'error', $dismiss = true, $show_button = false, $echo = true ) {

	// Bail without the required message text.
	if ( empty( $notice ) ) {
		return;
	}

	// Set my base class.
	$class  = 'notice notice-' . esc_attr( $result ) . ' miauthors-admin-notice-message';

	// Add the dismiss class.
	if ( $dismiss ) {
		$class .= ' is-dismissible';
	}

	// Set an empty.
	$build  = '';

	// Start the notice markup.
	$build .= '<div class="' . esc_attr( $class ) . '">';

		// Display the actual message.
		$build .= '<p><strong>' . wp_kses_post( $notice ) . '</strong></p>';

		// Show the button if we set dismiss and button variables.
		$build .= $dismiss && $show_button ? '<button type="button" class="notice-dismiss">' . screen_reader_text() . '</button>' : '';

	// And close the div.
	$build .= '</div>';

	// Echo it if requested.
	if ( ! empty( $echo ) ) {
		echo $build; // WPCS: XSS ok.
	}

	// Just return it.
	return $build;
}
