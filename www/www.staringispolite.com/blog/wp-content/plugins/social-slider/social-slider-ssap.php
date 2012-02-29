<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/wp-config.php');

if($_GET['ssap']!="")
	{
	$ssexplode = explode("*", base64_decode($_GET['ssap']));
	
	list($socialslider_rok, $socialslider_miesiac, $socialslider_dzien) = explode('-', base64_decode(base64_decode($ssexplode[3])));
	if(@checkdate($socialslider_miesiac, $socialslider_dzien, $socialslider_rok))
		{
		if(($ssexplode[0]=="W" && $ssexplode[2] == str_replace("https://", "http://", get_bloginfo('wpurl'))) || ($ssexplode[0]=="E" && $ssexplode[1] == get_bloginfo('admin_email')) || ($ssexplode[0]=="M" && $ssexplode[1] == get_bloginfo('admin_email') && $ssexplode[2] == str_replace("https://", "http://", get_bloginfo('wpurl'))))
			{
			if(get_option('socialslider_licencja'))		{update_option('socialslider_licencja', $ssexplode[3]);}
			else										{add_option('socialslider_licencja', $ssexplode[3], ' ', 'yes');}
			
			echo base64_decode(base64_decode($ssexplode[3]));
			}
		else
			{delete_option('socialslider_licencja');}
		}
	}
?>