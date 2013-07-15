<?php

	error_reporting(0);
	define('KOKEN_VERSION', '0.9.2');

	ini_set('default_charset', 'UTF-8');

	if (function_exists('mb_internal_encoding'))
	{
		mb_internal_encoding('UTF-8');
	}

	// If this isn't set, they have enabled URL rewriting for purty links and arrived here directly
	// (not through /index.php/this/that)
	if (!isset($rewrite))
	{
		$rewrite = true;
		$raw_url = $_GET['url'];
	}
	else
	{
		if (isset($_SERVER['QUERY_STRING']) && ( strpos($_SERVER['QUERY_STRING'], '/') === 0 || strpos($_SERVER['QUERY_STRING'], '%2F') === 0))
		{
			$raw_url = $_SERVER['QUERY_STRING'];
		}
		else
		{
			$raw_url = '/';
		}
	}

	$url_vars = array(
		'page' => 1
	);

	$to_replace = array();
	if (preg_match('~(page/(\d+)/)(&.*)?$~', $raw_url, $page_match))
	{
		$to_replace[] = $page_match[1];
		$url_vars['page'] = $page_match[2];
	}

	$preview = $pjax = false;

	if (isset($_SERVER['HTTP_X_PJAX']) || strpos($_SERVER['QUERY_STRING'], '_pjax=') !== false)
	{
		$pjax = true;
	}

	if (!isset($draft))
	{
		$draft = false;
	}
	else if ($draft && isset($_GET['preview']))
	{
		$preview = $_GET['preview'];
	}

	if ($draft)
	{
		$basename = 'preview.php';
	}
	else
	{
		$basename = 'index.php';
	}

	if ($rewrite)
	{
		$real_base_folder = preg_replace('~/app/site/site\.php(.*)?$~', '', $_SERVER['SCRIPT_NAME']);
		$base_path = isset($_GET['base_folder']) ? $_GET['base_folder'] : $real_base_folder;
		if ($base_path === '/')
		{
			$base_path = '';
		}
		$base_folder = $base_path;
	}
	else
	{
		$basename_regex = str_replace('.', '\\.', $basename);
		$base_folder = preg_replace("~/$basename_regex(.*)?$~", '', $_SERVER['SCRIPT_NAME']);
		$base_path = $base_folder . "/$basename?";

		if (!isset($real_base_folder))
		{
			$real_base_folder = $base_folder;
		}
	}

	if ($draft && !$preview && !isset($_COOKIE['koken_session']))
	{
		header("Location: $base_folder/admin/#/site");
		exit;
	}

	if ($rewrite)
	{
		$url = $raw_url;
	}
	else
	{
		$url = preg_replace('/([\?&].*$)/', '', urldecode($raw_url));
	}

	if (empty($url))
	{
		$url = $raw_url = '/';
	}

	if ($url[strlen($url)-1] !== '/' && strpos($url, '.') === false)
	{
		header("HTTP/1.1 301 Moved Permanently");
		// Rewrite non-trailing slash URLs to trailing slash for SEO purposes.
		if ($rewrite)
		{
			$canon = "{$base_folder}$url/";

			$gets = array();
			foreach($_GET as $key => $val)
			{
				if (!empty($val) && $key !== 'url')
				{
					$gets[] = $key . '=' . $val;
				}
			}

			if (!empty($gets))
			{
				$canon .= '?' . join('&', $gets);
			}
		}
		else
		{
			$canon = $_SERVER['PHP_SELF'] . "?$url/";

			foreach($_GET as $key => $val)
			{
				if (!empty($val))
				{
					$canon .= '&' . $key . '=' . $val;
				}
			}
		}
		header("Location: $canon");
		exit;
	}

	if ($rewrite && preg_match('~/__rewrite_test/?$~', $url))
	{
		die('koken:rewrite');
	}

	$ds = DIRECTORY_SEPARATOR;
	$root_path = dirname(dirname(dirname(__FILE__)));

	$is_ssl = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === 1 : $_SERVER['SERVER_PORT'] == 443;
	$protocol = $is_ssl ? 'https' : 'http';
	$original_url = $protocol . '://' . $_SERVER['HTTP_HOST'] . preg_replace('/\?.*$/', '', $_SERVER['SCRIPT_NAME']) . '?' . $url;

	if ($url === '/')
	{
		$cache_url = '/index';
	}
	else
	{
		$cache_url = $url;
	}

	if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']))
	{
		$parts = explode('&', $_SERVER['QUERY_STRING']);

		foreach($parts as $p)
		{
			if (strpos($p, '/') === 0) continue;

			if (strpos($p, '=') === false)
			{
				$url_vars[$p] = true;
			}
			else
			{
				list($key, $val) = explode('=', $p);
				$url_vars[$key] = urldecode($val);
			}
		}
	}

	$url = str_replace($to_replace, '', $url);

	if (empty($url))
	{
		$url = '/';
	}

	// Enable caching in case .htaccess missed it or isn't available
	if ((!$draft || $preview) && !isset($_GET['default_link']) && !isset($_COOKIE['share_to_tumblr']))
	{
		$cache_url = rtrim($cache_url, '/');

		$css = false;

		if ($cache_url === '/settings.css.lens')
		{
			$css = true;
			$cache_url = $base_path . $cache_url;
		}
		else if (!preg_match('/\.rss$/', $cache_url))
		{
			$cache_url = $base_path . preg_replace('/\?|&|=/', '_', preg_replace('/\?|&_pjax=[^&$]+/', '', urldecode(rtrim($cache_url, '/')))) . '/cache';
		}

		if ($preview)
		{
			$cache_url = '/__preview/' . $preview . $cache_url;
		}

		$cache_path = $root_path . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'site' . str_replace('/', DIRECTORY_SEPARATOR, $cache_url) . ( $css || preg_match('/\.rss$/', $cache_url) ? '' : ( $pjax ? '.phtml' : '.html' ) );

		if (file_exists($cache_path))
		{
			if ($css)
			{
				header('Content-type: text/css');
			}
			else if (preg_match('/\.rss$/', $cache_url))
			{
				header('Content-type: application/rss+xml; charset=UTF-8');
			}
			else
			{
				header('Content-type: text/html; charset=UTF-8');
			}

			$mtime = filemtime($cache_path);

			if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $mtime) {
				header("HTTP/1.1 304 Not Modified");
				exit;
			}

			header('Cache-control: must-revalidate');
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT');

			die( file_get_contents($cache_path) );
		}
	}

	require 'Koken.php';
	require $root_path . '/app/application/libraries/shutter.php';

	Koken::$protocol = $protocol;
	Koken::$original_url = $original_url;
	Koken::$root_path = $root_path;
	Koken::$draft = $draft;
	Koken::$preview = $preview;
	Koken::$rewrite = $rewrite;
	Koken::$pjax = $pjax;

	Koken::$location = array(
		'root' => $base_path,
		'root_folder' => $base_folder,
		'real_root_folder' => $real_base_folder,
		'here' => $url,
		'rewrite' => $rewrite,
		'parameters' => $url_vars,
		'host' => $protocol . '://' . $_SERVER['HTTP_HOST'],
		'hostname' => $_SERVER['HTTP_HOST'],
		'site_url' => $protocol . '://' . $_SERVER['HTTP_HOST'] . $base_folder
	);

	Koken::$rss_feeds = array(
		'contents' => "$base_path/feed/content/recent.rss",
		'essays' => "$base_path/feed/essays/recent.rss"
	);

	// Fallback path with default themes
	Koken::$fallback_path = $root_path . $ds . 'app' . $ds . 'site' . $ds . 'themes';

	if (isset($cache_path))
	{
		Koken::$cache_path = $cache_path;
	}

	$site_api = Koken::api( '/site' . ( $draft ? ( $preview ? '/preview:' . $preview : '/draft:true') : '' ) );
	if (!is_array($site_api))
	{
		die( file_get_contents(Koken::$fallback_path . $ds . 'error' . $ds . 'api.html') );
	}
	$plugins = Koken::api( '/plugins' );
	$categories = Koken::api('/categories/summary:false');

	if (isset($site_api['error']))
	{
		die( str_replace('<!-- ERROR -->', $site_api['error'], file_get_contents(Koken::$fallback_path . $ds . 'error' . $ds . 'json.html')) );
	}

	Koken::$site = $site_api;
	Koken::$profile = Koken::$site['profile'];
	Koken::$location['theme_path'] = $real_base_folder . '/storage/themes/' . $site_api['theme']['path'];

	Shutter::finalize_plugins($plugins['plugins']);

	foreach($categories['categories'] as $c)
	{
		Koken::$categories[strtolower($c['title'])] = $c['id'];
	}

	if (isset($_GET['default_link']))
	{
		$location = Koken::$site['default_links'][$_GET['default_link']];
		unset($_GET['default_link']);
		foreach($_GET as $key => $val)
		{
			$location = str_replace(":$key", $val, $location);
		}
		header("Location: {$base_folder}$location");
	}

	date_default_timezone_set(Koken::$site['timezone']);

	// Setup path to current theme
	Koken::$template_path = $root_path . $ds .'storage' . $ds . 'themes' . $ds . Koken::$site['theme']['path'];

	$nav = array();

	if (isset(Koken::$site['navigation']))
	{
		foreach(Koken::$site['navigation']['items'] as &$n)
		{
			if (isset($n['front']) && $n['front'])
			{
				Koken::$navigation_home_path = rtrim($n['path'], '/') . '/';
			}
		}

		if (isset(Koken::$site['navigation']['groups']))
		{
			$groups = array();
			foreach(Koken::$site['navigation']['groups'] as $g)
			{
				$key = $g['key'];
				$groups[$key] = array(
					'items' => $g['items'],
					'items_nested' => $g['items_nested']
				);
			}
			Koken::$site['navigation']['groups'] = $groups;
		}

	}

	$temp = array();

	if (isset(Koken::$site['settings_flat']))
	{
		foreach(Koken::$site['settings_flat'] as $key => $obj)
		{
			$val = isset($obj['type']) && $obj['type'] === 'boolean' && is_bool($obj['value']) ? (bool) $obj['value'] : $obj['value'];
			Koken::$settings[$key] = $val;
		}
	}

	if (isset(Koken::$site['pulse_flat']))
	{
		Koken::$site['pulse'] = array();

		foreach(Koken::$site['pulse_flat'] as $key => $obj)
		{
			if ($obj['type'] === 'boolean')
			{
				$val = (bool) $obj['value'] ? 'true' : 'false';
			}
			else if (is_numeric($obj['value']))
			{
				$val = $obj['value'];
			}
			else
			{
				$val = "'{$obj['value']}'";
			}
			Koken::$site['pulse'][$key] = $val;
		}
	}

	$page_types = array();

	foreach(Koken::$site['templates'] as $arr)
	{
		$page_types[$arr['path']] = $arr['info'];
	}

	$page_types['lightbox'] = array( 'source' => 'content' );

	if (file_exists(Koken::$template_path . $ds . 'error.lens'))
	{
		$routes = array('/error/:code/' => array( 'template' => 'error' ));
		$http_error = false;
	}
	else
	{
		$routes = array();
		$http_error = true;
	}

	$lightbox = false;
	$redirects = array();

	// Create routes array
	foreach(Koken::$site['routes'] as $arr)
	{
		if (strpos($arr['path'], '.') === false)
		{
			$arr['path'] = rtrim($arr['path'], '/') . '/';
		}

		$r = array(
			'template' => $arr['template'],
			'source' => isset($arr['source']) ? $arr['source'] : false,
			'filters' => isset($arr['filters']) ? $arr['filters'] : false,
			'vars' => isset($arr['variables']) ? $arr['variables'] : false
		);

		if (strpos($arr['template'], 'redirect:') === 0)
		{
			$redirects[$arr['path']] = $r;
		}
		else
		{
			$routes[$arr['path']] = $r;
		}
	}

	if (!isset($routes['/']))
	{
		$routes['/'] = array(
			'template' => 'index',
			'source' => false,
			'filters' => false,
			'vars' => false
		);
	}

	$routes += $redirects;

	Koken::$location['urls'] = Koken::$site['urls'];

	$routed_variables = array();

	if ($url === '/')
	{
		if (Koken::$navigation_home_path)
		{
			$url = Koken::$navigation_home_path;
		}
		else if (Koken::$site['default_front_page'])
		{
			$url = Koken::$site['urls'][Koken::$site['default_front_page']];
		}
	}

	$stylesheet = $source = false;

	if ($url === '/settings.css.lens')
	{
		$final_path = 'css/settings.css';
		$stylesheet = true;
		$variables_to_pass[] = array();
		$variables_to_pass[] = array();
	}
	else
	{
		// Loop through template defined routes and match URL
		foreach($routes as $route => $page)
		{
			// Find magic :name variables in the route
			preg_match_all('/(\:[a-z_-]+)/', $route, $variables);

			// We need to save the matched variables so we can reassign them after the match
			$match_variables = array();
			if (!empty($variables[0]))
			{
				foreach($variables[1] as $str)
				{
					// Save variable name for later
					$match_variables[] = str_replace(':', '', $str);
					// Replace magic :name variable with regular expression
					if ($str === ':year')
					{
						$pattern = '[0-9]{4}';
					}
					else if ($str === ':month')
					{
						$pattern = '[0-9]{1,2}';
					}
					else if ($str === ':id' || $str === ':content_id' || $str === ':album_id')
					{
						$pattern = '(?:(?:[0-9]+)|(?:[0-9a-z]{32}))';
					}
					else if ($str === ':code')
					{
						$pattern = '[0-9]{3}';
					}
					else
					{
						if (defined(PCRE_VERSION) && version_compare(PCRE_VERSION, '5.0.0') >= 0)
						{
							$pattern = '\d*[\-_\sa-z\p{L}][\-\s_a-z0-9\p{L}]*';
						}
						else
						{
							$pattern = '\d*[\-_\sa-z][\-\s_a-z0-9]*';
						}
					}
					$route = str_replace($str, "($pattern)", $route);
				}
			}

			if (preg_match('~^' . $route . '$~u', $url, $matches))
			{
				if (strpos($page['template'], 'redirect:') === 0)
				{
					$redirect = str_replace('redirect:', '', $page['template']);
				}
				else
				{
					$redirect = false;
				}

				if (isset($matches['lightbox']))
				{
					$final_path = 'lightbox';
				}
				else
				{
					$final_path = $page['template'];
				}

				$info = isset($page_types[$page['template']]) ? $page_types[$page['template']] : array();

				if (!empty($matches[1]))
				{
					foreach($match_variables as $index => $name)
					{
						// For some reason double urldecoding is necessary for rewritten URLs
						if (isset($matches[$index+1]) && !empty($matches[$index+1]))
						{
							$routed_variables[$name] = urldecode(urldecode($matches[$index+1]));
						}
					}
				}

				if ($redirect)
				{

					if (strpos($redirect, 'soft:') !== false)
					{
						$redirect_type = '302 Moved Temporarily';
						$redirect = str_replace('soft:', '', $redirect);
					}
					else
					{
						$redirect_type = '301 Moved Permanently';
					}

					$redirect_to = Koken::$site['urls'][$redirect];

					foreach($routed_variables as $key => $val)
					{
						$redirect_to = str_replace(':' . $key, $val, $redirect_to);
					}

					$redirect_to = str_replace('(?:', '', $redirect_to);
					$redirect_to = str_replace(')?', '', $redirect_to);
					$redirect_to = str_replace('/:month', '', $redirect_to);

					if (strpos($redirect_to, ':') !== false)
					{
						if (isset($routed_variables['id']))
						{
							$id = $routed_variables['id'];
						}
						else
						{
							$id = 'slug:' . $routed_variables['slug'];
						}

						switch ($redirect) {
							case 'album':
								$url = '/albums';
								break;

							case 'essay':
							case 'page':
								$url = '/text';
								break;

							default:
								$url = '/content';
								break;
						}
						$url .= "/$id";
						$data = Koken::api($url);
						$redirect_to = $data['__koken_url'];
					}

					if (isset($routed_variables['album_id']) || isset($routed_variables['album_slug']))
					{
						$data = Koken::api('/content/' . ( isset($routed_variables['id']) ? $routed_variables['id'] : 'slug:' . $routed_variables['slug'] ) . '/context:' . ( isset($routed_variables['album_slug']) ? $routed_variables['album_slug'] : $routed_variables['album_id'] ));
						$redirect_to = $data['__koken_url'];
					}

					if (isset($matches['lightbox']))
					{
						$redirect_to .= '/lightbox';
					}

					$redirect_to = Koken::$location['root'] . $redirect_to . ( Koken::$preview ? '&amp;preview=' . Koken::$preview : '' );

					header("HTTP/1.1 $redirect_type");
					header("Location: $redirect_to");
					exit;
				}

				if (isset($routed_variables['content_slug']) || isset($routed_variables['content_id']))
				{
					if (isset($routed_variables['id']))
					{
						$routed_variables['album_id'] = $routed_variables['id'];
						unset($routed_variables['id']);
					}
					else
					{
						$routed_variables['album_slug'] = $routed_variables['slug'];
						unset($routed_variables['slug']);
					}

					if (isset($routed_variables['content_id']))
					{
						$routed_variables['id'] = $routed_variables['content_id'];
						unset($routed_variables['content_id']);
					}
					else
					{
						$routed_variables['slug'] = $routed_variables['content_slug'];
						unset($routed_variables['content_slug']);
					}

					if ($final_path !== 'lightbox')
					{
						$final_path = 'content';
					}
					$page['source'] = 'content';
					$page['filters'] = array();
				}
				else
				{
					if ($final_path === 'lightbox' && isset($info['source']) && $info['source'] === 'album')
					{
						$final_path = 'lightbox_album';
					}
				}

				if (isset($matches['template']))
				{
					foreach(Koken::$site['url_data'] as $key => $data)
					{
						if (isset($data['plural']) && $matches['template'] === strtolower($data['plural']))
						{
							$type = $key . 's';
							$final_path .= '.' . $type;
							$page['filters'] = array( "members=$type" );
							break;
						}
					}
				}

				$load = $source = isset($page['source']) && $page['source'] ? $page['source'] : ( isset($info['source']) ? $info['source'] : false );
				$filters = isset($page['filters']) && is_array($page['filters']) ? $page['filters'] : ( isset($info['filters']) ? $info['filters'] : false );

				if ($load)
				{
					if ($filters)
					{
						foreach($filters as &$f)
						{
							if (strpos($f, ':') !== false)
							{
								$f = preg_replace_callback("/:([a-z_]+)/",
										create_function(
											'$matches',
											'return Koken::$routed_variables[$matches[1]];'
										), $f);
							}
						}
					}
					Koken::$source = array( 'type' => $load, 'filters' => $filters );
				}

				Koken::$page_class = $page['template'] === 'index' ? 'k-source-index' : ( Koken::$source ? 'k-source-' . Koken::$source['type'] : '' );

				if (in_array(Koken::$page_class, array('k-source-tag', 'k-source-category', 'k-source-archive')))
				{
					Koken::$page_class = 'k-source-archive-' . str_replace('members=', '', Koken::$source['filters'][0]);
				}

				break;
			}
		}
	}

	if (isset($_COOKIE['share_to_tumblr']) && Koken::$source['type'] === 'content')
	{
		setcookie('share_to_tumblr', "", time() - 3600, '/');
		$final_path = 'content_tumblr_share';
	}

	if (!isset($final_path))
	{
		$default_path = trim($url, '/');
		$test = Koken::get_path("$default_path.lens");
		if ($test)
		{
			$final_path = $default_path;
			foreach(Koken::$site['templates'] as $template)
			{
				if ($template['path'] === $default_path && isset($template['info']['source']))
				{
					Koken::$source = array( 'type' => $template['info']['source'], 'filters' => false );
					Koken::$page_class = 'k-source-' . $template['info']['source'];
					break;
				}

			}
		}

	}

	if (isset($final_path))
	{
		Koken::$rss = preg_match('/\.rss$/', $final_path);

		$final_path .= '.lens';

		if ($final_path === 'error.lens') {
			$httpErrorCodes = array();
			$httpErrorCodes['400'] = 'Bad Request';
			$httpErrorCodes['401'] = 'Unauthorized';
			$httpErrorCodes['403'] = 'Forbidden';
			$httpErrorCodes['404'] = 'Not Found';
			$httpErrorCodes['405'] = 'Method Not Allowed';
			$httpErrorCodes['406'] = 'Not Acceptable';
			$httpErrorCodes['407'] = 'Proxy Authentication Required';
			$httpErrorCodes['408'] = 'Request Timeout';
			$httpErrorCodes['409'] = 'Conflict';
			$httpErrorCodes['410'] = 'Gone';
			$httpErrorCodes['411'] = 'Length Required';
			$httpErrorCodes['412'] = 'Precondition Failed';
			$httpErrorCodes['413'] = 'Request Entity Too Large';
			$httpErrorCodes['414'] = 'Request-url Too Long';
			$httpErrorCodes['415'] = 'Unsupported Media Type';
			$httpErrorCodes['416'] = 'Requested Range not satisfiable';
			$httpErrorCodes['417'] = 'Expectation Failed';
			$httpErrorCodes['500'] = 'Internal Server Error';
			$httpErrorCodes['501'] = 'Not Implemented';
			$httpErrorCodes['502'] = 'Bad Gateway';
			$httpErrorCodes['503'] = 'Service Unavailable';
			$httpErrorCodes['504'] = 'Gateway Timeout';
			$httpErrorCodes['505'] = 'HTTP Version Not Supported';
			header('HTTP/1.0 ' . $routed_variables['code'] . ' ' . $httpErrorCodes[$routed_variables['code']]);
		}

		$full_path = Koken::get_path($final_path);

		$tmpl = preg_replace( '#<\?.*?(\?>|$)#s', '', file_get_contents($full_path) );

		Koken::$routed_variables = $routed_variables;

		if ($stylesheet)
		{
			function go($tmpl)
			{
				Koken::$settings['style'] =& Koken::$settings['__style'];

				function url($matches)
				{
					$path = $matches[1];

					if (strpos($path, 'http') === 0 || strpos($path, 'data:') === 0)
					{
						return "url($path);";
					}
					else
					{
						if (strpos($path, '"') === 0)
						{
							$wrap = '"';
						}
						else if (strpos($path, "'") === 0)
						{
							$wrap = "'";
						}
						else
						{
							$wrap = '';
						}
						$path = preg_replace('~^../~', '', trim($path, "'\""));
						return 'url(' . $wrap . Koken::$location['real_root_folder'] . '/storage/themes/' . Koken::$site['theme']['path'] . "/$path{$wrap})";
					}
				}

				$raw = preg_replace('/\[?\$([a-z_0-9]+)\]?/', '<?php echo Koken::$settings[\'${1}\']; ?>', $tmpl);
				$raw = preg_replace_callback('/url\(([^\)]+)\)/', 'url', $raw);

				$contents = Koken::render($raw);

				function to_rgb($matches)
				{
					$color = $matches[1];

					if (strlen($color) === 3)
					{
						$color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
					}

					list($r, $g, $b) = array(
											hexdec($color[0].$color[1]),
											hexdec($color[2].$color[3]),
											hexdec($color[4].$color[5])
										);

					return "$r, $g, $b";
				}

				$contents = preg_replace_callback('/to_rgb\(#([0-9a-zA-Z]{3,6})\)/', 'to_rgb', $contents);

				if (!empty(Koken::$site['custom_css']) && !Koken::$draft)
				{
					$contents .= "\n\n" . Koken::$site['custom_css'];
				}

				Koken::cache($contents);

				header('Content-type: text/css');
				echo $contents;
			}
		}
		else
		{
			// For autoloading tagName classes as needed
			function __autoload($class_name)
			{
				include "tags/$class_name.php";
			}

			function parse_replacements($matches)
			{
				return Koken::$settings[$matches[2]];
			}

			function parse_include($matches)
			{
				$path = preg_replace_callback('/\{\{\s*(site\.)?settings\.([^\}\s]+)\s*\}\}/', 'parse_replacements', $matches[1]);
				$path = Koken::get_path($path);
				if ($path)
				{
					return file_get_contents($path);
				}
				return '';
			}

			function parse_asset($matches)
			{
				$id = '';
				$passthrough = array();

				if ($matches[1] === 'settings')
				{
					$path = Koken::$location['root_folder'] . '/' . (Koken::$draft ? 'preview.php?/' : (Koken::$rewrite ? '' : 'index.php?/')) . 'settings.css.lens' . (Koken::$preview ? '&preview=' . Koken::$preview : '');
					$info = array( 'extension' => 'css' );
					$id = ' id="koken_settings_css_link"';
				}
				else
				{
					preg_match_all('/([a-z_]+)="([^"]+)"/', $matches[1], $params);

					foreach($params[1] as $i => $name)
					{
						$value = $params[2][$i];

						if ($name === 'file')
						{
							$file = $value;
						}
						else if ($name === 'common')
						{
							$common = $value;
						}
						else
						{
							$passthrough[] = "$name=\"$value\"";
						}
					}

					$info = pathinfo($file);

					if (strpos($file, 'http') === 0)
					{
						$path = $file;
					}
					else
					{
						$path = Koken::$location['real_root_folder'];

						if (isset($common) && $common)
						{
							$path .= '/app/site/themes/common/' . $info['extension'] . '/' . $file . '?' . KOKEN_VERSION;
						}
						else
						{
							$path .= '/storage/themes/' . Koken::$site['theme']['path'] . '/' . $file . '?' . Koken::$site['theme']['version'];
						}
					}
				}

				if (count($passthrough))
				{
					$parameters = ' ' . join(' ', $passthrough);
				}
				else
				{
					$parameters = '';
				}

				if ($info['extension'] == 'css' || $info['extension'] == 'less')
				{
					return "<link$id rel=\"stylesheet\" type=\"text/{$info['extension']}\" href=\"$path\"$parameters />";
				}
				else if ($info['extension'] == 'js')
				{
					return "<script src=\"$path\"$parameters></script>";
				}
				else if (in_array($info['extension'], array('jpeg', 'jpg', 'gif', 'png')))
				{
					return "<img src=\"$path\"$parameters />";
				}
				else if ($info['extension'] === 'svg')
				{
					return "<embed src=\"$path\" type=\"image/svg+xml\"$parameters />";
				}
			}

			while (strpos($tmpl, '<koken:include') !== false)
			{
				$tmpl = preg_replace_callback('/<koken\:include\sfile="([^"]+?)" \/>/', 'parse_include', $tmpl);
			}

			$tmpl = preg_replace_callback('/<koken\:asset\s?(.+?)\s?\/>/', 'parse_asset', $tmpl);
			$tmpl = preg_replace_callback('/<koken\:(settings)\s?\/>/', 'parse_asset', $tmpl);

			// Wrap this to control context, variable availability
			function go($tmpl, $pass = 1)
			{

				$raw = Koken::parse($tmpl);

				// Fix PHP whitespace issues in koken:loops
				$raw = preg_replace('/\s+<\?php\s+endforeach/', '<?php endforeach', $raw);
				$raw = preg_replace('/<a(.*)>\s+<\?php/', '<a$1><?php', $raw);
				$raw = preg_replace('/\?>\s+<\/a>/', '?></a>', $raw);

				if ($pass === 1)
				{
					// Filters
					$raw = str_replace('<head>', '<head><?php Shutter::hook(\'after_opening_head\'); ?>', $raw);
					$raw = str_replace('</head>', '<?php Shutter::hook(\'before_closing_head\'); ?></head>', $raw);
					$raw = str_replace('<body>', '<body><?php Shutter::hook(\'after_opening_body\'); ?>', $raw);
					$raw = str_replace('</body>', '<?php Shutter::hook(\'before_closing_body\'); ?></body>', $raw);

					// die($raw);
					Koken::$location['page_class'] = Koken::$page_class;
					$location_json = json_encode(Koken::$location);

					if (Koken::$pjax)
					{
						$js = "<script>\$K.location = $location_json;$(window).trigger('k-pjax-end');</script>";
					}
					else
					{
						$hdpi = Koken::$site['hidpi'] ? 'true' : 'false';
						$pulse_obj = array();
						$location = Koken::$location;
						$site = Koken::$site;
						$pulse_srcs = array("/app/site/themes/common/js/pulse.js");
						foreach(Shutter::$active_pulse_plugins as $arr)
						{
							$pulse_obj[] = "'{$arr['key']}': '{$arr['path']}'";
							$pulse_srcs[] = $arr['path'];
						}
						$pulse_str = '';
						if (!empty($pulse_obj))
						{
							$pulse_obj = join(', ', $pulse_obj);
							$pulse_str .= "<script>\$K.pulse.plugins = { $pulse_obj };</script>\n";
						}
						$pulse_str .= '<script src="' . Koken::$location['real_root_folder'] . join('?' . KOKEN_VERSION . '"></script><script src="' . Koken::$location['real_root_folder'], $pulse_srcs) . '?' . KOKEN_VERSION . '"></script>';
						$stamp = '?' . KOKEN_VERSION;
						$generator = 'Koken ' . KOKEN_VERSION;
						$theme = Koken::$site['theme']['name'] . ' ' . Koken::$site['theme']['version'];

						$image_defaults = array();
						foreach(array('tiny', 'small', 'medium', 'medium_large', 'large', 'xlarge', 'huge') as $preset)
						{
							$image_defaults[$preset] = array(
								'quality' => Koken::$site["image_{$preset}_quality"],
								'sharpening' => Koken::$site["image_{$preset}_sharpening"]
							);
						}
						$image_defaults = json_encode($image_defaults);

						$js = <<<JS
<link href="{$location['real_root_folder']}/app/site/themes/common/css/mediaelement/mediaelementplayer.min.css{$stamp}" rel="stylesheet">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="{$location['real_root_folder']}/app/site/themes/common/js/jquery.min.js"><\/script>')</script>
<script src="{$location['real_root_folder']}/app/site/themes/common/js/koken.js{$stamp}"></script>
<script>\$K.location = $location_json;\$K.imageDefaults = $image_defaults;\$K.theme = '{$site['theme']['path']}';\$K.retinaEnabled = $hdpi;</script>
<script src="{$location['real_root_folder']}/app/site/themes/common/js/mediaelement-and-player.min.js{$stamp}"></script>
<link rel="alternate" type="application/atom+xml" title="{$site['title']}: All uploads" href="{$location['root']}/feed/content/recent.rss" />
<link rel="alternate" type="application/atom+xml" title="{$site['title']}: Essays" href="{$location['root']}/feed/essays/recent.rss" />
<meta name="generator" content="$generator" />
<meta name="theme" content="$theme" />
$pulse_str
JS;
					}

					if (Koken::$draft && !Koken::$preview)
					{
						$original_url = Koken::$original_url;
						$js .= <<<JS
<script>
if (parent && parent.\$) {
	parent.\$(parent.document).trigger('previewready', '$original_url');
	$(function() { parent.\$(parent.document).trigger('previewdomready'); });

	$(window).on('pjax:end', function() {
		parent.\$(parent.document).trigger('previewready', location.href);
		parent.\$(parent.document).trigger('previewdomready');
	});
}
if (parent && parent.__koken__) {
	\$(window).on('keydown', function(e) { parent.__koken__.shortcuts(e); });
	\$(function() { parent.__koken__.panel(); });
}
</script>

<style type="text/css">
i.k-control-structure { font-style: normal !important; }

	div[data-pulse-group] div.cover {
		width: 100%;
		height: 100%;
		z-index: 1000;
		border: 5px solid transparent;
		box-sizing: border-box;
		position: absolute;
		box-shadow: 0 0 20px rgba(0,0,0,0.6);
		display: none;
		pointer-events:none;
		top: 0;
		left: 0;
	}

	div[data-pulse-group]:hover div.cover, div[data-pulse-group] div.cover.active {
		display: block !important;
	}

	div[data-pulse-group] div.cover.active {
		border-color: #ff6e00 !important;
	}

	div[data-pulse-group] div.cover div {
		pointer-events:auto;
		width: 32px;
		height: 32px;
		background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAADrUlEQVR42sWXz0pbQRjFvflHmlz8k6iJRk2j1YLoRsRtTYJ20Qcp0i7EirjxAVyXvoD4Dyn4CAUrRegjdNdFF10UfILp97sc5Mq9g6kReuGQycz5zpk7883cmYF/eAJD1lAwPDOUDaFQVl0Bjrj9PxLKGPIyqRgmDE3DnGEBqNxUW0XcvGKDfszzesNxw6yhazg0fDH8MPwClFV3CAeuYkJpBI9566LepmXYMlxls1kXhqEbHR119XrdTU5OAsrURW1w4CqmJY2iZzS881wy1A1tw2Uul4sMZmZmegJcYoiVRh3NXvIjI+Kk4a3h6/DwMKKPArFooCXNsjrhffuiersVBIGrVqtuenq6L6CBFppoyyPwJRzz9crwjWGUSN9AC01NRyWRmBr6UEnzeXBw0E1NTT0p0ERbHqE8756Cls0WydNoNB4UbLVa7uzsDFB+kI8m2njIqxAf/rLW7hVz5hPZ3Nx0y8vLUbnT6biLi4sI3W6XOtrgeOPRxkNeZXkPZDUvr1nD9DQNGxsb7vz83J2cnLiDgwN+MQfxOjhwvTp44CXPbDT8WiIf2Uh8gYuLi+74+FimXsCB69XBAy88I299RJ4briuVCjtbKmq1mtvf379ntLu7C+IdgwPXq4MHXnhG3pqLecPP8fHx1KBmsxnN8+npKSbR7/z8PG8DKMfb4BKTqoUHXnhG3loSLw1/6PnExEQC8fkG29vbbmxs7K6d8s7OTpxDTKoWHuZ1K88w3oFbTwcSc7+3t5fgWF0iF3rqQHwK9JVLoFwuu7W1NYb3TnxlZeWunbI6CQcuMalaeMSn4F4S8vFICaLXJA8JFh9i/gPK8SSES0yqFh7xJLy3DEulEoGpYGkdHR3JyA84S0tLXh1Gxrw+ybMQ34jesEn4AtvtNpsMQ81bxvPiXh2c9fV1rw4eeBmqeCe24qGhIZZKAiMjI8xttJQYKeYdc7C6ukodbXDgpmqgreGfk2eQ+BjRQ8i+TpBElAEJB/SfNq850Nu/k1fB9zm+ZJ4U5AVrP5/PA8oP8tFEWyMd4uk7kHQMNwwXwk8BtNBEO3Eg8RzJ3nOMeopOcBBBC83kkSz5ZJUcDQXckFzM7WNArI5iW2h6DqWJUchBVG87OpbzJj0bwyWGWGnUpZnr/W6gi4mS5oPheyaTccViEQN2NHY8QJm6qA0OXMXMSqMozeCxV7Maazd2NbvWdew3UPlabV1xa/GrWb+X00LK5fSFYQFQTrmcFnq5jv336/lfIxqvKevtZooAAAAASUVORK5CYII=);
		position: absolute;
		top: 0;
		right: 0;
		margin-top: -16px;
		margin-right: -16px;
		cursor: pointer;
		z-index: 1001
	}

	div[data-pulse-group] div.cover div:hover {
		background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAD10lEQVR42sWXS0tbURSFm9wYTby+ouZRkT5sE1Fw6EylDVKhP6SEdmQnjhWRIjqwdCoIToSAiiKCYjWNQe1P6KyDDjoo+AtO93fZNwRyrqYx0AuLbM5ee62c173nPPqHJyRwBFFBTNApcIHGMc05ym3Jg1BY0KYmCUFG8EQwIsgCYtrIwYGrNWE0HmLepj1MCp4L8oJPgq+CH4JfgJg2cnDgao2rGqFmet2hvXkmKAhK4XDYxONx09vbawYGBszg4CAgps3LwYFLjdYm0LKPRvA8xwVpwSvBbiQSMX19fSaTyTQEuNRQqxppNBtZH2ElPha8E3zr6upCtClQiwZaaOracO7qfYf+20IoFPKGNZ1OPwhooIUm2uoRClpwzNeMoKLmLQFaaOp0JGwLk6F3ddEUXdelsKVAE231cNWz+kR12xQcxzGpVOpeweHhYbO5uQmI7+WjiTYe6hWtHf5O3bulnp4eyFZMT0+bbDbrxVNTU+bo6MjDzMwMbeTgBNajjQdeePrT4Oi8vNHeW4HJ9va22draMgsLC6ZYLJpyuQyIaSMHB26gDh54qafjDz9b5DMvkqDC8fFxc3BwYM7OzszJyYkplUrm5uYGENNGDg7cQB088MLTn4aY4KmgzBAlk8lArK6uVk3p+fr6OiD22+HcqYEHXnh63joXLwU/E4mEtWhoaMib2+PjY8/k8vLSTE5O+nli2sjBgUuNVQsPvPD0vHVL5AR/eKfbiljp+/v71WFfXl6u46ysrPjTAZcaqxYe4nWrnm7tH7jt7+/n41KHvb296jBfX1+bxcXFOs7S0hI5f3qosWrhUfsH6qbAVsRwzs3NmUqlgom30CYmJvw8MW3k4MClxqZlnYLqIuzu7oYUiLW1Na+HFxcXZmdnx8zPzwNi2sjBuVNDPOoWYXUbxmIx5siKXC5nDg8P/WnAkAUHiKvDD2d0dDRQBw/x+uJvw9oX0VteEkGFvFxOT0+9+d3Y2OAXU1DbBgduoA4eeAn68a57FfMNDyqenZ01Y2NjXpzP5+kxoJ02cn5sQ+3wj6hnyPYxYqXeCw4c5+fngLihGu39e/WKBn2Od3ldUtBKoIm2jrSLZ9CB5LXgiqlolTlaaKJtOZBYj2QfOEZR2ApztNCsO5JZHkcXx5AWXLFteHk0A2r1KFZA03YotY1CRIlpHbJdFg89adQYLjXUqkZaNSON3w30YqKL5qPgO5eO9vZ2zndsKQ6bgJg2LwcHLjVam1AtB+1mr2Yp9m7N1ays17HfQOMyOTjKTVmuZk1fTqOWy+kLQRYQWy6n0UauY//9ev4Xd3y51rHE8WMAAAAASUVORK5CYII=);
	}
</style>
JS;
					}
				}

				$contents = Koken::render($raw);

				if ($pass === 1)
				{
					// Rerun parse to catch shortcode renders
					while(strpos($contents, '<koken:') !== false && $pass < 3)
					{
						$pass++;
						$contents = go($contents, $pass);
					}
				}
				else
				{
					return $contents;
				}

				if ((strpos($contents, 'settings.css.lens"') === false && !empty(Koken::$site['custom_css'])) || Koken::$draft)
				{
					$js .= '<style id="koken_custom_css">' . Koken::$site['custom_css'] . '</style>';
				}

				preg_match_all('/<\!\-\- KOKEN HEAD BEGIN \-\->(.*)<!\-\- KOKEN HEAD END \-\->/msU', $contents, $headers);
				$contents = preg_replace('/<\!\-\- KOKEN HEAD BEGIN \-\->(.*)<!\-\- KOKEN HEAD END \-\->/msU', '', $contents);

				$header_str = '';

				foreach($headers[1] as $header)
				{
					$header_str .= "\t" . $header . "\n";
				}

				if (strpos($header_str, '<title>') !== false)
				{
					$contents = preg_replace('/<title>.*<\/title>/msU', '', $contents);
					$header_str = preg_replace('/<koken_title>.*<\/koken_title>/', '', $header_str);
				}
				else if (strpos($header_str, '<koken_title>') !== false && strpos($contents, '<koken_title') !== false)
				{
					$contents = preg_replace('/<title>.*<\/title>/msU', '', $contents);
					$header_str = str_replace('koken_title', 'title', $header_str);
				}
				else if (strpos($contents, '<koken_title') !== false)
				{
					$contents = str_replace('koken_title', 'title', $contents);
				}

				if (Koken::$pjax && strpos($header_str, '<title>'))
				{
					preg_match('~<title>.*</title>~', $header_str, $title_match);
					$contents .= $title_match[0];
				}

				$contents = preg_replace('/<koken_title>.*<\/koken_title>/msU', '', $contents);

				$header_str .= "\n<!--[if IE]>\n<script src=\"" . Koken::$location['real_root_folder'] . "/app/site/themes/common/js/html5shiv.js\"></script>\n<![endif]-->\n";

				if (strpos($contents, '<head>'))
				{
					preg_match('/<head>(.*)?<\/head>/msU', $contents, $header);
					if (count($header))
					{
						$head = isset($header[1]) ? $header[1] : '';
						preg_match_all('/<script.*<\/script>/msU', $head, $head_js);
						$head = preg_replace('/\s*<script.*<\/script>\s*/msU', '', $head) . "\n$header_str\n$js\n" . join("\n", $head_js[0]);
						$contents = preg_replace('/<head>(.*)?<\/head>/msU', "<head>\n$head\n</head>", $contents);
					}

				}
				else if (strpos($contents, '</body>'))
				{
					$contents = str_replace('</body>', "$js\n$header_str\n</body>", $contents);
				}
				else if (Koken::$pjax)
				{
					$contents .= $js;
				}


				if (preg_match_all('/<body(?:[^>]+)?>/', $contents, $match) && Koken::$page_class)
				{
					foreach($match[0] as $body)
					{
						if (strpos($body, 'class="') !== false)
						{
							$new_body = preg_replace('/class="([^"]+)"/', "class=\"$1 " . Koken::$page_class . "\"", $body);
						}
						else
						{
							$new_body = str_replace('>', ' class="' . Koken::$page_class . '">', $body);
						}
						$contents = str_replace($body, $new_body, $contents);
					}
				}

				$contents = preg_replace('/\t+/', "\t", $contents);

				if (Koken::$rss)
				{
					$contents = '<?xml version="1.0" encoding="utf-8"?>' . "\n$contents";
				}
				else
				{
					$contents = Shutter::filter('site.output', $contents);
				}

				Koken::cache($contents);

				if (Koken::$rss)
				{
					header('Content-type: text/xml; charset=UTF-8');
				}
				else
				{
					header('Content-type: text/html; charset=UTF-8');
				}

				die($contents);

			}
		}

		go($tmpl);
	} else {
		if ($http_error)
		{
			header('HTTP/1.0 404 Not Found');
		}
		else
		{
			header("Location: $base_path/error/404/");
		}
	}
