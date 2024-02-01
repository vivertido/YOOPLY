<?php $roles = array('a' => 'Admin', 't' => 'Teacher', 's' => 'Student', 'p' => 'Parent'); ?>

Submitted by: <?php if(!(isset($ispublic) && $ispublic)): ?><a href="/<?= strtolower($roles[$user->accounttype]) ?>/view/<?= $user->userid ?>"><?php endif; ?><?= $user->firstname ?> <?= $user->lastname ?><?php if(!(isset($ispublic) && $ispublic)): ?></a><?php endif; ?> on <?= date('m/d', $report->timecreated) ?>

<?php if(!empty($subject)): ?>
	<br /><?= $roles[$subject->accounttype] ?>: <?php if(!(isset($ispublic) && $ispublic)): ?><a href="/<?= strtolower($roles[$subject->accounttype]) ?>/view/<?= $subject->userid ?>"><?php endif; ?><?= $subject->firstname ?> <?= $subject->lastname ?><?php if(!(isset($ispublic) && $ispublic)): ?></a><?php endif; ?>
<?php endif; ?>

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

<form action="/report/remove/<?= $report->reportid ?>" method="POST" data-ajax="false">
	Are you sure you want to remove this report?<br />
	<input type="submit" name="submit" value="Remove Report" />
	<input type="submit" name="cancel" value="Cancel" />
</form>