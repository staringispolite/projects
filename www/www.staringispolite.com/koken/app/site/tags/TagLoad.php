<?php

	class TagLoad extends Tag {

		public $tokenize = true;
		protected $allows_close = true;
		public $source = false;

		function generate()
		{

			$params = array();
			foreach($this->parameters as $key => $val)
			{
				if ($key === 'tree') continue;
				$params[] = "'$key' => \"" . $this->attr_parse($val) . '"';
			}

			$params = join(',', $params);

			if (!Koken::$main_load_token && !isset($this->parameters['source']) && isset($this->parameters['infinite']))
			{
				$infinite = $this->attr_parse($this->parameters['infinite']);
				Koken::$main_load_token = Koken::$tokens[0];
				if (isset($this->parameters['infinite_toggle']))
				{
					$infinite_selector = $this->attr_parse($this->parameters['infinite_toggle']);
					unset($this->parameters['infinite_toggle']);
				}
				else
				{
					$infinite_selector = '';
				}
				unset($this->parameters['infinite']);
			}
			else
			{
				$infinite = 'false';
				$infinite_selector = '';
			}

			$main = '$value' . Koken::$tokens[0];
			$curl = '$curl' . Koken::$tokens[0];
			$page = '$page' . Koken::$tokens[0];
			$options = '$options' . Koken::$tokens[0];
			$collection_name = '$collection' . Koken::$tokens[0];
			$paginate = '$paginate' . Koken::$tokens[0];
			$custom_source_var = '$source' . Koken::$tokens[0];
			$custom_source = $custom_source_var . ' = ' . (isset($this->parameters['source']) ? 'true' : 'false');
			$load_url = '$url' . Koken::$tokens[0];
			$load_url_var = '\$url' . Koken::$tokens[0];
			$top_token = Koken::$tokens[0];

			return <<<DOC
<?php

	list($load_url, $options, $collection_name, $paginate) = Koken::load( array($params) );
	$custom_source;

	if ($paginate)
	{
		if (isset(Koken::\$location['parameters']['page']))
		{
			$load_url .= '/page:' . Koken::\$location['parameters']['page'];
		}
	}

	if ({$options}['list'] && isset(Koken::\$routed_variables['tags']))
	{
		$load_url .= '/tags:' . Koken::\$routed_variables['tags'];
	}

	$main = Koken::api($load_url);

	if ($custom_source_var && isset({$main}['error']))
	{
		header("Location: " . Koken::\$location['root_folder'] . "/error/{{$main}['http']}/");
	}

	if (isset({$main}['page']) && {$options}['list'])
	{
		$page = array(
			'page' => {$main}['page'],
			'pages' => {$main}['pages'],
			'per_page' => {$main}['per_page'],
			'total' => {$main}['total'],
		);

		if ($infinite)
		{
			Koken::\$location['__infinite_token'] = '$top_token';
?>
			<script>
				\$K.infinity.totalPages = <?php echo {$page}['pages']; ?>;
				\$K.infinity.selector = '$infinite_selector';
			</script>
<?php
		}

		if (isset({$main}['content']))
		{
			{$main}['__loop__'] = {$main}['content'];
		}
		else if (isset({$main}['albums']))
		{
			{$main}['__loop__'] = {$main}['albums'];
		}
		else if (isset({$main}['text']))
		{
			{$main}['__loop__'] = {$main}['text'];
		}
		else if (isset({$main}[$collection_name]))
		{
			{$main}['__loop__'] = {$main}[$collection_name];
			{$main}[$collection_name] =& {$main}['__loop__'];
		}
		else
		{
			{$main}['__loop__'] = {$main};
		}

		if (array_key_exists('counts', $main))
		{
			{$main}[$collection_name]['counts'] =& {$main}['counts'];
		}
	}

	if (({$options}['list'] && !empty({$main}['__loop__'])) || (!{$options}['list'] && $main && !isset({$main}['error']))):

		if ({$options}['list'])
		{
			if ({$options}['archive'])
			{
				switch({$options}['archive'])
				{
					case 'tag':
						{$main}['archive'] = array('__koken__' => 'tag', 'title' => str_replace(',', ', ', urldecode(isset(Koken::\$routed_variables['id']) ? Koken::\$routed_variables['id'] : Koken::\$routed_variables['slug'])));
						break;

					case 'category':
						{$main}['archive'] = array('__koken__' => 'category', 'title' => {$main}['category']['title']);
						break;

					case 'date':
						{$main}['archive'] = array('__koken__' => 'archive', 'month' => isset(Koken::\$routed_variables['month']) ? Koken::\$routed_variables['month'] : false, 'year' => Koken::\$routed_variables['year'], 'title' => !isset(Koken::\$routed_variables['month']) ? Koken::\$routed_variables['year'] : date('F Y', strtotime(Koken::\$routed_variables['month'] . '/1/' . Koken::\$routed_variables['year'])));
						break;
				}
			}
		}
		else
		{
			if (!isset({$main}[{$main}['__koken__']]))
			{
				{$main}[{$main}['__koken__']] =& $main;
			}
		}

		if (!$custom_source_var)
		{
			if (!empty({$main}['title']))
			{
				\$the_title = {$main}['title'];
			}
			else if (isset({$main}['filename']))
			{
				\$the_title = {$main}['filename'];
			}
			else if (isset({$main}['album']['title']))
			{
				\$the_title = {$main}['album']['title'];
			}
			else if (isset({$main}['archive']['title']))
			{
				\$the_title = {$main}['archive']['title'];
			}

			if (isset({$main}['canonical_url']))
			{
				echo '<!-- KOKEN HEAD BEGIN --><link rel="canonical" href="' . {$main}['canonical_url'] . '"><!-- KOKEN HEAD END -->';
			}

			if (isset(\$the_title) && isset(Koken::\$the_title_separator))
			{
				echo '<!-- KOKEN HEAD BEGIN --><koken_title>' . \$the_title . Koken::\$the_title_separator . Koken::\$site['page_title'] . '</koken_title><!-- KOKEN HEAD END -->';
			}

			if (isset({$main}['essay']) && !isset(\$_COOKIE['koken_session']) && !{$main}['essay']['published'])
			{
				header('Location: ' . Koken::\$location['root'] . '/error/403/');
				exit;
			}

			if (isset({$main}['album']) || isset({$main}['context']['album']))
			{
				if (isset({$main}['album']))
				{
					\$__rss = {$main}['album']['rss'] = Koken::\$location['root'] . '/feed/albums/' . {$main}['album']['id'] . '/recent.rss';
					\$__title = {$main}['album']['title'];
				}
				else
				{
					\$__rss = {$main}['context']['album']['rss'] = Koken::\$location['root'] . '/feed/albums/' . {$main}['context']['album']['id'] . '/recent.rss';
					\$__title = {$main}['context']['album']['title'];
				}
				echo '<!-- KOKEN HEAD BEGIN --><link rel="alternate" type="application/atom+xml" title="' . Koken::\$site['page_title'] . ': Uploads from ' . \$__title . '" href="' . \$__rss . '" /><!-- KOKEN HEAD END -->';
			}
		}
?>
DOC;

		}

	}