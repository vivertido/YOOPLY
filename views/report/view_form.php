<style>
p{
	white-space: pre-wrap;
}
</style>
<?php $roles = array('a' => 'Admin', 't' => 'Teacher', 's' => 'Student', 'p' => 'Parent'); ?>

<div style="background-color: white; overflow:auto; padding:10px" class="ui-shadow">
	<?php if(!empty($subject)): ?>
		<h2><?php if(!$ispublic): ?><a href="/<?= strtolower($roles[$subject->accounttype]) ?>/view/<?= $subject->userid ?>" style="text-decoration:none;color:black"><?php endif; ?><?= $subject->firstname ?> <?= $subject->lastname ?><?php if(!$ispublic): ?></a><?php endif; ?></h2>
	<?php endif; ?>

	Submitted by: <?php if(!$ispublic): ?><a href="/<?= strtolower($roles[$user->accounttype]) ?>/view/<?= $user->userid ?>"><?php endif; ?><?= $user->firstname ?> <?= $user->lastname ?><?php if(!$ispublic): ?></a><?php endif; ?><?php if($report->timeincident == '0'): ?> on <?= date('m/d', $report->timecreated) ?><?php endif; ?>



	<?php $responses = json_decode($report->report); 

	foreach($responses as $response): ?>
	<h3><?= $response->label ?></h3>
	<?php if(is_array($response->value)): ?>
	<ul>
		<?php foreach($response->value as $o): ?>
			<li><?= $o ?></li>
		<?php endforeach; ?>
	</ul>
	<?php else: ?>
		<p><?= $response->value ?></p>
	<?php endif; 
	endforeach; ?>
</div>

<?php if(!empty($consequences)): ?>
<h3>Consequences</h3>
<ul data-role="listview" data-inset="true" data-split-icon="delete">
<?php foreach($consequences as $consequence): ?>
<li><a href="/consequence/edit/<?= $consequence->consequenceid ?>" style="text-decoration:none; color:black; font-weight:normal">
	<h2><?= $consequence->title ?></h2><p class="ui-li-aside"><?= $consequence->progress ?></p>
	<p>
<?php $data = json_decode($consequence->data); echo preg_replace("/\r?\n/", '<br />', htmlentities($data->notes)); ?></p></a>
<a href="/consequence/remove/<?= $consequence->consequenceid ?>" data-ajax="false" data-rel="popup" data-position-to="window" data-transition="pop">Remove</a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if($this->session->userdata('role') == 'a' || $this->session->userdata('role') == 't'): ?>
<ul data-role="listview" data-inset="true">
	<?php if($showeditbutton): ?><li><a href="/consequence/add/report/<?= $report->reportid ?>" data-ajax="false">Add Consequence</a></li><?php endif; ?>
	<?php if($showeditbutton): ?><li><a href="/report/edit/<?= $report->reportid ?>">Edit</a></li><?php endif; ?>
	<?php if($showeditbutton): ?><li><a href="/report/remove/<?= $report->reportid ?>">Remove</a></li><?php endif; ?>
</ul>
<?php endif; ?>