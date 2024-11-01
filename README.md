# Salesforce Wordpress To Lead Integration with Affiliate WP

This Salesforce Affiliate WP add-on integrates Salesforce with Affiliate WP

## Description

This add-on enables you to create Affiliate WP referrals on submitting Salesforce WordPress to Lead plugin forms. You will be able to decide what forms need to create affiliate referrals. It gives us option to add referral rate types and referral rate specific
to referrals creates using salesforms.

**Prerequisites**

- Wordpress
- Affiliate WP
- Salesfrce wordpress to lead

**Features**

- Create referrals on submitting salesforce forms
- Option to enable referrals on sbumitting salesforce forms
- Option to enable referrals for specific salesforce forms
- Option to disable referrals for salesforce specific forms
- Option to add referral rate type specific for Salesforce
- Option to add referral rate specific for Salesforce

**Hooks & Filters:**

- "sawp_referral_created" (action hook)
Runs after creating referrals, it has four parameters
    1. $post -  Data of the form submitted
    2. $form_id - Affiliate ID
    3. $form_type - Type of the form submitted
    4. $referral_id - Id of Affiliate

- "sawp_rate_type" (filter hook)
Return referral rate type specific to salesforce, it has two parameters
    1. $rate_type -  Referral rate type
    2. $affiliate_id - Affiliate ID

- "sawp_rate" (filter hook)
Return referral rate specific to salesforce, it has four parameters
    1. $salesforce_rate -  Referral rate
    2. $affiliate_id - Affiliate ID
    3. $rate_type -  Referral rate type
    4. $reference - Referrence of the referral

## Installation

Before installation please make sure you have latest Salesforce wordpress to lead && Affiliate WP plugins installed.

1. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress

## FAQ

**Is it necessary to have Affiliate WP and Salesforce wordpress to lead plugin activated to use this add-on?**

Yes, you must have Affiliate WP and Salesforce wordpress to lead plugins enabled to use this add-on

## Changelog

[See all version changelogs](CHANGELOG.md)