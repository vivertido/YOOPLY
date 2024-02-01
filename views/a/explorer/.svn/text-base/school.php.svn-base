<h2><?= $school->title ?></h2>

<a href="/a/home/addgroup/<?= $school->schoolid ?>">Add Group</a>
<table border="1" width="100%">
<?php foreach($groups as $group): ?>
<tr>
	<td><?= $group->groupid ?></td>
	<td><a href="/a/explorer/group/<?= $group->groupid ?>"><?= $group->title ?></a></td>
</tr>
<?php endforeach; ?>
</table>

<h3>Teachers</h3>

<table border="1" width="100%">
<?php foreach($teachers as $admin): ?>
<tr>
	<td><?= $admin->userid ?></td>
	<td><a href="/a/explorer/user/<?= $admin->userid ?>"><?= $admin->firstname ?> <?= $admin->lastname ?></a></td>
</tr>
<?php endforeach; ?>
</table>

<h3>Teacher Admins</h3>

<table border="1" width="100%">
<?php foreach($teacheradmins as $admin): ?>
<tr>
	<td><?= $admin->userid ?></td>
	<td><a href="/a/explorer/user/<?= $admin->userid ?>"><?= $admin->firstname ?> <?= $admin->lastname ?></a></td>
</tr>
<?php endforeach; ?>
</table>

<h3>Admins</h3>

<table border="1" width="100%">
<?php foreach($admins as $admin): ?>
<tr>
	<td><?= $admin->userid ?></td>
	<td><a href="/a/explorer/user/<?= $admin->userid ?>"><?= $admin->firstname ?> <?= $admin->lastname ?></a></td>
</tr>
<?php endforeach; ?>
</table>

<a href="/dev/login/<?= $school->schoolid ?>">Login</a>