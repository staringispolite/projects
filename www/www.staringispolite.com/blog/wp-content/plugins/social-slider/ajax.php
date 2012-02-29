<?php 
require_once(dirname(__FILE__).'/../../../wp-config.php');
global $wpdb, $table_prefix;

$get_control		= get_option('socialslider_instalacja');
$SocialSliderArray 	= $_POST['rA'];

if(mysql_real_escape_string($_POST['action']) == "ZapiszPozycje" && $get_control == mysql_real_escape_string($_POST['control']))
	{
	$lC = 1;
	foreach ($SocialSliderArray as $recordIDValue)
		{
		if(is_numeric($lC) && is_numeric($recordIDValue))
			{
			$query = "UPDATE ".$table_prefix."socialslider SET lp = ".$lC." WHERE id = ".$recordIDValue;
			mysql_query($query);
			$lC = $lC + 1;
			}
		}
	}
?>