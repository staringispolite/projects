<?php

	class TagPulse extends Tag {

		function _clean_val($val)
		{
			if (strpos($val, '$') === 0)
			{
				return $val;
			}
			else if (strpos($val, '{$') === false)
			{
				if ($val != 'true' && $val != 'false' && !is_numeric($val))
				{
					$val = "\"$val\"";
				}
			}
			else
			{
				$val = "trim('" . preg_replace('/\{(\$[^}]+)\}/', "' . $1 . '", $val) . "')";
				$val = "is_numeric($val) ? (int) $val : ( $val == 'false' || $val == 'true' ? $val === 'true' : $val)";
			}
			return $val;
		}

		function generate()
		{

			$options = array( 'group' => 'default' );
			$disabled = array();

			$group_wrap = '<?php echo "default"; ?>';

			$params = array();
			foreach($this->parameters as $key => $val)
			{
				if ($key === 'source' || strpos($key, 'filter:') === 0)
				{
					$params[] = "'$key' => \"" . $this->attr_parse($val) . '"';
					unset($this->parameters[$key]);
				}
				else
				{
					if ($key === 'group')
					{
						$group_wrap = $this->attr_parse($val, true);
					}
					$val = $this->attr_parse($val);
					if (strpos($key, ':') !== false)
					{
						$bits = explode(':', $key);
						if (in_array($bits[0], $disabled))
						{
							continue;
						}
						if ($bits[1] === 'enabled' && $val == 'false')
						{
							$disabled[] = $bits[0];
							unset($options[$bits[0]]);
						}
						else
						{
							if (!isset($options[$bits[0]]))
							{
								$options[$bits[0]] = array();
							}
							$options[$bits[0]][$bits[1]] = $val;
						}
					}
					else
					{
						$options[$key] = $val;
					}
				}
			}

			$params = join(',', $params);

			if (isset($options['jsvar']))
			{
				$js = 'var ' . $this->attr_parse($options['jsvar'], true) . ' = ';
			}
			else
			{
				$js = '';
			}
			if (isset($options['data_from_url']))
			{
				$options['dataUrl'] = Koken::$location['real_root_folder'] . '/api.php?' . $this->attr_parse($options['data_from_url']);
				unset($options['data_from_url']);
			}
			else if (isset($options['data']))
			{
				$data = $this->field_to_keys('data');
				if (strpos($data, 'covers') !== false)
				{
					$base = str_replace("['covers']", '', $data);
					$options['data'] = "array( 'content' => $data, 'album_id' => {$base}['id'], 'album_type' => {$base}['album_type'] )";
				}
				else
				{
					$options['data'] = "array( 'content' => $data )";
				}
			}

			unset($options['source']);

			$native = array();

			foreach($options as $key => $val)
			{
				if ($key === 'data')
				{
					$native[] = "'$key' => $val";
				}
				else if ($key !== 'group')
				{
					$native[] = "'$key' => " . $this->_clean_val($val);
				}
			}

			if (isset(Koken::$site['urls']['album']))
			{
				$native[] = "'albumUrl' => '" . Koken::$site['urls']['album'] . "'";
			}
			$native = join(', ', $native);

			return <<<OUT
<?php
	\$__id = 'pulse_' . md5(uniqid());
	\$__group = '{$options['group']}';
	\$__native_raw = array($native);

	if (!isset(\$__native_raw['data']) && !isset(\$__native_raw['dataUrl']))
	{
		list(\$__url,) = Koken::load( array($params) );
		\$__native_raw['dataUrl'] = Koken::\$location['real_root_folder'] . '/api.php?' . \$__url;
	}

	\$__native = array_merge( \$__native_raw, isset(Koken::\$site['pulse_groups'][\$__group]) ? Koken::\$site['pulse_groups'][\$__group] : array() );
	if (\$__group === 'essays' && isset(\$__native_raw['link_to']) && \$__native_raw['link_to'] !== 'default')
	{
		\$__native['link_to'] = \$__native_raw['link_to'];
	}

	if (isset(\$__native['link_to']) && \$__native['link_to'] === 'default')
	{
		\$__native['link_to'] = 'advance';
	}
?>
<div id="<?php echo \$__id; ?>" style="clear:left;" data-pulse-group="$group_wrap"></div>
<script>
	{$js}\$K.pulse.register({ id: '<?php echo \$__id; ?>', options: <?php echo json_encode(\$__native); ?> })
</script>
OUT;

		}

	}