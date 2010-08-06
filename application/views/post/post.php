<!-- wrapper needed -->
<?php
	$feed_content = null;
	$title = null;
	if (is_array($feed))
	{
		$feed_content = unserialize($feed['content_serialized']);
		$title = Model_Feed::generate_title($feed_content['body'], $feed['id']);
	}
	else
	{
		$feed_content = unserialize($feed->content_serialized);
		$title = Model_Feed::generate_title($feed_content['body'], $feed->id);
	}

	$ts = strtotime($feed_content['date']);	
	$span = Dc_Date::fuzzy_span($ts);
?>
<div class="profile">
	<a href="http://friendfeed.com/<?php echo $feed_content['from']['id'] ?>">
		<img src="http://friendfeed-api.com/v2/picture/<?php echo $feed_content['from']['id'] ?>?size=medium" alt="<?php echo HTML::chars($feed_content['from']['name'] . " profile image") ?>" />
	</a>
</div>
<div class="ebody">
	<p class="name">
		<a href="<?php echo URL::site('post/index/' . $feed_content['id'], true) ?>" title="<?php echo $title ?>">
			<?php echo HTML::chars($feed_content['from']['name']) ?>
		</a>
	</p>
	<div class="title">
		<p class="text"><?php echo $feed_content['body'] ?></p>
		<p class="info"><?php
			echo HTML::anchor($feed_content['url'], $span, array(
					'class' => 'fuzzy_span',
					'id'	=> 'ts_' . $ts
					))
				,
				' from ',
				HTML::anchor('http://friendfeed.com/', 'Friendfeed');
			
			if (isset($feed_content['via']))
			{
				echo ' via ',
					HTML::anchor($feed_content['via']['url'], $feed_content['via']['name']);
			}
		?></p>
	</div>
	<?php
		if (!empty($feed_content['thumbnails']))
		{
			// check if first entry is a video
			$first_thumb = reset($feed_content['thumbnails']);
			if (isset($first_thumb['player']))
			{
				// thumbnail images
				$w = ' width="' . $first_thumb['width'] . '"';
				$h = ' height="' . $first_thumb['height'] . '"';
				
				// output video player link
				// get button position
				$button_position = Player::button_position($first_thumb['width'], $first_thumb['height']);
				$button_style = ' style="top: ' . $button_position['top'] . 'px; left: ' . $button_position['left'] . 'px;"';
				echo '<div class="player-thumb">',
						'<a href="' . HTML::chars($first_thumb['link']) . '" class="play-trigger" id="play_alt_', $feed_content['id'], '" title="Play video">',
							'<img src="' . $first_thumb['url'] . '"' . $w . $h . ' alt="' . $title . '" />',
						'</a>',
						'<div class="player-button"', $button_style, '>',
							'<a href="#" title="Play video" class="play-trigger" id="play_main_', $feed_content['id'], '">',
								'<img src="', URL::site('/media/img/playbutton.png'), '" alt="Play video" />',
							'</a>',
						'</div>',
					'</div>';
				// output player but invisible by default
				echo '<p class="player" id="player_', $feed_content['id'], '">',
						$first_thumb['player'],
					'</p>';
			}
			else
			{
				// thumnail photos
				$exceed_limit = false;
				$more_button_applied = false;
				$acc_width = 0;
				echo '<p class="thumb" id="thumbs_', $feed_content['id'], '">';
				foreach ($feed_content['thumbnails'] as $key => $thumb)
				{
					$w = null;
					$img_w = 0;
					if (isset($thumb['width']))
					{
						$img_w = (int)$thumb['width'];
						$w = ' width="' . $thumb['width'] . '"';
					}
					
					// calculate the accumulated width if given
					$acc_width += $img_w;
					if ($acc_width >= 550)
					{
						$exceed_limit = true;
					}
					
					$h = null;
					if (isset($thumb['height']))
					{
						$h = ' height="' . $thumb['height'] . '"';
					}
					
					// if no dimension is set, set the max w and h
					$style = null;
					if (!$w && !$h)
					{
						$style = ' style="max-height: 175px; max-width: 525px;"';
					}
					
					// only show the first row and hide the rest
					// if exceed 525px accumulated width
					$class_hide = '';
					if ($exceed_limit)
					{
						$class_hide = ' hidden';
						if (!$more_button_applied)
						{
							$more_button_applied = true;
							// render more button
							echo '<span>',
								'<a href="#" id="thumb_more_' . $feed_content['id'] . '" class="show-more-thumbs" title="More images">',
									'<img src="', URL::site('/media/img/more.png'), '" alt="More images" />',
								'</a>',
							'</span>';
						}
					}
					echo '<span class="img-thumb', $class_hide, '">',
							'<a href="' . HTML::chars($thumb['link']) . '" title="' . $title . '">',
								'<img src="' . $thumb['url'] . '"' . $w . $h . $style . ' alt="' . $title . '" />',
							'</a>',
						'</span>';
				}
				echo '</p>';
			}
		}
	?>
</div><!-- ebody -->
<!-- insert clearer if needed -->
<!-- wrapper ends here -->
