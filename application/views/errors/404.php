<h1><strong>Unexpected error occured</strong></h1>

<p class="error">Page not found</p>

<div class="entries">
	<div class="entry-body">
	<p>The requested page <?php echo HTML::anchor('{KOHANA_REQUESTED_PAGE}', '{KOHANA_REQUESTED_PAGE}') ?> is not found.</p>

		<p>It is either not existing, moved or deleted. 
		Make sure the URL is correct. </p>

		<p>To go back to the previous page, click the Back button.</p>
		
		<p><a href="<?php echo URL::site('/', true) ?>">If you wanted to go to the main page instead, click here.</a></p>
	</div>
</div>
