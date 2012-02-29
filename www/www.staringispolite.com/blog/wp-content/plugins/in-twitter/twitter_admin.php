<h1>In Twitter</h1>

Firstly! <b>Thank you</b> for downloading my plugin. I plan to have more options in this area, and I will try and get these running shortly. In the meantime! Enjoy! <br><br>

<h2>Installation Instructions</h2>

1. Activate Plugin.<br>

2. Go to Settings>> In Twitter<br>

3. Enter your Username, height(standard height is 211) and how many twitter feeds you want displayed.<br>

4.  Press "Update" <br>

5. Go to Appearance >> Widgets >> and place a "Text" box in. Name the Text Box twitter (or whatever you want)<br>

6. Place the following in the "text area"<br>

<code>&lt;div id='twitterbox' class='twitterbox'>&lt;/div></code><br>

<b>PS.  Make sure you enter a height and twitter feed number otherwise the plugin will not work!</b><br>

Please feel free to comment or ask questions about this app <a href="http://www.iannash.com/intwitter/">here</a>. <br><br>

<?PHP

	add_option( 'intwitter_uid', '', 'Twitter user name', 'yes' );
	add_option( 'intwitter_height', '', 'Twitter height', 'yes' );
	add_option( 'intwitter_twitfeed', '', 'Twitter feed', 'yes' );

	function intwitter_options_page() {
	global $wpdb;
	$table_name = "intwitter";
	$username = get_option( 'intwitter_uid' );
	$height = get_option( 'intwitter_height' );
	$twitfeed = get_option( 'intwitter_twitfeed' );
	$submitFieldID = 'intwitter_submit_hidden';
	if ( $_POST[ $submitFieldID ] == 'Y' ) {
		update_option( 'intwitter_uid', $_POST[ 'intwitter_form_username' ] );
		update_option( 'intwitter_height', $_POST[ 'intwitter_form_height' ] );
		update_option( 'intwitter_twitfeed', $_POST[ 'intwitter_form_feed' ] );
	?>
		<div class="updated"><p><strong><?php _e('Options saved.', 'mt_trans_domain' ); ?></strong></p></div>
	<?php	} ?>
	
<?php } ?>

	<form name="intwitter_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>">
		<input type="hidden" name="intwitter_submit_hidden" value="Y">
		<p>
			<h3>Username</h3>
			<input type="text" name="intwitter_form_username" value="<?php echo ( get_option ( 'intwitter_uid' ) ); ?>">
			
			<h3>Height (in px)</h3>
			<input type="text" name="intwitter_form_height" value="<?php echo ( get_option ( 'intwitter_height' ) ); ?>">
			
			<h3>Feed Number</h3>
			<input type="text" name="intwitter_form_feed" value="<?php echo ( get_option ( 'intwitter_twitfeed' ) ); ?>">
			
			<p class="submit"><input type="submit" name="Submit" value="<?php _e( 'Update', 'mt_trans_domain' ) ?>"></p>
			
		</p>
	</form>

<?php	



mysql_select_db($wpdb);

if(isset($_POST['Submit'])){
	$Submit=$_POST['intwitter_form_username'];
	
	//Enter the first line
	$updateid="INSERT INTO intwitter VALUE(1,userid)";
	mysql_query($updateid);
	
	//Update the field
	$query="UPDATE intwitter SET userid='$Submit' WHERE id='1'";
	mysql_query($query);
	//var_dump(mysql_error());
	//Run the query
	//mysql_query($query) or die("Failed to update");
	//update the userid
	update_option( 'intwitter_uid', $_POST[ 'intwitter_form_username' ] );
	update_option( 'intwitter_height', $_POST[ 'intwitter_form_height' ] );
	update_option( 'intwitter_twitfeed', $_POST[ 'intwitter_form_feed' ] );
	?>
	<div class="updated"><p><strong><?php _e('Option saved.', 'mt_trans_domain' ); ?></strong></p></div>
<?PHP
}





?>
<br>
This is my first wordpress plugin which I made in my own personal time and I am hoping to make a facebook "like" one shortly.<br>
If you feel like donating to my plug in, please do so here: <form action="https://www.paypal.com/cgi-bin/webscr" method="post"><div class="paypal-donations"><input type="hidden" name="cmd" value="_donations" /><input type="hidden" name="business" value="ian.nash@gmail.com" /><input type="hidden" name="currency_code" value="AUD" /><input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online." /><img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" /></div></form>