<?php if(empty($interventions)): ?>
There are no interventions for this student.
<?php else: ?>
<ul data-role="listview" data-inset="true">
<?php foreach($interventions as $intervention):  ?>
	<li><a href="/intervention/view/<?= $intervention->interventionid ?>" data-ajax="false">
		<h3><?= $intervention->intervention ?></h3>
		<p><?= date('m/d g:i a', $intervention->timeincident > 0 ? $intervention->timeincident : $intervention->timecreated) ?></p>
	</a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>