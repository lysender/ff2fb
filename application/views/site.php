<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>We've got a message for you!</title>
	
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	
	<link href="<?php echo URL::base(); ?>css/reset.css" media="screen" rel="stylesheet" type="text/css" />
	<style type="text/css">
		body {font-family: Georgia;}
		h1 {font-style: italic;}
	</style>
</head>
<body>
	<h1><?php echo $message; ?></h1>
	<p>We just wanted to say it! :)</p>
	
	<div id="kohana-profiler">
		<?php echo View::factory('profiler/stats') ?>
	</div>
	
	<script type="text/javascript" src="<?php echo URL::base(); ?>js/jquery-1.3.2.min.js"></script>
</body>
</html>