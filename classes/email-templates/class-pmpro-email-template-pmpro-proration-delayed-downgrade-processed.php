<?php


// $templates['delayed_downgrade_processed'] = array(
// 	'subject' => esc_html( sprintf( __( 'Your downgrade has been processed at %s', 'pmpro-proration' ), get_option( 'blogname' ) ) ),
// 	'description' => esc_html__( 'Proration Downgrade Processed', 'pmpro-proration' ),
// 	'body' => pmprorate_get_default_delayed_downgrade_processed_email_body(),
// 	'help_text' => esc_html__( 'This email is sent when a membership downgrade is processed.', 'pmpro-proration' ),
// );

class PMPro_Email_Template_PMProRate_Delayed_Downgrade_Processed extends PMPro_Email_Template {

	/**
	 * The parent user.
	 *
	 * @var WP_User
	 */
	protected $user;

	/**
	 * Constructor.
	 *
	 * @since TBD
	 *
	 * @param WP_User $user The user downgrading.
	 * @param PMProrate_Downgrade $downgrade The downgrade object.
	 */
	public function __construct( WP_User $user ) {
		$this->user = $user;
	}

	/**
	 * Get the email template slug.
	 *
	 * @since TBD
	 *
	 * @return string The email template slug.
	 */
	public static function get_template_slug() {
		return 'delayed_downgrade_processed';
	}

	/**
	 * Get the "nice name" of the email template.
	 *
	 * @since TBD
	 *
	 * @return string The "nice name" of the email template.
	 */
	public static function get_template_name() {
		return esc_html__( 'Proration Downgrade Processed', 'pmpro-proration' );
	}

	/**
	 * Get "help text" to display to the admin when editing the email template.
	 *
	 * @since TBD
	 *
	 * @return string The "help text" to display to the admin when editing the email template.
	 */
	public static function get_template_description() {
		return esc_html__( 'This email is sent when a membership downgrade is processed.', 'pmpro-prorate' );
	}

	/**
	 * Get the default subject for the email.
	 *
	 * @since TBD
	 *
	 * @return string The default subject for the email.
	 */
	public static function get_default_subject() {
		return esc_html__( sprintf( __( 'Your downgrade has been processed at %s', 'pmpro-prorate' ), get_option( 'blogname' ) ) );
	}

	/**
	 * Get the default body content for the email.
	 *
	 * @since TBD
	 *
	 * @return string The default body content for the email.
	 */
	public static function get_default_body() {
			return wp_kses_post( __( pmprorate_get_default_delayed_downgrade_processed_email_body() ) );
	}

	/**
	 * Get the email template variables for the email paired with a description of the variable.
	 *
	 * @since TBD
	 *
	 * @return array The email template variables for the email (key => value pairs).
	 */
	public static function get_email_template_variables_with_description() {
	
		return array(
			'!!display_name!!' => esc_html__( 'The user\'s display name.', 'pmpro-proration' ),
		);
	}

	/**
	 * Get the email template variables for the email.
	 *
	 * @since TBD
	 *
	 * @return array The email template variables for the email (key => value pairs).
	 */
	public function get_email_template_variables() {
		$user = $this->user;
		$email_template_variables = array(	
			'display_name' => $user->display_name,
			'edit_member_downgrade_url' => admin_url( 'admin.php?page=pmpro-member&user_id=' . $user->ID . '&pmpro_member_edit_panel=pmprorate-downgrades' ),
		);
		return $email_template_variables;
	}

	/**
	 * Get the email address to send the email to.
	 *
	 * @since TBD
	 *
	 * @return string The email address to send the email to.
	 */
	public function get_recipient_email() {
		return $this->user->user_email;
	}

	/**
	 * Get the name of the email recipient.
	 *
	 * @since TBD
	 *
	 * @return string The name of the email recipient.
	 */
	public function get_recipient_name() {
		$user = $this->user;
		return empty( $user->display_name ) ? esc_html__( 'User', 'pmpro-proration' ) : $user->display_name;
	}

	/**
	 * Send a test email.
	 *
	 * @since TBD
	 *
	 * @param string $email The email address to send the test email to.
	 * @return bool Whether the email was sent successfully.
	 */
	public static function send_test( $email ) {
		global $current_user;

		//Instantiate this class with mock data to get access to the non-static methods
		$test_checkout_check_template = new PMPro_Email_Template_PMProRate_Delayed_Downgrade_Processed( $current_user );

		$test_email = new PMProEmail();
		$test_email->email = $email;
		$test_email->subject  =  self::get_default_subject();
		// Add test mail text to the default body
		$test_email->body = pmpro_email_templates_test_body( self::get_default_body() );
		$test_email->data = array_merge( $test_checkout_check_template->get_base_email_template_variables(),
			$test_checkout_check_template->get_email_template_variables() );
		$test_email->template = self::get_template_slug();
		return $test_email->sendEmail();
	}
}
/**
 * Register the email template.
 *
 * @since TBD
 *
 * @param array $email_templates The email templates (template slug => email template class name)
 * @return array The modified email templates array.
 */
function pmpro_email_template_pmpro_proration_delayed_downgrade_processed( $email_templates ) {
	$email_templates['delayed_downgrade_processed'] = 'PMPro_Email_Template_PMProRate_Delayed_Downgrade_Processed';
	return $email_templates;
}
add_filter( 'pmpro_email_templates', 'pmpro_email_template_pmpro_proration_delayed_downgrade_processed' );
