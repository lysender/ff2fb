<h1><strong>Default Role - Add</strong></h1>

<?php if (isset($error_message)): ?>
<p class="error"><?php echo $error_message ?></p>
<?php endif ?>

<p><?php echo HTML::anchor('/admin/defaultrole', 'Back') ?></p>
<div class="entries">
	<div class="entry-body">
		<div class="reg-form">
			<form id="acl_form" action="<?php echo Url::site('/admin/defaultrole/add') ?>" method="post">
				<div class="block">
					<label for="role_name">Role Name</label>
					<div class="input">
						<span class="form-input">
							<?php echo $role->input('role_id', array('id' => 'role_id', 'class' => 'frm-sel')) ?>
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