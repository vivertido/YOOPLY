<a href="/a/explorer/school/<?= $school->schoolid ?>">Back to <?= $school->title ?></a>

<h2><?= $group->title ?></h2>

<h3>Students</h3>
<a href="/a/home/addstudent/<?= $group->groupid ?>">Add Student</a>
<table border="1" width="100%">
<?php foreach($students as $student): ?>
<tr>
	<td><?= $student->userid ?></td>
	<td><a href="/a/explorer/user/<?= $student->userid ?>"><?= $student->firstname ?> <?= $student->lastname ?></a></td>
</tr>
<?php endforeach; ?>
</table>

<h3>Teachers</h3>
<a href="/a/home/addteacher/<?= $group->groupid ?>">Add Teacher</a>
<table border="1" width="100%">
<?php foreach($teachers as $teacher): ?>
<tr>
	<td><?= $teacher->userid ?></td>
	<td><a href="/a/explorer/user/<?= $teacher->userid ?>"><?= $teacher->firstname ?> <?= $teacher->lastname ?></a></td>
</tr>
<?php endforeach; ?>
</table>