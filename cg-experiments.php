<?php

/**
 *
 * @link              https://www.commercegurus.com
 * @since             1.0.0
 * @package           CommerceGurus_Experiments
 *
 * @wordpress-plugin
 * Plugin Name:       CommerceGurus Experiments
 * Plugin URI:        https://www.commercegurus.com
 * Description:       CommerceGurus Experiments
 * Version:           1.0.0
 * Author:            CommerceGurus
 * Author URI:        https://www.commercegurus.com
 * Requires at least: 4.9.7
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       commercegurus-experiments
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly....
}

/**
 * Required minimums and constants
 */
define( 'CG_EXPERIMENTS_MIN_WC_VER', '1.0.0' );

define( 'CG_EXPERIMENTS_BASE_PATH', plugin_dir_path( __FILE__ ) );
define( 'CG_EXPERIMENTS_BASE_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main CommerceGurus_Experiments Class
 *
 * @class CommerceGurus_Experiments
 * @version 1.0.0
 * @since 1.0.0
 * @package CommerceGurus_Experiments
 */

if ( ! class_exists( 'CommerceGurus_Experiments' ) ) {

	class CommerceGurus_Experiments {

		/**
		 * Plugin version.
		 *
		 * @var string
		 */
		const VERSION = '1.0.0';

		/**
		 * Notices (array)
		 *
		 * @var array
		 */
		public $notices = array();

		public function __construct() {

			$this->includes();

			add_action( 'admin_init', array( $this, 'check_environment' ) );
			add_action( 'admin_notices', array( $this, 'admin_notices' ), 15 );
			add_action( 'plugins_loaded', array( $this, 'init' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_styles' ) );
		}

		/**
		 * Init the plugin after plugins_loaded so environment variables are set.
		 */
		public function includes() {
			// frontend includes
			if ( ! defined( 'DOING_CRON' ) && ! is_admin() ) {
				add_action( 'wp_head', array( $this, 'cg_setup_experiments' ) );
				add_action( 'wp_head', array( $this, 'header_frontend_includes' ) );
				add_action( 'wp_footer', array( $this, 'footer_frontend_includes' ) );
			}

		}

		/**
		 * Include required header frontend files
		 *
		 * @since 1.0.0
		 */
		public function header_frontend_includes() {
			require_once dirname( __FILE__ ) . '/includes/buynowcta.php';

		}

		/**
		 * Include required footer frontend files
		 *
		 * @since 1.0.0
		 */
		public function footer_frontend_includes() {
			require_once dirname( __FILE__ ) . '/includes/bnw0.php';
			// require_once dirname( __FILE__ ) . '/includes/bnw1.php';
			// require_once dirname( __FILE__ ) . '/includes/bnw2.php';
		}

		/**
		 * Enqueues front end scripts and styles.
		 *
		 * @internal
		 *
		 * @since 1.4.5
		 */
		public function enqueue_scripts_styles() {
			$this->load_scripts();
		}

		/**
		 * Loads front end scripts.
		 *
		 * @since 1.4.5
		 */
		private function load_scripts() {
			wp_enqueue_script( 'cg-experiments', CG_EXPERIMENTS_BASE_URL . 'assets/js/experiments.js', array(), '20161205' );
			wp_enqueue_script( 'cg-lazyload', CG_EXPERIMENTS_BASE_URL . 'assets/js/lazyload.js', array(), '20161205' );
		}
		/**
		 * Init the plugin after plugins_loaded so environment variables are set.
		 */
		public function init() {
			// Don't hook anything else in the plugin if we're in an incompatible environment.
			if ( self::get_environment_warning() ) {
				return;
			}
		}

		/**
		 * The backup sanity check, in case the plugin is activated in a weird way,
		 * or the environment changes after activation.
		 */
		public function check_environment() {
			$environment_warning = self::get_environment_warning();

			if ( $environment_warning && is_plugin_active( plugin_basename( __FILE__ ) ) ) {
				$this->add_admin_notice( 'bad_environment', 'error', $environment_warning );
			}
		}

		/**
		 * Checks the environment for compatibility problems.  Returns a string with the first incompatibility
		 * found or false if the environment has no problems.
		 */
		static function get_environment_warning() {
			if ( ! defined( 'WC_VERSION' ) ) {
				return __( 'CommerceGurus Utility Belt requires WooCommerce 3.0+ to be activated to work.', 'commercegurus-utility-belt' );
			}

			if ( version_compare( WC_VERSION, CG_EXPERIMENTS_MIN_WC_VER, '<' ) ) {
				$message = __( 'CommerceGurus Utility Belt - The minimum WooCommerce version required for this plugin is %1$s. You are running %2$s.', 'commercegurus-utility-belt', 'commercegurus-utility-belt' );

				return sprintf( $message, CG_EXPERIMENTS_MIN_WC_VER, WC_VERSION );
			}

			return false;
		}

		/**
		 * Display any notices we've collected thus far.
		 */
		public function admin_notices() {
			foreach ( (array) $this->notices as $notice_key => $notice ) {
				echo "<div class='" . esc_attr( $notice['class'] ) . "'><p>";
				echo wp_kses( $notice['message'], array( 'a' => array( 'href' => array() ) ) );
				echo '</p></div>';
			}
		}

		/**
		 * Allow this class and other classes to add slug keyed notices (to avoid duplication)
		 */
		public function add_admin_notice( $slug, $class, $message ) {
			$this->notices[ $slug ] = array(
				'class'   => $class,
				'message' => $message,
			);
		}


		/**
		 * Setup Shipping Experiments
		 *
		 * @return void
		 */
		public function cg_setup_experiments() {
			if ( isset( $_GET['cgexp'] ) ) {
				if ( 'bnw1' === $_GET['cgexp'] ) { ?>
					<script>
						document.cookie = 'cgexp=bnw1;path=/' ;
					</script>
				<?php } elseif ( 'bnw2' === $_GET['cgexp'] ) { ?>
					<script>
						document.cookie = 'cgexp=bnw2;path=/' ;
					</script>
					<?php
				}
			}
		}

	}

	$CommerceGurus_Experiments = new CommerceGurus_Experiments();
}
