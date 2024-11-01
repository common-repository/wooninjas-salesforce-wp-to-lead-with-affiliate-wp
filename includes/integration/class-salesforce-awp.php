<?php
/**
 * Class to create referrals
 *
 * @class     Affiliate_WP_Salesforce
 * @version   1.0
 * @package   SAWP/Classes/Salesforce Integration
 * @category  Class
 * @author    WooNinjas
 */

/**
 * Salesforce integration class.
 * This integration provides support for Salesforce, https://wordpress.org/plugins/salesforce-wordpress-to-lead/
 *
 * @since 1.0
 */
class Affiliate_WP_Salesforce extends Affiliate_WP_Base {

    /**
     * The Help Scout docs url for this integration.
     *
     * @var string Documentation URL.
     */
    public $doc_url;

    /**
     * Helps in identifying salesforce
     *
     * @var $context
     */
    public $context;

    /**
     * @access  public
     * @see     Affiliate_WP_Base::init
     */
    public function init() {

        $this->doc_url = 'https://developer.salesforce.com/page/Wordpress-to-lead';

        $this->context = 'salesforce';

        $this->maybe_unhook_salesforce();

        // Mark referral complete.
        add_action( "wp_footer", array( $this, "mark_referral_complete" ), 9999 );

        // Revoke referral.
        add_action( "wp_footer", array( $this, "revoke" ), 9999 );

        // Set reference.
        add_filter( "affwp_referral_reference_column", array( $this, "reference_link" ), 5, 2 );

        // Set referral rate type
        add_filter( "affwp_get_affiliate_rate_type", array( $this, "salesforce_referral_rate_type" ), 10, 2 );

        // Set referral rate
        add_filter( "affwp_get_affiliate_rate", array( $this, "salesforce_referral_rate" ), 10, 4 );
    }

    /**
     * hooks the `salesforce_w2l_after_submit` function only if a referring affiliate is found.
     *
     * @return void
     */
    public function maybe_unhook_salesforce() {
        if ( $this->was_referred() ) {
            add_action( "salesforce_w2l_after_submit", array( $this, "add_pending_referral" ), 10, 3 );
        }
    }

    /**
     * Adds a referral when a form is submitted.
     *
     * @param object $post submitted form data.
     * @param object $form_id submitted form id.
     * @param object $form_type submitted form type.
     */
    public function add_pending_referral( $post, $form_id, $form_type ) {

        if ( ! $form_id ) {
            return false;
        }

        if ( $this->was_referred() ) {
            if ( $this->referred_enabled( $form_id ) ) {

                $product_id  = 0;
                $description = "Salesforce " . $form_id;
                $base_amount = 0;

                $reference       = $form_id . '-' . date_i18n( 'U' );
                $affiliate_id    = $this->get_affiliate_id( $reference );
                $referral_total  = $this->calculate_referral_amount( $base_amount, $reference, $product_id, $affiliate_id );
                $referral_id     = $this->insert_pending_referral( $referral_total, $reference, $description, $product_id );

                if ( empty( $referral_total ) ) {
                    $this->complete_referral( $reference );
                }

                /**
                 * This action is specific to the AffiliateWP Salesforce integration.
                 *
                 * @param object $post post.
                 * @param object $form_id form id.
                 * @param int $form_type form type.
                 * @param int $referral_id referral_id.
                 */
                do_action( "sawp_referral_created", $post, $form_id, $form_type, $referral_id );
            }
        }
    }

    /**
     * Updates the referral status when a PayPal refund or transaction completion occurs,
     * via the success or cancel pages provided in the PayPal add-ons.
     *
     * @param int    $current_page_id - The current page ID.
     * @param mixed  $reference - The referral reference.
     */
    public function mark_referral_complete( $current_page_id = 0, $reference = "" ) {

        $form_id         = ! empty( $_GET["form_id"] )     ? absint( $_GET["form_id"] )         : false;
        $referral_id     = ! empty( $_GET["referral_id"] ) ? absint( $_GET["referral_id"] )     : false;
        $txn_id          = ! empty( $_GET["tx"] )          ? sanitize_text_field( $_GET["tx"] ) : false;

        if ( ! $form_id || ! $referral_id ) {
            if( $this->debug ) {
                $this->log( "Salesforce integration: The form ID or referral ID could not be determined." );
            }
            return false;
        }

        $referral = affwp_get_referral( $referral_id );

        if( $referral ) {
            if( ! empty( $txn_id ) ) {
                $referral->set( "reference", $txn_id, true );
            }
            $this->complete_referral( $referral );
        } else if( $this->debug ) {
            $this->log( sprintf( "Salesforce integration: Referral could not be retrieved during mark_referral_complete(). ID given: %d." ), $referral_id );
        }
    }

