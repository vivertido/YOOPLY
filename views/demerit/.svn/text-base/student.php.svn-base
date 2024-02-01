<?php if(isset($showteacherviewall)): ?>
	 Assigned by: <?php foreach(array('' => 'Me', 'all' => 'Me & Others') as $k=>$v): ?>
	 <?php if(isset($filter) && $filter == $k): ?><?= $v ?> |<?php else: ?>
	 <a href="/demerit/student/<?= $student->userid ?>/<?= $k ?>"><?= $v ?></a> |
	<?php endif; ?>
	<?php endforeach; ?>
	<br /><br />
<?php endif; ?>

<?php if(empty($demerits)): ?>
There are no <?= htmlentities(trim($demeritlabel)) ?>s for this student.
<?php else: ?>
<ul data-role="listview" data-inset="true">
<?php foreach($demerits as $demerit):  ?>
	<li><a href="/demerit/view/<?= $demerit->demeritid ?>" data-ajax="false">
		<h3><?= $demerit->reason ?></h3>
		<p><?= date('m/d g:i a', $demerit->timeincident == 0 ? $demerit->timecreated : $demerit->timeincident) ?><br />
		Assigned by: <?= $demerit->firstname ?> <?= $demerit->lastname ?></p>
	</a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>