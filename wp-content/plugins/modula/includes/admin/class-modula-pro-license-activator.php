<?php

class Modula_Pro_License_Activator {

	private $main_item_name = 'Modula Grid Gallery';

	function __construct() {

		add_action( 'admin_init', array( $this, 'register_license_option' ) );
		add_action( 'admin_init', array( $this, 'activate_license' ) );
		add_action( 'admin_init', array( $this, 'deactivate_license' ) );
		add_action( 'modula_license_errors', array( $this, 'admin_notices' ) );

	}

	public function activate_license() {

		// listen for our activate button to be clicked
		if ( isset( $_POST['modula_pro_license_activate'] ) ) {

			// run a quick security check
			if ( ! check_admin_referer( 'modula_pro_license_nonce', 'modula_pro_license_nonce' ) ) {
				return;
			}

			// retrieve the license from the database
			$license = trim( get_option( 'modula_pro_license_key' ) );

			// data to send in our API request
			$api_params = array(
				'edd_action' => 'activate_license',
				'license'    => $license,
				'item_id'    => MODULA_PRO_STORE_ITEM_ID,
				'url'        => home_url(),
			);

			// Call the custom API.
			$response = wp_remote_post(
				MODULA_PRO_STORE_URL,
				array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      => $api_params,
				)
			);

			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.', 'modula' );
				}
			} else {
				$license_data = json_decode( wp_remote_retrieve_body( $response ) );
				if ( false === $license_data->success ) {
					switch ( $license_data->error ) {
						case 'expired':
							$message = sprintf(
								__( 'Your license key expired on %s.', 'modula' ),
								date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
							);
							break;
						case 'disabled':
						case 'revoked':
							$message = __( 'Your license key has been disabled.', 'modula' );
							break;
						case 'missing':
							$message = __( 'Invalid license.', 'modula' );
							break;
						case 'invalid':
						case 'site_inactive':
							$message = __( 'Your license is not active for this URL.', 'modula' );
							break;
						case 'item_name_mismatch':
							$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'modula' ), $this->main_item_name );
							break;
						case 'no_activations_left':
							$message = __( 'Your license key has reached its activation limit.', 'modula' );
							break;
						default:
							$message = __( 'An error occurred, please try again.', 'modula' );
							break;
					}
				}
			}

			// Check if anything passed on a message constituting a failure
			if ( ! empty( $message ) ) {
				$base_url = admin_url( 'edit.php?post_type=modula-gallery&page=modula&modula-tab=licenses' );
				$redirect = add_query_arg(
					array(
						'sl_activation' => 'false',
						'message'       => urlencode( $message ),
					),
					$base_url
				);
				wp_redirect( $redirect );
				exit();
			}

			// $license_data->license will be either "valid" or "invalid"
			update_option( 'modula_pro_license_status', $license_data );
			wp_redirect( admin_url( 'edit.php?post_type=modula-gallery&page=modula&modula-tab=licenses' ) );
			exit();
		}
	}

	public function deactivate_license() {

		// listen for our activate button to be clicked
		if ( isset( $_POST['modula_pro_license_deactivate'] ) ) {

			// run a quick security check
			if ( ! check_admin_referer( 'modula_pro_license_nonce', 'modula_pro_license_nonce' ) ) {
				return;
			}

			// retrieve the license from the database
			$license = trim( get_option( 'modula_pro_license_key' ) );

			// data to send in our API request
			$api_params = array(
				'edd_action' => 'deactivate_license',
				'license'    => $license,
				'item_id'    => MODULA_PRO_STORE_ITEM_ID,
				'url'        => home_url(),
			);

			// Call the custom API.
			$response = wp_remote_post(
				MODULA_PRO_STORE_URL,
				array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      => $api_params,
				)
			);

			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.', 'modula' );
				}

				$base_url = admin_url( 'edit.php?post_type=modula-gallery&page=modula&modula-tab=licenses' );
				$redirect = add_query_arg(
					array(
						'sl_activation' => 'false',
						'message'       => urlencode( $message ),
					),
					$base_url
				);
				wp_redirect( $redirect );
				exit();
			}

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if ( $license_data->license == 'deactivated' ) {
				delete_option( 'modula_pro_license_status' );
			}

			wp_redirect( admin_url( 'edit.php?post_type=modula-gallery&page=modula&modula-tab=licenses' ) );
			exit();
		}
	}

	public function register_license_option() {
		// creates our settings in the options table
		register_setting( 'modula_pro_license_key', 'modula_pro_license_key', array( $this, 'sanitize_license' ) );
	}

	public function sanitize_license( $new ) {
		$old = get_option( 'modula_pro_license_key' );
		if ( $old && $old != $new ) {
			delete_option( 'modula_pro_license_status' ); // new license has been entered, so must reactivate
			delete_transient( 'modula_pro_licensed_extensions' );
		}
		return $new;
	}

	function admin_notices() {
		if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {
			switch ( $_GET['sl_activation'] ) {
				case 'false':
					$message = urldecode( $_GET['message'] );
					?>
					<div class="error">
						<p><?php echo esc_html( $message ); ?></p>
					</div>
					<?php
					break;
				case 'true':
				default:
					// Developers can put a custom success message here for when activation is successful if they way.
					break;
			}
		}
	}
}

new Modula_Pro_License_Activator();
