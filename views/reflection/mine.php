<?php if(!empty($reflections)): ?>

<ul data-role="listview">
<?php foreach($reflections as $reflection): ?>
<li><a href="/reflections/view/<?= $reflection->referralid ?>"><?= date('m/d', $reflection->timereflection) ?> - <?= $reflection->incident ?></a></li>
<?php endforeach; ?>
</ul>
<?php else: ?>
You haven't completed any reflections yet.


<?php endif; ?>