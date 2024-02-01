<?php if($referral->timecheckin == 0): ?>
<ul data-role="listview" data-inset="true">
	<li><a href="/admin/checkinstudent/<?= $referral->referralid ?>">Check in student</a></li>
</ul>
<?php else: ?>
Transit time: <?= time_elapsed_string($referral->timecreated, $referral->timecheckin, '') ?>
<?php endif; ?>

<?php $teacher_notes = json_decode($referral->teachernotes); ?>
<div class="referralBackground ui-shadow" style="padding:20px; background-color:#ffffff; margin-top:10px; border-radius:0.25em">
<h2>Teacher Referral Statement</h2>

<b>Incident Time:</b><br />
<p style="font-weight:normal"><?= date('m/d/Y H:i', $referral->timecreated) ?></p> 

<b>Teacher:</b> 
<p style="font-weight:normal"><?= $teacher->firstname ?> <?= $teacher->lastname ?></p>

<?php
	foreach($teacher_notes as $q): ?>
<b><?= htmlentities($q->label) ?></b><br />
<?php if(is_array($q->value)): ?>
<ul><li><?= implode('</li><li>', $q->value) ?></li></ul>
<?php else: ?>
<p style="font-weight:normal"><?= empty($q->value) ? 'No response' : htmlentities($q->value) ?></p>
<?php endif;
endforeach;

 if(!empty($referral->studentnotes)): ?>

<hr />

<h2>Student Referral Statement</h2>
<?php
	$student_notes = json_decode($referral->studentnotes);

	foreach($student_notes as $q): ?>
<b><?= htmlentities($q->label) ?></b><br />
<?php if(is_array($q->value)): ?>
<ul><li><?= implode('</li><li>', $q->value) ?></li></ul>
<?php else: ?>
<p style="font-weight:normal"><?= empty($q->value) ? 'No response' : htmlentities($q->value) ?></p>
<?php endif;
endforeach;
endif; ?>
<hr>

<?php if(!empty($referral->reflection)): ?>
<h2>Student In-Depth Reflection</h2>

<b>Time completed:</b><br />
<p style="font-weight:normal"><?= date('m/d/Y H:i', $referral->timereflection) ?></p>

<?php $reflection = json_decode($referral->reflection);
	foreach($reflection as $q): ?>
<b><?= htmlentities($q->label) ?></b><br />
<?php if(is_array($q->value)): ?>
<ul><li><?= implode('</li><li>', $q->value) ?></li></ul>
<?php else: ?>
<p style="font-weight:normal"><?= empty($q->value) ? 'No response' : htmlentities($q->value) ?></p>
<?php endif;
endforeach;
endif; ?>

</div>


<ul data-role="listview" data-inset="true">

<?php if(empty($referral->adminnotes)): ?>
	<li><a href="/referral/edit/<?= $referral->referralid ?>" data-ajax="false">Review</a></li>
<?php endif; ?>

<?php if($referral->timecheckout == 0): ?>
	<li><a href="/admin/sendbacktoclass/<?= $referral->referralid ?>" data-ajax="false">Send back to Class</a></li>
<?php endif; ?>
	<li><a href="/referral/remove/<?= $referral->referralid ?>" data-ajax="false">Remove</a></li>
</ul>