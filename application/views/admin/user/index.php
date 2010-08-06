<h1><strong>User Management</strong></h1>

<?php if (isset($successMessage)): ?>
<p class="success"><?php echo $successMessage ?></p>
<?php endif ?>

<?php if (isset($errorMessage)): ?>
<p class="error"><?php echo $errorMessage ?></p>
<?php endif ?>

<div class="entries">
	<div class="entry-body">
		<p><?php echo HTML::anchor(URL::site('/admin', TRUE), 'Back to main') ?></p>
		
		<table class="reg-list">
			<thead>
				<tr>
					<th>Section</th>
					<th>Module</th>
					<th>Description</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo HTML::anchor(URL::site('/admin/role', TRUE), 'Roles') ?></td>
					<td>ACL</td>
					<td>Objects which you assign privileges and applied to a user</td>
				</tr>
				<tr>
					<td><?php echo HTML::anchor(URL::site('/admin/resource', TRUE), 'Resources') ?></td>
					<td>ACL</td>
					<td>Objects that a role is either allowed or denied to access</td>
				</tr>
				<tr>
					<td><?php echo HTML::anchor(URL::site('/admin/privilege', TRUE), 'Privileges') ?></td>
					<td>ACL</td>
					<td>Defines what a role is allowed or denied to access</td>
				</tr>
				<tr>
					<td><?php echo HTML::anchor(URL::site('/admin/defaultrole', TRUE), 'Default roles') ?></td>
					<td>User</td>
					<td>Default roles when a user sign up for an account</td>
				</tr>
			</tbody>
		</table>
		
	</div>
</div>