

<form action="/admin/settings/permissions" method="POST" data-ajax="false">
	<?php foreach($options as $k=>$v): ?>
	<label><input type="checkbox" name="<?= $k ?>" value="1"<?php if(isset($settings->$k) && $settings->$k): ?> checked="checked"<?php endif; ?> /> <?= $v ?></label>
	<?php endforeach; ?>
	<input type="submit" name="submit" value="Save Changes" />
</form>