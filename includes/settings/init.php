<?php
namespace SAWP;
/**
 * Plugin Settings
 *
 * @class     Settings
 * @version   1.0
 * @package   SAWP/Classes/Settings
 * @category  Class
 * @author    WooNinjas
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Main for plugin initiation
 *
 * @since 1.0
 */
final class Main {
    public static $version = "1.0";

    // Main instance
    protected static $_instance = null;

    protected function __construct () {
        register_activation_hook ( __FILE__, array ( $this, "activation" ) );
        register_deactivation_hook ( __FILE__, array ( $this, "deactivation" ) );

        add_action ( "admin_enqueue_scripts", array ( $this, "admin_enqueue_scripts" ) );

        // Adding settings tab
        add_filter( "plugin_action_links_" . plugin_basename( DIR_FILE ), function( $links ) {
            return array_merge( $links, array(
                sprintf(
                    '<a href="%s">Settings</a>',
                    admin_url( "admin.php?page=affiliate-wp-salesforce" )
                ),
            ));
        });

        // Upgrade
        add_action ( "plugins_loaded", array ( $this, "upgrade" ) );

        if( file_exists( INCLUDES_DIR . "integration/class-salesforce-awp.php" ) ) {
            require_once ( INCLUDES_DIR . "integration/class-salesforce-awp.php" );
        }

        if( file_exists( INCLUDES_DIR . "settings/options.php" ) ) {
            require_once ( INCLUDES_DIR . "settings/options.php" );
        }

        add_action ( "admin_notices", array($this, "review_notice"));
    }

    /**
     * @return $this
     */
    public static function instance () {
        if ( is_null ( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Activation function hook
     *
     * @return void
     */
    public static function activation () {
        if ( !current_user_can ( "activate_plugins" ) )
            return;

        update_option( "sawp_version", self::$version );
    }

    /**
     * Deactivation function hook
     * No used in this plugin
     *
     * @return void
     */
    public static function deactivation () {
        //
    }

    /**
     * Enqueue stylesheet on admin
     */
    public static function admin_enqueue_scripts () {
        $screen = $screen = get_current_screen();
        if( ! isset( $screen->id ) && $screen->id != "affiliates_page_affiliate-wp-salesforce" ) {
             return;
        }
        wp_register_style ( "sawp-salesforce-awp-css", ASSETS_URL . "css/salesforce-awp.css", array(), self::$version );
        wp_enqueue_style ( "sawp-salesforce-awp-css" );
        wp_enqueue_script ( "sawp-salesforce-awp-js", ASSETS_URL . "js/salesforce-awp.js", array( "jquery" ), self::$version );
    }

    public static function upgrade() {
        if ( get_option ( "sawp_version" ) != self::$version ) {
        }
    }

    public function review_notice() {
        $screen = get_current_screen();

        if( isset( $screen->id ) && $screen->id == "affiliates_page_affiliate-wp-salesforce" ) {
            return false;
        }

        if( isset( $_GET['sawp_dismiss_notice'] ) ) {
            update_user_meta(get_current_user_id(), "sawp_review_dismissed", 1);
        }

        $user_data = get_userdata(get_current_user_id());
        $sawp_review_dismissed = get_user_meta(get_current_user_id(), "sawp_review_dismissed", true);
        $dismiss_url = add_query_arg( 'sawp_dismiss_notice', 1 );

        if ( ! $sawp_review_dismissed ) {
        ?>
        <div class="notice notice-info">
            <?php _e('<p>Hi <strong>' . $user_data->user_nicename . '</strong>, thankyou for using '.SAWP_PLUGIN_NAME. '. If you find our plugin useful kindly take some time to leave a review and a rating for us <a href="https://wordpress.org/plugins/salesforce-wp-to-lead-integration-with-affiliate-wp/" target="_blank" ><strong>here</strong></a> </p><p><a href="'.esc_attr($dismiss_url).'">Dismiss Notice</a></p>', "edd-sale-price"); ?>
        </div>
            <?php
        }
    }
}

/**
 * Main instance
 *
 * @return Main
 */
function SAWP() {
    if ( !class_exists( "Affiliate_WP" ) || !class_exists( "Salesforce_Admin" ) ) {
        return;
    }
    return Main::instance();
}
add_action( "plugins_loaded", __NAMESPACE__ . "\SAWP", 101 );