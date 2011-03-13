<div class="footer-w">
	<h3><strong>FF2FB - Post Friendfeed to Facebook better. </strong></h3>
	<p>FF2FB is an ongoing project the aims to sync Friedfeed feeds to facebook
	in a more manageable way. This project is under heavy development. You may
	expect a lot of bugs and annoyances, however, as the development goes on
	those may get fixed. For the mean time, I synced my feeds to this site - those
	feeds include posts of mine and likes.</p>
	<div class="copyright">
		<p>&copy; 2009-2010 FF2FB by <a href="http://lysender.co.cc/">Lysender</a>. All rights reserved.<br />
		Proudly powered by Kohana <?php echo Kohana::VERSION ?></p>
	</div>
	
	<?php if (Kohana::$environment === Kohana::DEVELOPMENT && Kohana::$profiling): ?>
	<!-- Profiler stats -->
	<div id="kohana-profiler">
		<?php echo View::factory('profiler/stats') ?>
	</div>
	<?php endif ?>
</div>