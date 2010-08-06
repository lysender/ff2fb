<h1><strong>Default Roles - User Component</strong></h1>

<?php if (isset($success_message)): ?>
<p class="success"><?php echo $success_message ?></p>
<?php endif ?>

<?php if (isset($error_message)): ?>
<p class="error"><?php echo $error_message ?></p>
<?php endif ?>

<div class="entries">
	<div class="entry-body">
		<p><?php echo HTML::anchor('/admin/defaultrole/add', 'Add Default Role') ?> | <?php echo HTML::anchor('/admin/user', 'Back') ?></p>
		<table class="reg-list">
			<thead>
				<tr>
					<th>Role Name</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php if (isset($roles) && !empty($roles)): ?>
			<?php foreach ($roles as $role): ?>
				<tr>
					<td><?php echo $role->title('role_id') ?></td>
					<td class="delete"><?php echo HTML::anchor('/admin/defaultrole/delete/' . $role->verbose('role_id'), 'Delete') ?></td>
				</tr>
			<?php endforeach ?>
			<?php endif ?>
			</tbody>
		</table>
	</div>
</div>