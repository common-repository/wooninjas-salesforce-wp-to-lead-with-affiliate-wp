<?php
namespace SAWP;

/**
 * Salesforce Options
 * Displays the Salesforce Options for Affiliate WP.
 *
 * @author   WooNinjas
 * @category Admin
 * @package  SAWP/Classes/Plugin Options
 * @version  1.0
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

/**
 * Affiliate_WP_Salesfroce_Options Class.
 */
class Affiliate_WP_Salesfroce_Options {

    /**
     * Hook in tabs.
     */
    public function __construct () {
        add_action( "admin_menu", array( __CLASS__, "salesforce_options" ), 50 );
        add_action ( "admin_notices", array( __CLASS__, "save_sawp_settings" ) );
    }

    /**
     * Add plugin's menu
     */
    public static function salesforce_options() {

        add_submenu_page(
            "affiliate-wp",
            __( "Salesforce", "sawp" ),
            __( "Salesforce", "sawp" ),
            "manage_options",
            "affiliate-wp-salesforce",
            array( __CLASS__, "affiliate_wp_salesforce_options" )
        );
    }

    /**
     * Setting page data
     */
    public static function affiliate_wp_salesforce_options() {

        $global_referrals = get_option( "sawp_global_referrals" );
        $referred_forms = get_option( "sawp_referred_forms" );
        $exclude_forms = get_option( "sawp_exclude_forms" );
        $rate_type = get_option( "sawp_referral_rate_type" );
        $salesforce_referral_rate = get_option( "sawp_salesforce_referral_rate" );
        $salesforce_rate = get_option( "sawp_salesforce_default_referral_rate" );

        ?>
        <div class="wrap">
            <div class="branding">
                <img class="branding-logo" src="<?php echo ASSETS_URL . "wooninjas.png"; ?>" alt="">
                <span class="branding-text">For any further support or assistance for the plugin kindly reach out to us at <a href="http://wooninjas.com/contact-us" target="_blank">Wooninjas</a><br> If you like our plugin kindly take some time to leave a review and a rating for us <br><a href="https://wordpress.org/plugins/salesforce-wp-to-lead-integration-with-affiliate-wp/" target="_blank" class="csld_support">Review Salesforce WP to Lead Integration with Affiliate WP</a></span>
            </div>
            <h2>
                <?php echo __( "Salesforce WP to Lead Integration with Affiliate WP", "sawp" ); ?>
            </h2>

            <div class="sawp-settings-wrapper">
                <form action="" method="post">
                    <table cellspacing="0" cellpadding="0" border="0">
                        <thead></thead>
                        <tbody>
                        <tr>
                            <td><strong><?php echo __( "Enable referrals for all Salesforce forms", "sawp" ); ?></strong></td>
                            <td>
                                <label for="sawp_global_referrals">
                                    <input type="checkbox" <?php echo ( $global_referrals == "on" ) ? "checked" : ""; ?> name="sawp_global_referrals" id="sawp_global_referrals" >
                                    <?php echo __( "Check this box to enable referrals for all salesforce forms.", "sawp" ); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><?php echo __( "Enable referrals for specific Salesforce forms", "sawp" ); ?></strong></td>
                            <td>
                                <label for="sawp_referred_forms">
                                    <select name="sawp_referred_forms[]"  id="sawp_referred_forms" multiple >
                                        <?php
                                        $forms = get_option( "salesforce2" );
                                        if ( is_array ( $forms ) && !empty ( $forms ) ) {
                                           if( !empty ( $forms["forms"] ) && is_array ( $forms["forms"] ) ) {
                                               echo "<option value='' >" . __( "Select Forms", "sawp" ) . "</option>";
                                               foreach ( $forms["forms"] as $key => $form ) {
                                                   $selected = "";
                                                   if( is_array ( $referred_forms ) ) {
                                                       if( in_array ( $key, $referred_forms ) ) {
                                                           $selected = "selected";
                                                       }
                                                   }

                                                   echo "<option value=" . $key . " " . $selected . " >" . $form["form_name"] . "</option>";
                                               }
                                           } else {
                                               echo "<option value='' >" . __( "No salesforce form found", "sawp" ) . "</option>";
                                           }
                                        } else {
                                            echo "<option value='' >" . __( "No salesforce form found", "sawp" ) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><?php echo __( "Exclude referrals for specific Salesforce forms", "sawp" ); ?></strong></td>
                            <td>
                                <label for="sawp_exclude_forms">
                                    <select name="sawp_exclude_forms[]"  id="sawp_exclude_forms" multiple >
                                        <?php
                                        $forms = get_option( "salesforce2" );
                                        if ( is_array ( $forms ) && !empty ( $forms ) ) {
                                            if( !empty ( $forms["forms"] ) && is_array ( $forms["forms"] ) ) {
                                                echo "<option value='' >" . __( "Select Forms", "sawp" ) . "</option>";
                                                foreach ( $forms["forms"] as $key => $form ) {
                                                    $selected = "";
                                                    if( is_array( $exclude_forms ) ) {
                                                        if( in_array( $key, $exclude_forms ) ) {
                                                            $selected = "selected";
                                                        }
                                                    }

                                                    echo "<option value=" . $key . " " . $selected . " >" . $form["form_name"] . "</option>";
                                                }
                                            } else {
                                                echo "<option value='' >" . __( "No salesforce form found", "sawp" ) . "</option>";
                                            }
                                        } else {
                                            echo "<option value='' >" . __( "No salesforce form found", "sawp" ) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><?php echo __( "Referral Rate Type", "sawp" ); ?></strong></td>
                            <td>
                                <label for="sawp_referral_rate_type">
                                    <select name="sawp_referral_rate_type"  id="sawp_referral_rate_type" >
                                        <option value="" ><?php echo __( "Select Rate Type", "sawp" ); ?></option>
                                        <option value="percentage" <?php echo $rate_type == "percentage" ? "selected" : ""; ?> ><?php echo __( "Percentage %", "sawp" ); ?></option>
                                        <option value="flat" <?php echo $rate_type == "flat" ? "selected" : ""; ?> ><?php echo __( "Flat Rate", "sawp" ); ?></option>
                                        <option value="global" <?php echo $rate_type == "global" ? "selected" : ""; ?> ><?php echo __( "Global", "sawp" ); ?></option>
                                    </select>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><?php echo __( "Use Salesforce Referral Rate", "sawp" ); ?></strong></td>
                            <td>
                                <label for="sawp_salesforce_referral_rate">
                                    <input type="checkbox" <?php echo ( $salesforce_referral_rate == "on" ) ? "checked" : ""; ?> name="sawp_salesforce_referral_rate" id="sawp_salesforce_referral_rate" >
                                    <?php echo __( "Check this to use custom referral rate for salesforce", "sawp" ); ?>
                                </label>
                            </td>
                        </tr>
                        <tr class="<?php echo ( $salesforce_referral_rate != "on" ) ? "sawp-disabled" : ""; ?> salesforce-referral-rate">
                            <td><strong><?php echo __( "Salesforce Referral Rate", "sawp" ); ?></strong></td>
                            <td>
                                <label for="sawp_salesforce_default_referral_rate">
                                    <input type="number" value="<?php echo ( $salesforce_rate === "" ) ? "" : $salesforce_rate; ?>" name="sawp_salesforce_default_referral_rate" id="sawp_salesforce_default_referral_rate" >
                                    <?php echo __( "The default referral rate for salesforce.", "sawp" ); ?>
                                </label>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot></tfoot>
                    </table>
                    <hr>
                    <?php submit_button( __( "Save Changes", "sawp" ), "primary large", "Save" ); ?>
                    <?php wp_nonce_field( 'sawp_settings', 'sawp_settings_field' ); ?>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Save Plugin's Settings
     */
    public static function save_sawp_settings() {

        $screen = get_current_screen();
        if ( $screen->id === "affiliates_page_affiliate-wp-salesforce" ) {
            if ( current_user_can( "manage_options" ) ) {
                if ( ! empty( $_POST ) && check_admin_referer( "sawp_settings", "sawp_settings_field" ) ) {

                    if ( $_POST["sawp_global_referrals"] == "on" ) {
                        update_option( "sawp_global_referrals", "on" );
                    } else {
                        update_option( "sawp_global_referrals", null );
                    }

                    if ( is_array( $_POST["sawp_referred_forms"] ) ) {
                        update_option( "sawp_referred_forms", $_POST["sawp_referred_forms"] );
                    } else {
                        update_option( "sawp_referred_forms", "" );
                    }

                    if ( is_array( $_POST["sawp_exclude_forms"] ) ) {
                        update_option( "sawp_exclude_forms", $_POST["sawp_exclude_forms"] );
                    } else {
                        update_option( "sawp_exclude_forms", "" );
                    }

                    update_option( "sawp_referral_rate_type", esc_attr( $_POST["sawp_referral_rate_type"] ) );

                    if ( $_POST["sawp_salesforce_referral_rate"] == "on" ) {
                        update_option( "sawp_salesforce_referral_rate", "on" );
                    } else {
                        update_option( "sawp_salesforce_referral_rate", null );
                    }

                    update_option( "sawp_salesforce_default_referral_rate", is_numeric( $_POST["sawp_salesforce_default_referral_rate"] ) ? $_POST["sawp_salesforce_default_referral_rate"] : "" );

                    $class = "notice notice-success";
                    $message = __( "Changes Updated.", "sawp" );

                    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
                }
            }
        }
    }
}

new Affiliate_WP_Salesfroce_Options();