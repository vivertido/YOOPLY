Assigned : <?php
$periods = array('today' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'all' => 'All');

foreach($periods as $k=>$v): ?>
<?php if($period != $k): ?><a href="/teacher/interventions/<?= $k ?>"><?php else: ?><b><?php endif; ?><?= $v ?><?php if($period != $k): ?></a><?php else: ?></b><?php endif; ?>&nbsp;|
<?php endforeach; ?>

<?php if(empty($interventions)): ?>
<p>You haven't assigned any interventions <?php switch($period):
	case 'today': echo " today"; break;
	case 'week': echo " this week"; break;
	case 'month': echo " this month"; break;
endswitch;
?>.</p>
<?php else: ?>
<ul data-role="listview" data-inset="true">
<?php foreach($interventions as $intervention): ?>
<li><a href="/intervention/view/<?= $intervention->interventionid ?>"><h2><?= $intervention->intervention ?></h2>
<p><?= $intervention->studentfirstname ?> <?= $intervention->studentlastname ?> - <?= date('m/d g:i a', $intervention->timecreated) ?></p></a></li>

<?php endforeach; ?>
</ul>
<?php endif; ?>