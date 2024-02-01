<?php if(empty($detentions)): ?>
There are no detentions for this student.
<?php else: ?>
<ul data-role="listview" data-inset="true">
<?php foreach($detentions as $detention):  ?>
	<li><a href="/detention/view/<?= $detention->detentionid ?>" data-ajax="false">
		<h3><?= $detention->reason ?></h3>
		<p><?= date('m/d g:i a', $detention->timecreated) ?></p>
	</a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>