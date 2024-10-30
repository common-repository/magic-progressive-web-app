<?php /**
 * Plugin Name: Magic Progressive Web App
 * Plugin URI: https://wordpress.org/plugins/magic-progressive-web-app/
 * Description: Convert your WordPress website into a Progressive Web App
 * Author: Spaculus Software Pvt Ltd.
 * Author URI: https://spaculus.org
 * Contributors: Spaculus
 * Version: 1.0
 * Text Domain: MagicProgressiveWebApp
 * Domain Path: /languages
 * License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;
if ( ! defined( 'MPWA_VERSION' ) )		define( 'MPWA_VERSION'	, '1.0' ); // 
if ( ! defined( 'MPWA_PATH_ABS' ) ) 	define( 'MPWA_PATH_ABS'	, plugin_dir_path( __FILE__ ) ); // Absolute path to the plugin directory. eg - /var/www/html/wp-content/plugins/spacpwd/
if ( ! defined( 'MPWA_PATH_SRC' ) ) 	define( 'MPWA_PATH_SRC'	, plugin_dir_url( __FILE__ ) ); // Link to the plugin folder. eg - https://example.com/wp-content/plugins/spacpwd/
if ( ! defined( 'MPWA_PATH_URL' ) ) 	define( 'MPWA_PATH_URL'	, trailingslashit( plugin_dir_url( __FILE__ ) ) );
define('ROOTDIR', plugin_dir_path(__FILE__));
register_activation_hook(__FILE__,'mpwa_install'); 
/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'mpwa_remove' );
require_once(ROOTDIR . 'admin_setting_options.php');
function mpwa_install() {
/* Creates new database field */
$application_name = $application_short_name =  get_bloginfo('name');
$application_description = get_bloginfo('description');
$application_icon = MPWA_PATH_SRC.'public/images/logo.png';
$application_bgcolor = '#1e73be';
$application_themecolor = '#1e73be';
$application_orientation = 'any';
add_option("application_name", $application_name, '', 'yes');
add_option("application_short_name", $application_short_name, '', 'yes');
add_option("application_description", $application_description, '', 'yes');
add_option("application_icon", $application_icon, '', 'yes');
add_option("application_bgcolor", $application_bgcolor, '', 'yes');
add_option("application_themecolor", $application_themecolor , '', 'yes');
add_option("application_orientation", $application_orientation , '', 'yes');
//genrate file
$icon  = array (
			0 => array (
			  'src' => $application_icon,
			  'sizes' => '128x128',
			  'type' => 'image/png',
			),
			1 => array (
			  'src' => $application_icon,
			  'sizes' => '144x144',
			  'type' => 'image/png',
			),
			2 => array (
			  'src' => $application_icon,
			  'sizes' => '152x152',
			  'type' => 'image/png',
			),
			3 => array (
			  'src' => $application_icon,
			  'sizes' => '192x192',
			  'type' => 'image/png',
			),
			4 => array (
			  'src' => $application_icon,
			  'sizes' => '256x256',
			  'type' => 'image/png',
			),
		  );
	$manifest 						= array();
	$manifest['name']				= $application_name;
	$manifest['short_name']			= $application_short_name;
	$manifest['description'] 		= $application_description;
	$manifest['icons']				= $icon;
	$manifest['background_color']	= $application_bgcolor;
	$manifest['theme_color']		= $application_themecolor;
	$manifest['display']			= 'standalone';
	$manifest['orientation']		= $application_orientation;
	$manifest['start_url']			= home_url().'\/?utm_source=launcher';
	//$manifest['scope']				= '';
	$manifest_write_str	 =  json_encode( $manifest );
	$manifestmy_file = MPWA_PATH_ABS.'manifest.json';
    
    $handle = @fopen($manifestmy_file, 'w+');
	fwrite($handle, $manifest_write_str);
	fclose($handle); chmod($manifestmy_file, 0777);
	$my_file = ABSPATH.'service-worker.js';
    $write_str = "self.addEventListener('fetch', (event) => {
					  console.info('Event: Fetch');
				});";
	$handle = @fopen($my_file, 'w') ;
	fwrite($handle, $write_str);
	fclose($handle); chmod($my_file, 0777);
}
function mpwa_remove() {
/* Deletes the database field */
delete_option("application_name");
delete_option("application_short_name");
delete_option("application_description");
delete_option("application_icon");
delete_option("application_bgcolor");
delete_option("application_themecolor");
delete_option("application_orientation");
// remove include script form page 
 wp_dequeue_script( MPWA_PATH_SRC . 'scripts/main.js' );
 wp_dequeue_script( MPWA_PATH_SRC.'manifest.json' );
 unlink(MPWA_PATH_ABS.'scripts/main.js');
 unlink(MPWA_PATH_ABS.'manifest.json');
 unlink(ABSPATH.'service-worker.js');
 remove_action('wp_head', 'mpwa_add_manifest_to_wp_head',0);
}
//add in menu under setting 
if ( is_admin() ){
/* Call the html code */
add_action('admin_menu', 'mpwa_admin_menu');
function mpwa_admin_menu() {
		add_options_page('Magic MPWA', 'Magic MPWA', 'administrator','mpwa-setting', 'mpwa_setting_page');
	}
}
// include the color picker js and css -Admin side- START
function mpwa_load_custom_script()
{
	wp_enqueue_style( 'wp-color-picker');
	wp_enqueue_script( 'wp-color-picker');
	wp_enqueue_media();
	wp_enqueue_script( 'main-js', MPWA_PATH_SRC . 'scripts/admin/main.js', array(), null, true );
}
add_action( 'admin_enqueue_scripts', 'mpwa_load_custom_script' );

// include the color  js and css - ADmin side- CLOSE 
//inlcude plugin  important files front end side 
function mpwa_register_sw() {

	wp_enqueue_script( 'mpwa_register-sw', MPWA_PATH_SRC . 'scripts/main.js', array(), null, false );

}

add_action( 'wp_enqueue_scripts', 'mpwa_register_sw' );

//add mainfest.json in header file 
function mpwa_add_manifest_to_wp_head() {
	
	echo '<!-- /Manifest MagicProgressive Web Apps Plugin For WordPress / -->'. PHP_EOL;
	//echo "<script type='text/javascript' src='".MPWA_PATH_SRC."scripts/main.js'></script>". PHP_EOL;
	//echo '<!-- / spaculus.info plugin url -->' . PHP_EOL; 
	echo '<link rel="manifest" href="'.MPWA_PATH_SRC.'manifest.json">'. PHP_EOL;
	//echo '<!-- / spaculus.info plugin url -->' . PHP_EOL; 
}
add_action( 'wp_head', 'mpwa_add_manifest_to_wp_head');
?>
