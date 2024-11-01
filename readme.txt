=== WooNinjas Salesforce WP to Lead with AffiliateWP ===
Contributors: wooninjas, adeelraza_786hotmailcom
Tags: salesforce, salesforce referrals, affiliate wp, form submission referrals, wp to lead, brilliant web to lead, salesforce integration for wordpress, salesforce integration with affiliate wp
Requires at least: 4.0
Tested up to: 4.8.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This add-on integrates Salesforce Wordpress To Lead with AffiliateWP

== Description ==

This add-on enables you to create AffiliateWP referrals on submitting [Salesforce WordPress to Lead plugin forms](https://wordpress.org/plugins/salesforce-wordpress-to-lead/). You will be able to decide which forms would create an affiliate referrals. It gives you the option to add referral rate types and referral rates specific to the referrals created using salesforce wordpress to lead forms.

For any further support or assistance for the plugin kindly reach out us at [WooNinjas](http://wooninjas.com/contact-us)

= Prerequisites: =

* Wordpress
* AffiliateWP
* Salesfrce wordpress to lead

= Features: =

* Create referrals on submitting salesforce wordpress to lead forms
* Option to enable referrals on submitting salesforce wordpress to lead forms
* Option to enable referrals for specific salesforce wordpress to lead form
* Option to disable referrals for specific salesforce wordpress to lead form
* Option to add referral rate type for specific salesforce wordpress to lead form
* Option to add referral rate for specific salesforce wordpress to lead form

= Hooks & Filters: =

* "sawp_referral_created" (action hook)
Runs after creating referrals, it has four parameters
    1. $post -  Data of the form submitted
    2. $form_id - Affiliate ID
    3. $form_type - Type of the form submitted
    4. $referral_id - Id of Affiliate

* "sawp_rate_type" (filter hook)
Return referral rate type specific to salesforce, it has two parameters
    1. $rate_type -  Referral rate type
    2. $affiliate_id - Affiliate ID

* "sawp_rate" (filter hook)
Return referral rate specific to salesforce, it has four parameters
    1. $salesforce_rate -  Referral rate
    2. $affiliate_id - Affiliate ID
    3. $rate_type -  Referral rate type
    4. $reference - Referrence of the referral


== Installation ==

Before installation please make sure you have latest Salesforce wordpress to lead && AffiliateWP plugins installed.

1. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress

== Frequently Asked Questions ==

= Is it necessary to have AffiliateWP and Salesforce wordpress to lead plugin activated to use this add-on?

Yes, you must have AffiliateWP and Salesforce wordpress to lead plugins enabled to use this add-on

== Screenshots ==

1. Addon Settings Page assets/salesforce-affiliate.png

== Changelog ==

= 1.0 =
* Initial
