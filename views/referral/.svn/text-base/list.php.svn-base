Assigned : <?php
$periods = array('today' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'all' => 'All');

foreach($periods as $k=>$v): ?>
<?php if($period != $k): ?><a href="/referral/<?= $filter ?>/<?= $k ?>"><?php else: ?><b><?php endif; ?><?= $v ?><?php if($period != $k): ?></a><?php else: ?></b><?php endif; ?>&nbsp;|
<?php endforeach; ?>

<?php switch($this->session->userdata('role')):
	case 'a': $word = "No one has assigned"; break;
	case 's': $word = "You haven't been assigned"; break;
	case 't': $word = "You haven't assigned"; break;
	case 'p': $word = "Your child hasn't been assigned"; break;
endswitch; ?>

<?php if(empty($referrals)): ?>
<p><?= $word ?> any referrals <?php switch($period):
	case 'today': echo " today"; break;
	case 'week': echo " this week"; break;
	case 'month': echo " this month"; break;
endswitch;
?>.</p>
<?php else: ?>
<ul data-role="listview" data-inset="true">
<?php foreach($referrals as $referral): ?>
	<li><a href="/referral/view/<?= $referral->referralid ?>">
	<h2><?= $referral->incident ?></h2>
	<p><?php if(isset($referral->studentfirstname)): ?>Assigned to: <?= $referral->studentfirstname ?> <?= $referral->studentlastname ?><br /><?php endif; ?>
	<?php if(isset($referral->teacherfirstname)): ?>Assigned by: <?= $referral->teacherfirstname ?> <?= $referral->teacherlastname ?><br /><?php endif; ?>
	<?= date('m/d g:i a', $referral->timecreated) ?></p></a>
	</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>