<div style="background-color:#ffffff; padding:15px;" class="ui-shadow"> 
	<h2><?= $student->firstname ?> <?= $student->lastname ?></h2>

	<h3>Type of reinforcement</h3>
	<p><?= $reinforcement->reason ?></p>

	<h3>Notes</h3>
	<p><?= $reinforcement->notes ?></p>

	<?php if($reinforcement->timeincident > 0): ?>
		<h3>Time incident</h3>
		<p><?= date('m/d g:i a', $reinforcement->timeincident); ?></p>
	<?php else: ?>
		<h3>Time created</h3>
		<p><?= date('m/d g:i a', $reinforcement->timecreated); ?></p>

	<?php endif; ?>
</div>

<ul data-role="listview" data-inset="true">
	<li><a href="/reinforcement/remove/<?= $reinforcement->reinforcementid ?>" data-ajax="false">Remove</a></li>
</ul>
