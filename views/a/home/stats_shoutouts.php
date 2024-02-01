<table>
<?php foreach($shoutouts as $shoutout): ?>
<tr>
	<td><?= date('m-d H:i', $shoutout->timecreated) ?></td>
	<td><a href="/a/explorer/user/<?= $shoutout->fromuserid ?>"><?= $shoutout->fromfirstname ?> <?= $shoutout->fromlastname ?></a></td>
	<td><a href="/a/explorer/user/<?= $shoutout->touserid ?>"><?= $shoutout->tofirstname ?> <?= $shoutout->tolastname ?></a></td>
	<td><?= htmlentities($shoutout->content) ?></td>
</tr>
<?php endforeach; ?>
</table>