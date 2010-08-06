<h1><strong>Privileges - ACL Component</strong></h1>

<?php if (isset($success_message)): ?>
<p class="success"><?php echo $success_message ?></p>
<?php endif ?>

<?php if (isset($error_message)): ?>
<p class="error"><?php echo $error_message ?></p>
<?php endif ?>

<div class="entries">
	<div class="entry-body">
		<p><?php echo HTML::anchor('/admin/privilege/add', 'Add Privilege') ?> | <?php echo HTML::anchor('/admin/user', 'Back') ?></p>
		<table class="reg-list">
			<thead>
				<tr>
					<th>Role</th>
					<th>Resource</th>
					<th>Privilege</th>
					<th>Privilege Description</th>
					<th>Permission</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php if (isset($privileges) && !empty($privileges)): ?>
			<?php foreach ($privileges as $privilege): ?>
				<tr>
					<td><?php echo HTML::anchor('/admin/privilege/edit/' . $privilege['privilege_id'], HTML::chars($privilege['role_name'])) ?></td>
					<td><?php echo HTML::chars($privilege['resource_name']) ?></td>
					<td><?php echo HTML::chars($privilege['privilege_name']) ?></td>
					<td><?php echo HTML::chars($privilege['privilege_description']) ?></td>
					<td><?php echo ($privilege['allow']) ? 'Allowed' : 'Denied' ?></td>
					<td class="delete"><?php echo HTML::anchor('/admin/privilege/delete/' . $privilege['privilege_id'], 'Delete') ?></td>
				</tr>
			<?php endforeach ?>
			<?php endif ?>
			</tbody>
		</table>
	</div>
</div>