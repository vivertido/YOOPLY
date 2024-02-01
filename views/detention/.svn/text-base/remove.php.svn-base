<h2><?= $student->firstname ?> <?= $student->lastname ?></h2>

<?php if($detention->type == 'assigned'): ?>
<h3>Reason for <?= strtolower(htmlentities($labels->detention)) ?></h3>
<p><?= $detention->reason ?></p>

<h3>Number of <?= htmlentities($labels->detentionunits) ?> assigned</h3>
<p><?= $detention->minutes ?></p>

<h3>Time created</h3>
<?= date('m/d H:i', $detention->timecreated); ?>
<?php endif; ?>

<?php if($detention->type == 'served'): ?>
<h3>Number of <?= htmlentities($labels->detentionunits) ?> served</h3>
<p><?= abs($detention->minutes) ?></p>

<h3>Time created</h3>
<?= date('m/d H:i', $detention->timecreated); ?>
<?php endif; ?>
<br /><br />
<form action="/detention/remove/<?= $detention->detentionid ?>" method="POST" data-ajax="false">
	Are you sure you want to remove the above <?= strtolower(htmlentities($labels->detention)) ?>?

	<input type="submit" name="submit" value="Yes!" />
	<input type="submit" name="cancel" value="No!" />
</form>