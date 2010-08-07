<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo $title ?> | FF2FB</title>
	
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<meta name="robots" content="all" />
	
<?php if (isset($description) && $description): ?>
<meta name="description" content="<?php echo $description ?>" />
<?php endif ?>
	
<?php if (isset($keywords) && $keywords): ?>
<meta name="keywords" content="<?php echo $keywords ?>" />
<?php endif ?>

<link rel="shortcut icon" href="<?php echo URL::site('/ff2fb.ico?v='.APP_VERSION, true) ?>" />

<!-- basic styles -->
<?php foreach ($styles as $style => $media)
	echo HTML::style(URL::site($style.'?v='.APP_VERSION, true), array('media' => $media)), "\n" ?>

<script type="text/javascript">
//<![CDATA[
	var base_url = "<?php echo URL::site('/', true) ?>";
//]]>
</script>

<?php if (!IN_PRODUCTION && Kohana::$profiling): ?>
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
	echo HTML::script(URL::site($script.'?v='.APP_VERSION, true)), "\n" ?>

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

<?php if (IN_PRODUCTION): ?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16855730-2']);
  _gaq.push(['_trackPageview']);

  (function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<?php endif ?>
</body>
</html>
