<?php
/*
Plugin Name: Social Slider
Plugin URI: http://xn--wicek-k0a.pl/projekty/social-slider
Description: This plugin adds links to your social networking sites' profiles in a box floating at the left side of the screen.
Version: 7.1.1
Author: Łukasz Więcek
Author URI: http://mydiy.pl/
*/

$socialslider			= "social-slider";
$socialslider_wersja	= "7.1.1";
$socialslider_baza		= str_replace("https://", "http://", get_bloginfo('wpurl'));
$socialslider_katalog	= WP_PLUGIN_URL .'/'.$socialslider;

// Language
add_action('init', 'ss_language'); function ss_language() {load_plugin_textdomain('social-slider', false, dirname(plugin_basename( __FILE__ )).'/language');}

                                            $socialslider_tryb = "uproszczony";
if(get_option('socialslider_tryb'))			$socialslider_tryb = get_option('socialslider_tryb');

if(get_option('socialslider_instalacja'))
	{
	$socialslider_instalacja = get_option('socialslider_instalacja');
	}
else
	{
	$socialslider_instalacja = md5(time());

	add_option('socialslider_instalacja',			$socialslider_instalacja);
	add_option('socialslider_widget_widget',		' ',			' ', 'yes');	// Widget
	add_option('socialslider_widget_width',			'200px',		' ', 'yes');	// Widget width
	add_option('socialslider_widget_height',		'auto',			' ', 'yes');	// Widget height
	add_option('socialslider_miejsce',				'lewa',			' ', 'yes');	// Miejsce
	add_option('socialslider_kolor',				'jasny',		' ', 'yes');	// Kolor
	add_option('socialslider_custom_background',	'#ffffff',		' ', 'yes');	// Własny CSS - tło
	add_option('socialslider_custom_border',		'#cccccc',		' ', 'yes');	// Własny CSS - obramowanie
	add_option('socialslider_custom_font',			'#666666',		' ', 'yes');	// Własny CSS - czcionka
	add_option('socialslider_custom_radius',		'6px',			' ', 'yes');	// Własny CSS - narożnik
	add_option('socialslider_opacity',				'1',			' ', 'yes');	// Przezroczystość
	add_option('socialslider_ikony',				'standard',		' ', 'yes');	// Ikony
	add_option('socialslider_szybkosc',				'normal',		' ', 'yes');	// Szybkosc
	add_option('socialslider_link',					'text',			' ', 'yes');	// Link
	add_option('socialslider_position',				'fixed',		' ', 'yes');	// Pozycja
	add_option('socialslider_target',				'self',			' ', 'yes');	// Target
	add_option('socialslider_nofollow',				'tak',			' ', 'yes');	// Nofollow
	add_option('socialslider_mobile',				'tak',			' ', 'yes');	// Mobile
	add_option('socialslider_rozdzielczosc',		'0px',			' ', 'yes');	// Rozdzielczość
	add_option('socialslider_top',					'150px',		' ', 'yes');	// Top
	add_option('socialslider_tryb',					'uproszczony', 	' ', 'yes');	// Tryb
	}

$socialslider_promocja = "N";

// licencja
$ssp64 = dirname(dirname(dirname(__FILE__)))."/".$socialslider."/license.key";

if(file_exists($ssp64))
	{
	$ssexplode = explode("*", base64_decode(fread(fopen($ssp64,"r"), filesize($ssp64))));

	if(($ssexplode[0]=="W" && $ssexplode[2] == base64_encode(str_replace("https://", "http://", get_bloginfo('wpurl')))) || ($ssexplode[0]=="E" && $ssexplode[1] == base64_encode(get_bloginfo('admin_email'))) || ($ssexplode[0]=="M" && $ssexplode[1] == base64_encode(get_bloginfo('admin_email')) && $ssexplode[2] == base64_encode(str_replace("https://", "http://", get_bloginfo('wpurl')))))
		{$socialslider_licencja = base64_decode($ssexplode[3]);}
	else
		{$socialslider_licencja = base64_decode(get_option('socialslider_licencja'));}
	}
else
	{$socialslider_licencja = base64_decode(get_option('socialslider_licencja'));}

list($socialslider_rok, $socialslider_miesiac, $socialslider_dzien) = explode('-', base64_decode($socialslider_licencja));
if(@checkdate($socialslider_miesiac, $socialslider_dzien, $socialslider_rok))	$socialslider_data = $socialslider_licencja;
else																			$socialslider_data = "MjAxMC0wMS0wMQ==";

