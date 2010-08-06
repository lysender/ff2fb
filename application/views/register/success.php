<h1><strong>Registration - Success</strong></h1>

<?php if (isset($error_message)): ?>
<p class="error"><?php echo $error_message ?></p>
<?php endif ?>

<?php if (isset($success_message)): ?>
<p class="success"><?php echo $success_message ?></p>
<?php endif ?>

<div class="entries">
	<div class="entry-body">
		<p>You may now login using your account.<br /><br /><br /></p>
		<p><?php echo HTML::anchor('/login', 'Click here to login.') ?></p>
	</div>
</div>