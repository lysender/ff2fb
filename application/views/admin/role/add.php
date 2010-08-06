<h1><strong>Role - Add</strong></h1>

<?php if (isset($error_message)): ?>
<p class="error"><?php echo $error_message ?></p>
<?php endif ?>

<p><?php echo HTML::anchor('/admin/role', 'Back') ?></p>
<div class="entries">
	<div class="entry-body">
		<div class="reg-form">
			<form id="acl_form" action="<?php echo Url::site('/admin/role/add') ?>" method="post">
				<div class="block">
					<label for="role_name">Role Name</label>
					<div class="input">
						<span class="form-input">
							<input type="text" class="txt" name="role_name" id="role_name" maxlength="20" value="<?php echo HTML::chars($role->role_name) ?>" />
						</span>
					</div>
				</div>
				
				<div class="block">
					<label for="role_description">Role Description</label>
					<div class="input">
						<span class="form-input">
							<input type="text" class="txt txtlong" name="role_description" id="role_description" maxlength="128" value="<?php echo HTML::chars($role->role_description) ?>" />
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