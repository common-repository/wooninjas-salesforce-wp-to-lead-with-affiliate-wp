<?php
namespace SAWP;
/**
 * Plugin Name: WooNinjas Salesforce WP to Lead with AffiliateWP
 * Description: This add-on enables you to create Affiliate WP referrals on submitting Salesforce WordPress to Lead plugin forms
 * Version: 1.0
 * Author: Wooninjas
 * Author URI: http://wooninjas.com/
 * Text Domain: sawp
 */

if ( !defined ( "ABSPATH" ) ) exit;

function require_dependency() {
    if ( !class_exists( "Affiliate_WP" ) ) {
        deactivate_plugins ( plugin_basename ( __FILE__ ), true );
        $class = "notice is-dismissible error";
        $message = __( "Salesforce Affiliate WP add-on requires Affiliate WP plugin to be activated.", "sawp" );
        printf ( "<div id='message' class='%s'> <p>%s</p></div>", $class, $message );
    }
    elseif ( !class_exists( "Salesforce_Admin" ) ) {
        deactivate_plugins ( plugin_basename ( __FILE__ ), true );
        $class = "notice is-dismissible error";
        $message = __( "Salesforce Affiliate WP add-on requires Brilliant Web-to-Lead for Salesforce plugin to be activated.", "sawp" );
        printf ( "<div id='message' class='%s'> <p>%s</p></div>", $class, $message );
    }
}
add_action( "admin_notices", __NAMESPACE__ . "\\require_dependency" );


// Directory
define( "SAWP\DIR", plugin_dir_path ( __FILE__ ) );
define( "SAWP\DIR_FILE", DIR . basename ( __FILE__ ) );
define( "SAWP\INCLUDES_DIR", trailingslashit ( DIR . "includes" ) );
define( "SAWP\BASE_DIR", plugin_basename(__FILE__));

// URLS
define( "SAWP\URL", trailingslashit ( plugins_url ( "", __FILE__ ) ) );
define( "SAWP\ASSETS_URL", trailingslashit ( URL . "assets" ) );

$plugin_data = get_plugin_data( __FILE__ );
define( 'SAWP_PLUGIN_NAME', $plugin_data['Name'] );

if( file_exists( INCLUDES_DIR . "settings/init.php" ) ) {
    require_once ( INCLUDES_DIR . "settings/init.php" );
}