<h2><?= $student->firstname ?> <?= $student->lastname ?></h2>

<h3>Type of reinforcement</h3>
<p><?= $reinforcement->reason ?></p>

<h3>Notes</h3>
<p><?= $reinforcement->notes ?></p>

<h3>Time created</h3>
<?= date('m/d H:i', $reinforcement->timecreated); ?>

<br /><br />

<form action="/reinforcement/remove/<?= $reinforcement->reinforcementid ?>" method="POST" data-ajax="false">
	Are you sure you want to remove this record?<br />
	<input type="submit" name="submit" value="Yes" data-inline="true" /> <input type="submit" name="cancel" value="No" data-inline="true" />
</form>