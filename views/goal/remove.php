<form action="/goal/remove/<?= $goal->goalid ?>" method="POST" data-ajax="false">
	Are you sure you want to remove this goal?
	<input type="submit" name="submit" value="Yes" />
	<input type="submit" name="cancel" value="No" />
</form