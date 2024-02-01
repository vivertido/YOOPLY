Assigned : <?php
$periods = array('today' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'year' => 'This Year', 'all' => 'All');

foreach($periods as $k=>$v): ?>
<?php if($period != $k): ?><a href="/reinforcement/<?= $filter ?>/<?= $k ?>"><?php else: ?><b><?php endif; ?><?= $v ?><?php if($period != $k): ?></a><?php else: ?></b><?php endif; ?>&nbsp;|
<?php endforeach; ?>

<?php switch($this->session->userdata('role')):
	case 'a': $word = "No one has assigned"; break;
	case 's': $word = "You haven't been assigned"; break;
	case 't': $word = "You haven't assigned"; break;
	case 'p': $word = "Your child hasn't been assigned"; break;
endswitch; ?>

<?php if(empty($reinforcements)): ?>
<p><?= $word ?> any <?= htmlentities(trim($reinforcementlabel)) ?> <?php switch($period):
	case 'today': echo " today"; break;
	case 'week': echo " this week"; break;
	case 'month': echo " this month"; break;
	case 'year': echo " this year"; break;
endswitch;
?>.</p>
<?php else: ?>
<ul data-role="listview" data-inset="true">
<?php foreach($reinforcements as $reinforcement): ?>
	<li><a href="/reinforcement/view/<?= $reinforcement->reinforcementid ?>">
	<h2><?= $reinforcement->reason ?></h2>
	<p><?php if(isset($reinforcement->studentfirstname)): ?>Assigned to: <?= $reinforcement->studentfirstname ?> <?= $reinforcement->studentlastname ?><br /><?php endif; ?>
  	<?php if(isset($reinforcement->teacherfirstname)): ?>Assigned by: <?= $reinforcement->teacherfirstname ?> <?= $reinforcement->teacherlastname ?><br /><?php endif; ?>
	<?= date('m/d g:i a', $reinforcement->timeincident > 0 ? $reinforcement->timeincident : $reinforcement->timecreated) ?></p></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>