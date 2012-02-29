<?php
if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') ){ exit(); }
		
delete_option( 'googleplusone_location' );
delete_option( 'googleplusone_breakbefore' );
delete_option( 'googleplusone_breakafter' );
delete_option( 'googleplusone_showonlyinsingle' );
delete_option( 'googleplusone_size' );
delete_option( 'googleplusone_parse' );
delete_option( 'googleplusone_language' );
delete_option( 'googleplusone_displaycount' );
delete_option( 'googleplusone_jscallback' );
delete_option( 'googleplusone_divstyling' );
delete_option( 'googleplusone_async' );
delete_option( 'googleplusone_htmlfive' );
?>