<?php if(empty($goals)): ?>
There are no goals for this student.
<?php else: ?>
<ul data-role="listview" data-inset="true">
<?php foreach($goals as $goal):  ?>
	<li><a href="/goal/view/<?= $goal->goalid ?>" data-ajax="false">
		<h3><?= $goal->title ?></h3>
		<p><?= $goal->status == 2 ? 'Completed:' : 'Achieve By:' ?> <?= date('m/d g:i a', $goal->status == 2 ? $goal->timecompleted : $goal->timedue) ?><br />
		Assigned by: <?= $goal->teacherfirstname ?> <?= $goal->teacherlastname ?></p>
	</a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>