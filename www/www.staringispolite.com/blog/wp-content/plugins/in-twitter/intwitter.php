<?php
/*
Plugin Name:In Twitter
Plugin URI: http://www.iannash.com/intwitter
Description: Twitter Widget. This is a very simple and clean Twitter Plugin. The client must have internet access for this plugin to work.
Version: 2.3
Author: Ian Nash
Author URI: http://www.iannash.com/intwitter
License: GPL2
*/


/*  Copyright 2012  Ian Nash  (email : iannash.com/intwitter)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


register_activation_hook( __FILE__, 'intwitter_install' );


add_action('wp_head', 'twitter_head_scripts');
function twitter_head_scripts() {
	echo '<script type="text/javascript" src="http://widgets.twimg.com/j/2/widget.js"></script>'."\n";
	echo "<script type='text/javascript' src='" . plugins_url() . "/in-twitter/jquery-1.7.1.min.js'></script>"."\n";
	//echo "<script type='text/javascript' src='" . plugins_url() . "/in-twitter/jquery.intwitter.js'></script>"."\n";
	echo "<link rel='stylesheet' type='text/css' href='" . plugins_url() . "/in-twitter/intwitter.widget.css' media='all'>"."\n";
	echo "<script type='text/javascript'>$(document).ready(function(){var w_twit =  new TWTR.Widget({version: 2, type: 'search',	search: 'from:";
	echo ( get_option ( 'intwitter_uid' ) ); 
	echo "',rpp:";
	echo ( get_option ( 'intwitter_twitfeed' ) ); 
	echo ",interval: 30000,title: '', subject: '',	width: 196,	height:";
	echo ( get_option ( 'intwitter_height' ) ); 
	echo ", id: 'twitterbox', theme: {	shell: {background: '#333', color: '#ffffff'},tweets: {background: '#ffffff', color: '#000000', links: '#094F95'}},features: {scrollbar: false,loop: false,live: true, hashtags: true,	timestamp: true, avatars: false, toptweets: true, behavior: 'all'} }); w_twit.render().start();});</script>"."\n";
}
	
// create the admin page
add_action('admin_menu', 't_add_admin_menu');

function t_add_admin_menu() {
// add an options page for the plugin
	add_options_page('In Twitter', 'In Twitter', 'manage_options', 'twitter', 't_plugin_options');
}

function t_plugin_options(){
	// include the plugin admin page
	require_once('twitter_admin.php');
}

//Install the intwitter database
function intwitter_install() {

	global $wpdb;
	$table_name = "intwitter";
	
	intwitter_db_drop_table();

   	if( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
		$sql = "CREATE TABLE " . $table_name . " (
	 	 id INT NOT NULL,
	 	 userid VARCHAR(200) NOT NULL,
		 twitheight INT,
		 twitfeed INT,
	 	 PRIMARY KEY(id)
		);";

		add_option( "twitter_db_version", "2.0" );
	}
}


//Delete intwitter table
function intwitter_db_drop_table() {
	global $wpdb;
	$table_name = "intwitter";
   	if( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
		$sql = "DROP TABLE " . $table_name;
	}
}

?>