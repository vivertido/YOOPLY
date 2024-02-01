<h2><?= $student->firstname ?> <?= $student->lastname ?></h2>

<h3>Reason for <?= htmlentities($demeritlabel) ?></h3>
<p><?= $demerit->reason ?></p>

<h3>Notes</h3>
<p><?= $demerit->notes ?></p>

<h3>Time created</h3>
<?= date('m/d H:i', $demerit->timecreated); ?><br /><br />

<form action="/demerit/remove/<?= $demerit->demeritid ?>" method="POST" data-ajax="false">
	Are you sure you want to remove this record?<br />
	<input type="submit" name="submit" value="Yes" data-inline="true" /> <input type="submit" name="cancel" value="No" data-inline="true" />
</form>