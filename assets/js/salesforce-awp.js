/**
 * Salesforce jQuery
 */
jQuery( document ).ready(function( $ ) {

    $( "#sawp_salesforce_referral_rate" ).change(function() {
       var self = $( this );
       if ( self.prop( "checked" ) ) {
           self.parents( "tr" ).siblings( "tr.salesforce-referral-rate" ).removeClass( "sawp-disabled" );
       } else {
           self.parents( "tr" ).siblings( "tr.salesforce-referral-rate" ).addClass( "sawp-disabled" );
       }

    });
});