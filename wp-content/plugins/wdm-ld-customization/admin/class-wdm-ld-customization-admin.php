<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wisdmlabs.com
 * @since      1.0.0
 *
 * @package    Wdm_Ld_Customization
 * @subpackage Wdm_Ld_Customization/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wdm_Ld_Customization
 * @subpackage Wdm_Ld_Customization/admin
 * @author     WisdmLabs <software@wisdmlabs.com>
 */
class Wdm_Ld_Customization_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wdm_Ld_Customization_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wdm_Ld_Customization_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wdm-ld-customization-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wdm_Ld_Customization_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wdm_Ld_Customization_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wdm-ld-customization-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Adds the SMS settings metabox to the given post type.
	 */
	public function wdm_add_sms_settings() {
		$screen = 'ld-notification';

		add_meta_box( 'wdm_learndash_sms_settings', __( 'SMS Notification', 'wdm-ld-customization' ), array( $this, 'wdm_ld_sms_settings_callback' ), $screen, 'side', 'default' );
	}

	/**
	 * Renders the SMS settings metabox.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function wdm_ld_sms_settings_callback( $post ) {
		$post_id          = $post->ID;
		$sms_notification = get_post_meta( $post_id, 'wdm_sms_notification', true );
		ob_start();
		?>
		<div class="sfwd sfwd_options inputs-wrapper ld_notifications_metabox_settings wdm_ld_sms_settings">
			<label for="wdm_send_sms">Send Notification via SMS</label>
			<input type="checkbox" name="wdm_send_sms" id="wdm_send_sms" value="yes" <?php echo 'yes' === $sms_notification ? esc_attr( 'checked' ) : ''; ?> >
		</div>
		<?php
		$content = ob_get_clean();
		echo $content;
	}

	/**
	 * Save the SMS settings meta.
	 *
	 * @param int $notification_id The notification ID.
	 */
	public function wdm_save_sms_settings_meta( $notification_id ) {
		$notification = get_post( $notification_id );

		if ( ! isset( $_POST['learndash_notifications_nonce'] ) ) {
			return;
		}

		if ( 'ld-notification' !== $notification->post_type || ! check_admin_referer( 'learndash_notifications_meta_box', 'learndash_notifications_nonce' ) ) {
			return;
		}
		// Delete post meta if the array key 'wdm_send_sms' not present.
		if ( ! isset( $_POST['wdm_send_sms'] ) ) {
			delete_post_meta( $notification_id, 'wdm_sms_notification', 'yes' );
		}

		// Add post meta if the array value is present.
		if ( isset( $_POST['wdm_send_sms'] ) && 'yes' === $_POST['wdm_send_sms'] ) {
			update_post_meta( $notification_id, 'wdm_sms_notification', 'yes' );
		}
	}
}