    /**
     * Updates the status of the referral.
     *
     * @param  string $reference - The reference.
     * @param  int    $current_page_id - The current page ID.
     * @return mixed void|bool
     */
    public function revoke( $current_page_id = 0, $reference = '' ) {

        $form_id         = ! empty( $_GET["form_id"] )     ? absint( $_GET["form_id"] )     : false;
        $referral_id     = ! empty( $_GET["referral_id"] ) ? absint( $_GET["referral_id"] ) : false;

        if ( ! $form_id || ! $referral_id ) {
            if( $this->debug ) {
                $this->log( "Salesforce integration: The form ID or referral ID could not be determined." );
            }
            return false;
        }

        $referral = affwp_get_referral( $referral_id );

        if( $referral ) {
            $this->reject_referral( $referral );
        } else if( $this->debug ) {
            $this->log( sprintf( "Salesforce integration: Referral could not be retrieved during revoke(). ID given: %d." ), $referral_id );
        }
    }

    /**
     * Generates a link to the associated salesforce form in the referral reference column.
     *
     * @param  int    $reference
     * @param  object $referral
     * @return string
     */
    public function reference_link( $reference, $referral ) {

        if ( ! $referral ) {
            if( $this->debug ) {
                $this->log( "Salesforce integration: No referral data found when attempting to add a referral reference." );
            }

            return false;
        }

        if( false !== strpos( $reference, '-' ) ) {
            $form_id = strstr( $reference, '-', true );
        } else {
            return $reference;
        }
        
        $url = admin_url( "options-general.php?page=salesforce-wordpress-to-lead&tab=form&id=" . $form_id );
        return '<a href="' . esc_url( $url ) . '">' . $reference . '</a>';
    }

    /**
     * Returns True
     * If referrer enabled
     *
     * @param $form_id
     * @return bool
     */
    public function referred_enabled ( $form_id ) {

        $global_referrals = get_option( "sawp_global_referrals" );
        $referred_forms = get_option( "sawp_referred_forms" );
        $exclude_forms = get_option( "sawp_exclude_forms" );

        if ( is_array ( $exclude_forms ) ) {
            if ( in_array ( $form_id, $exclude_forms ) ) {
                return false;
            }
        }

        if ( $global_referrals === "on"  ) {
            if ( is_array ( $exclude_forms ) ) {
                if ( !in_array ( $form_id, $exclude_forms ) ) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        }

        if ( is_array ( $referred_forms ) ) {
            if ( in_array ( $form_id, $referred_forms ) ) {
                if( is_array ( $exclude_forms ) ) {
                    if ( !in_array ( $form_id, $exclude_forms ) ) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    /**
     * Set referral rate type for salesforce specific
     *
     * @param $type
     * @param $affiliate_id
     * @return mixed|void
     */
    public function salesforce_referral_rate_type( $type, $affiliate_id ) {

        if ( $this->context === "salesforce" ) {
            $rate_type = get_option( "sawp_referral_rate_type" );
            if ( $rate_type === "global" || $rate_type == "" || $rate_type == null ) {
                return $type;
            }

            return apply_filters( "sawp_rate_type", $rate_type, $affiliate_id );
        } else {
            return $type;
        }
    }

    /**
     * Set referral rate for salesforce specific
     *
     * @param $rate
     * @param $affiliate_id
     * @param $type
     * @param $reference
     * @return mixed|void
     */
    public function salesforce_referral_rate( $rate, $affiliate_id, $type, $reference ) {

        if ( $this->context === "salesforce" ) {
            $salesforce_rate_enabled = get_option( "sawp_salesforce_referral_rate" );
            if ( $salesforce_rate_enabled === "on" ) {
                $salesforce_rate = get_option( "sawp_salesforce_default_referral_rate" );
                if ( $salesforce_rate == "" || $salesforce_rate == null ) {
                    return $rate;
                } else {
                    return apply_filters( "sawp_rate", $salesforce_rate, $affiliate_id, $type, $reference );
                }
            } else {
                return $rate;
            }
        } else {
            return $rate;
        }
    }
}
new Affiliate_WP_Salesforce;