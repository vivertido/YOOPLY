<?php 

?>

<form action="/admin/settings/features" method="POST" data-ajax="false">
<h2>Set School feature list</h2>
	<p>Uncheck the features you want to hide for your school. Unchecked items will not appear on any user menu or list, and no reports will include this data. Data for unchecked features will persist in case a school wants to retain it, or re-enable a particular feature in the future. </p>
	<hr/>
	<fieldset data-role="controlgroup">
	

		 
		<?php foreach($features as $k=>$feature): ?>
		<input type="checkbox" name="feature[<?= $k ?>]" value="1" id="checkbox-<?= $k ?>"<?php if(isset($settings->$k) && $settings->$k == 'ats'): ?> checked="checked"<?php endif; ?> />
		<label for="checkbox-<?= $k ?>"><?= $feature['title'] ?></label>
		<?php endforeach; ?>
	</fieldset>
	<input type="submit" name="submit" value="Save Changes" data-theme="c"/>
</form>