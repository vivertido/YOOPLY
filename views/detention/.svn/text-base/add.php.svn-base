<form action="/detention/add/<?= $studentid ?>" method="POST" data-ajax="false">
	Reason:<br />
	<select name="reason">
<?php foreach($settings->detentions as $option): ?>
		<option value="<?= htmlentities($option) ?>"><?= htmlentities($option) ?></option>
<?php endforeach; ?>
	</select>

	<div data-role="rangeslider" data-track-theme="b" data-theme="a">
		<label for="minutes"># <?= htmlentities($labels->detentionunits) ?>:</label>
		<input name="minutes" min="0" max="100" value="0" type="range" />
	</div>

	<input type="submit" name="submit" value="Assign <?= htmlentities($labels->detention) ?>" />
</form>