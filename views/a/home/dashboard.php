<?php foreach($schools as $school): ?>
<a href="/a/explorer/school/<?= $school->schoolid ?>"><?= $school->title ?></a><br />
<?php endforeach; ?>

<a href="/a/users/adduser">Add users</a>