<h1><strong>Resource - Edit</strong></h1>

<?php if (isset($error_message)): ?>
<p class="error"><?php echo $error_message ?></p>
<?php endif ?>

<p><?php echo HTML::anchor('/admin/resource', 'Back') ?></p>
<div class="entries">
	<div class="entry-body">
		<div class="reg-form">
			<form id="acl_form" action="<?php echo Url::site("/admin/resource/edit/" . $resource->resource_id) ?>" method="post">
				<div class="block">
					<label for="resource_name">Resource Name</label>
					<div class="input">
						<span class="form-input">
							<input type="text" class="txt" name="resource_name" id="resource_name" maxlength="20" value="<?php echo HTML::chars($resource->resource_name) ?>" />
						</span>
					</div>
				</div>
				
				<div class="block">
					<label for="resource_description">Resource Description</label>
					<div class="input">
						<span class="form-input">
							<input type="text" class="txt txtlong" name="resource_description" id="resource_description" maxlength="128" value="<?php echo HTML::chars($resource->resource_description) ?>" />
						</span>
					</div>
				</div>
				<div class="block submit">
					<div class="input">
						<input type="submit" value="Save" class="btn" />
					</div>
				</div>
			</form>
		</div>
		<div class="clearer"></div>
	</div>
</div>