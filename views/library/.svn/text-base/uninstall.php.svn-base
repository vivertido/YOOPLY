Uninstall

<form action="/library/uninstall/<?= $resource->libraryid ?>" method="POST">
	Are you sure you want to remove the following:
	<ul>
	<?php foreach($resources as $r): ?>
		<li><a href="<?= $r['href'] ?>" target="_blank"><?= $r['title'] ?></a></li>
	<?php endforeach; ?>
	</ul>
	<input type="submit" name="submit" value="Uninstall" />
</form>