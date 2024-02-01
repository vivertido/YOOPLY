Select a district:

<ul data-role="listview" data-inset="true">
	<?php foreach($districts as $district): ?>
		<li><a href="/partner/clever/district/<?= $district->data->id ?>"><?= $district->data->name ?></a></li>
	<?php endforeach; ?>
</ul>