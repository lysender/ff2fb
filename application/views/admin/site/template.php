<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo $title ?> | FF2FB Administration</title>
	
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	
<?php if (isset($description) && $description): ?>
<meta name="description" content="<?php echo $description ?>" />
<?php endif ?>
	
<?php if (isset($keywords) && $keywords): ?>
<meta name="keywords" content="<?php echo $keywords ?>" />
<?php endif ?>

<link rel="shortcut icon" href="<?php echo URL::site('/favicon.ico?v='.APP_VERSION, true) ?>" />

<!-- basic styles -->
<?php foreach ($styles as $style => $media)
	echo HTML::style($style.'?v='.APP_VERSION, array('media' => $media)), "\n" ?>

<script type="text/javascript">
//<![CDATA[
	var base_url = '<?php echo URL::site('/') ?>';
//]]>
</script>
	
<?php if (Kohana::$environment === Kohana::DEVELOPMENT && Kohana::$profiling): ?>
<!-- Profiler Styles -->
<style type="text/css">
	<?php include Kohana::find_file('views', 'profiler/style', 'css') ?>
</style>
<?php endif ?>
</head>

<body>
<div id="header">
	<?php echo $header ?>
</div>

<div id="content">
	<div class="content-w">
		<div class="main">
			<?php echo $content ?>
		</div>
		<div class="clearer"></div>
	</div>
</div>

<div id="footer">
	<?php echo $footer ?>
</div>

<!-- basic scripts -->
<?php foreach ($scripts as $script)
	echo HTML::script($script.'?v='.APP_VERSION), "\n" ?>

<script type="text/javascript">
//<![CDATA[
	<?php
		if (isset($head_scripts) && $head_scripts) {
			echo $head_scripts."\n";
		}
	?>
	$(function(){
		<?php
			if (isset($head_readyscripts) && $head_readyscripts) {
				echo $head_readyscripts."\n";
			}
		?>
	});
//]]>
</script>
</body>
</html>