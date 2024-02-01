<?php
$has_more_students = count($students) == $pagesize+1;
$has_more_teachers = count($teachers) == $pagesize+1;

if($has_more_students)
{
	array_pop($students);
}

if($has_more_teachers)
{
	array_pop($teachers);
}

?>
<h2>Students</h2>
<?php if(!empty($students)): ?>
<ul data-role="listview" data-inset="true">
<?php if($page > 0): ?>
	<li data-theme="c"><a href="/group/view/<?= $group->groupid ?>/<?= $page==1 ? '' : $page-1 ?>">Previous Page</a></li>
<?php endif; ?>
<?php foreach($students as $student): ?>
<li><a href="/student/view/<?= $student->userid ?>"><?= $student->lastname ?>, <?= $student->firstname ?></a></li>
<?php endforeach; ?>
<?php if($has_more_students): ?>
	<li data-theme="c"><a href="/group/view/<?= $group->groupid ?>/<?= $page+1 ?>">Next Page</a></li>
<?php endif; ?>
</ul>
<?php else: ?>
<p>There are no students in this class.</p>
<?php endif; ?>

<h2>Teachers</h2>
<?php if(!empty($teachers)): ?>
<ul data-role="listview" data-inset="true">
<?php if($page > 0): ?>
	<li data-theme="c"><a href="/group/view/<?= $group->groupid ?>/<?= $page==1 ? '' : $page-1 ?>">Previous Page</a></li>
<?php endif; ?>	
<?php foreach($teachers as $teacher): ?>
<li><a href="/teacher/view/<?= $teacher->userid ?>"><?= $teacher->lastname ?>, <?= $teacher->firstname ?></a></li>
<?php endforeach; ?>
<?php if($has_more_teachers): ?>
	<li data-theme="c"><a href="/group/view/<?= $group->groupid ?>/<?= $page+1 ?>">Next Page</a></li>
<?php endif; ?>
</ul>
<?php else: ?>
<p>There are no teachers in this class.</p>
<?php endif; ?>

<?php if($group->groupid != 0): ?>
<ul data-role="listview" data-inset="true">
	<li><a href="/group/edit/<?= $group->groupid ?>">Edit class name</a></li>
</ul>
<?php endif; ?>