<h1><strong>New user registration</strong></h1>

<?php if (isset($error_message)): ?>
<p class="error"><?php echo $error_message ?></p>
<?php endif ?>

<p><?php echo HTML::anchor(URL::site('/', TRUE), 'Back') ?></p>
<div class="entries">
	<div class="entry-body">
		<div class="reg-form">
			<form id="acl_form" action="<?php echo Url::site('/register') ?>" method="post">
				<div class="block">
					<label for="role_name">User name</label>
					<div class="input">
						<span class="form-input">
							<?php echo $user->input('username', array('id' => 'username', 'class' => 'txt')) ?>
						</span>
					</div>
				</div>
				
				<div class="block">
					<label for="role_name">Email</label>
					<div class="input">
						<span class="form-input">
							<?php echo $user->input('email', array('id' => 'email', 'class' => 'txt')) ?>
						</span>
					</div>
				</div>

				<div class="block">
					<label for="role_name">Password</label>
					<div class="input">
						<span class="form-input">
							<?php echo $user->input('password', array('id' => 'password', 'class' => 'txt')) ?>
						</span>
					</div>
				</div>

				<div class="block">
					<label for="role_name">Confirm password</label>
					<div class="input">
						<span class="form-input">
							<?php echo $user->input('confirm_password', array('id' => 'confirm_password', 'class' => 'txt')) ?>
						</span>
					</div>
				</div>
				
				<div class="block submit">
					<div class="input">
						<input type="submit" value="Add" class="btn" />
					</div>
				</div>
			</form>
		</div>
		<div class="clearer"></div>
	</div>
</div>