<table>
<?php foreach($settings as $setting): ?>
<tr>
	<td><?= $setting->schoolid ?></td>
	<td><a href="/a/explorer/settings/<?= $setting->settingsid ?>"><?= $setting->type ?></a></td>
  <td><?= date('Y-m-d H:i', $setting->timecreated) ?></a></td>
</tr>
<?php endforeach; ?>
</table>