<h1><strong>Resources - ACL Component</strong></h1>

<?php if (isset($success_message)): ?>
<p class="success"><?php echo $success_message ?></p>
<?php endif ?>

<?php if (isset($error_message)): ?>
<p class="error"><?php echo $error_message ?></p>
<?php endif ?>

<div class="entries">
	<div class="entry-body">
		<p><?php echo HTML::anchor('/admin/resource/add', 'Add Resource') ?> | <?php echo HTML::anchor('/admin/user', 'Back') ?></p>
		<table class="reg-list">
			<thead>
				<tr>
					<th>Resource Name</th>
					<th>Resource Description</th>
					<th>Resource ID</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php if (isset($resources) && !empty($resources)): ?>
			<?php foreach ($resources as $resource): ?>
				<tr>
					<td><?php echo HTML::anchor('/admin/resource/edit/' . $resource->resource_id, HTML::chars($resource->resource_name)) ?></td>
					<td><?php echo HTML::chars($resource->resource_description) ?></td>
					<td><?php echo $resource->resource_id ?></td>
					<td class="delete"><?php echo HTML::anchor('/admin/resource/delete/' . $resource->resource_id, 'Delete') ?></td>
				</tr>
			<?php endforeach ?>
			<?php endif ?>
			</tbody>
		</table>
	</div>
</div>