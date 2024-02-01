<div style="overflow:auto; font-size: 10pt">
	<div style="width: 33%; float: left;text-align:center">
		<span style="font-size: 24pt"><?= $dollartotal ?></span><br />
		<?= $dollarlabel ?>
	</div>
	<?php if(strpos($settings->referrals, $this->session->userdata('role')) !== false): ?>
	<div style="width: 33%; float: left;text-align:center">
		<span style="font-size: 24pt"><?= $referralcount ?></span><br />
		Referrals
	</div>
	<?php endif; ?>
	<?php if(strpos($settings->shoutouts, $this->session->userdata('role')) !== false): ?>
	<div style="width: 33%; float: left;text-align:center">
		<span style="font-size: 24pt"><?= $shoutouts ?></span><br />
		Shoutouts
	</div>
	<?php endif; ?>
</div>

<?php if(strpos($settings->referrals, $this->session->userdata('role')) !== false): ?>
	<?php if(!empty($activereferrals)): ?>
	<ul data-role="listview" data-inset="true">
	<?php foreach($activereferrals as $referral): ?>
		<li><a href="/referral/view/<?= $referral->referralid ?>"><?php if($referral->timeteachersave == 0): ?>Pending Referral: <?php endif; ?><?= date('m/d H:i', $referral->timecreated) ?> - <?= $referral->incident ?></a></li>
	<?php endforeach; ?>
	</ul>
	<?php endif; ?>
<?php endif; ?>

<?php
/** if this is a teacher, allow them to act on the student **/
if($role == 't' || $role == 'a'):
?>
<h3>Assign</h3>
<ul data-role="listview" data-inset="true">
	<?php if(strpos($settings->reinforcements, $this->session->userdata('role')) !== false): ?><li><a href="/reinforcement/add/<?= $user->userid ?>"><?= htmlentities($labels->reinforcement) ?></a></li><?php endif; ?>
	<?php if($this->session->userdata('schoolid') != 19 && strpos($settings->demerits, $this->session->userdata('role')) !== false): ?><li><a href="/demerit/add/<?= $user->userid ?>"><?= htmlentities($labels->demerit) ?></a></li><?php endif; ?>
	<?php if(strpos($settings->interventions, $this->session->userdata('role')) !== false): ?><li><a href="/intervention/add/<?= $user->userid ?>">Intervention</a></li><?php endif; ?>
	<?php if(strpos($settings->referrals, $this->session->userdata('role')) !== false): ?><li><a href="/referral/add/<?= $user->userid ?>">Referral</a></li><?php endif; ?>
	<?php if(strpos($settings->detentions, $this->session->userdata('role')) !== false): ?><li><a href="/detention/add/<?= $user->userid ?>"><?= htmlentities($labels->detention) ?></a></li><?php endif; ?>
	<?php if($dollartotal > 0): ?><li><a href="/teacher/redeemaward/<?= $user->userid ?>">Redeem Scholar Dollars</a></li><?php endif; ?>
	<?php if(strpos($settings->goals, $this->session->userdata('role')) !== false): ?><li><a href="/goal/add/<?= $user->userid ?>">Goal</a></li><?php endif; ?>
	<?php foreach($formsassign as $form): ?>
		<li><a href="/form/respond/<?= $form->formid ?>/<?= $user->userid ?>"><?= $form->title ?></a></li>
	<?php endforeach; ?>
</ul>
<?php
endif;
?>

<h3>Past Activity</h3>
<ul data-role="listview" data-inset="true">
	<?php if(strpos($settings->referrals, $this->session->userdata('role')) !== false): ?><li><a href="/referral/student/<?= $user->userid ?>" data-ajax="false">Referrals</a></li><?php endif; ?>
	<?php if(strpos($settings->interventions, $this->session->userdata('role')) !== false): ?><li><a href="/intervention/student/<?= $user->userid ?>" data-ajax="false">Interventions</a></li><?php endif; ?>
	<?php if(strpos($settings->reinforcements, $this->session->userdata('role')) !== false): ?><li><a href="/reinforcement/student/<?= $user->userid ?>" data-ajax="false"><?= htmlentities($labels->reinforcements) ?></a></li><?php endif; ?>
	<?php if(strpos($settings->demerits, $this->session->userdata('role')) !== false): ?><li><a href="/demerit/student/<?= $user->userid ?>" data-ajax="false"><?= htmlentities($labels->demerits) ?></a></li><?php endif; ?>
	<?php if(strpos($settings->detentions, $this->session->userdata('role')) !== false): ?><li><a href="/detention/student/<?= $user->userid ?>" data-ajax="false"><?= htmlentities($labels->detentions) ?></a></li><?php endif; ?>
	<?php if(strpos($settings->goals, $this->session->userdata('role')) !== false): ?><li><a href="/goal/student/<?= $user->userid ?>">Goals</a></li><?php endif; ?>
	<?php foreach($formsview as $form): ?>
		<li><a href="/report/form/<?= $form->formid ?>/today/<?= $user->userid ?>"><?= $form->title ?>s</a></li>
	<?php endforeach; ?>

	<!--LINK TO student_print_report -->
	<li><a href="/student/summary/<?= $user->userid ?>" data-ajax="false">Summary Report</a></li>

</ul>

<?php if($role == 'a'): ?>
<ul data-role="listview" data-inset="true">
	<li><a href="/student/edit/<?= $user->userid ?>" data-ajax="false">Edit</a></li>
</ul>
<?php endif; ?>