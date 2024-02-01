Assigned : <?php
$periods = array('today' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'year' => 'This Year', 'all' => 'All');

foreach($periods as $k=>$v): ?>
<?php if($period != $k): ?><a href="/intervention/<?= $filter ?>/<?= $k ?>"><?php else: ?><b><?php endif; ?><?= $v ?><?php if($period != $k): ?></a><?php else: ?></b><?php endif; ?>&nbsp;|
<?php endforeach; ?>

<?php switch($this->session->userdata('role')):
	case 'a': $word = "No one has assigned"; break;
	case 's': $word = "You haven't been assigned"; break;
	case 't': $word = "You haven't assigned"; break;
	case 'p': $word = "Your child hasn't been assigned"; break;
endswitch; ?>

<?php if(empty($interventions)): ?>
<p><?= $word ?> any <?= htmlentities(trim($interventionlabel)) ?> <?php switch($period):
	case 'today': echo " today"; break;
	case 'week': echo " this week"; break;
	case 'month': echo " this month"; break;
	case 'year': echo " this year"; break;	
endswitch;
?>.</p>
<?php else: ?>
<ul data-role="listview" data-inset="true">
<?php foreach($interventions as $intervention): ?>
	<li><a href="/intervention/view/<?= $intervention->interventionid ?>">
	<h2><?= $intervention->intervention ?></h2>
	<p><?php if(isset($intervention->studentfirstname)): ?>Assigned to: <?= $intervention->studentfirstname ?> <?= $intervention->studentlastname ?><br /><?php endif; ?>
  	<?php if(isset($intervention->teacherfirstname)): ?>Assigned by: <?= $intervention->teacherfirstname ?> <?= $intervention->teacherlastname ?><br /><?php endif; ?>
	<?= date('m/d g:i a', $intervention->timeincident > 0 ? $intervention->timeincident : $intervention->timecreated) ?></p></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>