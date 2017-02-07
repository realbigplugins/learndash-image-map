<?php
/*
Plugin Name: LearnDash Image Map Question Type
Plugin URL: https://github.com/realbigplugins/learndash-image-map
Description: This plugin creates a new Question Type that allows Users to select pre-defined regions of an image as their Answer.
Version: 0.1.0
Text Domain: ld-image-map
Author: Eric Defore
Author URL: http://realbigmarketing.com/
Contributors: d4mation
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'LD_Image_Map' ) ) {

	/**
	 * Main LD_Image_Map class
	 *
	 * @since	  1.0.0
	 */
	class LD_Image_Map {
		
		/**
		 * @var			LD_Image_Map $plugin_data Holds Plugin Header Info
		 * @since		1.0.0
		 */
		public $plugin_data;
		
		/**
		 * @var			LD_Image_Map $admin_errors Stores all our Admin Errors to fire at once
		 * @since		1.0.0
		 */
		private $admin_errors;

		/**
		 * Get active instance
		 *
		 * @access	  public
		 * @since	  1.0.0
		 * @return	  object self::$instance The one true LD_Image_Map
		 */
		public static function instance() {
			
			static $instance = null;
			
			if ( null === $instance ) {
				$instance = new static();
			}
			
			return $instance;

		}
		
		protected function __construct() {
			
			$this->setup_constants();
			$this->load_textdomain();
			
			$this->require_necessities();
			
			// Register our CSS/JS for the whole plugin
			add_action( 'init', array( $this, 'register_scripts' ) );
			
		}

		/**
		 * Setup plugin constants
		 *
		 * @access	  private
		 * @since	  1.0.0
		 * @return	  void
		 */
		private function setup_constants() {
			
			// WP Loads things so weird. I really want this function.
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . '/wp-admin/includes/plugin.php';
			}
			
			// Only call this once, accessible always
			$this->plugin_data = get_plugin_data( __FILE__ );
			
			if ( ! defined( 'LD_Image_Map_ID' ) ) {
				// Plugin Text Domain
				define( 'LD_Image_Map_ID', $this->plugin_data['TextDomain'] );
			}

			if ( ! defined( 'LD_Image_Map_VER' ) ) {
				// Plugin version
				define( 'LD_Image_Map_VER', $this->plugin_data['Version'] );
			}

			if ( ! defined( 'LD_Image_Map_DIR' ) ) {
				// Plugin path
				define( 'LD_Image_Map_DIR', plugin_dir_path( __FILE__ ) );
			}

			if ( ! defined( 'LD_Image_Map_URL' ) ) {
				// Plugin URL
				define( 'LD_Image_Map_URL', plugin_dir_url( __FILE__ ) );
			}
			
			if ( ! defined( 'LD_Image_Map_FILE' ) ) {
				// Plugin File
				define( 'LD_Image_Map_FILE', __FILE__ );
			}

		}

		/**
		 * Internationalization
		 *
		 * @access	  private 
		 * @since	  1.0.0
		 * @return	  void
		 */
		private function load_textdomain() {

			// Set filter for language directory
			$lang_dir = LD_Image_Map_DIR . '/languages/';
			$lang_dir = apply_filters( 'ld_image_map_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), LD_Image_Map_ID );
			$mofile = sprintf( '%1$s-%2$s.mo', LD_Image_Map_ID, $locale );

			// Setup paths to current locale file
			$mofile_local   = $lang_dir . $mofile;
			$mofile_global  = WP_LANG_DIR . '/' . LD_Image_Map_ID . '/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/ld-image-map/ folder
				// This way translations can be overridden via the Theme/Child Theme
				load_textdomain( LD_Image_Map_ID, $mofile_global );
			}
			else if ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/ld-image-map/languages/ folder
				load_textdomain( LD_Image_Map_ID, $mofile_local );
			}
			else {
				// Load the default language files
				load_plugin_textdomain( LD_Image_Map_ID, false, $lang_dir );
			}

		}
		
		/**
		 * Include different aspects of the Plugin
		 * 
		 * @access	  private
		 * @since	  1.0.0
		 * @return	  void
		 */
		private function require_necessities() {
			
		}
		
		/**
		 * Show admin errors.
		 * 
		 * @access	  public
		 * @since	  1.0.0
		 * @return	  HTML
		 */
		public function admin_errors() {
			?>
			<div class="error">
				<?php foreach ( $this->admin_errors as $notice ) : ?>
					<p>
						<?php echo $notice; ?>
					</p>
				<?php endforeach; ?>
			</div>
			<?php
		}
		
		/**
		 * Register our CSS/JS to use later
		 * 
		 * @access	  public
		 * @since	  1.0.0
		 * @return	  void
		 */
		public function register_scripts() {
			
			wp_register_style(
				LD_Image_Map_ID . '-admin',
				LD_Image_Map_URL . 'assets/css/admin.css',
				null,
				defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : LD_Image_Map_VER
			);
			
			wp_register_script(
				LD_Image_Map_ID . '-admin',
				LD_Image_Map_URL . 'assets/js/admin.js',
				array( 'jquery' ),
				defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : LD_Image_Map_VER,
				true
			);
			
			wp_localize_script( 
				LD_Image_Map_ID . '-admin',
				'lDImageMap',
				apply_filters( 'ld_image_map_localize_admin_script', array() )
			);
			
		}
		
	}
	
} // End Class Exists Check

/**
 * The main function responsible for returning the one true LD_Image_Map
 * instance to functions everywhere
 *
 * @since	  1.0.0
 * @return	  \LD_Image_Map The one true LD_Image_Map
 */
add_action( 'plugins_loaded', 'ld_image_map_load' );
function ld_image_map_load() {

	require_once __DIR__ . '/core/ld-image-map-functions.php';
	LDIMAGEMAP();

}
