<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo $head_title ?></title>
	
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<meta name='robots' content='all' />
	
<?php if (isset($head_desc) && $head_desc): ?>
<meta name="description" content="<?php echo $head_desc ?>" />
<?php endif ?>
	
<?php if (isset($head_keyword) && !empty($head_keyword)): ?>
<meta name="keywords" content="<?php echo implode(', ', $head_keyword) ?>" />
<?php endif ?>
<link rel="shortcut icon" href="<?php echo URL::site('/media/img/ff2fb.ico?v=' . APP_VERSION, true) ?>" />
<?php echo
	HTML::style(URL::site('/media/css/reset.css?v=' . APP_VERSION, true), array('media' => 'all')),
	HTML::style(URL::site('/media/css/default.css?v=' . APP_VERSION, true), array('media' => 'all'));
	
	if (isset($head_css) && !empty($head_css)) {
		foreach ($head_css as $key => $css) {
			echo HTML::style(URL::site("/media/css/$css?v=" . APP_VERSION, true), array('media' => 'all'));
		}
	}
?>
	
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
			<div class="sidebar">
				<?php echo $sidebar ?>
			</div>
			<div class="clearer"></div>
		</div>
	</div>
	
	<div id="footer">
		<?php echo $footer ?>
	</div>
	
	<!-- jQuery is required -->
	<?php echo HTML::script(URL::site('/media/js/jquery-1.3.2.min.js?v=' . APP_VERSION, TRUE)) ?>
	
	<!-- Custom js -->
	<?php
		if (isset($head_js) && !empty($head_js)) {
			foreach ($head_js as $key => $js) {
				echo HTML::script(URL::site("/media/js/$js?v=" . APP_VERSION, TRUE));
			}
		}
	?>
	
	<script type="text/javascript">
	//<![CDATA[
		var base_url = "<?php echo URL::base() ?>";
		<?php
			if (isset($head_script) && !empty($head_script)) {
				echo implode("\n", $head_script);
			}
		?>

		$(function(){
			<?php
				if (isset($head_readyscript) && !empty($head_readyscript)) {
					echo implode("\n", $head_readyscript);
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