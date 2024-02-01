<h2><?= $student->firstname ?> <?= $student->lastname ?></h2>

<h3>Type of intervention</h3>
<p><?= $intervention->intervention ?></p>

<h3>Notes</h3>
<p><?= $intervention->notes ?></p>

<?php if($this->session->userdata('role') != 't'): ?>
<h3>Assigned by</h3>
<p><?= $teacher->firstname ?> <?= $teacher->lastname ?></p>
<?php endif; ?>

<h3>Time created</h3>
<?= date('m/d H:i', $intervention->timecreated); ?><br /><br />

<form action="/intervention/remove/<?= $intervention->interventionid ?>" method="POST" data-ajax="false">
	Are you sure you want to remove this record?<br />
	<input type="submit" name="submit" value="Yes" data-inline="true" /> <input type="submit" name="cancel" value="No" data-inline="true" />
</form>