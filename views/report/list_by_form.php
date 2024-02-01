<style>
.ui-listview>li h3{

	color:#1CCFE7;
	font-size:1.4em;
}
.ui-listview>li p {


	color:#663300;
	font-size:1.0em;
}
.ui-listview>li h2{

	color:orange;
	font-size:1.2em;
}
.ui-btn>p:hover, .ui-btn>h2:hover, .ui-btn>h3:hover{

	color:#ffffff;
}
p{

	white-space: pre-wrap;
}

</style>

Reported : <?php
$periods = array('today' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'all' => 'All');

foreach($periods as $k=>$v): ?>
<?php if($period != $k): ?><a href="/report/form/<?= $form->formid ?>/<?= $k ?><?= isset($subjectid) ? '/'.$subjectid : '' ?>"><?php else: ?><b><?php endif; ?><?= $v ?><?php if($period != $k): ?></a><?php else: ?></b><?php endif; ?>&nbsp;|
<?php endforeach; ?>

<?php switch($this->session->userdata('role')):
	case 'a': $word = "No one has submitted"; break;
	case 's': $word = "You haven't reported"; break;
	case 't': $word = "You haven't reported"; break;
endswitch; ?>

<?php if(empty($responses)): ?>
<p><?= $word ?> <?= $form->title ?>s <?php switch($period):
	case 'today': echo " today"; break;
	case 'week': echo " this week"; break;
	case 'month': echo " this month"; break;
endswitch;
?>.</p>
<?php else: ?>
<ul data-role="listview" data-inset="true">
	<?php foreach($responses as $response): ?>
		<li><a href="/report/response/<?= $response->reportid ?>">
			<?php if(isset($response->subjectfirstname)): ?><h3 class="users_name_label"><?= $response->subjectfirstname ?> <?= $response->subjectlastname ?></h3><?php endif; ?>
			<h2><?= $response->title ?></h2>
			<p>Submitted by: <?= $response->firstname ?> <?= $response->lastname ?><br />
			<?= date('m/d g:i a', $response->timeincident > 0 ? $response->timeincident : $response->timecreated) ?></p></a></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>




