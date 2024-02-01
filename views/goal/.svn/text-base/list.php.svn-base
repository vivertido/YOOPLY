Due : <?php
$periods = array('today' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'year' => 'This Year', 'all' => 'All');

foreach($periods as $k=>$v): ?>
<?php if($period != $k): ?><a href="/goal/<?= $filter ?>/<?= $k ?>"><?php else: ?><b><?php endif; ?><?= $v ?><?php if($period != $k): ?></a><?php else: ?></b><?php endif; ?>&nbsp;|
<?php endforeach; ?>

<?php switch($this->session->userdata('role')):
	case 'a': $word = "No one has assigned"; break;
	case 's': $word = "You haven't been assigned"; break;
	case 't': $word = "You haven't assigned"; break;
	case 'p': $word = "Your child hasn't been assigned"; break;
endswitch; ?>

<?php if(empty($goals)): ?>
<p><?= $word ?> any goals<?php switch($period):
	case 'today': echo " today"; break;
	case 'week': echo " this week"; break;
	case 'month': echo " this month"; break;
	case 'year': echo " this year"; break;
endswitch;
?>.</p>
<?php else: ?>
<ul data-role="listview" data-inset="true">
<?php foreach($goals as $goal):  ?>
	<li><a href="/goal/view/<?= $goal->goalid ?>" data-ajax="false">
		<h3><?= $goal->title ?></h3>
		<p><?= $goal->status == 2 ? 'Completed:' : 'Achieve By:' ?> <?= date('m/d g:i a', $goal->status == 2 ? $goal->timecompleted : $goal->timedue) ?><br />
		<?php if(isset($goal->studentfirstname)): ?>Assigned to: <?= $goal->studentfirstname ?> <?= $goal->studentlastname ?><br /><?php endif; ?>
		<?php if(isset($goal->teacherfirstname)): ?>Assigned by: <?= $goal->teacherfirstname ?> <?= $goal->teacherlastname ?><?php endif; ?></p>
	</a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
