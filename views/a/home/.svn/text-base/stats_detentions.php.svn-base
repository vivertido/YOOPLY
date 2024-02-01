<table>
<?php foreach($detentions as $detention): ?>
<tr>
	<td><?= date('m-d H:i', $detention->timecreated) ?></td>
	<td><a href="/a/explorer/user/<?= $detention->adminid ?>"><?= $detention->teacherfirstname ?> <?= $detention->teacherlastname ?></a></td>
	<td><a href="/a/explorer/user/<?= $detention->studentid ?>"><?= $detention->studentfirstname ?> <?= $detention->studentlastname ?></a></td>
	<td><?= $detention->reason ?></td>
	<td><?= $detention->minutes ?></td>
</tr>
<?php endforeach; ?>
</table>