<h1><strong>Friendfeed Streams</strong> posts and likes of mine from friendfeed</h1>

<?php
	// top paginator
	if (!empty($paginator))
	{
		echo $paginator;
	}
	
	// feeds / entries
	$view = View::factory('post/post');
	if (isset($feeds) && !empty($feeds))
	{
		foreach ($feeds as $feed)
		{
			// post/entry wrapper
			echo '<div class="entries">';
			
			// render single post
			$view->bind('feed', $feed);
			echo $view;
			
			// post/entry clearer and close wrapper
			echo '<div class="clearer"></div>',
			'</div>';
		}
	}
	
	// bottom paginator
	if (!empty($paginator))
	{
		echo $paginator;
	}
?>