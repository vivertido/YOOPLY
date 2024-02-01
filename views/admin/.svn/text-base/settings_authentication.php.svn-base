<h2>Login Settings</h2>
<?php if(isset($error)): switch($error): case 'nooptionselected': ?>Please select how a user can log into Yooply<?php break;
endswitch; endif; ?>

<p>You can choose how users authenticate with Yooply.</p>

<p>Domain: https://<?= $school->domain ?></p>

<form action="/admin/settings/authentication" method="POST">
	<label for="method">What do users provide?</label>
	<ul data-role="listview" data-inset="true">
		<?php foreach($availableoptions as $k=>$v): ?>
			<li><input type="checkbox" id="<?= $k ?>" name="option[<?= $k ?>]" value="1"<?php if(isset($school->metadata->$k) && $school->metadata->$k): ?> checked="checked"<?php endif ?> /> <label for="<?= $k ?>"><?= $v ?></label></li>
		<?php endforeach; ?>
	</ul>

	<input type="submit" name="submit" value="Save Changes" />
</form>