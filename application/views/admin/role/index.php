<h1><strong>Roles - ACL Component</strong></h1>

<?php if (isset($success_message)): ?>
<p class="success"><?php echo $success_message ?></p>
<?php endif ?>

<?php if (isset($error_message)): ?>
<p class="error"><?php echo $error_message ?></p>
<?php endif ?>

<div class="entries">
	<div class="entry-body">
		<p><?php echo HTML::anchor('/admin/role/add', 'Add Role') ?> | <?php echo HTML::anchor('/admin/user', 'Back') ?></p>
		<table class="reg-list">
			<thead>
				<tr>
					<th>Role Name</th>
					<th>Role Description</th>
					<th>Role ID</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php if (isset($roles) && !empty($roles)): ?>
			<?php foreach ($roles as $role): ?>
				<tr>
					<td><?php echo HTML::anchor('/admin/role/edit/' . $role->role_id, HTML::chars($role->role_name)) ?></td>
					<td><?php echo HTML::chars($role->role_description) ?></td>
					<td><?php echo $role->role_id ?></td>
					<td class="delete"><?php echo HTML::anchor('/admin/role/delete/' . $role->role_id, 'Delete') ?></td>
				</tr>
			<?php endforeach ?>
			<?php endif ?>
			</tbody>
		</table>
	</div>
</div>