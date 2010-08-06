<div class="footer-w">			
	<div class="copyright">
		<p>&copy; 2009-2010 FF2FB by <a href="http://lysender.co.cc/">Lysender</a>. All rights reserved.<br />
		Proudly powered by Kohana v3</p>
	</div>
	
	<?php if (Kohana::$profiling): ?>
	<!-- Profiler stats -->
	<div id="kohana-profiler">
		<?php echo View::factory('profiler/stats') ?>
	</div>
	<?php endif ?>
</div>