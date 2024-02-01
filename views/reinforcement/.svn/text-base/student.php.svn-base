<?php if(empty($reinforcements)): ?>
There are no reinforcements for this student.
<?php else: ?>
<ul data-role="listview" data-inset="true">
<?php foreach($reinforcements as $reinforcement):  ?>
	<li><a href="/reinforcement/view/<?= $reinforcement->reinforcementid ?>" data-ajax="false">
		<h3><?= $reinforcement->reason ?></h3>
		<p><?= date('m/d g:i a', $reinforcement->timeincident > 0 ? $reinforcement->timeincident : $reinforcement->timecreated) ?><br />
		Awarded by: <?= $reinforcement->firstname ?> <?= $reinforcement->lastname ?></p>
	</a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>