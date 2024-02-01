<?php if(empty($referrals)): ?>
There are no referrals for this student.
<?php else: ?>
<ul data-role="listview" data-inset="true">
<?php foreach($referrals as $referral): ?>
	<li><a href="/referral/view/<?= $referral->referralid ?>" data-ajax="false">
		<h3><?= $referral->incident ?></h3>
		<p><?= date('m/d g:i a', $referral->timecreated) ?></p>
	</a></li>
<?php endforeach; ?>
</ul>
<?php endif;?>