function SocialSliderUstawienia()
	{
	global $wpdb, $table_prefix, $socialslider, $socialslider_baza, $socialslider_katalog, $socialslider_promocja, $socialslider_data, $socialslider_referer, $socialslider_wersja, $socialslider_instalacja;

	$socialtabela = $table_prefix."socialslider";

	if($wpdb->get_var("SHOW TABLES LIKE '".$socialtabela."'") != $socialtabela)
		{
		// Tworzenie nowej tabeli
		$wpdb->query("CREATE TABLE `".$socialtabela."` (`id` TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY,`lp` TINYINT NOT NULL,`ikona` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,`nazwa` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,`adres` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL) ENGINE = MYISAM;");
		$wpdb->query("ALTER TABLE `".$socialtabela."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

		// Uzupełnienie tabeli
		$is = 1;

		$wpdb->query("INSERT INTO `".$socialtabela."` (`id`,`lp`,`ikona`,`nazwa`,`adres`) VALUES
			(NULL,	'".$is++."',	'rss',				'RSS',				''),
			(NULL,	'".$is++."',	'newsletter',		'Newsletter',		''),

			(NULL,	'".$is++."',	'facebook',			'Facebook',			'http://www.facebook.com/SocialSlider'),
			(NULL,	'".$is++."',	'googleplus',		'Google+',			''),
			(NULL,	'".$is++."',	'spinacz',			'Spinacz',			''),
			(NULL,	'".$is++."',	'goldenline',		'GoldenLine',		''),
			(NULL,	'".$is++."',	'linkedin',			'LinkedIn',			''),
			(NULL,	'".$is++."',	'naszaklasa',		'Nasza Klasa',		''),
			(NULL,	'".$is++."',	'networkedblogs',	'NetworkedBlogs',	''),
			(NULL,	'".$is++."',	'myspace',			'MySpace',			''),
			(NULL,	'".$is++."',	'orkut',			'Orkut',			''),
			(NULL,	'".$is++."',	'grono',			'Grono',			''),
			(NULL,	'".$is++."',	'friendconnect',	'FriendConnect',	''),
			(NULL,	'".$is++."',	'friendfeed',		'FriendFeed',		''),

			(NULL,	'".$is++."',	'sledzik',			'Śledzik',			''),
			(NULL,	'".$is++."',	'blip',				'Blip',				''),
			(NULL,	'".$is++."',	'flaker',			'Flaker',			''),
			(NULL,	'".$is++."',	'twitter',			'Twitter',			''),
			(NULL,	'".$is++."',	'soup',				'Soup.io',			''),
			(NULL,	'".$is++."',	'buzz',				'Buzz',				''),
			(NULL,	'".$is++."',	'tumblr',			'Tumblr',			''),

			(NULL,	'".$is++."',	'digg',				'Digg',				''),
			(NULL,	'".$is++."',	'wykop',			'Wykop',			''),
			(NULL,	'".$is++."',	'kciuk',			'Kciuk',			''),

			(NULL,	'".$is++."',	'picasa',			'Picasa',			''),
			(NULL,	'".$is++."',	'flickr',			'Flickr',			''),
			(NULL,	'".$is++."',	'panoramio',		'Panoramio',		''),
			(NULL,	'".$is++."',	'deviantart',		'DeviantArt',		''),

			(NULL,	'".$is++."',	'youtube',			'YouTube',			''),
			(NULL,	'".$is++."',	'vimeo',			'Vimeo',			''),
			(NULL,	'".$is++."',	'imdb',				'IMDb',				''),

			(NULL,	'".$is++."',	'lastfm',			'Last.fm',			''),
			(NULL,	'".$is++."',	'ising',			'iSing',			''),
			(NULL,	'".$is++."',	'blipfm',			'Blip.fm',			''),

			(NULL,	'".$is++."',	'delicious',		'Delicious',		''),
			(NULL,	'".$is++."',	'unifyer',			'Unifyer',			''),
			(NULL,	'".$is++."',	'pinterest',		'Pinterest',		'')
			;");
		}

	// Resetowanie ustawień
	if($_POST['SocialSliderResetuj'])
		{
		if($wpdb->get_var("SHOW TABLES LIKE '".$socialtabela."'") == $socialtabela)
			{
			// Tabela istnieje - usuwanie tabeli
			$wpdb->query("DROP TABLE `".$socialtabela."`");
			}

		delete_option('socialslider_widget');

		if(get_option('socialslider_widget_width'))		{update_option('socialslider_widget_width', '200px');}
		else											{add_option('socialslider_widget_width', '200px', ' ', 'yes');}

		if(get_option('socialslider_widget_height'))	{update_option('socialslider_widget_height', 'auto');}
		else											{add_option('socialslider_widget_height', 'auto', ' ', 'yes');}

		if(get_option('socialslider_miejsce'))			{update_option('socialslider_miejsce', 'lewa');}
		else											{add_option('socialslider_miejsce', 'lewa', ' ', 'yes');}

		if(get_option('socialslider_kolor'))			{update_option('socialslider_kolor', 'jasny');}
		else											{add_option('socialslider_kolor', 'jasny', ' ', 'yes');}

		if(get_option('socialslider_custom_background')){update_option('socialslider_custom_background', '#ffffff');}
		else											{add_option('socialslider_custom_background', '#ffffff', ' ', 'yes');}

		if(get_option('socialslider_custom_border'))	{update_option('socialslider_custom_border', '#cccccc');}
		else											{add_option('socialslider_custom_border', '#cccccc', ' ', 'yes');}

		if(get_option('socialslider_custom_font'))		{update_option('socialslider_custom_font', '#666666');}
		else											{add_option('socialslider_custom_font', '#666666', ' ', 'yes');}

		if(get_option('socialslider_custom_radius'))	{update_option('socialslider_custom_radius', '6px');}
		else											{add_option('socialslider_custom_radius', '6px', ' ', 'yes');}

		if(get_option('socialslider_opacity'))			{update_option('socialslider_opacity', '1');}
		else											{add_option('socialslider_opacity', '1', ' ', 'yes');}

		if(get_option('socialslider_ikony'))			{update_option('socialslider_ikony', 'standard');}
		else											{add_option('socialslider_ikony', 'standard', ' ', 'yes');}

		if(get_option('socialslider_szybkosc'))			{update_option('socialslider_szybkosc', 'normal');}
		else											{add_option('socialslider_szybkosc', 'normal', ' ', 'yes');}

		if(get_option('socialslider_link'))				{update_option('socialslider_link', 'text');}
		else											{add_option('socialslider_link', 'text', ' ', 'yes');}

		if(get_option('socialslider_position'))			{update_option('socialslider_position', 'fixed');}
		else											{add_option('socialslider_position', 'fixed', ' ', 'yes');}

		if(get_option('socialslider_target'))			{update_option('socialslider_target', 'self');}
		else											{add_option('socialslider_target', 'self', ' ', 'yes');}

		if(get_option('socialslider_nofollow'))			{update_option('socialslider_nofollow', 'tak');}
		else											{add_option('socialslider_nofollow', 'tak', ' ', 'yes');}

		if(get_option('socialslider_mobile'))			{update_option('socialslider_mobile', 'tak');}
		else											{add_option('socialslider_mobile', 'tak', ' ', 'yes');}

		if(get_option('socialslider_rozdzielczosc'))	{update_option('socialslider_rozdzielczosc', '0px');}
		else											{add_option('socialslider_rozdzielczosc', '0px', ' ', 'yes');}

		if(get_option('socialslider_top'))				{update_option('socialslider_top', '150px');}
		else											{add_option('socialslider_top', '150px', ' ', 'yes');}

		if(get_option('socialslider_tryb'))				{delete_option('socialslider_tryb');}
		}

	// Zapisywanie ustawień
	if($_POST['SocialSliderZapisz'])
		{
		// Serwisy
		$serwisy = $wpdb->get_results("SELECT * FROM ".$socialtabela."");

		foreach ($serwisy as $serwis)
			{
			$input	= "socialslider_".$serwis->ikona;
			$inputn	= "socialslider_nazwa_".$serwis->ikona;

			if(isset($_POST[$inputn]))
				{
				$wpdb->query("UPDATE `".$socialtabela."` SET `adres` = '".$_POST[$input]."' WHERE `ikona` = '".$serwis->ikona."'");
				$wpdb->query("UPDATE `".$socialtabela."` SET `nazwa` = '".$_POST[$inputn]."' WHERE `ikona` = '".$serwis->ikona."'");
				}

			if(empty($_POST[$inputn]))
				{
				$wpdb->query("DELETE FROM `".$socialtabela."` WHERE `ikona` = '".$serwis->ikona."'");
				}
			}

		// Widget
		if(!empty($_POST['socialslider_widget']))				{update_option('socialslider_widget', $_POST['socialslider_widget']);}

		// Widget width
		if(!empty($_POST['socialslider_widget_width']))			{update_option('socialslider_widget_width', $_POST['socialslider_widget_width']);}

		// Widget height
		if(!empty($_POST['socialslider_widget_height']))		{update_option('socialslider_widget_height', $_POST['socialslider_widget_height']);}

		// Miejsce
		if(!empty($_POST['socialslider_miejsce']))				{update_option('socialslider_miejsce', $_POST['socialslider_miejsce']);}

		// Kolor
		if(!empty($_POST['socialslider_kolor']))				{update_option('socialslider_kolor', $_POST['socialslider_kolor']);}

		// Własny CSS - tło
		if(!empty($_POST['socialslider_custom_background']))	{update_option('socialslider_custom_background', $_POST['socialslider_custom_background']);}

		// Własny CSS - obramowanie
		if(!empty($_POST['socialslider_custom_border']))		{update_option('socialslider_custom_border', $_POST['socialslider_custom_border']);}

		// Własny CSS - czcionka
		if(!empty($_POST['socialslider_custom_font']))			{update_option('socialslider_custom_font', $_POST['socialslider_custom_font']);}

		// Własny CSS - narożnik
		if(!empty($_POST['socialslider_custom_radius']))		{update_option('socialslider_custom_radius', $_POST['socialslider_custom_radius']);}

		// Przezroczystość
		if(!empty($_POST['socialslider_opacity']))				{update_option('socialslider_opacity', $_POST['socialslider_opacity']);}

		// Ikony
		if(!empty($_POST['socialslider_ikony']))				{update_option('socialslider_ikony', $_POST['socialslider_ikony']);}

		// Szybkosc
		if(!empty($_POST['socialslider_szybkosc']))				{update_option('socialslider_szybkosc', $_POST['socialslider_szybkosc']);}

		// Link
		if(!empty($_POST['socialslider_link']))					{update_option('socialslider_link', $_POST['socialslider_link']);}

		// Pozycja
		if(!empty($_POST['socialslider_position']))				{update_option('socialslider_position', $_POST['socialslider_position']);}

		// Target
		if(!empty($_POST['socialslider_target']))				{update_option('socialslider_target', $_POST['socialslider_target']);}

		// Nofollow
		if(!empty($_POST['socialslider_nofollow']))				{update_option('socialslider_nofollow', $_POST['socialslider_nofollow']);}

		// Mobile
		if(!empty($_POST['socialslider_mobile']))				{update_option('socialslider_mobile', $_POST['socialslider_mobile']);}

		// Rozdzielczość
		if(!empty($_POST['socialslider_rozdzielczosc']))		{update_option('socialslider_rozdzielczosc', $_POST['socialslider_rozdzielczosc']);}

		// Top
		if(!empty($_POST['socialslider_top']))					{update_option('socialslider_top', $_POST['socialslider_top']);}

		// Tryb
		if(!empty($_POST['socialslider_tryb']))					{update_option('socialslider_tryb', $_POST['socialslider_tryb']);}
		}

	if($_POST['SocialSliderNew'])
		{
		if(!empty($_POST['socialslider_new']))
			{
			$nazwa		= $_POST['socialslider_new'];
			$ikona		= $_POST['socialslider_new_images'];
			$lastid		= $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM `".$socialtabela."`;"));
			$newid		= $lastid+1;

			$wpdb->query("INSERT INTO `".$socialtabela."` (`id`,`lp`,`ikona`,`nazwa`,`adres`) VALUES (NULL, '".$newid."', '".$ikona."', '".$nazwa."', '')");
			}
		}
	?>
	<div class="wrap">
		<style type="text/css">
			input, textarea, select, table tr td
				{
				color: #555;
				font-size: 10px;
				}

			ul.serwisy
				{
				width: 660px;
				margin: 15px 0 0 20px;
				}

			ul.serwisy li
				{
				margin-bottom: 10px;
				}

			ul.serwisy label
				{
				width: 150px;
				float: left;
				}

			div.pro label
				{
				width: 140px;
				float: left;
				}

			ul.serwisy label img
				{
				margin-right: 5px;
				vertical-align: middle;
				width: 20px;
				height: 20px;
				}

			ul.serwisy input, ul.serwisy textarea, div.pro input.text, div.pro select
				{
				float: right;
				}

			ul.serwisy textarea
				{
				width: 500px;
				}

			ul.serwisy input.text
				{
				width: 388px;
				}

			ul.serwisy input.textn
				{
				width: 110px;
				}

			div.opcja
				{
				margin: 0 0 40px 20px;
				padding-left: 15px;
				border-top: 1px solid #ddd;
				border-left: 1px solid #ddd;
				}

			p.radio
				{
				color: #555;
				font-size: 10px;
				margin-left: 20px;
				}

			div.pro
				{
				width: 800px;
				margin: 10px 0 40px 20px;
				}

			div.pro input.text, div.pro select
				{
				margin-right: 340px;
				}

			div.pro ul
				{
				list-style-type: circle;
				margin: 0 0 40px 20px;
				}

			div.tryby
				{
				float: left;
				margin-left: 15px;
				text-align: center;
				}

			div.tryby img
				{
				border: 1px solid #ddd;
				height: 250px;
				}
		</style>

		<?php
		if(preg_match('/[a-z]+/', get_option('socialslider_top')))				$socialslider_top			= get_option('socialslider_top');			else $socialslider_top				= get_option('socialslider_top')."px";
		if(preg_match('/[a-z]+/', get_option('socialslider_widget_width')))		$socialslider_widget_width	= get_option('socialslider_widget_width');	else $socialslider_widget_width		= get_option('socialslider_widget_width')."px";
		if(preg_match('/[a-z]+/', get_option('socialslider_widget_height')))	$socialslider_widget_height	= get_option('socialslider_widget_height');	else $socialslider_widget_height	= get_option('socialslider_widget_height')."px";

		$socialslider_name		= "Social Slider";
		$socialslider_sort		= "id";
		$socialslider_disable	= " disabled";
		$socialslider_only		= " (".__('This option is available in the <a href=\'#pro\'>Pro version</a>', 'social-slider').")";
        $socialslider_promo     = '<div class="error fade" style="background-color: #c6ffc7; border-color: #114212;"><p style="line-height: 18px;">'.__("Hey Blogger! I've got some great news! Social Slider Pro plugin is now on sale, so if you decide to buy the license now, <strong>the price will be 50&#37; lower</strong>! Only 113 PLN (~34 USD)!", 'social-slider').' '.__("<a href='#pro'>More info...</a>", 'social-slider').'</p><p style="line-height: 18px;">'.__("A Developer License is on sale! This special type of license lets you use Social Slider on an unlimited number of your blogs without any limitations. The promotional price for this license is only 940 PLN (~299 USD). After the sale is over, the price will rise to 1880 PLN (that is ~599 USD). <strong>Don't wait! Buy the special Social Slider Developer License for only 299 USD now!</strong>", 'social-slider').' '.__("<a href='#devpro'>More info...</a>", 'social-slider').'</p></div>';

		if(date("Y-m-d")<=base64_decode($socialslider_data))
			{
			$socialslider_name		= "Social Slider Pro";
			$socialslider_sort		= "lp";
			$socialslider_disable	= "";
			$socialslider_only		= "";
            $socialslider_promo     = "";
			}

		if(get_option('socialslider_ikony'))
			{
			$socialslider_ikony	= get_option('socialslider_ikony');
			}
		else
			{
			$socialslider_ikony	= "standard";
			}
		?>

		<div id="socialslider">
			<h2><?php _e('Configuration of', 'social-slider') ?> <?php echo $socialslider_name; ?></h2>

            <?php echo $socialslider_promo ?>

			<form action="options-general.php?page=<?php echo $socialslider; ?>/<?php echo $socialslider; ?>.php" method="post" id="social-slider-config">

				<div class="opcja">
					<p><?php _e("Social Slider display mode:", 'social-slider') ?></p>
					<p class="radio" id="ss_pelny"><input type="radio" name="socialslider_tryb" id="socialslider_tryb_pelny" value="pelny"<?php if(get_option('socialslider_tryb')=="pelny") echo " checked"; ?> /> <label for="socialslider_tryb_pelny"><?php _e("Full", 'social-slider') ?> (<a href="http://social-slider.com/demo/full.html" target="_blank"><?php _e("live demo", 'social-slider') ?></a>)</label></p>
					<p class="radio"><input type="radio" name="socialslider_tryb" id="socialslider_tryb_uproszczony" value="uproszczony"<?php if(get_option('socialslider_tryb')=="uproszczony" OR !get_option('socialslider_tryb')) echo " checked"; ?> /> <label for="socialslider_tryb_uproszczony"><?php _e("Simple (without the widget)", 'social-slider') ?> (<a href="http://social-slider.com/demo/simple.html" target="_blank"><?php _e("live demo", 'social-slider') ?></a>)</label></p>
					<p class="radio"><input type="radio" name="socialslider_tryb" id="socialslider_tryb_kompaktowy" value="kompaktowy"<?php if(get_option('socialslider_tryb')=="kompaktowy") echo " checked"; ?> /> <label for="socialslider_tryb_kompaktowy"><?php _e("Compact (without the widget and large icons)", 'social-slider') ?> (<a href="http://social-slider.com/demo/compact.html" target="_blank"><?php _e("live demo", 'social-slider') ?></a>)</label></p>
					<p class="radio"><input type="radio" name="socialslider_tryb" id="socialslider_tryb_minimalny" value="minimalny"<?php if(get_option('socialslider_tryb')=="minimalny") echo " checked"; ?> /> <label for="socialslider_tryb_minimalny"><?php _e("Minimal with small icons (Non-expandable set of small icons)", 'social-slider') ?> (<a href="http://social-slider.com/demo/minimal-small-icons.html" target="_blank"><?php _e("live demo", 'social-slider') ?></a>)</label></p>
					<p class="radio"><input type="radio" name="socialslider_tryb" id="socialslider_tryb_minimalny_duzy" value="minimalny_duzy"<?php if(get_option('socialslider_tryb')=="minimalny_duzy") echo " checked"; echo $socialslider_disable; ?> /> <label for="socialslider_tryb_minimalny_duzy"><?php _e("Minimal with big icons (Non-expandable set of big icons)", 'social-slider') ?> (<a href="http://social-slider.com/demo/minimal-big-icons.html" target="_blank"><?php _e("live demo", 'social-slider') ?></a>)</label> <?php echo $socialslider_only; ?></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php _e("Save", 'social-slider') ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php _e("Position of Social Slider from the top of the page:", 'social-slider') ?></p>
					<p class="radio"><input type="text" class="text" style="width: 50px;" value="<?php if(get_option('socialslider_top')) {echo $socialslider_top;} else {echo "150px";} ?>" name="socialslider_top" id="socialslider_top" /> (<?php _e("default:", 'social-slider') ?> 150px)</p>
					<input type="submit" name="SocialSliderZapisz" value="<?php _e("Save", 'social-slider') ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php _e("Social Slider behaviour during scrolling:", 'social-slider') ?></p>
					<p class="radio"><input type="radio" name="socialslider_position" id="socialslider_position_fixed" value="fixed"<?php if(get_option('socialslider_position')=="fixed" OR !get_option('socialslider_position')) {echo " checked";} ?> /> <label for="socialslider_position_fixed"><?php _e("Place Social Slider on a fixed height of the browser's window", 'social-slider') ?></label></p>
					<p class="radio"><input type="radio" name="socialslider_position" id="socialslider_position_absolute" value="absolute"<?php if(get_option('socialslider_position')=="absolute") {echo " checked";}?> /> <label for="socialslider_position_absolute"><?php _e("Scroll Social Slider along with the page", 'social-slider') ?></label></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php _e("Save", 'social-slider') ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php _e("Widget's container size in full mode:", 'social-slider') ?></p>
					<p class="radio"><?php _e("Width:", 'social-slider') ?><br /><input type="text" class="text" style="width: 50px;" value="<?php if(get_option('socialslider_widget_width')) {echo $socialslider_widget_width;} else {echo "200px";} ?>" name="socialslider_widget_width" id="socialslider_widget_width" <?php if(date("Y-m-d")>base64_decode($socialslider_data)) {echo "readonly ";} ?>/> <?php echo $socialslider_only; ?></p>
					<p class="radio"><?php _e("Height:", 'social-slider') ?><br /><input type="text" class="text" style="width: 50px;" value="<?php if(get_option('socialslider_widget_height')) {echo $socialslider_widget_height;} else {echo "auto";} ?>" name="socialslider_widget_height" id="socialslider_widget_height" <?php if(date("Y-m-d")>base64_decode($socialslider_data)) {echo "readonly ";} ?>/> <?php echo $socialslider_only; ?></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php _e("Save", 'social-slider') ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php _e("Placement of Social Slider:", 'social-slider') ?></p>
					<p class="radio"><input type="radio" name="socialslider_miejsce" id="socialslider_miejsce_lewa" value="lewa"<?php if(get_option('socialslider_miejsce')=="lewa" OR !get_option('socialslider_miejsce') OR !empty($socialslider_disable)) {echo " checked";} ?> /> <label for="socialslider_miejsce_lewa"><?php _e("Left side of the screen", 'social-slider') ?></label></p>
					<p class="radio"><input type="radio" name="socialslider_miejsce" id="socialslider_miejsce_prawa" value="prawa"<?php if(get_option('socialslider_miejsce')=="prawa" AND empty($socialslider_disable)) {echo " checked";} echo $socialslider_disable; ?> /> <label for="socialslider_miejsce_prawa"><?php _e("Right side of the screen", 'social-slider') ?></label> <?php echo $socialslider_only; ?></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php _e("Save", 'social-slider') ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php _e("Color Scheme:", 'social-slider') ?></p>
					<p class="radio"><input type="radio" name="socialslider_kolor" id="socialslider_kolor_jasny" value="jasny"<?php if(get_option('socialslider_kolor')=="jasny" OR !get_option('socialslider_kolor') OR !empty($socialslider_disable)) {echo " checked";} ?> /> <label for="socialslider_kolor_jasny"><?php _e("Light", 'social-slider') ?></label></p>
					<p class="radio"><input type="radio" name="socialslider_kolor" id="socialslider_kolor_ciemny" value="ciemny"<?php if(get_option('socialslider_kolor')=="ciemny") {echo " checked";}?> /> <label for="socialslider_kolor_ciemny"><?php _e("Dark", 'social-slider') ?></label></p>
					<p class="radio"><input type="radio" name="socialslider_kolor" id="socialslider_kolor_css" value="css"<?php if(get_option('socialslider_kolor')=="css" AND empty($socialslider_disable)) {echo " checked";} echo $socialslider_disable; ?> /> <label for="socialslider_kolor_css"><?php _e("Custom style", 'social-slider') ?></label> <?php echo $socialslider_only; ?></p>

					<table style="margin: 15px 0 10px 40px;">
						<tr><td style="width: 140px;"><?php _e("Background color:", 'social-slider') ?></td><td><input type="text" class="text" style="width: 70px;" value="<?php if(get_option('socialslider_custom_background')) {echo get_option('socialslider_custom_background');} else {echo "#ffffff";} ?>" name="socialslider_custom_background" id="socialslider_custom_background" <?php if(date("Y-m-d")>base64_decode($socialslider_data)) {echo "readonly ";} ?>/> <?php echo $socialslider_only; ?></td></tr>
						<tr><td><?php _e("Border color:", 'social-slider') ?></td><td><input type="text" class="text" style="width: 70px;" value="<?php if(get_option('socialslider_custom_border')) {echo get_option('socialslider_custom_border');} else {echo "#cccccc";} ?>" name="socialslider_custom_border" id="socialslider_custom_border" <?php if(date("Y-m-d")>base64_decode($socialslider_data)) {echo "readonly ";} ?>/> <?php echo $socialslider_only; ?></td></tr>
						<tr><td><?php _e("Font color:", 'social-slider') ?></td><td><input type="text" class="text" style="width: 70px;" value="<?php if(get_option('socialslider_custom_font')) {echo get_option('socialslider_custom_font');} else {echo "#666666";} ?>" name="socialslider_custom_font" id="socialslider_custom_font" <?php if(date("Y-m-d")>base64_decode($socialslider_data)) {echo "readonly ";} ?>/> <?php echo $socialslider_only; ?></td></tr>
						<tr><td><?php _e("Corner radius:", 'social-slider') ?></td><td><input type="text" class="text" style="width: 70px;" value="<?php if(get_option('socialslider_custom_radius')) {echo get_option('socialslider_custom_radius');} else {echo "6px";} ?>" name="socialslider_custom_radius" id="socialslider_custom_radius" <?php if(date("Y-m-d")>base64_decode($socialslider_data)) {echo "readonly ";} ?>/> <?php echo $socialslider_only; ?></td></tr>
					</table>

					<p class="radio" style="margin: 0 0 20px 40px;"><?php _e("\"Custom style\" option gives you the ability to customize Social Slider, so as it fits your blog's theme. But remember that not all browsers fully support CSS3 on which this option is based. Modern browsers like Chrome or Firefox will display Social Slider correctly, however your readers using Opera or Internet Explorer will see Social Slider without rounded corners. The selected colors will be displayed correctly in all browsers.", 'social-slider') ?></p>

					<input type="submit" name="SocialSliderZapisz" value="<?php _e("Save", 'social-slider') ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php _e("Transparency:", 'social-slider') ?></p>
					<p class="radio"><input type="radio" name="socialslider_opacity" id="socialslider_opacity_1" value="1"<?php if(get_option('socialslider_opacity')=="1" OR !get_option('socialslider_opacity') OR !empty($socialslider_disable)) {echo " checked";} ?> /> <label for="socialslider_opacity_1"><?php _e("Opacity 100&#37;", 'social-slider') ?></label></p>
					<p class="radio"><input type="radio" name="socialslider_opacity" id="socialslider_opacity_9" value="9"<?php if(get_option('socialslider_opacity')=="9" AND empty($socialslider_disable)) {echo " checked";} echo $socialslider_disable; ?> /> <label for="socialslider_opacity_9"><?php _e("Opacity 90&#37;", 'social-slider') ?></label> <?php echo $socialslider_only; ?></p>
					<p class="radio"><input type="radio" name="socialslider_opacity" id="socialslider_opacity_8" value="8"<?php if(get_option('socialslider_opacity')=="8" AND empty($socialslider_disable)) {echo " checked";} echo $socialslider_disable; ?> /> <label for="socialslider_opacity_8"><?php _e("Opacity 80&#37;", 'social-slider') ?></label> <?php echo $socialslider_only; ?></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php _e("Save", 'social-slider') ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php _e("Icon set:", 'social-slider') ?></p>
					<p class="radio"><input type="radio" name="socialslider_ikony" id="socialslider_ikony_standard" value="standard"<?php if(get_option('socialslider_ikony')=="standard" OR !get_option('socialslider_ikony')) {echo " checked";} ?> /> <label for="socialslider_ikony_standard"><?php _e("Standard icon set", 'social-slider') ?>
						<p style="margin: 8px 0 0 20px;">
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/standard/buzz-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/standard/goldenline-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/standard/lastfm-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/standard/networkedblogs-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/standard/orkut-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/standard/panoramio-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/standard/picasa-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/standard/twitter-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/standard/youtube-32.png" alt="" />
						</p>
					</label></p>

					<p class="radio"><input type="radio" name="socialslider_ikony" id="socialslider_ikony_futomaki" value="futomaki"<?php if(get_option('socialslider_ikony')=="futomaki") {echo " checked";} ?> /> <label for="socialslider_ikony_futomaki"><?php _e("Icons created by <a href='http://futomaki.pl' title='Alex Apleczny'>Alex Paleczny</a>", 'social-slider') ?>
						<p style="margin: 8px 0 0 0;">
							<img style="width: 32px; height: 32px; margin-left: 20px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/futomaki/buzz-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/futomaki/goldenline-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/futomaki/lastfm-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/futomaki/networkedblogs-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/futomaki/orkut-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/futomaki/panoramio-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/futomaki/picasa-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/futomaki/twitter-32.png" alt="" />
							<img style="width: 32px; height: 32px;" src="<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/icons/futomaki/youtube-32.png" alt="" />
						</p>
					</label></p>

					<input type="submit" name="SocialSliderZapisz" value="<?php _e("Save", 'social-slider') ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php _e("How fast should the Social Slider expand:", 'social-slider') ?></p>
					<p class="radio"><input type="radio" name="socialslider_szybkosc" id="socialslider_szybkosc_slow" value="slow"<?php if(get_option('socialslider_szybkosc')=="slow") echo " checked"; ?> /> <label for="socialslider_szybkosc_slow"><?php _e("Slowly", 'social-slider') ?></label></p>
					<p class="radio"><input type="radio" name="socialslider_szybkosc" id="socialslider_szybkosc_normal" value="normal"<?php if(get_option('socialslider_szybkosc')=="normal" OR !get_option('socialslider_szybkosc')) {echo " checked";} ?> /> <label for="socialslider_szybkosc_normal"><?php _e("Normal", 'social-slider') ?></label></p>
					<p class="radio"><input type="radio" name="socialslider_szybkosc" id="socialslider_szybkosc_fast" value="fast"<?php if(get_option('socialslider_szybkosc')=="fast") echo " checked"; ?> /> <label for="socialslider_szybkosc_fast"><?php _e("Fast", 'social-slider') ?></label></p>
					<p class="radio"><input type="radio" name="socialslider_szybkosc" id="socialslider_szybkosc_nojs" value="nojs"<?php if(get_option('socialslider_szybkosc')=="nojs") echo " checked"; ?> /> <label for="socialslider_szybkosc_nojs"><?php _e("Without smooth expanding (recommended if smooth expanding doesn't work for any reasons)", 'social-slider') ?></label></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php _e("Save", 'social-slider') ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php _e("Display name of the plugin Social Slider:", 'social-slider') ?></p>
					<p class="radio"><input type="radio" class="text" value="tak" name="socialslider_link" id="socialslider_link_tak"<?php if(get_option('socialslider_link')=="tak") {echo " checked";} ?>/> <label for="socialslider_link_tak"><?php _e("Show link to the Social Slider website", 'social-slider') ?></label></p>
					<p class="radio"><input type="radio" class="text" value="text" name="socialslider_link" id="socialslider_link_text"<?php if(get_option('socialslider_link')=="text" OR !get_option('socialslider_link')) {echo " checked";} ?>/> <label for="socialslider_link_text"><?php _e("Display only plugin name", 'social-slider') ?></label></p>
					<p class="radio"><input type="radio" class="text" value="nie" name="socialslider_link" id="socialslider_link_nie"<?php if(get_option('socialslider_link')=="nie"  AND empty($socialslider_disable)) {echo " checked";} echo $socialslider_disable; ?>/> <label for="socialslider_link_nie"><?php _e("Don't show anything", 'social-slider') ?> <?php echo $socialslider_only; ?></label></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php _e("Save", 'social-slider') ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php _e("Show Social Slider in mobile browsers:", 'social-slider') ?></p>
					<p class="radio"><input type="radio" class="text" value="tak" name="socialslider_mobile" id="socialslider_mobile_tak"<?php if(get_option('socialslider_mobile')=="tak") echo " checked"; ?>/> <label for="socialslider_mobile_tak"><?php _e("Yes", 'social-slider') ?></label></p>
					<p class="radio"><input type="radio" class="text" value="nie" name="socialslider_mobile" id="socialslider_mobile_nie"<?php if(get_option('socialslider_mobile')=="nie" OR !get_option('socialslider_mobile')) {echo " checked";} ?>/> <label for="socialslider_mobile_nie"><?php _e("No", 'social-slider') ?></label></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php _e("Save", 'social-slider') ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php _e("Don't show Social Slider when the screen's resolution is lower than:", 'social-slider') ?></p>
					<p class="radio"><input type="text" class="text" style="width: 50px;" value="<?php if(get_option('socialslider_rozdzielczosc')) {echo get_option('socialslider_rozdzielczosc');} else {echo "0px";} ?>" name="socialslider_rozdzielczosc" id="socialslider_rozdzielczosc" <?php if(date("Y-m-d")>base64_decode($socialslider_data)) {echo "readonly ";} ?> /> (<?php _e("default:", 'social-slider') ?> 0px) <?php echo $socialslider_only; ?></p>
					<p class="radio"><?php _e("Leave \"0px\" if you want Social Slider to be displayed in any resolution", 'social-slider') ?></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php _e("Save", 'social-slider') ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php _e("After clicking on a site's icon, load it in:", 'social-slider') ?></p>
					<p class="radio"><input type="radio" name="socialslider_target" id="socialslider_target_self" value="self"<?php if(get_option('socialslider_target')=="self" OR !get_option('socialslider_target') OR !empty($socialslider_disable)) {echo " checked";} ?> /> <label for="socialslider_target_self"><?php _e("The current window", 'social-slider') ?></label></p>
					<p class="radio"><input type="radio" name="socialslider_target" id="socialslider_target_blank" value="blank"<?php if(get_option('socialslider_target')=="blank" AND empty($socialslider_disable)) {echo " checked";} echo $socialslider_disable; ?> /> <label for="socialslider_target_blank"><?php _e("A new window", 'social-slider') ?></label> <?php echo $socialslider_only; ?></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php _e("Save", 'social-slider') ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
					<p><?php _e("Add \"nofollow\" attribute to outgoing links:", 'social-slider') ?></p>
					<p class="radio"><input type="radio" name="socialslider_nofollow" id="socialslider_nofollow_tak" value="tak"<?php if(get_option('socialslider_nofollow')=="tak" OR !get_option('socialslider_nofollow')) {echo " checked";} ?> /> <label for="socialslider_nofollow_tak"><?php _e("Yes", 'social-slider') ?></label></p>
					<p class="radio"><input type="radio" name="socialslider_nofollow" id="socialslider_nofollow_nie" value="nie"<?php if(get_option('socialslider_nofollow')=="nie") {echo " checked";} ?> /> <label for="socialslider_nofollow_nie"><?php _e("No", 'social-slider') ?></label></p>
					<input type="submit" name="SocialSliderZapisz" value="<?php _e("Save", 'social-slider') ?>" style="margin: 5px 0 5px 20px;" />
				</div>

				<div class="opcja">
				<?php
				if(date("Y-m-d")<=base64_decode($socialslider_data))
					{
					?>
					<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery(function() {
								jQuery("ul#serwisy").sortable({ opacity: 0.6, cursor: 'nw-resize', update: function() {
									var order = jQuery(this).sortable("serialize") + '&action=ZapiszPozycje&control=<?php echo $socialslider_instalacja; ?>';
									jQuery.post("<?php echo WP_PLUGIN_URL; ?>/<?php echo $socialslider; ?>/ajax.php", order, function(theResponse){
										jQuery("div#ajax").html(theResponse);
									});
								}
								});
							});
						});
					</script>
					<?php } ?>

					<p><?php _e("Provide the full URLs of your profiles on social networking sites. If you don't use a site, leave the field blank.<br /><br />Note: You can change the order of the fields using drag&drop in the <a href='#pro'>Pro version</a>.", 'social-slider') ?></p>
					<ul id="serwisy" class="serwisy">
						<?php
						$serwisy = $wpdb->get_results("SELECT * FROM ".$table_prefix."socialslider ORDER BY ".$socialslider_sort." ASC");
						foreach ($serwisy as $serwis)
							{
							$ikona = $serwis->ikona;
							if($ikona[0]=="_")		$socialslider_katalog_ikon = $socialslider_baza."/wp-content/".$socialslider;
							else					$socialslider_katalog_ikon = WP_PLUGIN_URL."/".$socialslider."/icons/".$socialslider_ikony;

							echo "<li id='rA_".$serwis->id."'>
								<label for 'socialslider_".$serwis->ikona."'><img src='".$socialslider_katalog_ikon."/".$serwis->ikona."-20.png' alt='".$serwis->nazwa."' />".$serwis->nazwa.":</label>

								<input type='text' class='text' value='".$serwis->adres."' name='socialslider_".$serwis->ikona."' id='socialslider_".$serwis->ikona."' />
								<input type='text' class='textn' value='".$serwis->nazwa."' name='socialslider_nazwa_".$serwis->ikona."' id='socialslider_nazwa_".$serwis->ikona."' /><br style='clear: both;' />
							</li>";
							}
						?>
					</ul>

					<ul class="serwisy">
						<li>
							<label for 'socialslider_widget'><img src='<?php echo $socialslider_katalog ?>/icons/<?php echo $socialslider_ikony; ?>/widget-20.png' alt='Widget' /> <?php _e("Custom widget:", 'social-slider') ?></label>
							<textarea name="socialslider_widget" id="socialslider_widget" style="height: 200px;"><?php echo stripslashes(get_option('socialslider_widget')); ?></textarea><br />
							<p style="font-size: 10px; color: #777; line-height: 14px; margin-left: 20px;"><?php _e("Do you want to place a Facebook widget here? Check out <a href='http://wordpress.org/extend/plugins/social-slider/faq/'>FAQ</a> to see how to do it :)", 'social-slider') ?></p>
						</li>
						<br style='clear: both;' />
					</ul>

					<input type="submit" name="SocialSliderZapisz" value="<?php _e("Save", 'social-slider') ?>" style="margin: 15px 0 5px 20px;" />
				</div>
			</form>

			<h2><?php _e("Add new site", 'social-slider') ?></h2>
			<div class="pro">
				<p style="margin-bottom: 25px;"><?php _e("<strong>Social Slider</strong> contains a list of the most popular social networking sites. However, if there's no icon of a site that you use, you can add it to the list manually using this form.", 'social-slider') ?></p>

				<form action="options-general.php?page=<?php echo $socialslider; ?>/<?php echo $socialslider; ?>.php" method="post" id="social-slider-pro" style="margin-left: 20px;">

					<?php $times = time(); ?>

					<ul style="margin-left: 25px; list-style-type: none;">
						<li>
							<label for 'socialslider_new'><?php _e("Name of the site:", 'social-slider') ?></label>
							<input type='text' class='text' value='' name='socialslider_new' id='socialslider_new' /><br style='clear: both;' />
							<input type='hidden' class='text' name='socialslider_new_images' id='socialslider_new_images' value='_<?php echo $times; ?>' />
						</li>
					</ul>

					<p style="margin-top: 25px;"><?php _e("You can add the URL of your profile later - after adding the site to the list. Before you add a new site to the list, make sure you uploaded two icons of the site to <i>/wp-content/social-slider/</i> named:", 'social-slider') ?></p>

					<ul style="margin-left: 25px; list-style-type: none;">
						<li><b>_<?php echo $times; ?>-20.png</b> <i><?php _e("icon of the site, size 20px", 'social-slider') ?></i></li>
						<li><b>_<?php echo $times; ?>-32.png</b> <i><?php _e("icon of the site, size 32px", 'social-slider') ?></i></li>
					</ul>

					<p style="margin-top: 25px;"><?php _e("Set the names of the icon files exactly as given above (including the underscore at the beginning). When both of the files are uploaded,  click the button below:", 'social-slider') ?><br /><input type="submit" name="SocialSliderNew" value="<?php _e("Add new site", 'social-slider') ?>" style="margin: 20px 0 20px 0;" <?php if(date("Y-m-d")>base64_decode($socialslider_data)) {echo "onclick='this.disabled=true;' ";} ?>/> <?php echo $socialslider_only; ?></p>
				</form>
			</div>

			<h2><?php _e("Dynamic links", 'social-slider') ?></h2>
			<div class="pro">
				<p><?php _e("Users of <a href='#pro'>Pro version</a> can use dynamically generated addresses to social sites. You can use them to create quick links that enable readers of your blog to add the links to the read articles on sites like Digg, Twitter or Facebook, with a single click.", 'social-slider') ?></p>

				<p><?php _e("You can use special tags <strong>[URL]</strong> and <strong>[TITLE]</strong> to create dynamic addresses. An example address created using the tags would look like this:", 'social-slider') ?></p>

					<p style="margin-left: 25px; text-decoration: underline;">http://del.icio.us/post?url=<strong>[URL]</strong>&title=<strong>[TITLE]</strong></p>

				<p style="margin-top: 25px;"><?php _e("Readers of your blog will see the address as:", 'social-slider') ?></p>

					<?php
					$w					= $wpdb->get_row("SELECT ID,post_title FROM $wpdb->posts WHERE `post_status` LIKE 'publish' AND `post_type` LIKE 'post' ORDER BY post_date DESC LIMIT 1");
					$socialslider_url	= rawurlencode(get_permalink($w->ID));
					$socialslider_title	= rawurlencode($w->post_title);
					?>

					<p style="margin-left: 25px;"><a href="http://del.icio.us/post?url=<?php echo $socialslider_url; ?>&title=<?php echo $socialslider_title; ?>" title="Facebook">http://del.icio.us/post?url=<?php echo $socialslider_url; ?>&title=<?php echo $socialslider_title; ?></a></p>
			</div>

			<h2><?php _e("What to do when Social Slider doesn't work?", 'social-slider') ?></h2>
			<div class="pro">
				<p><?php _e("It may happen that Social Slider won't show on your blog, even if it's activated and configured. First of all, make sure that there's no caching plugin (eg. WP-SuperCache) turned on - Social Slider may be working properly, but the cache is created before <strong>Social Slider</strong> is loaded. If <strong>Social Slider</strong> still doesn't show up on page, try placing the function that starts Social Slider manually in the template file - in the footer or sidebar, the position of the function doesn't matter, it will be displayed in the same way.", 'social-slider') ?></p>
				<p><?php _e("Below you can find the code to put in the template file if you would like to run <strong>Social Slider</strong> manually:", 'social-slider') ?></p>

				<pre style="margin-left: 20px;"><span style="color: #FF0000;">&lt;?php</span><span style="color: #333333;"> SocialSlider</span><span style="color: #AE00FB;">(); </span><span style="color: #FF0000;">?&gt;</span></pre>
			</div>

			<h2 id="pro"><?php _e("Buy Social Slider Pro", 'social-slider') ?></h2>
			<div class="pro">

			<?php
			if(date("Y-m-d")<=base64_decode($socialslider_data))
				{
				if(base64_decode($socialslider_data)!="2099-12-31")	{$socialslider_data_do = base64_decode($socialslider_data);}
				else												{$socialslider_data_do = __("unlimited", 'social-slider');}

				echo "<p style='margin-left: 20px; font-style: italic;'>".__("License's expiry date", 'social-slider').": ".$socialslider_data_do."</p>";
				}
			else
				{
				?>
				<p style="color: green;"><?php _e("Limited time only! Social Slider Pro is on sale and you can <strong>buy a license for 50&#37; off</strong> - you will pay just 113 PLN (~34 USD). After the sale is over, the price will rise to 226 PLN (~68 USD).", 'social-slider') ?></p>

				<p><?php _e("Attention! Before buying Social Slider Pro, check if the basic version of the plugin works correctly on your blog.", 'social-slider') ?></p>

				<p><?php _e("To gain access to all the features of <strong>Social Slider Pro</strong>, simply buy a lifetime license using <a href='http://paypal.com/' title='PayPal'>PayPal</a>.", 'social-slider') ?></p>

				<!-- FORMULARZ -->

				<?php $custom = base64_encode(get_bloginfo('admin_email').'*'.get_bloginfo("wpurl").'*bezterminowa**N*W*'.WPLANG.'*social-slider'); ?>

				<form name="f" action="https://www.paypal.com/cgi-bin/webscr" method="post" style="margin: 0 0 20px 20px;" target="_blank">
							<input type="hidden" name="amount" value="113" />
							<input type="hidden" name="cmd" value="_xclick" />
							<input type="hidden" name="no_note" value="1" />
							<input type="hidden" name="no_shipping" value="1" />
							<input type="hidden" name="currency_code" value="PLN" />
							<input type="hidden" name="notify_url" value="http://social-slider.com/ipn_ss.php" />
							<input type="hidden" name="business" value="paypal@karteczkowo.pl" />
							<input type="hidden" name="item_name" value="Social Slider" />
							<input type="hidden" name="item_number" value="" />
							<input type="hidden" name="quantity" value="1" />
							<input type="hidden" name="lc" value="US" />
							<input type="hidden" name="custom" value="<?php echo $custom; ?>" />
							<input type="hidden" name="return" value="http://mydiy.pl" />
							<input type="hidden" name="cancel_return" value="http://mydiy.pl" />
							<input type="image" style="margin: 0 0 0 40px;" src="http://social-slider.com/img/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
				</form>

				<p><?php _e("Your license will be automatically activated just after the transaction.", 'social-slider') ?></p>
				<p><?php _e("If you would like to purchase licenses for more of your blogs, please send an e-mail to <a href='mailto:slider@wiecek.biz'>slider@wiecek.biz</a>. You can get a discount when purchasing multiple licenses at one time.", 'social-slider') ?></p>
				<?php } ?>
			</div>

			<h2 id="devpro"><?php _e("Buy Social Slider Pro Developer License", 'social-slider') ?></h2>
			<div class="pro">
				<p style="color: green;"><?php _e("A Developer License is on sale! This special type of license lets you use Social Slider on an unlimited number of your blogs without any limitations. The promotional price for this license is only 940 PLN (~299 USD). After the sale is over, the price will rise to 1880 PLN (that is ~599 USD). <strong>Don't wait! Buy the special Social Slider Developer License for only 299 USD now!</strong>", 'social-slider') ?></p>

				<!-- FORMULARZ -->

				<?php $custom = base64_encode(get_bloginfo('admin_email').'*'.get_bloginfo("wpurl").'*deweloperska**N*W*'.WPLANG.'*social-slider'); ?>

				<form name="f" action="https://www.paypal.com/cgi-bin/webscr" method="post" style="margin: 0 0 20px 20px;" target="_blank">
					<input type="hidden" name="amount" value="940" />
					<input type="hidden" name="cmd" value="_xclick" />
					<input type="hidden" name="no_note" value="1" />
					<input type="hidden" name="no_shipping" value="1" />
					<input type="hidden" name="currency_code" value="PLN" />
					<input type="hidden" name="notify_url" value="http://social-slider.com/ipn_ss.php" />
					<input type="hidden" name="business" value="paypal@karteczkowo.pl" />
					<input type="hidden" name="item_name" value="Social Slider - Developer License" />
					<input type="hidden" name="item_number" value="" />
					<input type="hidden" name="quantity" value="1" />
					<input type="hidden" name="lc" value="US" />
					<input type="hidden" name="custom" value="<?php echo $custom; ?>" />
					<input type="hidden" name="return" value="http://mydiy.pl" />
					<input type="hidden" name="cancel_return" value="http://mydiy.pl" />
					<input type="image" style="margin: 0 0 0 40px;" src="http://social-slider.com/img/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
				</form>
			</div>

			<h2><?php _e("Restoring default settings", 'social-slider') ?></h2>
			<div class="pro">
				<form action="options-general.php?page=<?php echo $socialslider; ?>/<?php echo $socialslider; ?>.php" method="post" id="social-slider-reset">
					<input type="submit" name="SocialSliderResetuj" value="<?php _e("Reset settings", 'social-slider') ?>" style="margin: 15px 0 5px 20px;" />
				</form>
			</div>

			<h2><?php _e("Social Slider's / Social Slider Pro's Terms of Use", 'social-slider') ?></h2>
			<div class="pro">
				<p><ol>
					<li><?php _e("The author and owner of the source code is <a href='http://mydiy.pl/' title='myDIY - zrób to sam!'>Łukasz Więcek</a>.", 'social-slider') ?></li>
					<li><?php _e("Please send your suggestions, ideas, questions and problem reports to <a href='mailto:slider@wiecek.biz'>slider@wiecek.biz</a>.", 'social-slider') ?></li>
					<li><?php _e("Source code of <strong>Social Slider</strong> plugin is based on the <a href='http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt' alt='GPL v2.0'>GPL v2.0</a> license.", 'social-slider') ?></li>
				</ol></p>
			</div>

			<p style="margin-top: 30px;"><?php _e("The English translation of <strong>Social Slider</strong> / <strong>Social Slider Pro</strong> was prepared by <a href='http://tomasz.topa.pl' title='Tomasz Topa'>Tomasz Topa</a>.", 'social-slider') ?></p>

		</div>
		<div id="ajax">&nbsp;</div>
	</div>
	<?php
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////						//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////		PARAMETRY		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////						//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// *** Wartości domyślne *********************************************************************************************************************************************
$socialslider_kolor					= "jasny";
$socialslider_link					= "text";
$socialslider_miejsce				= "lewa";
$socialslider_nazwa					= "Social Slider";
$socialslider_nocustom				= " AND ikona NOT LIKE '\_%'";
$socialslider_nofollow				= "tak";
$socialslider_opacity				= "1";
$socialslider_position 				= "fixed";
$socialslider_rozdzielczosc			= "0";
$socialslider_sort					= "id";
$socialslider_szybkosc 				= "normal";
$socialslider_target				= "self";
$socialslider_top					= "150px";
$socialslider_widget_height			= "auto";
$socialslider_widget_width			= "200px";
// *******************************************************************************************************************************************************************

// *** Pobranie ustawień *********************************************************************************************************************************************
if(get_option('socialslider_kolor')=="ciemny")		$socialslider_kolor				= "ciemny";
else												$socialslider_kolor				= "jasny";

if(get_option('socialslider_nofollow'))				$socialslider_nofollow			= get_option('socialslider_nofollow');
if(get_option('socialslider_position'))				$socialslider_position			= get_option('socialslider_position');
if(get_option('socialslider_szybkosc'))				$socialslider_szybkosc			= get_option('socialslider_szybkosc');
if(get_option('socialslider_top'))					$socialslider_top				= get_option('socialslider_top');
if(get_option('socialslider_widget'))				$socialslider_widget			= get_option('socialslider_widget');
if(get_option('socialslider_link'))		   		    $socialslider_link			    = get_option('socialslider_link');
// *******************************************************************************************************************************************************************

if($socialslider_nofollow!="nie")	{$nofollow = " rel='nofollow'";}
else								{$nofollow = "";}

// *** Pobranie ustawień dla licencji Pro ****************************************************************************************************************************
if(date("Y-m-d")<=base64_decode($socialslider_data))
	{
	if(get_option('socialslider_kolor'))			$socialslider_kolor				= get_option('socialslider_kolor');
	if(get_option('socialslider_link'))				$socialslider_link				= get_option('socialslider_link');
	if(get_option('socialslider_miejsce'))			$socialslider_miejsce			= get_option('socialslider_miejsce');
													$socialslider_nazwa				= "Social Slider Pro";
													$socialslider_nocustom			= "";
	if(get_option('socialslider_opacity'))			$socialslider_opacity			= get_option('socialslider_opacity');
	if(get_option('socialslider_rozdzielczosc'))	$socialslider_rozdzielczosc		= str_replace("px","",get_option('socialslider_rozdzielczosc'));
													$socialslider_sort				= "lp";
	if(get_option('socialslider_target'))			$socialslider_target			= get_option('socialslider_target');
	if(get_option('socialslider_widget_height'))	$socialslider_widget_height		= get_option('socialslider_widget_height');
	if(get_option('socialslider_widget_width'))		$socialslider_widget_width		= get_option('socialslider_widget_width');
	}
// *******************************************************************************************************************************************************************

// *** Ustawienia wersji kolorystycznej ******************************************************************************************************************************
if($socialslider_kolor=="jasny")
	{
	$socialslider_bg_color		= "#fff";
	$socialslider_border_color	= "#ccc";
	$socialslider_a_color		= "#666";
	$socialslider_autor_color	= "#2275ad";
	}

if($socialslider_kolor=="ciemny")
	{
	$socialslider_bg_color		= "#222";
	$socialslider_border_color	= "#5b5b5b";
	$socialslider_a_color		= "#eee";
	$socialslider_autor_color	= "#ccc";
	}

if($socialslider_kolor=="css")
	{
	if(get_option('socialslider_custom_background'))	$socialslider_bg_color		= get_option('socialslider_custom_background');	else $socialslider_bg_color		= "#ffffff";
	if(get_option('socialslider_custom_border'))		$socialslider_border_color	= get_option('socialslider_custom_border');		else $socialslider_border_color	= "#cccccc";
	if(get_option('socialslider_custom_font'))			$socialslider_a_color		= get_option('socialslider_custom_font');		else $socialslider_a_color		= "#666666";
	if(get_option('socialslider_custom_font'))			$socialslider_autor_color	= get_option('socialslider_custom_font');		else $socialslider_autor_color	= "#666666";
	if(get_option('socialslider_custom_radius'))		$socialslider_custom_radius	= get_option('socialslider_custom_radius');		else $socialslider_custom_radius	= "6px";
	}
// *******************************************************************************************************************************************************************

if($socialslider_miejsce=="lewa" || empty($socialslider_miejsce))
	{
	$socialslider_handle				= "handle-lewy-".$socialslider_kolor;
	$socialslider_handle_lr				= "right";

	switch($socialslider_tryb)
		{
		case "pelny":
			$socialslider_width0		= 100+$socialslider_widget_width;
			$socialslider_width1		= 102+$socialslider_widget_width;
			$socialslider_width_js		= "left:'-".$socialslider_width1."'";
			$socialslider_width_0js		= "left:'0'";
			$socialslider_width_css		= "width: ".$socialslider_width0."px; left: -".$socialslider_width1."px; border-right: 1px solid ".$socialslider_border_color."; border-top: 1px solid ".$socialslider_border_color."; border-bottom: 1px solid ".$socialslider_border_color."; background: ".$socialslider_bg_color."; position: ".$socialslider_position.";";
			$socialslider_width_ikony	= "style=\"right: -33px;\"";
			break;

		case "uproszczony":
			$socialslider_width_js		= "left:'-86'";
			$socialslider_width_0js		= "left:'0'";
			$socialslider_width_css		= "width: 85px; left: -86px; border-right: 1px solid ".$socialslider_border_color."; border-top: 1px solid ".$socialslider_border_color."; border-bottom: 1px solid ".$socialslider_border_color."; background: ".$socialslider_bg_color."; position: ".$socialslider_position.";";
			$socialslider_width_ikony	= "style=\"right: -33px;\"";
			break;

		case "kompaktowy":
			$socialslider_width_js		= "left:'-86'";
			$socialslider_width_0js		= "left:'0'";
			$socialslider_width_css		= "width: 85px; left: -86px; border-right: 1px solid ".$socialslider_border_color."; border-top: 1px solid ".$socialslider_border_color."; border-bottom: 1px solid ".$socialslider_border_color."; background: ".$socialslider_bg_color."; position: ".$socialslider_position.";";
			$socialslider_width_ikony	= "style=\"right: -33px;\"";
			break;

		case "minimalny":
			$socialslider_width_css		= "width: 0px; left: -1px; border-right: 1px solid ".$socialslider_border_color."; border-top: 1px solid ".$socialslider_border_color."; border-bottom: 1px solid ".$socialslider_border_color."; background: ".$socialslider_bg_color."; position: ".$socialslider_position.";";
			$socialslider_width_ikony	= "style=\"right: -33px;\"";
			break;

		case "minimalny_duzy":
			$socialslider_width_css		= "width: 0px; left: -1px; border-right: 1px solid ".$socialslider_border_color."; border-top: 1px solid ".$socialslider_border_color."; border-bottom: 1px solid ".$socialslider_border_color."; background: ".$socialslider_bg_color."; position: ".$socialslider_position.";";
			$socialslider_width_ikony	= "style=\"right: -44px;\"";
			break;
		}
	}

if($socialslider_miejsce=="prawa")
	{
	$socialslider_handle 				= "handle-prawy-".$socialslider_kolor;
	$socialslider_handle_lr 			= "left";
	$socialslider_margin_right			= " margin-right: 0; margin-left: -1px;";

	switch($socialslider_tryb)
		{
		case "pelny":
			$socialslider_width			= 100+$socialslider_widget_width;
			$socialslider_width1		= 101+$socialslider_widget_width;
			$socialslider_width_js		= "right:'-".$socialslider_width1."'";
			$socialslider_width_0js		= "right:'0'";
			$socialslider_width_css		= "width: ".$socialslider_width."px; right: -".$socialslider_width1."px; border-left: 1px solid ".$socialslider_border_color."; border-top: 1px solid ".$socialslider_border_color."; border-bottom: 1px solid ".$socialslider_border_color."; background: ".$socialslider_bg_color."; position: ".$socialslider_position.";";
			$socialslider_width_ikony	= "style=\"right: ".$socialslider_width."px;\"";
			break;

		case "uproszczony":
			$socialslider_width_js		= "right:'-86'";
			$socialslider_width_0js		= "right:'0'";
			$socialslider_width_css		= "width: 85px; right: -86px; border-left: 1px solid ".$socialslider_border_color."; border-top: 1px solid ".$socialslider_border_color."; border-bottom: 1px solid ".$socialslider_border_color."; background: ".$socialslider_bg_color."; position: ".$socialslider_position.";";
			$socialslider_width_ikony	= "style=\"right: 85px;\"";
			break;

		case "kompaktowy":
			$socialslider_width_js		= "right:'-86'";
			$socialslider_width_0js		= "right:'0'";
			$socialslider_width_css		= "width: 85px; right: -86px; border-left: 1px solid ".$socialslider_border_color."; border-top: 1px solid ".$socialslider_border_color."; border-bottom: 1px solid ".$socialslider_border_color."; background: ".$socialslider_bg_color."; position: ".$socialslider_position.";";
			$socialslider_width_ikony	= "style=\"right: 85px;\"";
			break;

		case "minimalny":
			$socialslider_width_css		= "width: 0px; right: -1px; border-left: 1px solid ".$socialslider_border_color."; border-top: 1px solid ".$socialslider_border_color."; border-bottom: 1px solid ".$socialslider_border_color."; background: ".$socialslider_bg_color."; position: ".$socialslider_position.";";
			$socialslider_width_ikony	= "style=\"right: 0;\"";
			break;

		case "minimalny_duzy":
			$socialslider_width_css		= "width: 0px; right: -1px; border-left: 1px solid ".$socialslider_border_color."; border-top: 1px solid ".$socialslider_border_color."; border-bottom: 1px solid ".$socialslider_border_color."; background: ".$socialslider_bg_color."; position: ".$socialslider_position.";";
			$socialslider_width_ikony	= "style=\"right: 0;\"";
			break;
		}
	}

function katalog_ikon($ikona)
	{
	global $socialslider_baza, $socialslider;

	if($ikona[0]=="_")		return $socialslider_baza."/wp-content/".$socialslider;
	else					return WP_PLUGIN_URL ."/".$socialslider."/icons/".get_option('socialslider_ikony');
	}

if(WPLANG=="pl_PL")		{$li = "http://mydiy.pl";										$ti = "myDIY - zrób to sam! Blog dla majsterkowiczów.";}
else					{$li = "http://wordpress.org/extend/plugins/social-slider/";	$ti = "Social Slider";}

switch($socialslider_tryb)
	{
	case "pelny":
        if($socialslider_link=='tak')   $socialslider_alink = "<a href='".$li."' title='".$ti."' style='color: ".$socialslider_autor_color.";' target='_".$socialslider_target."'>Social Slider</a>";
        if($socialslider_link=='text')  $socialslider_alink = "<span>Social Slider</span>";
        break;

    case "uproszczony":
        if($socialslider_link=='tak')   $socialslider_alink = "<a href='".$li."' title='".$ti."' style='color: ".$socialslider_autor_color.";' target='_".$socialslider_target."'>Social Slider</a>";
        if($socialslider_link=='text')  $socialslider_alink = "<span>Social Slider</span>";
        break;

    case "kompaktowy":
        if($socialslider_link=='tak')   $socialslider_alink = "<a href='".$li."' title='".$ti."' style='color: ".$socialslider_autor_color.";' target='_".$socialslider_target."'>Social Slider</a>";
        if($socialslider_link=='text')  $socialslider_alink = "<span>Social Slider</span>";
        break;

    case "minimalny":
        if($socialslider_link=='tak')   $socialslider_alink = "<a href='".$li."' title='".$ti."' style='color: ".$socialslider_autor_color.";' target='_".$socialslider_target."'>Slider</a>";
        if($socialslider_link=='text')  $socialslider_alink = "<span>Slider</span>";
        break;

    case "minimalny_duzy":
        if($socialslider_link=='tak')   $socialslider_alink = "<a href='".$li."' title='".$ti."' style='color: ".$socialslider_autor_color.";' target='_".$socialslider_target."'>Social Slider</a>";
        if($socialslider_link=='text')  $socialslider_alink = "<span>Social Slider</span>";
        break;
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////						//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////		STYLE CSS		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////						//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

add_action('wp_head', 'headCSS');

function headCSS()
	{
	global $socialslider_szybkosc, $socialslider_miejsce, $socialslider_kolor, $socialslider_bg_color, $socialslider_handle_lr, $socialslider_custom_radius,
	$socialslider_border_color, $socialslider_margin_right, $socialslider_baza, $socialslider, $socialslider_handle, $socialslider_opacity;

	echo "<style type='text/css'>";
	if($socialslider_szybkosc=="nojs")
		{
		if($socialslider_miejsce=="lewa")	echo "#socialslider:hover {left: 0 !important;}";
		if($socialslider_miejsce=="prawa")	echo "#socialslider:hover {right: 0 !important;}";
		}

	if($socialslider_kolor=="css")
		{
		echo "#socialslider-ikony		{background: ".$socialslider_bg_color."; border-top-".$socialslider_handle_lr."-radius: ".$socialslider_custom_radius.";		-webkit-border-top-".$socialslider_handle_lr."-radius: ".$socialslider_custom_radius.";		-khtml-border-radius-top: ".$socialslider_custom_radius.";		-moz-border-radius-top".$socialslider_handle_lr.": ".$socialslider_custom_radius."; 	border-".$socialslider_handle_lr.": 1px solid ".$socialslider_border_color.";	border-top: 1px solid ".$socialslider_border_color.";}
		#socialslider-ikony ul			{background: ".$socialslider_bg_color."; border-bottom-".$socialslider_handle_lr."-radius: ".$socialslider_custom_radius.";	 	-webkit-border-bottom-".$socialslider_handle_lr."-radius: ".$socialslider_custom_radius.";	-khtml-border-radius-bottom: ".$socialslider_custom_radius.";	-moz-border-radius-bottom".$socialslider_handle_lr.": ".$socialslider_custom_radius."; 	border-".$socialslider_handle_lr.": 1px solid ".$socialslider_border_color.";	border-bottom: 1px solid ".$socialslider_border_color.";".$socialslider_margin_right."}";
		}
	else
		{
		echo "#socialslider-ikony		{background: transparent url('". WP_PLUGIN_URL ."/".$socialslider."/images/".$socialslider_handle.".png') no-repeat ".$socialslider_handle_lr." top; padding-top: 1px; padding-".$socialslider_handle_lr.": 1px;}
		#socialslider-ikony ul			{background: transparent url('". WP_PLUGIN_URL ."/".$socialslider."/images/".$socialslider_handle.".png') no-repeat ".$socialslider_handle_lr." bottom; padding-bottom: 1px; padding-".$socialslider_handle_lr.": 1px;".$socialslider_margin_right."}";
		}
	echo "</style>";

	if($socialslider_opacity=="9") echo '<!--[if !IE]><!--><link type="text/css" rel="stylesheet" href="'. WP_PLUGIN_URL .'/'.$socialslider.'/css/opacity-9.css" /><!--<![endif]-->';
	if($socialslider_opacity=="8") echo '<!--[if !IE]><!--><link type="text/css" rel="stylesheet" href="'. WP_PLUGIN_URL .'/'.$socialslider.'/css/opacity-8.css" /><!--<![endif]-->';
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////						//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////		  SLIDER		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////						//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function SocialSlider()
	{
	global $post, $socialslider, $serwisy, $socialslider_wersja, $socialslider_tryb, $socialslider_baza, $socialslider_szybkosc, $socialslider_miejsce,
	$socialslider_kolor, $socialslider_bg_color, $socialslider_handle_lr, $socialslider_custom_radius, $socialslider_border_color, $socialslider_margin_right,
	$socialslider_handle, $socialslider_opacity, $socialslider_nazwa, $socialslider_wersja, $socialslider_top, $socialslider_width_css, $socialslider_data,
	$socialslider_a_color, $socialslider_target, $nofollow, $socialslider_link, $socialslider_alink, $socialslider_widget, $socialslider_widget_width,
	$socialslider_widget_height, $socialslider_width_ikony, $socialslider_rozdzielczosc, $socialslider_width_0js, $socialslider_width_js, $socialslider_nocustom,
	$socialslider_sort, $wpdb, $table_prefix, $socialslider_promocja;

	if($socialslider_tryb!="minimalny" && $socialslider_tryb!="minimalny_duzy" && $socialslider_szybkosc!="nojs")
		{
		?>
		<script type="text/javascript">
				jQuery(document).ready(function () {var hideDelay=200;var hideDelayTimer=null;jQuery("#socialslider").hover(function(){if(hideDelayTimer)clearTimeout(hideDelayTimer);jQuery("#socialslider").animate({<?php echo $socialslider_width_0js; ?>},"<?php echo $socialslider_szybkosc; ?>");},function(){if(hideDelayTimer)clearTimeout(hideDelayTimer);hideDelayTimer=setTimeout(function(){hideDelayTimer=null;jQuery("#socialslider").animate({<?php echo $socialslider_width_js; ?>},"<?php echo $socialslider_szybkosc; ?>");},hideDelay);});});
		</script>
		<?php
		}
	?>

	<!-- <?php echo $socialslider_nazwa; ?> v.<?php echo $socialslider_wersja; ?> -->
	<!-- SS#<?php echo base64_encode(get_bloginfo('wpurl')."#".WPLANG."#".$socialslider_promocja."#".$socialslider); ?>## -->

	<div id="socialslider" style="top: <?php echo $socialslider_top; ?>; <?php echo $socialslider_width_css; ?>">
		<div id="socialslider-contener" class="socialslider-contener">

			<?php
			function adres($adres, $data)
				{
				global $post;

				if(date("Y-m-d")<=base64_decode($data))
					{
					if(is_home())
						{
						$ss_title	= rawurlencode(get_bloginfo('name'));
						$ss_perma	= rawurlencode(get_bloginfo('url'));
						}
					else
						{
						$ss_title	= rawurlencode(get_the_title($post->ID));
						$ss_perma	= rawurlencode(get_permalink($post->ID));
						}

					$adres = str_replace("[URL]", $ss_perma, $adres);
					$adres = str_replace("[TITLE]", $ss_title, $adres);
					}

				return $adres;
				}

			$serwisy = $wpdb->get_results("SELECT * FROM ".$table_prefix."socialslider WHERE adres NOT LIKE ''".$socialslider_nocustom." ORDER BY ".$socialslider_sort." ASC");

			if($socialslider_tryb!="minimalny" && $socialslider_tryb!="minimalny_duzy")
				{
				?>
				<div id="socialslider-linki" class="socialslider-grupa">
					<ul>
						<?php
						if($socialslider_tryb!="kompaktowy")
							{
							foreach ($serwisy as $serwis) {echo "<li><a href='".adres($serwis->adres, $socialslider_data)."' title='".$serwis->nazwa."' style='color: ".$socialslider_a_color.";' target='_".$socialslider_target."'".$nofollow."><img src='".katalog_ikon($serwis->ikona)."/".$serwis->ikona."-32.png' alt='".$serwis->nazwa."' />".$serwis->nazwa."</a></li>";}
							}
						else
							{
							foreach ($serwisy as $serwis) {echo "<li><a href='".adres($serwis->adres, $socialslider_data)."' title='".$serwis->nazwa."' style='color: ".$socialslider_a_color.";' target='_".$socialslider_target."'".$nofollow.">".$serwis->nazwa."</a></li>";}
							}

						if($socialslider_tryb!="minimalny" && $socialslider_tryb!="minimalny_duzy" && $socialslider_link!="nie")
							{
							echo "<li id='".base64_decode('c29jaWFsc2xpZGVyLWF1dG9y')."'>".$socialslider_alink."</li>";
							}
						?>
					</ul>
				</div>
				<?php
				}

			if($socialslider_tryb=="pelny" && !empty($socialslider_widget)) echo "<div id='socialslider-widget' class='socialslider-grupa' style='width: ".$socialslider_widget_width."; height: ".$socialslider_widget_height.";'>".stripslashes($socialslider_widget)."</div>";
			?>
			<div id="socialslider-ikony" <?php echo $socialslider_width_ikony; ?>>
				<ul>
				<?php
				if($socialslider_tryb=="minimalny")			$socialslider_minimalny_rozmiar = "20";
				if($socialslider_tryb=="minimalny_duzy")	$socialslider_minimalny_rozmiar = "32";

				if($socialslider_tryb=="minimalny" || $socialslider_tryb=="minimalny_duzy")
					{
					foreach ($serwisy as $serwis) {echo "<li><a href='".adres($serwis->adres, $socialslider_data)."' title='".$serwis->nazwa."' target='_".$socialslider_target."'".$nofollow."><img src='".katalog_ikon($serwis->ikona)."/".$serwis->ikona."-".$socialslider_minimalny_rozmiar.".png' alt='".$serwis->nazwa."' /></a></li>";}

					if($socialslider_link!="nie")
						{
						echo "<li id='".base64_decode('c29jaWFsc2xpZGVyLWF1dG9y')."'>".$socialslider_alink."</li>";
						}
					}
				else
					{
					foreach ($serwisy as $serwis) {echo "<li><img src='".katalog_ikon($serwis->ikona)."/".$serwis->ikona."-20.png' alt='".$serwis->nazwa."' /></li>";}
					}
				?>
				</ul>
			</div>
		</div>
	</div>

	<?php
	if($socialslider_rozdzielczosc>"0")
		{
		echo "<script type='text/javascript'>
				if(screen.width<".$socialslider_rozdzielczosc.")
					{
					var elss;
					elss = document.getElementById('socialslider').style.display='none';
					}
		</script>";
		}
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////						//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////		POZOSTAŁE		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////						//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function SocialSliderJS()
	{
	wp_enqueue_script('jquery');
	}

function SocialSliderNotice()
	{
	include("language.php");
	if(WPLANG=="pl_PL")		{$la = "pl_PL";}
	else					{$la = "en_US";}

	echo "<div class='error fade' style='background-color: #ff9999;'><p>".$lang[75][$la]."</p></div>";
	}

function SocialSliderCSS()
	{
	global $socialslider, $socialslider_tryb;

	wp_register_style("social-slider", WP_PLUGIN_URL ."/".$socialslider."/css/social-slider-".$socialslider_tryb.".css");
	wp_enqueue_style("social-slider");

	if($socialslider_tryb!="minimalny" && $socialslider_tryb!="minimalny_duzy" && $socialslider_szybkosc!="nojs")
		{
		add_action('wp_print_scripts', 'SocialSliderJS');
		}
	}

function SocialSliderMenu()
	{
	global $socialslider_data;

	if(date("Y-m-d")<=base64_decode($socialslider_data))	{$socialslider_name = "Social Slider Pro";}
	else													{$socialslider_name = "Social Slider";}

	add_options_page($socialslider_name, $socialslider_name, 7, __FILE__, 'SocialSliderUstawienia');
	}

function SocialSliderAdminHead()
	{
	global $socialslider, $socialslider_data;

	if($_GET['page']== $socialslider."/".$socialslider.".php" && date("Y-m-d")<=base64_decode($socialslider_data))
		{
		wp_enqueue_script('social-slider', WP_PLUGIN_URL .'/'.$socialslider.'/social-slider.js', array('jquery'));
		}
	}

add_action('admin_init', 'SocialSliderAdminHead');
add_action('admin_menu','SocialSliderMenu');

if(get_option('socialslider_mobile')=="nie" || !get_option('socialslider_mobile'))
	{
	$useragents = array(
		"iPhone",  			// Apple iPhone
		"iPod",				// Apple iPod touch
		"iPad",				// Apple iPad
		"Android", 			// 1.5+ Android
		"dream", 			// Pre 1.5 Android
		"CUPCAKE", 			// 1.5+ Android
		"blackberry9500",	// Storm
		"blackberry9530",	// Storm
		"blackberry9520",	// Storm v2
		"blackberry9550",	// Storm v2
		"blackberry9800",	// Torch
        "blackberry9900",	// Torch
		"webOS",			// Palm Pre Experimental
		"incognito", 		// Other iPhone browser
		"webmate", 			// Other iPhone browser
		"s8000", 			// Samsung Dolphin browser
		"bada", 		 	// Samsung Dolphin browser
		"mini",				// Opera Mini Experimental
		"Skyfire",			// Skyfire
		"Nokia",			// Nokia Phones
		);

	asort($useragents);

	$hua = $_SERVER['HTTP_USER_AGENT'];
	$mob = 0;

	foreach($useragents as $useragent)
		{if(eregi($useragent, $hua))	{$mob = 1;}}

	if($mob===0)
		{
		add_action('wp_print_styles', 'SocialSliderCSS');
		add_action('wp_footer', 'SocialSlider');
		}
	}

if(get_option('socialslider_mobile')=="tak")
	{
	add_action('wp_print_styles', 'SocialSliderCSS');
	add_action('wp_footer', 'SocialSlider');
	}

/*
if(!get_option('socialslider_position') && !$_POST['SocialSliderZapisz'])
	{
	add_action('admin_notices', 'SocialSliderNotice');
	}
*/
?>