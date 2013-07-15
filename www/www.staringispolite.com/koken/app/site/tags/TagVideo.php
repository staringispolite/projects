<?php

	class TagVideo extends Tag {

		function generate()
		{

			if (isset($this->parameters['data']))
			{
				$token = $this->field_to_keys($this->parameters['data']);
				unset($this->parameters['data']);
			}
			else
			{
				$token = '$value' . Koken::$tokens[0];
			}


			$params = array();

			foreach($this->parameters as $key => $val)
			{
				if (!isset($defaults[$key]))
				{
					$val = $this->attr_parse($val);
					$params[] = "$key=\"$val\"";
				}
			}

			$params = join(' ', $params);

			return <<<DOC
	<?php
		if (isset({$token}['presets']) && count({$token}['presets']))
		{
			\$top_preset = array_pop({$token}['presets']);
			\$poster = 'poster="' . \$top_preset['url'] . '"';
		}
		else
		{
			\$poster = '';
		}
	?>
<video preload="metadata" id="k-video-<?php echo {$token}['id']; ?>" src="<?php echo {$token}['original']['url']; ?>" $params <?php echo \$poster; ?>></video>

<script>
	$(function() {
		var v = $('#k-video-<?php echo {$token}['id']; ?>');
		v.attr('width', v.parent().width());
		<?php if (is_numeric({$token}['aspect_ratio'])): ?>
		v.data('aspect', <?php echo {$token}['aspect_ratio']; ?> );
		v.attr('height', v.attr('width') / v.data('aspect'));
		v.css({
			width: v.attr('width'),
			height: v.attr('height')
		});
		<?php endif; ?>
		var m = $('#k-video-<?php echo {$token}['id']; ?>').mediaelementplayer({
			success: function(player, dom) {
				$(player).bind('loadedmetadata', function() {
					v.data('aspect', this.videoWidth / this.videoHeight );
					\$K.resizeVideos();

					var p = $('#k-video-<?php echo {$token}['id']; ?>');
					if (p.attr('autoplay')) {
						player.play();
					}
				});
			}
		});
	});
</script>
DOC;
		}

	}