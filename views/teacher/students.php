<?php foreach($groups as $group): $group_id = $group->groupid; ?>
<div data-role="collapsible">
	<h2><?= $group->title ?></h2>
	<ul data-role="listview" data-inset="true">
<?php foreach($students as $student): if($student->groupid == $group_id): ?>
	<li><a href="/student/view/<?= $student->userid ?>"><?= $student->lastname ?>, <?= $student->firstname ?></a></li>
<?php endif; endforeach; ?>
</ul>
</div>
<?php endforeach; ?>
