<?php if(strpos($settings->referrals, $this->session->userdata('role')) !== false): ?>
<h3>Incoming students:</h3>
<ul data-role="listview" data-inset="true">
<?php if(!empty($referrals)): foreach($referrals as $referral): ?>
	<li><a class="listAnchorText" href="/referral/view/<?= $referral->referralid ?>"><?= $referral->firstname ?> <?= $referral->lastname ?>
	<span class="ui-li-count"><?= time_elapsed_string($referral->timecreated) ?></span>
	<p style="margin-top:10px" class="listviewSubtext"><?= $referral->incident ?> - <?= $referral->teacherfirstname ?> <?= $referral->teacherlastname ?> </p>
</a></li>
<?php endforeach; endif; ?>
</ul>
<?php endif; ?>

<?php 
$feature_required = array(
	'/detention/mystudents' => $settings->detentions,
	'/admin/interventions' => $settings->interventions,
	'/demerit/school' => $settings->demerits,
	'/referral/school' => $settings->referrals,
	'/admin/reports' => $settings->referrals.$settings->demerits
); 
?>

<ul data-role="listview" data-inset="true">
	<?php foreach($menu->menu as $k=>$v): 
		if(array_key_exists($k, $feature_required) && strpos($feature_required[$k], $this->session->userdata('role')) === false): 
				continue; 
		endif; ?>
	<li><a href="<?= $k ?>" data-ajax="false"><?= $v ?></a></li>
	<?php endforeach; ?>
</ul>