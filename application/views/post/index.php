<h1><strong>Friendfeed Post</strong> - your are viewing a post from Friendfeed</h1>

<p><?php echo HTML::anchor(URL::site('/', true), 'Back to main') ?></p>
<div class="entries">
	<?php
		$view = View::factory('post/post');
		$view->feed = $feed;
		echo $view;
	?>
	<div class="clearer"></div>
</div>