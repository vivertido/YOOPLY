<h2><?= $user->firstname ?> <?= $user->lastname ?></h2>

<?= $user->email ?>

<h3>Groups</h3>
<table border="1" width="100%">
<?php foreach($groups as $group): ?>
<tr>
	<td><?= $group->groupid ?></td>
	<td><a href="/a/explorer/group/<?= $group->groupid ?>"><?= $group->title ?></a></td>
</tr>
<?php endforeach; ?>
</table>