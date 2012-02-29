<?php
/*
Plugin Name: Google +1 button automator
Plugin URI: http://wordpress.org/extend/plugins/google-1-button-automator/
Description: Automatically adds Google +1 buttons to your posts and lets you customize it's display methods
Author: Martijn Heesters - d-Media
Version: 1.5.3
Author URI: http://d-media.nl
*/

/* init */

// since 1.5 there's a new layout/counter structure, for < 1.5 compatibility the following is included
if (get_option( 'googleplusone_displaycount') == '1' ){ update_option( 'googleplusone_displaycount', 'bubble' ); }
if (get_option( 'googleplusone_displaycount') == '' ){ update_option( 'googleplusone_displaycount', 'none' ); }

// Only of use in the admin interface
if ( is_admin() ) {
    add_action( 'admin_init' , 'googlepluspone_register_plugin_settings' ); // Setup plugin component registration
    add_action( 'admin_menu' , 'googleplusone_options' ); // if you're in the admin menu, show the options panel
} else {
    add_action( 'wp_print_footer_scripts','add_googleplusone_js' ); // add google js to footer
}

/* front end */

// this function adds the google js code to (the end of) the page
function add_googleplusone_js(){
        $options=array();
	// set language (if other than default)
	if ( get_option( 'googleplusone_language' ) != 'en-US'){ $options[]='lang:\''.get_option( 'googleplusone_language' ).'\''; }
	// set parse option
	if ( get_option( 'googleplusone_parse' ) == 'explicit' ){ $options[]='parsetags:\'explicit\''; }
	// set options between accolades
	if (count($options) >0){ $options='window.___gcfg = { '.implode(',',$options).' };'; } else { $options=''; }
	// print js code
	if ( get_option( 'googleplusone_async' ) == 1 ){
		echo trim('
		<script type="text/javascript">
		  '.$options.'
		  (function() {
		    var po = document.createElement(\'script\'); po.type = \'text/javascript\'; po.async = true;
		    po.src = \'https://apis.google.com/js/plusone.js\';
		    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(po, s);
		  })();
		</script>');
	} else {
		echo '<script type="text/javascript" src="https://apis.google.com/js/plusone.js">'.$options.'</script>';
	} // if
}

// this function receives the post content,adds the button, and returns the result
function add_post_footer_googleplusone( $text ){
	global $post;
	// only show if it's a single page or if it's not a single page and showonlysingle is not enabled
	if (
		is_single() ||
		( get_option( 'googleplusone_showonlyinsingle' ) != 1 )
	){
		// add break before iframe if option is selected
		if ( get_option( 'googleplusone_breakbefore' ) == 1 ){ $breakBefore = '<br />'; } else { $breakBefore = ''; }
		// add break after iframe if option is selected
		if ( get_option( 'googleplusone_breakafter' ) == 1 ){ $breakAfter = '<br />'; } else { $breakAfter = ''; }
		// set size, default = none
		if (get_option( 'googleplusone_size' ) != ''){ $size=' size="'.get_option( 'googleplusone_size' ).'" '; } else { $size=''; }
		// set count, can be inline, none and bubble (default)
		switch(get_option( 'googleplusone_displaycount' )){
			case 'inline': $displaycount=' annotation="inline" ';	break;
			case 'bubble': $displaycount=''; break;
			default: $displaycount=' annotation="none" '; // none
		}
		// js callback function
		if (get_option( 'googleplusone_jscallback' ) != ''){ $jscallback=' callback="'.trim(stripslashes(get_option( 'googleplusone_jscallback' ))).'" '; } else { $jscallback=''; }
		// code for the button
		if (get_option( 'googleplusone_htmlfive' ) == 1){
			$iframe='<div class="g-plusone" data-href="'.trim(get_permalink( $post->ID )).'" '.$size.' '.$displaycount.' '.$jscallback.'></div>';
		} else {
			$iframe='<g:plusone href="'.trim(get_permalink( $post->ID )).'" '.$size.' '.$displaycount.' '.$jscallback.'></g:plusone>';	
		} // if
		// if selected add a containing div with a classname
                if (get_option( 'googleplusone_divstyling' ) == 1){ $iframe='<div class="googlePlusOneButton">'.$iframe.'</div>'; }
                // show button before or after the post depening on setting
		if ( get_option( 'googleplusone_location' ) == 'top' ){
			$text=$breakBefore.$iframe.$breakAfter.$text;
		} else {
			$text=$text.$breakBefore.$iframe.$breakAfter;
		} // if
	}
	// return the post
	return $text;
}

// add filter to the content
add_filter( 'the_content', 'add_post_footer_googleplusone' );

/* admin area */

// register plugin options
function googlepluspone_register_plugin_settings() {
	// only for users who can manage options
	if ( current_user_can( 'manage_options' ) ){

                // register css
                wp_register_style( 'googleplusoneStylesheet', WP_PLUGIN_URL .'/'.basename( dirname( __FILE__ ) ).'/googleplusone.css' );

                // register js
                wp_register_script( 'googleplusoneScript', WP_PLUGIN_URL .'/'.basename( dirname( __FILE__ ) ).'/googleplusone.js' );
                
		// load localisation
		#if ( ! load_plugin_textdomain( 'googleplusone', '/wp-content/languages/' ) ){
			#load_plugin_textdomain( 'googleplusone', false, basename( dirname( __FILE__ ) ) . '/i18n' );
		#}
		// add options with default values (only adds them if they don't exist yet)
		add_option( 'googleplusone_location', 'bottom' );
		add_option( 'googleplusone_breakbefore' );
		add_option( 'googleplusone_breakafter' );
		add_option( 'googleplusone_showonlyinsingle' );
		add_option( 'googleplusone_size', 'standard' );
		add_option( 'googleplusone_parse','' );
		add_option( 'googleplusone_language','en-US' );
		add_option( 'googleplusone_displaycount','' );
		add_option( 'googleplusone_jscallback','' );
      add_option( 'googleplusone_divstyling','' );
      add_option( 'googleplusone_async','' );
      add_option( 'googleplusone_htmlfive','' );
	}
}

// adds page to the admin menu
function googleplusone_options(){
    $page=add_options_page( 'Google +1 button settings', 'Google +1', 'administrator', basename(__FILE__), 'googleplusone_options_page' );
    // Using registered $page handle to hook stylesheet loading
    add_action( 'admin_print_styles-' . $page, 'googleplusone_admin_stylesandscripts' );
}

// add js and stylesheet for options page, It will be called only on your plugin admin page, enqueue our stylesheet here
function googleplusone_admin_stylesandscripts() {
    wp_enqueue_style('googleplusoneStylesheet');
    wp_enqueue_script('googleplusoneScript');
}

// plugin options page
function googleplusone_options_page(){
	if ( isset( $_POST ) ){
		if ( isset( $_POST['Submit'] ) ){
			update_option( 'googleplusone_location', $_POST['location'] );
			update_option( 'googleplusone_breakbefore', $_POST['breakbefore'] );
			update_option( 'googleplusone_breakafter', $_POST['breakafter'] );
			update_option( 'googleplusone_showonlyinsingle', $_POST['showonlyinsingle'] );
			update_option( 'googleplusone_size', $_POST['size'] );
			update_option( 'googleplusone_parse', $_POST['parse'] );
			update_option( 'googleplusone_language', $_POST['language'] );
			update_option( 'googleplusone_displaycount', $_POST['displaycount'] );
			update_option( 'googleplusone_jscallback', $_POST['jscallback'] );
			update_option( 'googleplusone_divstyling', $_POST['divstyling'] );
			update_option( 'googleplusone_async', $_POST['async'] );
			update_option( 'googleplusone_htmlfive', $_POST['htmlfive'] );
		}
	}
	?>
	 <div class="wrap">
            <div class="icon32" id="icon-options-general"><br/></div>
            <h2><?php _e( 'Settings for Google +1 button automator', 'googleplusone' );?></h2>
            <form method="post" action="options-general.php?page=googleplusone.php">
                <table class="form-table">
                    <tr>
                        <td valign="top"><strong><?php _e( 'Display options', 'googleplusone' );?></strong></td>
                        <td valign="top">
                            <input type="checkbox" id="googleone_showonlyinsingle" value="1" <?php if ( get_option( 'googleplusone_showonlyinsingle') == '1' ) echo 'checked="checked"'; ?> name="showonlyinsingle" />
                            <label for="googleone_showonlyinsingle"><?php _e( 'Only show button on single post pages (ea. button doesn\'t show up in loops)', 'googleplusone' );?></label>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><strong><?php _e( 'Display style', 'googleplusone' );?></strong></td>
                        <td>
                            <select name="location" id="googleone_location">
                                <option value="bottom"><?php _e( 'Bottom', 'googleplusone' );?></option>
                                <option value="top" <?php if ( get_option( 'googleplusone_location' ) == 'top' ) echo ' selected="selected" '; ?>><?php _e( 'Top', 'googleplusone' );?></option>
                            </select>
                            <label for="googleone_location"><?php _e( 'Show button on top or bottom of post', 'googleplusone' );?></label>
                            <br />
                            <input type="checkbox" id="googleone_breakbefore" value="1" <?php if ( get_option( 'googleplusone_breakbefore' ) == '1' ) echo 'checked="checked"'; ?> name="breakbefore" />
                            <label for="googleone_breakbefore"><?php _e( 'Add break before the button', 'googleplusone' );?></label>
                            <br />
                            <input type="checkbox" id="googleone_breakafter" value="1" <?php if (get_option( 'googleplusone_breakafter' ) == '1' ) echo 'checked="checked"'; ?> name="breakafter" />
                            <label for="googleone_breakafter"><?php _e( 'Add break after the button', 'googleplusone' );?></label>
                            <br />
                            <input type="checkbox" id="googleone_divstyling" value="1" <?php if (get_option( 'googleplusone_divstyling' ) == '1' ) echo 'checked="checked"'; ?> name="divstyling" />
                            <label for="googleone_divstyling"><?php _e( 'Add a containing div for each button with the classname <i>googlePlusOneButton</i>, use this to style and position the button', 'googleplusone' );?></label>
                            <br />                            
                            
                            <label for="googleone_size"><?php _e( 'Size', 'googleplusone' );?>:</label>
                            <select name="size" id="googleone_size" onchange="googleone_changePreview()">
                                <option value="small" <?php if ( get_option( 'googleplusone_size' ) == 'small' ) echo ' selected="selected" '; ?>>Small (15px)</option>
                                <option value="standard" <?php if ( get_option( 'googleplusone_size' ) == 'standard' ) echo ' selected="selected" '; ?>>Standard (24px)</option>
                                <option value="medium" <?php if ( get_option( 'googleplusone_size' ) == 'medium' ) echo ' selected="selected" '; ?>>Medium (20px)</option>
                                <option value="tall" <?php if ( get_option( 'googleplusone_size' ) == 'tall' ) echo ' selected="selected" '; ?>>Tall (60px)</option>
                            </select>
                            <br />
                            <label for="googleone_displaycount"><?php _e( 'Show count', 'googleplusone' );?>:</label>
                            <select name="displaycount" id="googleone_displaycount" onchange="googleone_changePreview()">
                                <option value="inline" <?php if ( get_option( 'googleplusone_displaycount' ) == 'inline' ) echo ' selected="selected" '; ?>>Inline</option>
                                <option value="none" <?php if ( get_option( 'googleplusone_displaycount' ) == 'none' ) echo ' selected="selected" '; ?>>None</option>
                                <option value="bubble" <?php if ( get_option( 'googleplusone_displaycount' ) == 'bubble' ) echo ' selected="selected" '; ?>>Bubble (default)</option>
                            </select>									 
                            <br />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><strong><?php _e( 'Preview', 'googleone' );?></strong></td>
                        <td valign="top">
                            <div id="googleone_preview"></div>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><strong><?php _e( 'Advanced options', 'googleplusone' );?></strong></td>
                        <td valign="top">
                        <label for="googleone_language"><?php _e( 'Language', 'googleplusone' );?>:</label>
                        <select name="language" id="googleone_language">
                            <?php
                            $lang='
                            <option value="ar">Arabic</option>
                            <option value="bg">Bulgarian</option>
                            <option value="ca">Catalan</option>
                            <option value="zh-CN">Chinese (Simplified)</option>
                            <option value="zh-TW">Chinese (Traditional</option>
                            <option value="hr">Croatian</option>
                            <option value="cs">Czech</option>
                            <option value="da">Danish</option>
                            <option value="nl">Dutch</option>
                            <option value="en-US">English (US)</option>
                            <option value="en-GB">English (UK)</option>
                            <option value="et">Estonian</option>
                            <option value="fil">Filipino</option>
                            <option value="fi">Finnish</option>
                            <option value="fr">French</option>
                            <option value="de">German</option>
                            <option value="el">Greek</option>
                            <option value="iw">Hebrew</option>
                            <option value="hi">Hindi</option>
                            <option value="hu">Hungarian</option>
                            <option value="id">Indonesian</option>
                            <option value="it">Italian</option>
                            <option value="ja">Japanese</option>
                            <option value="ko">Korean</option>
                            <option value="lv">Latvian</option>
                            <option value="lt">Lithuanian</option>
                            <option value="ms">Malay</option>
                            <option value="no">Norwegian</option>
                            <option value="fa">Persian</option>
                            <option value="pl">Polish</option>
                            <option value="pt-BR">Portuguese (Brazil)</option>
                            <option value="pt-PT">Portuguese (Portugal)</option>
                            <option value="ro">Romanian</option>
                            <option value="ru">Russian</option>
                            <option value="sr">Serbian</option>
                            <option value="sv">Swedish</option>
                            <option value="sk">Slovak</option>
                            <option value="sl">Slovenian</option>
                            <option value="es">Spanish</option>
                            <option value="es-419">Spanish (Latin America)</option>
                            <option value="th">Thai</option>
                            <option value="tr">Turkish</option>
                            <option value="uk">Ukrainian</option>
                            <option value="vi">Vietnamese</option>';
                            // get selected language
                            $selected_lang=trim(get_option('googleplusone_language'));
                            // default en-US
                            if ($selected_lang == ''){ $selected_lang='en-US'; }
                            // make selected language active
                            $lang=str_replace('"'.$selected_lang.'"','"'.$selected_lang.'" selected="selected"',$lang);
                            // print languages
                            echo $lang;
                            ?>
                        </select>
                        <span><?php _e( '+1 Annotations are currently only available in US English on Google.com', 'googleplusone' );?></span>
                        <br />
                        <label for="googleone_parse"><?php _e( 'Parse', 'googleplusone' );?>:</label>
                        <select name="parse" id="googleone_parse">
                            <option value="onload">default (onload)</option>
                            <option value="explicit" <?php if ( get_option( 'googleplusone_parse' ) == 'explicit' ) echo "selected" ?>>Explicit</option>
                        </select>
                        <br />
                        <label for="googleone_jscallback"><?php _e( 'JS Callback function', 'googleplusone' );?>:</label>
                        <input name="jscallback" id="googleone_jscallback" value="<?php echo stripslashes(( get_option( 'googleplusone_jscallback' ) ));?>"/>
                        <br />
						<input type="checkbox" id="googleone_async" value="1" <?php if ( get_option( 'googleplusone_async' ) == '1' ) echo 'checked="checked"'; ?> name="async" />
						<label for="googleone_async"><?php _e( 'Turn on asynchronous mode', 'googleplusone' );?> (<a href="http://googlewebmastercentral.blogspot.com/2011/07/1-button-now-faster.html" target="_blank">info</a>)</label>
						<br />
						<input type="checkbox" id="googleone_htmlfive" value="1" <?php if ( get_option( 'googleplusone_htmlfive' ) == '1' ) echo 'checked="checked"'; ?> name="htmlfive" />
						<label for="googleone_htmlfive"><?php _e( 'HTML5 valid syntax', 'googleplusone' );?></label>
						<br />
                    </td>
                </tr>
            </table>
            <p class="submit"><input type="submit" name="Submit" value="<?php _e( 'Save Changes', 'googleplusone' );?>" /></p>
            </form>
            <div id="poststuff">
                <div class="stuffbox" style="background-color:#FFFFFF;width:600px;">
                    <h3><label for="link_name">Support</label></h3>
                    <div class="inside">
                        <ul>
                            <li>Please don't hesitate to send us your <a href="http://wordpress.org/tags/google-1-button-automator" target="_blank">support questions &raquo;</a> or <a href="http://wordpress.org/support/view/plugin-committer/martijnh" target="_blank">feature requests &raquo;</a></li>
                            <li>Support us back by mentioning or rating the <a href="http://wordpress.org/extend/plugins/google-1-button-automator/" target="_blank">Google +1 button automator plugin &raquo;</a></li>
                            <li>For an overview of our services, visit our company website <a href="http://d-media.nl?ref=wp-google-1-button-automator" target="_blank">d-Media</a></li>
                        </ul>                    
                    </div>
                </div>        
            </div>            
	</div>
	<?php
}

// add plugin settings link on the plugin overview page
add_action( 'plugin_action_links_' . plugin_basename(__FILE__), 'googleplusone_filter_plugin_actions' );
function googleplusone_filter_plugin_actions( $links ){
	return array_merge( array( '<a href="options-general.php?page=googleplusone.php">Settings</a>' ), $links );
}

?>
