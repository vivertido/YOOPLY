<?php 	$notes = json_decode($referral->teachernotes); $admin_notes = json_decode($referral->adminnotes); ?>
<style>

.student-name{

color:#663300;
font-family:'News Cycle';
font-weight:normal;
font-size:24px;
text-shadow:none;
 

}
.section-title{

display:inline;
font-size:20px;
text-shadow:none;
color:#663300;
font-weight:normal;


}
#referral-wrapper{

background-color:rgba(219,87,5,0.2);
padding:10px;
font-family:'News Cycle';

}
.student-grade{

 
}
.description{
color:#4A4A4A;
font-weight:normal;
text-shadow:none;
text-align:right;
margin-left:20px;
margin-right:40px;
}
.referral-title{

text-align:center;
font-size:140%;
font-weight:normal;
text-shadow:none;
}
.incident-text{

font-size:20px;
color:#4A4A4A;
font-weight:normal;
text-shadow:none;


}
.section-title2{

display:block;
color:#663300;
 text-shadow:none;
font-weight:normal;
font-size:140%;


}
@media all and (max-width: 400px) {

.student-name{

color:#663300;
font-family:'Dosis';
font-weight:normal;
font-size:18px;
 

}
.section-title{

display:block;
font-size:16px;



}
.section-title2{

display:block;
color:#663300;
 
font-weight:normal;
font-size:18px;
text-shadow:none;

}

#referral-wrapper{

background-color:rgba(219,87,5,0.2);
padding:10px;
font-family:'Dosis';

}
.student-grade{

 
}
.description{
color:#4A4A4A;
font-weight:normal;
text-shadow:none;
}


.incident-text{

font-size:16px;
color:#4A4A4A;
font-weight:normal;
text-shadow:none;


}
}
</style>


<div id="referral-wrapper" class="ui-shadow">
	 
	<h3 class="referral-title">Office Referral Form </h3>
	 
	<h2 class="student-name"><?= $student->firstname ?> <?= $student->lastname ?> <span style="color:#4A4A4A; margin-left:20px">Grade: <?= $student->grade ?></span></h2>
	<h3 class="student-grade"></h3>
	 
		 
	 

	<h3 class="section-title">Sent to office at: <span class="description"><?= date('m/d g:i a', $referral->timecreated); ?></span></h3>

	<h3 class="section-title">Location: <span class="description"><?= isset($notes->location) ? htmlentities($notes->location) : 'Unassigned' ?></span></h3>

	<h3 class="section-title">Assigned by: <span class="description"><?= $teacher->firstname ?> <?= $teacher->lastname ?></span></h3>
	<br>
	<h3 class="section-title">Status: <span class="description"><?php switch(true): 
	case $referral->timeteachersave == 0: ?>Incomplete<?php break;
	case $referral->timecheckin == 0: ?>Awaiting Student Checkin<?php break;
	case $referral->timereflection == 0: ?>Awaiting Student Reflection<?php break;	
	case $referral->timeadminsave == 0: ?>Awaiting review<?php break;
	case $referral->timecheckout == 0: ?>Awaiting Student Checkout<?php break;
	default: ?>Complete<?php break;
	endswitch; ?></span></h3>

	<hr>


	<!--<h3 class="section-title2">Severity: <span class="description"> Minor/Major </span></h3>-->
	<?php 
		foreach($notes as $field): if(!isset($field->label)): continue; endif; ?>
	<h3 class="referral-notes-title"><?= $field->label ?> : </h3>
	<?php if(is_array($field->value)): ?>
		<ul>
			<?php foreach($field->value as $value): ?>
			<li><?= $value ?></li>
			<?php endforeach; ?>
		</ul>
	<?php else: ?>
		<p class="referral-notes-text"><?= $field->value ?></p>
	<?php endif; ?>
	<?php endforeach; ?>

	<?php if(!empty($referral->studentnotes)): ?>

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

<?php if(!empty($referral->reflection)): ?>
	<hr>

	<h2>Student In-Depth Reflection</h2>

	<h3>Time completed</h3>
	<p><?= date('m/d/Y g:i a', $referral->timereflection) ?></p>

	<?php $reflection = json_decode($referral->reflection);
		foreach($reflection as $q): ?>
	<h3 ><?= htmlentities($q->label) ?></h3>
	<?php if(is_array($q->value)): ?>
	<ul><li><?= implode('</li><li>', $q->value) ?></li></ul>
	<?php else: ?>
	<p ><?= empty($q->value) ? 'N/A' : htmlentities($q->value) ?></p>
	<?php endif;
	endforeach;
endif; ?>

	<hr />
	<?php if($referral->timecheckin != 0): ?>
		<h3>Office checked in at</h3>
		<p><?= date('m/d g:i a', $referral->timecheckin); ?></p>
	<?php endif; ?>
		 
	<?php if($referral->timeadminsave > 0): ?>
		<h3>Actions Taken</h3>
		<p><?= isset($admin_notes->external->actionstaken) && !empty($admin_notes->external->actionstaken) ? $admin_notes->external->actionstaken : 'N/A'; ?></p>

		<h3>Admin Summary</h3>
		<p ><?= isset($admin_notes->external->note) && !empty($admin_notes->external->note) ? $admin_notes->external->note : 'N/A' ?><p>

		<?php if($this->session->userdata('userid') == $referral->adminid): ?>
		<h3>Admin Summary (internal)</h3>
		<p ><?= isset($admin_notes->internal->note) && !empty($admin_notes->internal->note) ? $admin_notes->internal->note : 'N/A' ?><p>
		<?php endif; ?>	
	<?php endif; ?>

	<?php if($referral->timecheckout != 0): ?>
		<h3>Sent back to class at</h3>
		<?= date('m/d g:i a', $referral->timecheckout); ?>
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
<?php // <a href="referral/referral_print.html"><h3>Print Referral<h3></a> ?>
<br /><br />

<form action="/referral/remove/<?= $referral->referralid ?>" method="POST" data-ajax="false">
	Are you sure you want to remove this record?<br />
	<input type="submit" name="submit" value="Yes" data-inline="true" /> <input type="submit" name="cancel" value="No" data-inline="true" />
</form> 

