<div style="background-color:#ffffff; padding:15px;" class="ui-shadow"> 
<h2><?= $student->firstname ?> <?= $student->lastname ?></h2>

<h3>Type of intervention</h3>
<p><?= $intervention->intervention ?></p>

<h3>Notes</h3>
<p><?= $intervention->notes ?></p>

<?php if($this->session->userdata('role') != 't'): ?>
<h3>Assigned by</h3>
<p><?= $teacher->firstname ?> <?= $teacher->lastname ?></p>
<?php endif; ?>

	<?php if($intervention->timeincident > 0): ?>
	<h3>Time of incident</h3>
	<p><?= date('m/d g:i a', $intervention->timeincident); ?></p>
	<?php else: ?>

	<h3>Time created</h3>
	<p><?= date('m/d g:i a', $intervention->timecreated); ?></p>
	<?php endif; ?>
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

<ul data-role="listview" data-inset="true">
	<li><a href="/consequence/add/intervention/<?= $intervention->interventionid ?>" data-ajax="false">Add Consequence</a></li>
	<li><a href="/intervention/edit/<?= $intervention->interventionid ?>" data-ajax="false">Edit</a></li>
	<li><a href="/intervention/remove/<?= $intervention->interventionid ?>" data-ajax="false">Remove</a></li>
</ul>