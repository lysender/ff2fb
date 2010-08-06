<div class="header-w">
	<h1><?php echo HTML::anchor(URL::site('/', TRUE), 'FF2FB - Feed FriendFeed to Facebook Better') ?></h1>
	<p id="user_ctrl">
		Welcome Guest [ <?php echo HTML::anchor(URL::site('/admin', TRUE), 'Admin Panel') ?> ]
	</p>
	<br />
</div>
<div id="nav">
	<ul>
		<li id="n_home"<?php echo $nav['index']['class'] ?>>
			<?php echo $nav['index']['link'] ?>
		</li>
		<li id="n_about"<?php echo $nav['about']['class'] ?>>
			<?php echo $nav['about']['link'] ?>
		</li>
		<li id="n_docs"<?php echo $nav['docs']['class'] ?>>
			<?php echo $nav['docs']['link'] ?>
		</li>
		<li id="n_account"<?php echo $nav['account']['class'] ?>>
			<?php echo $nav['account']['link'] ?>
		</li>
		<li id="n_contact"<?php echo $nav['contact']['class'] ?>>
			<?php echo $nav['contact']['link'] ?>
		</li>
		<li id="n_feed"<?php echo $nav['feed']['class'] ?>>
			<?php echo $nav['feed']['link'] ?>
		</li>
	</ul>
</div>