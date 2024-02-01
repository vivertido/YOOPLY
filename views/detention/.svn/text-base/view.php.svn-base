<h2><?= $student->firstname ?> <?= $student->lastname ?></h2>

<?php if($detention->type == 'assigned'): ?>
<h3>Reason for <?= strtolower(htmlentities($labels->detention)) ?></h3>
<p><?= $detention->reason ?></p>

<h3>Number of <?= htmlentities($labels->detentionunits) ?> assigned</h3>
<p><?= $detention->minutes ?></p>

<h3>Time created</h3>
<?= date('m/d g:i a', $detention->timecreated); ?>
<?php endif; ?>

<?php if($detention->type == 'served'): ?>
<h3>Number of <?= htmlentities($labels->detentionunits) ?> served</h3>
<p><?= abs($detention->minutes) ?></p>

<h3>Time created</h3>
<?= date('m/d g:i a', $detention->timecreated); ?>
<?php endif; ?>

<?php if($detention->type == 'adjust'): ?>
<h3>Adjustment of <?= htmlentities($labels->detentionunits) ?></h3>
<p><?= $detention->minutes ?></p>

<h3>Time created</h3>
<?= date('m/d g:i a', $detention->timecreated); ?>
<?php endif; ?>

<ul data-role="listview" data-inset="true">
	<li><a href="/detention/remove/<?= $detention->detentionid ?>">Remove</a></li>
</ul>