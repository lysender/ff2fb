<div class="header-w">
	<h1><?php echo HTML::anchor(URL::site('/admin', TRUE), 'FF2FB - Administration Panel') ?></h1>
	<p id="user_ctrl">
		<?php echo Auth::instance()->user()->username ?> [ <?php echo HTML::anchor(URL::site('/', TRUE), 'Visit site') ?> | <?php echo HTML::anchor(URL::site('/login/logout', TRUE), 'Logout') ?> ] </p>
	<br />
</div>
<div id="nav">
	<ul>
		<li id="n_ahome"<?php echo $nav['index']['class'] ?>>
			<?php echo $nav['index']['link'] ?>
		</li>
		<li id="n_ageneral"<?php echo $nav['general']['class'] ?>>
			<?php echo $nav['general']['link'] ?>
		</li>
		<li id="n_auser"<?php echo $nav['user']['class'] ?>>
			<?php echo $nav['user']['link'] ?>
		</li>
		<li id="n_amaint"<?php echo $nav['maintenance']['class'] ?>>
			<?php echo $nav['maintenance']['link'] ?>
		</li>
		<li id="n_areport"<?php echo $nav['report']['class'] ?>>
			<?php echo $nav['report']['link'] ?>
		</li>
	</ul>
</div>