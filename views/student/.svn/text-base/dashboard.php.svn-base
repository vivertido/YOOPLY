<?php $menu = $dashboardsettings->menu; ?>

<style> 
img.profImg{

border-style:solid; border-color: #2db7e5; border-width:3px; 
margin-right:10px;  padding:15px:
}
#containerForavatar{
padding:15px;
background-color: #ffffff;
border-style:solid; border-color:#33ccff; border-width:1px; border-radius:5px;

}


</style>

<div id="containerForavatar" class="ui-shadow">
	<p style="font-weight:normal; font-size:30px;margin-top:5px;  color:#603813; font-family:'Dosis';"><img class="profImg" src="/images/<?= $student->profileimage ?>" width="60" valign="middle" /><?= $student->firstname ?> <?= $student->lastname ?></p>
	<p style="font-weight:normal; text-shadow:0px 0px; font-size:18px"> <?= htmlentities($labels->reinforcements) ?>: <strong style="color:#603813; font-size:24px">$<?= $dollars ?></strong></p>
	 
	<?php if($detentionminutes > 0): ?><p style="font-size:15px; margin:0px; color: #603813; text-shadow:0px 0px"><img class="warning" src="/images/caution.png" style="margin-right:10px" />You have <?= $detentionminutes ?> min. of Lunch Detention today</p><?php endif; ?>
	<?php if(false): ?><p style="font-size:15px; margin:0px; color: #603813; text-shadow:0px 0px"><img class="warning" src="/images/message.png" style="float:left; margin-right:10px" />You have 2 new shoutouts!</p><?php endif; ?>
</div>

<ul data-role="listview" data-inset="true">
<?php foreach($referrals as $referral):
if(empty($referral->studentnotes)): // No students notes -> do a incident report ?>
	<li style="background:#33ccff" ><img class="warning" src="/images/write.png"  style="float:left; margin-left: 10px; margin-top:5px"><a href="/student/incident/<?= $referral->referralid ?>">New Referral Statement</a></li>
<?php elseif(empty($referral->reflection)): // No reflection -> do a reflection. ?>
	<li style="background:#33ccff" ><img class="warning" src="/images/write.png"  style="float:left; margin-left: 10px; margin-top:5px"><a href="/student/reflection/<?= $referral->referralid ?>">New Reflection</a></li>
<?php endif; ?>
<?php endforeach; 

$icons = array(
	'/student/awards' => 'images/dollar-sign.png',
	'/student/shoutouts' => 'images/speaker-electric.png',
	'/student/mydetentions' => 'images/no-entry.png',
	'/student/interventions' => '/images/caution.png',
	'/demerit/mine' => '/images/minus-sign.png',
	'/referral/mine' => '/images/whistle.png',
	'/student/bully' => '/images/smiley-beast.png',
	'/reflections/mine' => '/images/document.png'

);


$feature_required = array(
	'/student/mydetentions' => $features->detentions,
	'/student/shoutouts' => $features->shoutouts,
	'/student/interventions' => $features->interventions,
	'/demerit/mine' => $features->demerits,
	'/referral/mine' => $features->referrals,
	'/reflections/mine' => $features->referrals
); 


?>


<ul data-role="listview" data-inset="true">
	<?php foreach($menu as $k=>$v): 
		if(array_key_exists($k, $feature_required) && strpos($feature_required[$k], $this->session->userdata('role')) === false): 
			continue; 
		endif; ?>
	<li><a href="<?= $k ?>" data-ajax="false"><?= $v ?></a></li>
	<?php endforeach; ?>
</ul>

	<?php // <li> <a href="/referral/mine" data-ajax="false">My Behavior Contract</a></li> ?>
	<?php // <li> <a href="/student/shoutouts" data-ajax="false">Reward Menu</a></li> ?>
	<?php // <li> <a href="/student/shoutouts" data-ajax="false">School Rules</a></li> ?>
</ul>

