<h1><strong>Privilege - Add</strong></h1>

<?php if (isset($error_message)): ?>
<p class="error"><?php echo $error_message ?></p>
<?php endif ?>

<p><?php echo HTML::anchor('/admin/privilege', 'Back') ?></p>
<div class="entries">
	<div class="entry-body">
		<div class="reg-form">
			<form id="acl_form" action="<?php echo Url::site('/admin/privilege/add') ?>" method="post">
				<div class="block">
					<label for="role_id">Role</label>
					<div class="input">
						<span class="form-input">
							<?php echo $privilege->input('role_id', array('id' => 'role_id', 'class' => 'frm-sel')) ?>
						</span>
					</div>
				</div>
				<div class="block">
					<label for="resource_id">Resource</label>
					<div class="input">
						<span class="form-input">
							<?php echo $privilege->input('resource_id', array('id' => 'resource_id', 'class' => 'frm-sel')) ?>
						</span>
					</div>
				</div>
			
				<div class="block">
					<label for="privilege_name">Privilege Name</label>
					<div class="input">
						<span class="form-input">
							<input type="text" class="txt" name="privilege_name" id="privilege_name" maxlength="20" value="<?php echo HTML::chars($privilege->privilege_name) ?>" />
						</span>
					</div>
				</div>
				
				<div class="block">
					<label for="privilege_description">Privilege Description</label>
					<div class="input">
						<span class="form-input">
							<input type="text" class="txt txtlong" name="privilege_description" id="privilege_description" maxlength="128" value="<?php echo HTML::chars($privilege->privilege_description) ?>" />
						</span>
					</div>
				</div>
				
				<div class="block">
					<label for="allow">Allow</label>
					<div class="input">
						<span class="form-input">
							<input type="checkbox" class="chkbox" name="allow" id="allow" value="1" <?php echo ($privilege->allow) ? 'checked="checked" ' : '' ?>/>
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