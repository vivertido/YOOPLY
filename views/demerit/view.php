<?php if(!isset($mode)): $mode = ''; endif; ?>
<?php if(empty($mode)): ?><div style="background-color:#ffffff; padding:15px;" class="ui-shadow"><?php endif; ?>
	<h2><?= $student->firstname ?> <?= $student->lastname ?></h2>
	 

	<h3>Reason for <?= htmlentities($demeritlabel) ?></h3>
	<p><?= $demerit->reason ?></p>

	<h3>Notes</h3>
	<p><?= $demerit->notes ?></p>

	<?php if($demerit->timeincident > 0): ?>
	<h3>Time of incident</h3>
	<p><?= date('m/d g:i a', $demerit->timeincident); ?></p>
	<?php else: ?>
	<h3>Time created</h3>
	<p><?= date('m/d g:i a', $demerit->timecreated); ?></p>
	<?php endif; ?>
	
<?php if(empty($mode)): ?></div><?php endif; ?>

<?php if(!empty($consequences)): ?>
<h3>Consequences</h3>
<ul data-role="listview" data-inset="true" data-split-icon="delete">
<?php foreach($consequences as $consequence): ?>
<li><?php if(empty($mode)): ?><a href="/consequence/edit/<?= $consequence->consequenceid ?>" style="text-decoration:none; color:black; font-weight:normal"><?php endif; ?>
	<h2><?= $consequence->title ?></h2><p class="ui-li-aside"><?= $consequence->progress ?></p>
	<p>
<?php $data = json_decode($consequence->data); echo preg_replace("/\r?\n/", '<br />', htmlentities($data->notes)); ?></p><?php if(empty($mode)): ?></a><?php endif; ?>
<?php if(empty($mode)): ?><a href="/consequence/remove/<?= $consequence->consequenceid ?>" data-ajax="false" data-rel="popup" data-position-to="window" data-transition="pop">Remove</a><?php endif; ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if(empty($mode)): ?>
<ul data-role="listview" data-inset="true">
	<?php if($showeditbutton): ?><li><a href="/consequence/add/demerit/<?= $demerit->demeritid ?>" data-ajax="false">Add Consequence</a></li><?php endif; ?>
	<?php if($showeditbutton): ?><li><a href="/demerit/edit/<?= $demerit->demeritid ?>" data-ajax="false">Edit</a></li><?php endif; ?>
	<?php if($showeditbutton): ?><li><a href="/demerit/remove/<?= $demerit->demeritid ?>" data-ajax="false">Remove</a></li><?php endif; ?>
</ul>
<?php endif; ?>