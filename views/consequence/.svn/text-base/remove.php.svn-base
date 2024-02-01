<form action="/consequence/remove/<?= $consequence->consequenceid ?>" method="POST" data-ajax="false">
	<div style="background-color:#ffffff; padding:15px; margin-bottom: 10px" class="ui-shadow">
		<span style="float:right"><?= $consequence->progress ?></span>
		<span style="font-weight:bold"><?= $consequence->title ?></span><br /></br />
		<?php $data = json_decode($consequence->data); echo preg_replace("/\r?\n/", '<br />', htmlentities($data->notes)); ?>
	<div>

	Are you sure you want to remove this consequence?
	<input type="submit" name="submit" value="Remove" />
	<input type="submit" name="cancel" value="Cancel" />
</form>